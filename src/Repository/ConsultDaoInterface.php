<?php

namespace App\Repository;

interface ConsultDaoInterface
{

    public function findAllPendings();
    public function findById($id);
    public function update($id, $data);
}
