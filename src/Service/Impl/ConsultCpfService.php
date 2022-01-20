<?php

namespace App\Service\Impl;

use App\Model\Entity\CpfModel;
use App\Model\Entity\ErrorModel;
use App\Service\ConsultService;
use DI\Container;
use InvalidArgumentException;

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
                            // e atualiza a consulta no bd
                            $this->insertError($bdData, $cafData);
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
            throw new \Exception($e->getMessage());
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
     * construct an object with the new data and send it to be saved in database
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

    /**
     * construct an object with the list of errors and send it 
     * to be saved in error table in db
     * @return void
     */
    public function insertError(array $bdData, array $cafData)
    {
        try {
            // constroi um novo objeto do tipo error que e composto pelos dados
            // de motivo de reprovacao e identificacao da requisicao
            $error = $this->constructErrorModel($cafData);

            // constoi o objeto de cpf para ser atualizado
            $cpf = $this->constructCpfModel($bdData, $cafData);

            // envia os dados para serem inseridos no bd
            $this->consultDao->insert($cpf, $error);

            // salva registro em arquivo sobre consulta com resultado final reprovado
            $this->register->info("Data ID=" . $cpf->getId() . " is reproved", $cafData);
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
        try {
            // dados imutaveis: 
            $id = $bdData['idCpfVerificado'];
            $cpf = $bdData['cpf'];
            $cpfIndex = $bdData['cpf_indice'];
            $statusConsulta = $cafData['status'];

            // dados atualizaveis:
            $executionId = isset($cafData['_id']) ? $cafData['_id'] : $bdData['cocp_execution_id'];
            $dataAtualizacao = date('Y-m-d');

            $indicadorFraude = isset($cafData['fraud']) ? (($cafData['fraud']) == true ? 1 : 0) : NULL;
            $nome = isset($cafData['sections']['cpf']['name']) ? $cafData['sections']['cpf']['name'] : $bdData['nome'];
            $dataNascimento = isset($cafData['sections']['cpf']['birthDate']) ? $cafData['sections']['cpf']['birthDate'] : $bdData['data_nascimento'];
            $anoObito = isset($cafData['sections']['cpf']['deathYear']) ? (($cafData['sections']['cpf']['deathYear'] == "") ? NULL : $cafData['sections']['cpf']['deathYear']) : NULL;
            $indicadorObito = isset($cafData['sections']['cpf']['deathYear']) ? (($anoObito == '') ? 0 : 1) : NULL;

            // correção formato dd/mm/yyyy para yyyy-mm-dd:
            if ($dataNascimento != NULL) {
                $dataNascimento = date("Y-m-d", strtotime(str_replace('/', '-', $dataNascimento)));
            }
            if ($anoObito != NULL) {
                $anoObito = date("Y-m-d", strtotime(str_replace('/', '-', $anoObito)));
            }

            // novo objeto com dados atualizados
            return new CpfModel($id, $statusConsulta, $indicadorFraude, $executionId, $dataAtualizacao, $nome, $cpf, $cpfIndex, $dataNascimento, $anoObito, $indicadorObito);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * create an object of type error with a list of errors msg
     * @return ErrorModel
     */
    private function constructErrorModel($cafData)
    {
        try {
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
            if (empty($list)) {
                array_push($list, "Erro não informado. Verifique a resposta da CAF!");
            }

            // objeto error contendo as informações
            return new ErrorModel(null, $executionId, $reportId, $list, $deleted);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
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
