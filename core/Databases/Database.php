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

namespace Core\Databases;

use Core\Logging\Log;
use Monolog\Logger;
use PDOException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use xPDO\xPDO;

final class Database
{
    protected static ?xPDO $connection = null;
    protected static ?Logger $logger = null;

    protected function __clone(){}

    private static function match_values(string $query, bool $revers): array
    {
        $match = [];
        preg_match_all("/:[A-Za-z\d?]*/", $query, $match, PREG_PATTERN_ORDER);
        return $revers? array_reverse($match[0]) : $match[0];
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public static function Init(string $database) : Database
    {
        $pdo = new xPDOConstruct();
        self::$connection = $pdo->get($database);
        self::$connection->connect();

        $log = new Log();
        self::$logger = $log->getLogHandler();

        return new Database();
    }

    public static function MTO(string $query, array $ar, bool $reversed = false): array
    {
        self::$connection->beginTransaction();
        $keys = self::match_values($query, $reversed);
        $result = [];
        try {
            $stmt = self::$connection->prepare($query);
            foreach ($ar as $item)
            {
                $stmt->bindValue(":key", $item);
                $stmt->execute();
                $result[$item] = $stmt->fetchColumn();
                $stmt->closeCursor();
            }
            self::$connection->commit();
        } catch (PDOException $exception) {
            self::$connection->rollback();
            self::$logger->error(sprintf('%s: %d',$exception->getMessage(), $exception->getCode()));
        }
        return $result;
    }
    public static function OTO(string $sql, string $value, string $bind): mixed
    {
        self::$connection->beginTransaction();
        $result = null;
        try {
            $stmt = self::$connection->prepare($sql);
            $stmt->bindValue($bind, $value);
            $stmt->execute();
            $result = $stmt->fetchColumn();
            $stmt->closeCursor();
            self::$connection->commit();
        } catch (PDOException $exception) {
            self::$connection->rollback();
            self::$logger->error(sprintf('%s: %d',$exception->getMessage(), $exception->getCode()));
        }
        return $result;

    }
    public static function MTM(string $sql, array $ar)
    {

    }
    public static function OTM(string $sql, string $value)
    {

    }
}