<?php

namespace App\Repository\Impl;

use App\Model\Entity\CnpjModel;
use App\Repository\ConsultDao;
use DI\Container;

final class ConsultCnpjDao extends ConsultDao
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
                    `cocn_id`, `cocn_razao_social`, `cocn_cnpj`, `cocn_cnpj_index`, `cocn_data_abertura`, `cocn_nome_fantasia`, `cocn_telefone`, `cocn_cnae`, `cocn_natureza_juridica`, `cocn_porte`, `cocn_estado`, `cocn_cidade`, `cocn_bairro`, `cocn_logradouro`, `cocn_numero`, `cocn_complemento`, `cocn_cep`, `cocn_email`, `cocn_status_consulta`, `cocn_indicador_fraude`, `cocn_execution_id`, `cocn_data_cadastro`, `cocn_hora_cadastro`, `cocn_data_atualizacao`
                FROM
                    `tbCnpj`
                WHERE 
                    ((`cocn_status_consulta` = 0) OR (`cocn_status_consulta` = 1) OR (`cocn_status_consulta` = 2)) AND (`cocn_deleted` <> 1)
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
                    `cocn_id`, `cocn_razao_social`, `cocn_cnpj`, `cocn_cnpj_index`, `cocn_data_abertura`, `cocn_nome_fantasia`, `cocn_telefone`, `cocn_cnae`, `cocn_natureza_juridica`, `cocn_porte`, `cocn_estado`, `cocn_cidade`, `cocn_bairro`, `cocn_logradouro`, `cocn_numero`, `cocn_complemento`, `cocn_cep`, `cocn_email`, `cocn_status_consulta`, `cocn_indicador_fraude`, `cocn_execution_id`, `cocn_data_cadastro`, `cocn_hora_cadastro`, `cocn_data_atualizacao`
                FROM
                    `tbCnpj`
                WHERE 
                    `cocn_id` = :cocn_id
                ";

        $statement = $this->conn->prepare($query);
        $statement->execute([
            'cocn_id' => $id
        ]);

        return $statement->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * updates the corresponding id data
     * @return void
     */
    public function update(CnpjModel $data)
    {
        $query = "
                UPDATE
                    `tbCnpj`
                SET
                    `cocn_razao_social` = :cocn_razao_social,
                    `cocn_data_abertura` = :cocn_data_abertura,
                    `cocn_nome_fantasia` = :cocn_nome_fantasia,
                    `cocn_telefone` = :cocn_telefone,
                    `cocn_cnae` = :cocn_cnae,
                    `cocn_natureza_juridica` = :cocn_natureza_juridica,
                    `cocn_porte` = :cocn_porte,
                    `cocn_estado` = :cocn_estado,
                    `cocn_cidade` = :cocn_cidade,
                    `cocn_bairro` = :cocn_bairro,
                    `cocn_logradouro` = :cocn_logradouro,
                    `cocn_numero` = :cocn_numero,
                    `cocn_complemento` = :cocn_complemento,
                    `cocn_cep` = :cocn_cep,
                    `cocn_email` = :cocn_email,
                    `cocn_status_consulta` = :cocn_status_consulta,
                    `cocn_indicador_fraude` = :cocn_indicador_fraude,
                    `cocn_data_atualizacao` = :cocn_data_atualizacao
                WHERE
                    `cocn_id` = :cocn_id
                ";

        $statement = $this->conn->prepare($query);
        $statement->execute([
            'cocn_id' => $data->getId(),
            'cocn_razao_social' => $data->getRazaoSocial(),
            'cocn_data_abertura' => $data->getDataAbertura(),
            'cocn_nome_fantasia' => $data->getNomeFantasia(),
            'cocn_telefone' => $data->getTelefone(),
            'cocn_cnae' => $data->getCnae(),
            'cocn_natureza_juridica' => $data->getNaturezaJuridica(),
            'cocn_porte' => $data->getPorte(),
            'cocn_estado' => $data->getEstado(),
            'cocn_cidade' => $data->getCidade(),
            'cocn_bairro' => $data->getBairro(),
            'cocn_logradouro' => $data->getLogradouro(),
            'cocn_numero' => $data->getNumero(),
            'cocn_complemento' => $data->getComplemento(),
            'cocn_cep' => $data->getCep(),
            'cocn_email' => $data->getEmail(),
            'cocn_status_consulta' => $data->getStatusConsulta(),
            'cocn_indicador_fraude' => $data->getIndicadorFraude(),
            'cocn_data_atualizacao' => $data->getDataAtualizacao()
        ]);
    }
}
