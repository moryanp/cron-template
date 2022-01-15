<?php

use App\Cron;
use App\Database\DB;
use DI\ContainerBuilder;

require __DIR__ . '/vendor/autoload.php';
require 'paths.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/');
$dotenv->load();

// Instantiate PHP-DI ContainerBuilder
$container = ContainerBuilder::buildDevContainer();

// Set up dependencies
$dependencies = require SETTINGS_PATH . '/dependencies.php';
$dependencies($container);

try {
    $file = "cron-caf-cnpj.txt";

    // if (file_exists(CONTROL_PATH . '/' . $file)) {
    //     print_r("Arquivo de controle '{$file}' bloqueando");
    //     die();
    // }

    $f_file = fopen(CONTROL_PATH . '/' . $file, 'x');

    $cron = new Cron($container);
    $cron->execute();

    fclose($f_file);
    unlink(CONTROL_PATH . '/' . $file);
} catch (Exception $e) {
    echo $e->getMessage();
} finally {
    DB::cloneConnection();
}
