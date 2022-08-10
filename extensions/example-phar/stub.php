<?php
/*
 * Custom banner
 */

try {
    Phar::mapPhar('me.phar');
    spl_autoload_register(function ($class) {
        include 'phar://me.phar/' . str_replace('_', '/', strtolower($class)) . '.class.php';
    });
} catch (PharException $e) {
    echo $e->getMessage();
    die('Cannot initialize Phar');
}

__HALT_COMPILER(); ?>
