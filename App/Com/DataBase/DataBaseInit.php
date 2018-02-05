<?php

namespace App\Com\DataBase;

use Conf\Config;
use Core\Component\Di;
use Illuminate\Database\Capsule\Manager as Capsule;

class DataBaseInit
{
    private static $instance;

    function __construct()
    {
        $dbConf = Config::getInstance()->getConf('DATABASE');
        $capsule = new Capsule;
        //连接数据库
        $capsule->addConnection($dbConf);
        //设置全局静态可访问
        $capsule->setAsGlobal();
        // 启动Eloquent
        $capsule->bootEloquent();
    }

    static function getInstance()
    {
        if ( !isset(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }
}