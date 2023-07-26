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

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;

class Locale extends Base
{
    private array $array_conf;

    /**
     */
    public function __construct()
    {
        parent::__construct();
        $config = new Config("coreconfig");
        try {
            $this->array_conf = $config->get("locale");
        } catch (ContainerExceptionInterface|NotFoundExceptionInterface $e) {
            $this->log_handler->error(sprintf('%s: %c',$e->getMessage(), $e->getCode()));
        }
    }

    /**
     * @throws RuntimeException
     */
    public function init(string $domain = "messages") :void
    {
        putenv("LANG=". $this->array_conf['language']);
        //setlocale(LC_MESSAGES, $this->array_conf['language'].'.'.$this->array_conf['codeset']);
        setlocale(LC_ALL, $this->array_conf['language'].'.'.$this->array_conf['codeset']);

        if (!bindtextdomain($domain, SIMPLECMS_ROOT_DIR . "/locale")) {
            $this->log_handler->error(_("%%exc_bind_domain%%"));
            throw new RuntimeException(_("%%exc_bind_domain%%"));
        }
        textdomain($domain);
        bind_textdomain_codeset($domain, $this->array_conf['codeset']);
    }
}
