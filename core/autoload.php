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

set_include_path( './classes/' . PATH_SEPARATOR . get_include_path() );
spl_autoload_extensions( '.php , .class.php' );
spl_autoload_register();
function linux_namespaces_autoload($class) {
    /* use if you need to lowercase first char *
    $class  =  implode( DIRECTORY_SEPARATOR , array_map( 'lcfirst' , explode( '\\' , $class_name ) ) );/* else just use the following : */
    $class  =  implode(DIRECTORY_SEPARATOR , explode('\\' , $class));
    static $extensions = array();
    if (empty($extensions )) {
        $extensions = array_map('trim', explode(',', spl_autoload_extensions()));
    }
    static $include_paths = array();
    if (empty($include_paths)) {
        $include_paths = explode(PATH_SEPARATOR, get_include_path());
    }
    foreach ($include_paths as $path) {
        $path .= (DIRECTORY_SEPARATOR !== $path[strlen($path) - 1]) ? DIRECTORY_SEPARATOR : '';
        foreach ($extensions as $extension) {
            $file = $path . $class . $extension;
            if (file_exists($file) && is_readable($file)) {
                require $file;
                return;
            }
        }
    }
    //throw new Exception(_( 'class ' . $class_name . ' could not be found.' ));
    throw new Exception(fprintf(_('class %s could not be found.'), $class));
}
spl_autoload_register('linux_namespaces_autoload' , TRUE , FALSE );
