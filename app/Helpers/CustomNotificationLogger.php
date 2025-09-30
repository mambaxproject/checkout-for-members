<?php

namespace App\Helpers;

use Monolog\Logger;
use App\Helpers\NotificationLogHandler;

class CustomNotificationLogger
{
    public function __invoke(array $config)
    {
        $logger = new Logger('notification');
        $logger->pushHandler(new NotificationLogHandler());
        return $logger;
    }
}
