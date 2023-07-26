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

namespace Core\Extensions;

use Core\Attributes\Access;
use ArrayAccess;

final class Extensions implements ArrayAccess {
    private static array $extensions = array();
    private static array $resultset = array();

    private function __construct() {}


    #[Access()]
    public function install(array $phars): array {
        foreach ($phars as $phar) {
            self::$extensions[$phar] = new Extension($phar);
            if(!$this->offsetExists($phar)) {
                $this->offsetSet($phar, self::$extensions[$phar]->install());
            }
        }
        return self::$resultset;
    }
    public static function uninstall(array $phars): array
    {
    }
    public static function enable(array $phars): array
    {
    }
    public static function disable(array $phars): array
    {
    }
    public static function download(array $phars): array
    {
    }
    public static function remove(array $phars): array
    {
    }
    public static function update(array $phars): array
    {
    }
    public static function downgrade(array $phars): array
    {
    }

    /**
     * @inheritDoc
     */
    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, self::$resultset);
    }

    /**
     * @inheritDoc
     */
    public function offsetGet(mixed $offset): mixed
    {
        return self::$resultset[$offset];
    }

    /**
     * @inheritDoc
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        self::$resultset[$offset] = $value;
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset(mixed $offset): void
    {
        unset(self::$resultset[$offset]);
    }
}