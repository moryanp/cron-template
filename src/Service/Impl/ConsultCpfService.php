<?php

namespace App\Service\Impl;

use App\Model\Entity\CpfModel;
use App\Model\Entity\ErrorModel;
use App\Service\ConsultService;
use DI\Container;
use InvalidArgumentException;

use function DI\add;

class ConsultCpfService extends ConsultService
{

    public function __construct(Container $container)
    {
        parent::__construct($container);
    }


    /**
     * starts the update process if exists any pending data
     * the update process only get current day data
     * @return null
     */
    public function startUpdatePendingQueries()
    {
        try {
            // pesquisa dados que ainda estão processando
            $listPendings = $this->getPendings();

            // caso haja algum dado para atualizar
            if (!empty($listPendings)) {

                // para cada elemento do array, pesquisar seu status atual na CAF
                foreach ($listPendings as $bdData) {

                    // através do execution_id do atual dado, consultar na CAF a resposta
                    $cafData = $this->cafService->getByExecutionId($bdData['cocp_execution_id']);

                    // verifica se o status contido no BD diverge do status contigo na resposta da CAF
                    $status = $bdData['cocp_status_consulta'];
                    $statusCaf = $this->translateStatus($cafData['status']);
                    if ($status != $statusCaf) {

                        // Transforma o status string em valor int para salvar no bd
                        $cafData['status'] = $statusCaf;

                        // verifica o status e em caso de erro (status=3) salva os 
                        // motivos de reprovacao, senão salva os dados atualizados
                        if ($statusCaf == 3) {
                            // se status e cancelado, então envia os dados da resposta
                            // para serem salvos na tabela de motivos de reprovacao
                            $this->insertError($cafData);
                        } else {
                            // se status for diferente de reprovado, entao envia os
                            // dados para serem preparados para a atualização
                            $this->updateData($bdData, $cafData);
                        }
                    }
                }
                // escreve no arquivo de log o término da operação com sucesso
                $this->logger->info("CRON-CPF-CAF: finished operations");
            }
        } catch (\Exception $e) {
            $this->logger->critical("CRON-CPF-CAF: cannot update data with id={$bdData['idCpfVerificado']}. Error: {$e->getMessage()}");
        }
    }


    /**
     * return all data where status still unprocessed
     * @return array
     */
    public function getPendings()
    {
        $pendings = $this->consultDao->findAllPendings();

        return $pendings;
    }


    /**
     * construct an object with the new data to save it in database
     * @return void
     */
    public function updateData(array $bdData, array $cafData)
    {
        try {
            // constroi um novo objeto do tipo consult que é composto pelos 
            // dados corretos para serem salvos no bd
            $data = $this->constructCpfModel($bdData, $cafData);

            // envia os dados atualizado para serem salvos no bd
            $this->consultDao->update($data);

            // salva o registro em arquivo texto como registro
            $this->register->info("Data ID=" . $data->getId() . " updated with success", $cafData);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function insertError(array $cafData)
    {
        try {
            // constroi um novo objeto do tipo error que e composto pelos dados
            // de motivo de reprovacao e identificacao da requisicao
            $error = $this->constructErrorModel($cafData);

            // envia os dados para serem inseridos no bd
            $this->consultDao->insert($error);

            // salva registro em arquivo sobre consulta com resultado final reprovado

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * create an object with all updated data to be saved
     * @return CpfModel
     */
    private function constructCpfModel($bdData, $cafData)
    {
        // dados imutaveis: 
        $id = $bdData['idCpfVerificado'];
        $cpf = $bdData['cpf'];
        $cpfIndex = $bdData['cpf_indice'];

        // dados atualizaveis:
        $statusConsulta = isset($cafData['status']) ? $cafData['status'] : NULL;
        $indicadorFraude = isset($cafData['fraud']) ? (($cafData['fraud']) == true ? 1 : 0) : NULL;
        $executionId = isset($cafData['_id']) ? $cafData['_id'] : NULL;
        $dataAtualizacao = date('Y-m-d');
        $nome = isset($cafData['sections']['cpf']['name']) ? $cafData['sections']['cpf']['name'] : $bdData['nome'];
        $dataNascimento = isset($cafData['sections']['cpf']['birthDate']) ? $cafData['sections']['cpf']['birthDate'] : $bdData;
        $anoObito = isset($cafData['sections']['cpf']['deathYear']) ? (($cafData['sections']['cpf']['deathYear'] == "") ? NULL : $cafData['sections']['cpf']['deathYear']) : NULL;
        $indicadorObito = ($anoObito == '') ? 0 : 1;

        // correção formato dd/mm/yyyy para yyyy-mm-dd:
        $dataNascimento = date("Y-m-d", strtotime(str_replace('/', '-', $dataNascimento)));
        if ($anoObito != NULL) {
            $anoObito = date("Y-m-d", strtotime(str_replace('/', '-', $anoObito)));
        }

        // novo objeto com dados atualizados
        return new CpfModel($id, $statusConsulta, $indicadorFraude, $executionId, $dataAtualizacao, $nome, $cpf, $cpfIndex, $dataNascimento, $anoObito, $indicadorObito);
    }

    /**
     * create an object of type error with a list of errors msg
     * @return ErrorModel
     */
    private function constructErrorModel($cafData)
    {
        $executionId = $cafData['_id'];
        $reportId = $cafData['report'];
        $deleted = 0;
        $list = array();

        // lista de motivos para status reprovado
        foreach ($cafData['validations'] as $validation) {
            if (strcmp($validation['status'], "INVALID") == 0) {
                array_push($list, $validation['description']);
            }
        }

        // objeto error contendo as informações
        return new ErrorModel(null, $executionId, $reportId, $list, $deleted);
    }

    /**
     * translates the string status to a int according to its value
     * @return int
     */
    private function translateStatus($status)
    {
        if (is_string($status)) {

            $status = strtoupper($status);

            switch ($status) {
                case 'APROVADO':
                    $status = 4;
                    return $status;

                case 'REPROVADO':
                    $status = 3;
                    return $status;

                case 'PENDENTE OCR':
                    $status = 2;
                    return $status;

                case 'PENDENTE':
                    $status = 1;
                    return $status;

                case 'PROCESSANDO':
                    $status = 0;
                    return $status;

                default:
                    throw new InvalidArgumentException("TranslateStatus Error: cannot get properly status from CAF response");
                    break;
            }
        } else {
            throw new InvalidArgumentException("TranslateStatus Error: status must be a string");
        }
    }
}
