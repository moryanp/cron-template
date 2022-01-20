<?php

namespace App\Service;

use App\Repository\ConsultDao;
use DI\Container;
use Monolog\Logger;

abstract class ConsultService implements ConsultServiceInterface
{
    /**
     * @var CafService
     */
    protected CafService $cafService;

    /**
     * @var ConsultDao classe de persistência de dados
     */
    protected ConsultDao $consultDao;

    /**
     * @var Logger registro de eventos da aplicação
     */
    protected $logger;

    /**
     * @var Logger salva o histórico de resultados
     */
    protected $register;


    public function __construct(Container $container)
    {
        $this->cafService = $container->get('CafService');
        $this->consultDao = $container->get('ConsultDao');
        $this->logger = $container->get('LoggerService');
        $this->register = $container->get('RegisterService');
    }
}
