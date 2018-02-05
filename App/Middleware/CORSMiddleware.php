<?php

namespace App\Middleware;


use Conf\Config;

class CORSMiddleware
{
    protected static $instance;

    public static function getInstance()
    {
        if ( !isset(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    public function handle($request, $response)
    {
        $response->withHeader("Cache-Control", "no-cache");
        $response->withHeader('Access-Control-Allow-Origin', Config::getInstance()->getConf("ORIGIN"));
        $response->withHeader('Access-Control-Allow-Methods', 'GET,POST,OPTIONS');
        $response->withHeader('Access-Control-Allow-Headers', 'Content-Type,x-requested-with');
        $response->withHeader('Access-Control-Allow-Credentials', 'true');
    }
}