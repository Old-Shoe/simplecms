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

namespace App;

use Core\Databases\Database;
use Core\Logging\Log;
use Monolog\Level;

final class App extends Log
{
    private array $array_conf = array();

    public function __construct()
    {
        parent::__construct(level: Level::Info);

        //$Config = new Config(Level::Info);

        $query = 'INSERT INTO config (scope, `key`, value) VALUES (:scope, :key, :value);';
        $config = ["sda", "sf"];
        $result = Database::Init("primary")::MTO($query, $config);

        //var_dump($this->create(["blabla" => "123", "bloblo" => 321], "locale"));

        //var_dump($Config->read(["blabla", "bloblo"]));

        /*var_dump($this->create("blabla", "locale", "123"));
        var_dump($this->create("bloblo", "locale", "fuck you"));

        var_dump($this->read("blabla"));
        var_dump($this->read("bloblo"));

        var_dump($this->update("blabla", "321"));
        var_dump($this->update("bloblo", "hello you"));

        var_dump($this->read("blabla"));
        var_dump($this->read("bloblo"));

        var_dump($this->delete("blabla"));
        var_dump($this->delete("bloblo"));*/
    }
}