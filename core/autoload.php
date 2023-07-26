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

namespace Core\Autoload;

use Exception;

define('SIMPLECMS_DEBUG', 1);

define('SIMPLECMS_ROOT_DIR', $_SERVER['DOCUMENT_ROOT'] .DIRECTORY_SEPARATOR);
define('SIMPLECMS_CORE_DIR', SIMPLECMS_ROOT_DIR. 'Core' .DIRECTORY_SEPARATOR);
define('SIMPLECMS_CONFIG_DIR', SIMPLECMS_CORE_DIR. 'Config' .DIRECTORY_SEPARATOR);
define('SIMPLECMS_CACHE_DIR', SIMPLECMS_CORE_DIR. 'Cache' .DIRECTORY_SEPARATOR);
define('SIMPLECMS_EXT_DIR', SIMPLECMS_ROOT_DIR. 'Extensions' .DIRECTORY_SEPARATOR);
define('SIMPLECMS_WEBROOT_DIR', SIMPLECMS_ROOT_DIR. 'Webroot' .DIRECTORY_SEPARATOR);
define('SIMPLECMS_LOGS_DIR', SIMPLECMS_ROOT_DIR. 'Logs' .DIRECTORY_SEPARATOR);

set_include_path(SIMPLECMS_ROOT_DIR . PATH_SEPARATOR . get_include_path());

function namespaces_autoload ($class): void {
    $class = implode(DIRECTORY_SEPARATOR, explode('\\', $class));

    static $extension = null;
    if (is_null($extension)) {
        $extension = spl_autoload_extensions('.php');
    }
    static $include_paths = array();
    if (empty($include_paths)) {
        $include_paths = explode(PATH_SEPARATOR, get_include_path());
    }
    foreach ($include_paths as $path) {
        $path .= (DIRECTORY_SEPARATOR !== $path[strlen($path) - 1]) ? DIRECTORY_SEPARATOR : '';

        $file = $path . $class . $extension;
        try {
            if (file_exists($file) && is_readable($file)) {
                require_once $file; //TODO: Maybe cause problem. (Need to rewrite to DirectoryIterator)
            }
        } catch (Exception $e) {
            echo sprintf(_("%%exc_autoload %s: %s %%"), $class, $e->getMessage());
        }
    }
}

spl_autoload_register(__NAMESPACE__ . '\namespaces_autoload', true, false);
