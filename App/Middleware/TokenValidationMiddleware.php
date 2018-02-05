<?php

namespace App\Middleware;


use App\Com\Response\FrameWorkCode;
use Conf\Config;
use Conf\Servlet;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\ValidationData;

class TokenValidationMiddleware
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
            $not_login = Servlet::getInstance()->getConf("NOT_LOGIN");
            $path = $request->getUri()->getPath();
            if ( !in_array($path, $not_login)) {
                $token = $request->getRequestParam("token");
                if ( !$token) {
                    $response->exception(FrameWorkCode::ERROR_TOKEN);
                    return $response->end();
                }
                $token = (new Parser())->parse($token);
                $data = new ValidationData();
                if ( !$token->validate($data)) {
                    $response->exception(FrameWorkCode::ERROR_TOKEN);
                    return $response->end();
                }
            }
        }

    }
}