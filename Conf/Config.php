<?php

/**
 * Created by PhpStorm.
 * User: YF
 * Date: 16/8/25
 * Time: 上午12:05
 */

namespace Conf;

use Core\Component\Spl\SplArray;

class Config
{
    private static $instance;
    protected $conf;

    function __construct()
    {
        $conf = $this->sysConf() + $this->userConf();
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

    private function sysConf()
    {
        return [
            "ENV" => "dev",
            "SERVER" => [
                "LISTEN" => "0.0.0.0",
                "SERVER_NAME" => "",
                "PORT" => 9501,
                "RUN_MODE" => SWOOLE_PROCESS,//不建议更改此项
                "SERVER_TYPE" => \Core\Swoole\Config::SERVER_TYPE_WEB_SOCKET,//
                'SOCKET_TYPE' => SWOOLE_TCP,//当SERVER_TYPE为SERVER_TYPE_SERVER模式时有效
                "CONFIG" => [
                    'task_worker_num' => 8, //异步任务进程
                    "task_max_request" => 10,
                    'max_request' => 5000,//强烈建议设置此配置项
                    'worker_num' => 8,
                ],
            ],
            "DEBUG" => [
                "LOG" => true,
                "DISPLAY_ERROR" => true,
                "ENABLE" => true,
            ],
            "CONTROLLER_POOL" => true,//web或web socket模式有效
            "ForwardingDomain" => "http://swoole-framework.dev:7777",//静态资源的域名(此处为nginx分配的域名,用于web开发)

            "ORIGIN" => [
                "http://localhost:3000",
            ],
            #登录秘钥
            "SIGN" => [
                'secret' => 'LaravelChen',
                'iss' => 'http://127.0.0.1:9501',
                'aud' => 'http://localhost:3000',
                'exp' => time() + 60 * 60 * 60,
                'lose' => -1,
            ],
            'SALT' => "UmVwb3J0U2lnblZhbGlkYXRpb24=", #盐值（用于前后段分离的接口标识）
            'VERSION' => 'v1.0.0',  #接口版本

            #聊天种类
            'PUBLIC_CHAT' => 'PUBLIC', #群聊
            'PUBLIC_USER_LIST' => 'PUBLIC_USER_LIST',#群聊的在线用户
            'PUBLIC_USER_CLOSE' => 'PUBLIC_USER_CLOSE',#群聊的用户关闭
        ];
    }

    private function userConf()
    {
        return [
            'DATABASE' => [
                'driver' => 'mysql',
                'host' => '127.0.0.1',
                'prot' => '3306',
                'database' => 'swoole_framework',
                'username' => 'root',
                'password' => 'root',
                'charset' => 'utf8',
                'collation' => 'utf8_general_ci',
                'prefix' => '',
            ],
            'REDIS' => [
                'cluster' => false,
                'default' => [
                    'host' => '127.0.0.1',
                    'port' => 6379,
                    'database' => 0,
                ],
            ],

            #短信配置
            'MESSAGE' => [
                // HTTP 请求的超时时间（秒）
                'timeout' => 5.0,

                // 默认发送配置
                'default' => [
                    // 网关调用策略，默认：顺序调用
                    'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,

                    // 默认可用的发送网关
                    'gateways' => [
                        'aliyun',
                    ],
                ],
                // 可用的网关配置
                'gateways' => [
                    'aliyun' => [
                        'access_key_id' => 'LTAIEsjU4kdGShIV',
                        'access_key_secret' => 'J379helorkKSfJZ6XC21FsZnPXnuSj',
                        'sign_name' => '微课堂',
                    ],
                ],
            ],
        ];
    }
}