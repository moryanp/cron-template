<?php

namespace App\Service;

use DI\Container;
use Exception;

class ConsultService
{
    /**
     * @var ConsultDao
     */
    private $consultDao;
    private $logger;
    private $register;

    public function __construct(Container $container)
    {
        $this->consultDao = $container->get('ConsultDao');
        $this->logger = $container->get('LoggerService');
        $this->register = $container->get('RegisterService');
    }

    public function update($id, $data)
    {
        // aqui vai toda a lógica da regra de negócio para procurar os dados que necessitam atualizar
        // atualizar os dados
        try {
            $this->register->info("Json salvo no registro");

            $this->logger->info("Update realizado com sucesso");

            return $this->consultDao->update($id, $data);
        } catch (Exception $e) {
            $e->getMessage();
        }
    }
}
