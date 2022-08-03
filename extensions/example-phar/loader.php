<?php

class Loader
{
    public static function MetaInterface() :array
    {
        return ["Info" => ['author' => 'Leonid Kuzin <dg.inc.lcf@gmail.com>', 'version' => '0.0.1-dev', 'interaction' => 'internal'],
            'Instances' => ['example::print_hello_world :string']];
    }
}
