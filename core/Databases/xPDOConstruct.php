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

use ArrayAccess;
use Core\Logging\Log;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use Monolog\Level;
use Psr\Container\ContainerInterface;
use RuntimeException;
use xPDO\xPDO;
use xPDO\xPDOException;

final class xPDOConstruct extends Log implements ContainerInterface, ArrayAccess
{
    protected static array|null $connection = null;
    protected DatabaseConfig $dataconfig;

    private function __clone(){}

    public function __construct(int|string|Level $level= Level::Debug, bool $forceNew = false)
    {
        parent::__construct(level: $level);
        if (!extension_loaded('pdo_mysql')) { #TODO: Rewrite this shit!
            throw new RuntimeException("Module \"pdo_mysql\" not loaded!", 1);
        } elseif (!extension_loaded('pdo_pgsql')) {
            throw new RuntimeException("Module \"pdo_pgsql\" not loaded!", 2);
        }

        $this->dataconfig = new DatabaseConfig();

        foreach ($this->dataconfig['databases'] as $database => $config) {
            $this->set($this->createConnection($config), $database, $forceNew);
            $this->checkAndCreateCacheDirectory($database);
        }
    }

    private function checkAndCreateCacheDirectory(string $id): void
    {
        $endpoint = SIMPLECMS_CACHE_DIR . DIRECTORY_SEPARATOR . "Databases" . DIRECTORY_SEPARATOR . $id;
        if(!is_dir($endpoint)) {
            try {
                mkdir($endpoint, recursive: true);
            } catch (Exception $exception) {
                $this->logger->error(sprintf("%s: %d", $exception->getMessage(), $exception->getCode()));
            }
        }
    }

    #[ArrayShape(['dsn' => "string", 'username' => "mixed", 'password' => "mixed", 'options' => "bool[]", 'driverOptions' => "array"])]
    private function createConnection($connection): array
    {
        return [
                'dsn' => sprintf('%s:host=%s;dbname=%s;port=%d;charset=%s', $connection['driver'], $connection['host'], $connection['db'], $connection['port'], $connection['schema']),
                'username' => $connection['username'],
                'password' => $connection['password'],
                'options' => [
                    xPDO::OPT_CONN_MUTABLE => true,
                ],
                'driverOptions' => [],
            ];
    }

    public function set(array $connection, string|int|null $id = null, bool $forceNew = false): void
    {
        try {
            $tmp_con = xPDO::getInstance($id, [
                xPDO::OPT_CACHE_PATH => SIMPLECMS_CACHE_DIR . DIRECTORY_SEPARATOR . "databases" . DIRECTORY_SEPARATOR . $id,
                xPDO::OPT_HYDRATE_FIELDS => true,
                xPDO::OPT_HYDRATE_RELATED_OBJECTS => true,
                xPDO::OPT_HYDRATE_ADHOC_FIELDS => true,
                xPDO::OPT_CONNECTIONS => [$connection]
            ], $forceNew);
            $this->offsetSet($id, $tmp_con);
        } catch (xPDOException $exception) {
            $this->logger->error(sprintf('%s: %d',$exception->getMessage(), $exception->getCode()));
        }
    }

    /**
     * @inheritDoc
     * @throws RuntimeException
     */
    public function get(string $id): null|xPDO
    {
        if (!$this->has($id)) {
            $this->set($this->createConnection($this->dataconfig['data'][$id]), $id);
        }
        try {
            return $this->offsetGet($id);
        } catch (Exception $exception) {
            $this->logger->error(sprintf('%s: %d', $exception->getMessage(), $exception->getCode()));
            throw new RuntimeException("Dependency not found with key {$id}.");
        }

        //throw new Exception("Dependency not found with key {$id}.");
    }

    /**
     * @inheritDoc
     */
    public function has(string $id): bool
    {
        return $this->offsetExists($id);
    }

    /**
     * @inheritDoc
     */
    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, self::$connection);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet(mixed $offset): mixed
    {
        return self::$connection[$offset];
    }

    /**
     * @inheritDoc
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        self::$connection[$offset] = $value;
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset(mixed $offset): void
    {
        unset(self::$connection[$offset]);
    }
}
