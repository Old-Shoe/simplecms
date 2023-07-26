<?php
/*
 * Custom banner
 */

spl_autoload_register(function ($class) {
    include 'phar://users.phar/' . str_replace('_', '/', strtolower($class)) . '.class.php';
});

try {
    Phar::mapPhar('users.phar');
    include 'phar://users.phar/loader.php';
} catch (PharException $e) {
    echo $e->getMessage();
    die('Cannot initialize Phar');
}

__HALT_COMPILER(); ?>
