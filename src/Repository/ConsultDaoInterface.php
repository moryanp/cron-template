<?php

namespace App\Repository;

use App\Model\Entity\CpfModel;

interface ConsultDaoInterface
{

    public function findAllPendings();
    public function findById($id);
    public function update(CpfModel $data);
}
