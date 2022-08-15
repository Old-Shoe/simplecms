<?php

declare(strict_types=1);

namespace Core\Include;

use ArrayAccess;
use Core\Config;
use Core\Log;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use xPDO\xPDO;
use xPDO\xPDOException;

class xPDOConstruct implements ContainerInterface, ArrayAccess
{
    private static array|null $connection = null;
    private Config $dataconfig;
    private Logger $logger;

    private function __clone(){}

    public function __construct(bool $forceNew = false)
    {
        $this->logger = Log::set(__CLASS__);
        $this->dataconfig = new Config('database');

        foreach ($this->dataconfig['data'] as $database => $config) {
            $this->set($database, $this->createConnection($config), $forceNew);
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

    public static function getSingleRecord($conn, $sql, $var, $param, $type) {
        $stmt = $conn->prepare($sql);
        $stmt->bindParam($param,$var, $type);
        $stmt->execute();
        $result = $stmt->fetchColumn();
        $stmt->closeCursor();
        return $result;
    }

    public function set(string|int|null $id = null, array $connection, bool $forceNew = false): void
    {
        try {
            $tmp_con = xPDO::getInstance($id, [
                xPDO::OPT_CACHE_PATH => SIMPLECMS_CACHE_DIR,
                xPDO::OPT_HYDRATE_FIELDS => true,
                xPDO::OPT_HYDRATE_RELATED_OBJECTS => true,
                xPDO::OPT_HYDRATE_ADHOC_FIELDS => true,
                xPDO::OPT_CONNECTIONS => [$connection]
            ], $forceNew);
            $this->offsetSet($id, $tmp_con);
        } catch (xPDOException $e) {
            $this->logger->error($e->getMessage());
        }
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function get(string $id): xPDO
    {
        if ($this->has($id)) {
            try {
                return $this->offsetGet($id);
            } catch (Exception $e) {
                $this->logger->error(sprintf('%s: %c',$e->getMessage(), $e->getCode()));
            }
        } else {
            $this->set($id, $this->createConnection($this->dataconfig['data'][$id]));
            return $this->offsetGet($id);
        }
        throw new Exception("Dependency not found with key {$id}.");
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
