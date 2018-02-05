<?php

namespace Conf;

use Core\Component\Spl\SplArray;

class Servlet
{
    private static $instance;
    protected $conf;

    function __construct()
    {
        $conf = $this->conf();
        $this->conf = new SplArray($conf);
    }

    static function getInstance()
    {
        if ( !isset(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    function getConf($keyPath)
    {
        return $this->conf->get($keyPath);
    }

    /*
            * 在server启动以后，无法动态的去添加，修改配置信息（进程数据独立）
    */
    function setConf($keyPath, $data)
    {
        $this->conf->set($keyPath, $data);
    }

    function conf()
    {
        return [
            "NOT_LOGIN" => [
                '/UserCenter/UserCenterController/Login',
                '/UserCenter/UserCenterController/Register',
                '/UserCenter/UserCenterController/SendCode'
            ],
        ];
    }

}