<?php

namespace App\Service;

interface ConsultServiceInterface
{
    function execute();
    function getPendings();
    function update($id, $data);
}
