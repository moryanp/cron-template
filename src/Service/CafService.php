<?php

namespace App\Service;

class CafService
{
    /**
     * @var String the url to connect with CAF
     */
    private $apiUrlBase;

    /**
     * @var String the token to identify who is requesting data from CAF
     */
    private $apiKey;

    /**
     * @var String id from what type of request are doing
     */
    private $apiReports;


    public function __construct()
    {
        $this->apiUrlBase = ($_ENV['IS_DEVMODE'] == "0") ? $_ENV['URL_BASE'] : $_ENV['DEV_URL_BASE'];
        $this->apiKey = ($_ENV['IS_DEVMODE'] == "0") ? $_ENV['ACCESS_KEY'] : $_ENV['DEV_ACCESS_KEY'];
        $this->apiReports = ($_ENV['IS_DEVMODE'] == "0") ? $_ENV['REPORT_ID_CPF_BASIC'] : $_ENV['DEV_REPORT_ID_CPF_BASIC'];
    }

    /**
     * @return array returns data from API CAF using the execution_id
     */
    public function getByExecutionId($execution_id)
    {
        $path = '/reports/' . $this->apiReports . '/executions/' . $execution_id . '?token=' . $this->apiKey;

        $dataObj = $this->Call($path);

        return $dataObj;
    }


    /**
     * connect with api caf using CURL to obtain a response
     */
    private function call($path)
    {
        $url = $this->apiUrlBase . $path;

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        if (is_null($ch)) {
            // $this->logger->critical("call(): Erro ao estabelecer conexão com a CAF");
            throw new \Exception("Erro ao estabelecer conexão com a CAF");
        }

        $result = json_decode(curl_exec($ch), true);

        return $result;
    }
}
