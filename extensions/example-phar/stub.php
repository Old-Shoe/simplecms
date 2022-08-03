<?php
/*
 * Custom banner
 */

include_once 'phar://' . __FILE__ . '/loader.php';

spl_autoload_register(function ($class) {
    include 'phar://' . str_replace('_', '/', strtolower($class)) . '.php';
});
Phar::mapPhar('example-phar.phar');

__HALT_COMPILER(); ?>
