<?php

namespace Core;

use Exception;

class app {
    /**
     * @throws Exception
     */
    function init(): void {

        Locale::init();

        Router::route('/', function () {
            print 'Домашняя станица';
        });

        Router::execute(filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL));
    }
}