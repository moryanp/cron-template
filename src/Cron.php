<?php

namespace App;

use App\Service\ConsultService;
use DI\Container;

class Cron
{
    /**
     * @var ConsultService
     */
    private ConsultService $consultService;


    public function __construct(Container $container)
    {
        $this->consultService = $container->get('ConsultService');
    }


    /**
     * starts the process of veryfying all data where status still unprocessed and 
     * updates its information with the dada obtained from CAF API
     * @return void
     */
    public function start()
    {
        // inicia o serviço de atualização de dados pendentes
        $this->consultService->startUpdatePendingQueries();
    }
}
