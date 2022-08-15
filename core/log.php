<?php

namespace Core;

use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

class Log
{
    private static Logger $logger;
    private static string $filePath;

    public static function set(string $name, Level $level = Level::Error): Logger
    {
        // the default date format is "Y-m-d\TH:i:sP"
        $dateFormat = "Y n j, g:i a";

        // the default output format is "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n"
        // we now change the default output format according to our needs.
        $output = "%datetime% > %level_name% > %message% %context% %extra%\n";

        // finally, create a formatter
        $formatter = new LineFormatter($output, $dateFormat);

        self::$filePath = SIMPLECMS_CACHE_DIR. '/my_app.log';
        if (!file_exists(self::$filePath))
        {
            touch(self::$filePath);
        }

        // Create a handler
        $stream = new StreamHandler(self::$filePath, $level);
        $stream->setFormatter($formatter);

        // bind it to a logger object
        self::$logger = new Logger($name);
        return self::$logger->pushHandler($stream);
    }
}