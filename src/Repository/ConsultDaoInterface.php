<?php

namespace App\Repository;

use App\Model\Entity\CnpjModel;

interface ConsultDaoInterface
{

    public function findAllPendings();
    public function findById($id);
    public function update(CnpjModel $data);
}
