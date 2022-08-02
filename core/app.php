<?php

namespace SimpleCMS\Core;

class app {
    function init(): void {
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