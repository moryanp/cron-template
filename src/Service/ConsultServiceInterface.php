<?php

namespace App\Service;

interface ConsultServiceInterface
{
    function startUpdatePendingQueries();
    function getPendings();
    function updateData(array $bdData, array $cafData);
    function insertError(array $cafData);
}
