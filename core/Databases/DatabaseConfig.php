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
use Monolog\Level;
use Psr\Container\ContainerInterface;
use ReturnTypeWillChange;
use Yosymfony\Toml\Exception\ParseException;
use Yosymfony\Toml\Toml;

class DatabaseConfig extends Log implements ContainerInterface, ArrayAccess
{
    private array $data = array();

    public function __construct(int|string|Level $level= Level::Debug)
    {
        parent::__construct(level: $level);
        try {
            $this->data = Toml::ParseFile(SIMPLECMS_CONFIG_DIR. 'database.toml');
        } catch (ParseException $exc) {
            $this->logger->error($exc->getMessage());
        }
    }

    public function __destruct()
    {
        unset($this->logger);
        unset($this->data);
    }

    /**
     * @inheritDoc
     */
    public function get(string $id)
    {
        if ($this->has($id)) {
            try {
                return $this->offsetGet($id);
            } catch (Exception $exception) {
                $this->logger->error(sprintf('%s: %c',$exception->getMessage(), $exception->getCode()));
            }
        }
        $this->logger->error(sprintf("Dependency not found with key %s.", $id));
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
    #[ReturnTypeWillChange]
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