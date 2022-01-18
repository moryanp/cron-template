<?php

namespace App\Service;

interface ConsultServiceInterface
{
    function execute();
    function getPendings();
    function update(array $bdData, array $cafData);
}
