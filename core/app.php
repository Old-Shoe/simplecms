<?php

namespace Core;

use Exception;

class app {
    /**
     * @throws Exception
     */
    function init(): void {

        Locale::init();
        // главная страница вашсайт.рф
        Router::route('/', function () {
            print 'Домашняя станица';
        });

        // маршрут будет срабатывать на адрес вашсайт.рф/blog/myrusakov/12091983
        // и подобные
        Router::route('blog/(\w+)/(\d+)', function ($category, $id) {
            print $category . ':' . $id;
        });

        // запускаем маршрутизатор, передавая ему запрошенный адрес
        Router::execute($_SERVER['REQUEST_URI']);
    }
}