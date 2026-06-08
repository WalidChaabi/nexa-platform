<?php

namespace App\Logging;

use Monolog\Formatter\JsonFormatter;
use Monolog\Logger;

class IntegrationsLogTap
{
    public function __invoke(Logger $logger): void
    {
        if (! app()->environment('production')) {
            return;
        }

        foreach ($logger->getHandlers() as $handler) {
            $handler->setFormatter(new JsonFormatter);
        }
    }
}
