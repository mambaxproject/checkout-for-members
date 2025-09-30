<?php

namespace App\Helpers;

use Monolog\Logger;
use App\Helpers\DatabaseHandler;

class CustomDatabaseLogger
{
    public function __invoke(array $config): Logger
    {
        $logger = new Logger('database');
        $logger->pushHandler(new DatabaseHandler());
        return $logger;
    }
}
