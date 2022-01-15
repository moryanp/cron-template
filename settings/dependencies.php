<?php

use App\Database\DB;
use App\Repository\ConsultDao;
use App\Service\ConsultService;
use DI\Container;
use Gelf\Publisher;
use Gelf\Transport\TcpTransport;
use Monolog\Formatter\JsonFormatter;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\GelfHandler;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;

return function (Container $container) {

    $container->set(
        'DbConnection',
        function () {
            DB::getConneciton();
        }
    );

    $container->set(
        'ConsultDao',
        function ($container) {
            return new ConsultDao($container);
        }
    );

    $container->set(
        'ConsultService',
        function ($container) {
            return new ConsultService($container);
        }
    );

    $container->set(
        'LoggerService',
        function () {

            if ($_ENV['IS_DEVMODE'] == "1") {
                $logger = new Logger($_ENV['LOGGER_NAME']);

                $processor = new UidProcessor();
                $logger->pushProcessor($processor);

                $handler = new StreamHandler(
                    LOGS_PATH . '/app.log',
                    Logger::DEBUG
                );
                $logger->pushHandler($handler);
            } else {
                $logger = new Logger($_ENV['MONOLOG_FACILITY']);

                $graylogHandler = new GelfHandler(
                    new Publisher(new TcpTransport(
                        $_ENV['MONOLOG_HOST'],
                        $_ENV['MONOLOG_PORT']
                    )),
                    Logger::DEBUG
                );

                $logger->pushHandler($graylogHandler);
            }
            return $logger;
        }
    );

    $container->set(
        'RegisterService',
        function () {
            $day = date('d');
            $month = date('m');
            $year = date('Y');

            $logger = new Logger($_ENV['LOGGER_NAME']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $output = "%datetime% > %message% %context% %extra%\n";

            // $formatter = new LineFormatter($output);
            $formatter = new JsonFormatter($output);


            $stream = new StreamHandler(
                RECORDS_PATH . "/{$year}/{$month}/{$day}/register.log",
                Logger::DEBUG
            );
            $stream->setFormatter($formatter);

            $logger->pushHandler($stream);

            return $logger;
        }
    );
};
