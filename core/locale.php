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
namespace SimpleCMS\Core;

use Yosymfony\Toml\Toml;
use const SimpleCMS\SIMPLECMS_LOCALE_DIR;
use const SimpleCMS\SIMPLECMS_ROOT_DIR;
use const SimpleCMS\SIMPLECMS_VENDOR_DIR;

require SIMPLECMS_VENDOR_DIR . '/autoload.php';

$array = Toml::ParseFile(SIMPLECMS_ROOT_DIR . '/core/config/coreconfig.toml');

putenv("LANG=".$array['locale']['language']);
//setlocale(LC_MESSAGES, $array['locale']['language'].'.'.$array['locale']['codeset']);
setlocale(LC_ALL, $array['locale']['language'].'.'.$array['locale']['codeset']);
$domain = "messages";

if (SIMPLECMS_LOCALE_DIR != bindtextdomain($domain, SIMPLECMS_LOCALE_DIR)) {
    throw new Exception(_("%%exc_bind_domain%%"));
}
textdomain($domain);
bind_textdomain_codeset($domain, $array['locale']['codeset']);
