<?php

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

namespace Core\VPDO\VPDO;

/**
 * Description of vpdo
 *
 * @author Leonid Kuzin(Dg_INC) <dg.inc.lcf@gmail.com>
 */
class VPDO extends \PDO {
    public function __construct($settings)
    {
        $dsn = sprintf( '%s:dbname=%s;host=%s;port=%s', $settings['data']['primary']['driver'], $settings['data']['primary']['schema'], $settings['data']['primary']['host'], $settings['data']['primary']['port'] );
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        parent::__construct($dsn, $settings['data']['primary']['username'], $settings['data']['primary']['password'], $options);
    }
}
