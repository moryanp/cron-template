<?php

namespace App\Service;

interface ConsultServiceInterface
{
    function execute();
    function update($id, $data);
}
