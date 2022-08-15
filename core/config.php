<?php

namespace Core;

use ArrayAccess;
use Exception;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Yosymfony\Toml\Exception\ParseException;
use Yosymfony\Toml\Toml;

class Config implements ContainerInterface, ArrayAccess
{
    private array $data;
    private Logger $logger;

    /**
     * @param string $config
     */
    public function __construct(string $config)
    {
        $this->logger = Log::set(__CLASS__);

        try {
            $this->data = Toml::ParseFile(SIMPLECMS_CONFIG_DIR. $config .'.toml');
        } catch (ParseException $exc) {
            $this->logger->error($exc->getMessage());
        }
    }

    /**
     * @inheritDoc
     */
    public function get(string $id)
    {
        if ($this->has($id)) {
            try {
                return $this->offsetGet($id);
            } catch (Exception $e) {
                $this->logger->error(sprintf('%s: %c',$e->getMessage(), $e->getCode()));
            }
        }
        $this->logger->error("Dependency not found with key $id.");
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
        return array_key_exists($offset, $this->data);
    }

    /**
     * @inheritDoc
     */
    #[\ReturnTypeWillChange]
    public function offsetGet(mixed $offset): mixed
    {
        return $this->data[$offset];
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new Exception('Method not allowed');
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function offsetUnset(mixed $offset): void
    {
        throw new Exception('Method not allowed');
    }
}