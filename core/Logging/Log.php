<?php declare(strict_types=1);
/*
 * The MIT License
 *
 * Copyright 2020 Leonid Kuzin(Dg_INC) <dg.inc.lcf@gmail.com>.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Core\Logging;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

class Log
{
    protected static ?Logger $logger = null;

    public function __construct(
        string $name = "SimpleCMS",
        int|string|Level $level = Level::Debug,
        string $output = "[%datetime%] %level_name%: > %channel%: \"%message%\" %context% %extra%\n",
        string $dateFormat = "Y/n/j, g:ia",
        ?string $path = null,
        ?string $file_name = null)
    {
        $formatter = new LineFormatter($output, $dateFormat);

        if(is_null($path) && is_null($file_name))
        {
            $filePath = SIMPLECMS_LOGS_DIR . '/error.log';
        } else {
            $filePath = $path . DIRECTORY_SEPARATOR. $file_name;
        }
        if (!file_exists($filePath)) {
            touch($filePath);
        }

        // Create a handler
        $handler = new StreamHandler($filePath, $level);
        $handler->setFormatter($formatter);

        // bind it to a logger object
        self::$logger = new Logger($name);
        self::$logger->pushHandler($handler);
    }

    /**
     * @return Logger|null
     */
    public function getLogHandler(): ?Logger
    {
        return self::$logger;
    }

    /**
     * @param Logger|null $logger
     */
    public static function setLogger(?Logger $logger): void
    {
        self::$logger = $logger;
    }

    public function __destruct()
    {
        self::$logger->close();
    }
}