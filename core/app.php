<?php

namespace Core;

use Core\Include\xPDOConstruct;
use Exception;
use PDO;
use SmartyException;

class App {
    /**
     * @throws Exception
     */
    public function init(): void {

        Locale::init();


        Router::route($_SERVER['REQUEST_URI']);
        //$ext = new Extensions();
        //$ext->install('example-phar');
    }
}