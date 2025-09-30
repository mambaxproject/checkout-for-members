<?php

namespace App\Helpers;

use Monolog\Logger;
use App\Helpers\MembersLogHandler;

class CustomMembersLogger
{
    public function __invoke(array $config)
    {
        $logger = new Logger('members');
        $logger->pushHandler(new MembersLogHandler());
        return $logger;
    }
}
