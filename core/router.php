<?php

namespace Core;

use Core\Include\VPDO;
use PDO;

class Router
{
    private function __construct() {}
    private function __clone() {}

    private static function sanitize_url($value): bool|array
    {
        if (!str_contains($value, '/')) return false;
        $arr = explode('/', trim($value, '/'), 2);
        if (empty($arr)) {
            return false;
        } else {
            return $arr;
        }
    }

    public static function execute($filtered_request): void
    {
        if($filtered_request != '/' && $_SERVER['REQUEST_METHOD'] == 'POST')
        {
            $request = filter_var($filtered_request, FILTER_CALLBACK, array('options' => __NAMESPACE__ . '\Router::sanitize_url'));

            VPDO::connect();
            $class_instance = VPDO::getSingleRecord('SELECT extension FROM instances WHERE function = :function', $request[1], 'function', PDO::PARAM_STR);
            $file_name = VPDO::getSingleRecord('SELECT file_name FROM extensions where alias = :alias', $class_instance, 'alias', PDO::PARAM_STR);
            require SIMPLECMS_ROOT_DIR . DIRECTORY_SEPARATOR . 'extensions' . DIRECTORY_SEPARATOR . $file_name;
            $GLOBALS['simplecms']['extensions'] = $file_name;
            var_dump(call_user_func(implode('::', $request)));
        }
    }
}