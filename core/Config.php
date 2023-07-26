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

namespace Core;

use Monolog\Level;
use ReturnTypeWillChange;

class Config extends Log
{
    public function __construct(int|string|Level $level)
    {
        parent::__construct(level: $level);
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function create(string|array $config, string $scope, mixed $value = null): bool
    {
        $query = 'INSERT INTO Config (scope, `key`, value) VALUES (:scope, :key, :value);';
        $result = Database::Init("primary")::MTO($query, $config);
        return false;
    }

    #[ReturnTypeWillChange]
    public function readValue(string|array $config): array
    {
        $query = 'SELECT `value` FROM Config WHERE `key`=:key;';
        if(is_array($config)){
            $result = Database::Init("primary")::MTO($query, $config);
        } else {
            $result = Database::Init("primary")::OTO($query, $config, ":key");
        }
        return $result;
    }

    public function update(string|array $config, string $scope, mixed $value = null): bool
    {
        $sql = 'UPDATE Config SET `value`=:value WHERE `key`=:key;';
        return false;
    }

    public function delete(string|array $config, string $scope): bool
    {
        $sql = 'DELETE FROM Config WHERE `key`=:key;';
        return false;
    }

    public function readAllScope(string $scope): array
    {
        $query = 'SELECT * FROM Config WHERE scope = :scope;';
        // TODO: Implement readAll() method.
    }
}