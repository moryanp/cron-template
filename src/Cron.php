<?php

namespace App;

use DI\Container;

class Cron
{
    private $consultService;

    public function __construct(Container $container)
    {
        $this->consultService = $container->get('ConsultService');
    }

    public function execute()
    {
        echo "===== Starting CRON\n";

        echo "- Updated data: \n";
        print_r($this->consultService->update(24, array('Batata', 'Banana', 'Uva', 'Morango')));
    }
}
