<?php

namespace App\Repository;

use DI\Container;

class ConsultDao
{
    /**
     * @var \PDO
     */
    private $conn;


    public function __construct(Container $container)
    {
        $this->conn = $container->get('DbConnection');
    }

    public function findAllPendings()
    {
        return array("Natan Morya Paredes", "Alisson Colombelli Flecha", "Mauricio Fontana");
    }

    public function update($id, $data)
    {
        return "User with {$id} has been updated with succesfull! Data: [{$data[0]}, {$data[1]}, {$data[2]}, {$data[3]}]";
    }
}
