<?php

namespace Core;

use Smarty;

class templates
{
    function main()
    {
        $smarty = new Smarty();
        //Включаем кэширование страницы(по умолчанию на один час)
        $smarty->setCaching(Smarty::CACHING_LIFETIME_CURRENT);
        //Создадим переменную для примера
        $name = 'Vasya';
        //Передаем переменную в шаблонизатор Smarty
        $smarty->assign('name',$name);
        //Выводим на экран
        $smarty->display(SIMPLECMS_ROOT_DIR . '/web/templates/main.tpl');
    }
}