<?php

namespace App\Middleware;


use App\Com\Response\FrameWorkCode;
use Conf\Config;

class SignValidationMiddleware
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
        $server = $request->getServerParams();
        if ($server['request_method'] !== "OPTIONS") {
            $sign = $request->getRequestParam("sign");
            $encrypt = $request->getUri()->getPath() . Config::getInstance()->getConf('VERSION') . Config::getInstance()->getConf('SALT');
            $encrypt = md5($encrypt);
            if ($sign != $encrypt) {
                if (Config::getInstance()->getConf('ENV') == 'dev') {
                    $response->writeJson(500, ['message' => '签名不匹配!', 'sign' => $encrypt], FrameWorkCode::FLAG_NOTICE);
                } else {
                    $response->exception(FrameWorkCode::ERROR_SIGN);
                }
                return $response->end();
            }
        }
    }
}