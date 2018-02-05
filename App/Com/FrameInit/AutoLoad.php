<?php

namespace App\Com\FrameInit;

use Core\AutoLoader;

class AutoLoad
{
    private static $instance;

    function __construct()
    {
        $loader = AutoLoader::getInstance();
        $loader->requireFile('vendor/autoload.php');
        $loader->requireFile('Conf/Helpers.php');
        date_default_timezone_set('Asia/Shanghai');
    }

    static function getInstance()
    {
        if ( !isset(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }
}