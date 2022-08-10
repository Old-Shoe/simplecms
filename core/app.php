<?php

namespace Core;

use Core\Include\VPDO;
use Exception;
use PDO;
use SmartyException;

class App {
    /**
     * @throws Exception
     */
    function init(): void {

        Locale::init();

        $temp = new Template();
        $temp->assign(['title' => 'BBBBBBBB']);
        try {
            $var = $temp->fetch('main.tpl');
            var_dump($var);
        } catch (SmartyException $e) {
        }

        Router::execute(filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL));



        //var_dump(example::print_hello_world());
        //var_dump(call_user_func('example::print_hello_world'));

        //Router::execute();
    }
}