<?php

namespace App;

use App\Service\CafService;
use App\Service\ConsultService;
use DI\Container;

class Cron
{

    /**
     * @var ConsultService
     */
    private ConsultService $consultService;

    /**
     * @var CafService
     */
    private CafService $cafService;

    public function __construct(Container $container)
    {
        $this->consultService = $container->get('ConsultService');
        $this->cafService = $container->get('CafService');
    }

    /**
     * starts the process of veryfying all data where status still unprocessed and 
     * updates its information with the dada obtained from CAF API
     * @return void
     */
    public function start()
    {
        // pesquisa dados que ainda estão processando
        $listPendings = $this->consultService->getPendings();

        // caso haja algum dado para atualizar
        if (!empty($listPendings)) {

            // para cada elemento do array, pesquisar seu status atual na CAF
            foreach ($listPendings as $data) {

                // 1 - através do execution_id, buscar na CAF a consulta
                $execution_id = $data['cocn_execution_id'];
                $cafResult = $this->cafService->getByExecutionId($execution_id);

                // 2- se status desse dado na caf estiver diferente do dado armazenado no 
                // bd, atualizar ele com as novas informaçoes da CAF
            }
        }
    }
}
