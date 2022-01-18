<?php

namespace App\Service\Impl;

use App\Model\Entity\CnpjModel;
use App\Model\Entity\ConsultModel;
use App\Service\ConsultService;
use DI\Container;

class ConsultCnpjService extends ConsultService
{

    public function __construct(Container $container)
    {
        parent::__construct($container);
    }

    public function execute()
    {
        // $results = $this->consultDao->findAllPendings();
        // $results = $this->consultDao->findById(17);
        // print_r($results);
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

        // constroi um novo objeto do tipo consult que é composto pelos 
        // dados corretos para serem salvos no BD
        $data = $this->constructUpdatedObject($bdData, $cafData);

        try {
            // $this->register->info("Json salvo no registro");

            // $this->logger->info("Update realizado com sucesso");
            $this->consultDao->update($data);
        } catch (\Exception $e) {
            $e->getMessage();
        }
    }

    /**
     * create an object with all updated data to be saved
     * @return CnpjModel
     */
    public function constructUpdatedObject($bdData, $cafData)
    {

        // dados imutaveis: 
        $id = $bdData['cocn_id'];
        $cnpj = $bdData['cocn_cnpj'];
        $cnpjIndex = $bdData['cocn_cnpj_index'];

        // dados atualizaveis:
        $statusConsulta = isset($cafData['status']) ? $cafData['status'] : NULL;
        $indicadorFraude = isset($cafData['fraud']) ? (($cafData['fraud']) == true ? 1 : 0) : NULL;
        $executionId = isset($cafData['_id']) ? $cafData['_id'] : NULL;
        $dataAtualizacao = date('Y-m-d');
        $razaoSocial = isset($cafData['sections']['pjData']['data']['officialName']) ? $cafData['sections']['pjData']['data']['officialName'] : NULL;
        $email = isset($cafData['sections']['pjData']['data']['companyEmail']) ? $cafData['sections']['pjData']['data']['companyEmail'] : NULL;
        $dataAbertura = isset($cafData['sections']['pjData']['data']['openingDate']) ? $cafData['sections']['pjData']['data']['openingDate'] : NULL;
        $nomeFantasia = isset($cafData['sections']['pjData']['data']['fantasyName']) ? $cafData['sections']['pjData']['data']['fantasyName'] : NULL;
        $telefone = isset($cafData['sections']['pjData']['data']['phoneNumber']) ? $cafData['sections']['pjData']['data']['phoneNumber'] : NULL;
        $cnae = isset($cafData['sections']['pjData']['data']['mainActivity']) ? $cafData['sections']['pjData']['data']['mainActivity'] : NULL;
        $naturezaJuridica = isset($cafData['sections']['pjData']['data']['legalNature']) ? $cafData['sections']['pjData']['data']['legalNature'] : NULL;
        $porte = isset($cafData['sections']['pjData']['data']['companySize']) ? $cafData['sections']['pjData']['data']['companySize'] : NULL;
        $estado = isset($cafData['sections']['pjData']['data']['address']['state']) ? $cafData['sections']['pjData']['data']['address']['state'] : NULL;
        $cidade = isset($cafData['sections']['pjData']['data']['address']['city']) ? $cafData['sections']['pjData']['data']['address']['city'] : NULL;
        $bairro = isset($cafData['sections']['pjData']['data']['address']['neighborhood']) ? $cafData['sections']['pjData']['data']['address']['neighborhood'] : NULL;
        $logradouro = isset($cafData['sections']['pjData']['data']['address']['street']) ? $cafData['sections']['pjData']['data']['address']['street'] : NULL;
        $numero = isset($cafData['sections']['pjData']['data']['address']['number']) ? $cafData['sections']['pjData']['data']['address']['number'] : NULL;
        $complemento = isset($cafData['sections']['pjData']['data']['address']['complement']) ? $cafData['sections']['pjData']['data']['address']['complement'] : NULL;
        $cep = isset($cafData['sections']['pjData']['data']['address']['zipCode']) ? $cafData['sections']['pjData']['data']['address']['zipCode'] : NULL;

        // correção formato dd/mm/yyyy para yyyy-mm-dd:
        $dataAbertura = date("Y-m-d", strtotime(str_replace('/', '-', $dataAbertura)));

        // novo objeto com dados atualizados
        $cnpjModel = new CnpjModel($id, $statusConsulta, $indicadorFraude, $executionId, $dataAtualizacao, $cnpj, $cnpjIndex, $razaoSocial, $email, $dataAbertura, $nomeFantasia, $telefone, $cnae, $naturezaJuridica, $porte, $estado, $cidade, $bairro, $logradouro, $numero, $complemento, $cep);

        return $cnpjModel;
    }
}
