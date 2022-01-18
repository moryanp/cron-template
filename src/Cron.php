<?php

namespace App;

use App\Service\CafService;
use App\Service\ConsultService;
use DI\Container;
use InvalidArgumentException;

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

                // 1 - através do execution_id do atual dado, consultar na CAF a resposta
                $execution_id = $data['cocn_execution_id'];
                $cafResult = $this->cafService->getByExecutionId($execution_id);

                // 2- verifica se o status contido no BD diverge do status contigo na resposta da CAF
                $status = $data['cocn_status_consulta'];
                $statusCaf = $this->translateStatus($cafResult['status']);
                // $statusCaf = $this->translateStatus("APROVADO");
                if ($status != $statusCaf) {
                    // envia os dados para serem preparados para a atualização
                    $this->consultService->update($data, $cafResult);
                }
            }
        }
    }

    /**
     * translates the string status to a int according to its value
     * @return int
     */
    public function translateStatus($status)
    {
        if (is_string($status)) {

            $status = strtoupper($status);

            switch ($status) {
                case 'APROVADO':
                    $status = 4;
                    return $status;

                case 'REPROVADO':
                    $status = 3;
                    return $status;

                case 'PENDENTE OCR':
                    $status = 2;
                    return $status;

                case 'PENDENTE':
                    $status = 1;
                    return $status;

                case 'PROCESSANDO':
                    $status = 0;
                    return $status;

                default:
                    throw new InvalidArgumentException("TranslateStatus Error: cannot get properly status from CAF response");
                    break;
            }
        } else {
            throw new InvalidArgumentException("TranslateStatus Error: status must be a string");
        }
    }
}
