<?php

namespace App\Service\Impl;

use App\Model\Entity\CpfModel;
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

                    // 1 - através do execution_id do atual dado, consultar na CAF a resposta
                    $execution_id = $bdData['cocp_execution_id'];
                    $cafData = $this->cafService->getByExecutionId($execution_id);

                    // 2- verifica se o status contido no BD diverge do status contigo na resposta da CAF
                    $status = $bdData['cocp_status_consulta'];
                    $statusCaf = $this->translateStatus($cafData['status']);

                    if ($status != $statusCaf) {

                        // Transforma o status string em valor int para salvar no BD
                        $cafData['status'] = $statusCaf;

                        // envia os dados para serem preparados para a atualização
                        $this->update($bdData, $cafData);
                    }
                }
                // escreve no arquivo de log o término da operação com sucesso
                $this->logger->info("CRON-CPF-CAF: finished data updates");
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
    public function update(array $bdData, array $cafData)
    {
        try {
            // constroi um novo objeto do tipo consult que é composto pelos 
            // dados corretos para serem salvos no bd
            $data = $this->constructUpdatedObject($bdData, $cafData);

            // envia os dados atualizado para serem salvos no bd
            $this->consultDao->update($data);

            // salva o registro em arquivo texto como registro
            $this->register->info("Data ID=" . $data->getId() . " updated with success", $cafData);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }


    /**
     * create an object with all updated data to be saved
     * @return CpfModel
     */
    public function constructUpdatedObject($bdData, $cafData)
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
        $cpfModel = new CpfModel($id, $statusConsulta, $indicadorFraude, $executionId, $dataAtualizacao, $nome, $cpf, $cpfIndex, $dataNascimento, $anoObito, $indicadorObito);

        return $cpfModel;
    }

    /**
     * translates the string status to a int according to its value
     * @return int
     */
    public function translateStatus($status)
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
