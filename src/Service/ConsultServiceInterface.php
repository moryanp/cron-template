<?php

namespace App\Service;

interface ConsultServiceInterface
{
    function startUpdatePendingQueries();
    function getPendings();
    function update(array $bdData, array $cafData);
    function constructUpdatedObject(array $bdData, array $cafData);
}
