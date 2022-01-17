<?php

namespace App\Repository;

use DI\Container;

abstract class ConsultDao implements ConsultDaoInterface
{
    /**
     * @var \PDO
     */
    protected \PDO $conn;

    public function __construct(Container $container)
    {
        $this->conn = $container->get('DbConnection');
    }
}
