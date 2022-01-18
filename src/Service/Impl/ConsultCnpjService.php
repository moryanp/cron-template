<?php

namespace App\Service\Impl;

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

    public function update($id, $data)
    {
        // aqui vai toda a lógica da regra de negócio para procurar os dados que necessitam atualizar
        // atualizar os dados

        try {
            $this->register->info("Json salvo no registro");

            $this->logger->info("Update realizado com sucesso");

            return $this->consultDao->update($id, $data);
        } catch (\Exception $e) {
            $e->getMessage();
        }
    }
}
