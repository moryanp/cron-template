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

    public function start()
    {
        echo "===== Starting CRON\n";

        $this->consultService->execute();
    }
}
