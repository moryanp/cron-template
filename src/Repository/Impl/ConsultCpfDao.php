<?php

namespace App\Repository\Impl;

use App\Model\Entity\CpfModel;
use App\Model\Entity\ErrorModel;
use App\Repository\ConsultDao;
use DI\Container;

final class ConsultCpfDao extends ConsultDao
{

    public function __construct(Container $container)
    {
        parent::__construct($container);
    }

    /**
     * returns an array contents all the pendings results from db
     * @return array
     */
    public function findAllPendings()
    {
        $query = "
                SELECT
                    `idCpfVerificado`, `nome`, `cpf`, `cpf_indice`, `data_nascimento`, `data_baixa`, `anoObito`, `cocp_status_consulta`, `cocp_indicador_fraude`, `cocp_execution_id`, `cocp_indicador_obito`
                FROM
                    `tbCpfVerificado`
                WHERE 
                    ((`cocp_status_consulta` = 0) OR (`cocp_status_consulta` = 1) OR (`cocp_status_consulta` = 2)) AND (`cocp_deleted` <> 1) AND (`data_baixa` = current_date())
                ";

        $results = $this->conn->query($query)->fetchAll(\PDO::FETCH_ASSOC);

        return $results;
    }

    /**
     * return from db an element inside an array
     * @return array
     */
    public function findById($id)
    {
        $query = "
                SELECT
                    `idCpfVerificado`, `nome`, `cpf`, `cpf_indice`, `data_nascimento`, `data_baixa`, `anoObito`, `cocp_status_consulta`, `cocp_indicador_fraude`, `cocp_execution_id`, `cocp_indicador_obito`
                FROM
                    `tbCpfVerificado`
                WHERE 
                    `cocp_id` = :cocp_id
                ";

        $statement = $this->conn->prepare($query);
        $statement->execute([
            'cocp_id' => $id
        ]);

        return $statement->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * updates the corresponding id data
     * @return void
     */
    public function update(CpfModel $data)
    {
        $query = "
                UPDATE
                    `tbCpfVerificado`
                SET
                    `nome` = :nome,
                    `data_nascimento` = :data_nascimento,
                    `data_baixa` = :data_baixa,
                    `anoObito` = :anoObito,
                    `cocp_status_consulta` = :cocp_status_consulta,
                    `cocp_indicador_fraude` = :cocp_indicador_fraude,
                    `cocp_execution_id` = :cocp_execution_id,
                    `cocp_indicador_obito` = :cocp_indicador_obito
                WHERE
                    `idCpfVerificado` = :id
                ";

        $statement = $this->conn->prepare($query);
        $statement->execute([
            'id' => $data->getId(),
            'nome' => $data->getNome(),
            'data_nascimento' => $data->getDataNascimento(),
            'data_baixa' => $data->getDataAtualizacao(),
            'anoObito' => $data->getAnoObito(),
            'cocp_status_consulta' => $data->getStatusConsulta(),
            'cocp_indicador_fraude' => $data->getIndicadorFraude(),
            'cocp_execution_id' => $data->getExecutionId(),
            'cocp_indicador_obito' => $data->getIndicadorFraude()
        ]);
    }

    public function insert(CpfModel $data, ErrorModel $error)
    {
        $query = "
                INSERT INTO
                    `tbErrosCAF`(`caer_execution_id`, `caer_report_id`, `caer_error_message`, `caer_deleted`)
                Values
                    (:caer_execution_id, :caer_report_id, :caer_error_message, :caer_deleted)
                ";
        try {
            $this->conn->beginTransaction();

            $this->update($data);

            foreach ($error->getErrorList() as $item) {
                $statement = $this->conn->prepare($query);
                $statement->execute([
                    'caer_execution_id' => $error->getExecutionId(),
                    'caer_report_id' => $error->getReportId(),
                    'caer_error_message' => $item,
                    'caer_deleted' => $error->getDeleted()
                ]);
            }
            $this->conn->commit();
        } catch (\Exception $e) {
            $this->conn->rollBack();
            throw new \Exception($e->getMessage());
        }
    }
}
