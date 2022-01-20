<?php

namespace App\Repository;

use App\Model\Entity\CpfModel;
use App\Model\Entity\ErrorModel;

interface ConsultDaoInterface
{

    public function findAllPendings();
    public function findById($id);
    public function update(CpfModel $data);
    public function insert(CpfModel $data, ErrorModel $error);
}
