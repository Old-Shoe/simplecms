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

        Router::execute($_SERVER['REQUEST_URI']);
    }
}