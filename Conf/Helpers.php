<?php

if ( !function_exists('app')) {
    /**
     * 获取Di容器
     *
     * @param  string $abstract
     * @return mixed|\Core\Component\Di
     */
    function app($abstract = null)
    {
        if (is_null($abstract)) {
            return \Core\Component\Di::getInstance();
        }

        return \Core\Component\Di::getInstance()->get($abstract);
    }
}
if ( !function_exists('request')) {
    function request()
    {
        return \Core\Http\Request::getInstance();
    }
}

if ( !function_exists('request_data')) {
    function request_data()
    {
        $data = \Core\Http\Request::getInstance()->getRequestParam('data');
        if (is_string($data)) {
            return json_decode($data, true);
        }
        return $data;
    }
}

if ( !function_exists('request_sign')) {
    function request_sign()
    {
        $data = \Core\Http\Request::getInstance()->getRequestParam('sign');
        if (is_string($data)) {
            return json_decode($data, true);
        }
        return $data;
    }
}

if ( !function_exists('request_token')) {
    function request_token()
    {
        $data = \Core\Http\Request::getInstance()->getRequestParam('token');
        if (is_string($data)) {
            return json_decode($data, true);
        }
        return $data;
    }
}


if ( !function_exists('response')) {
    function response()
    {
        return \Core\Http\Response::getInstance();
    }
}

if ( !function_exists('cache')) {
    function cache()
    {
        return \App\Vendor\Redis\Cache::getInstance()->redisConnect()->connection();
    }
}

if ( !function_exists('config')) {
    function config()
    {
        return \Conf\Config::getInstance();
    }
}


if ( !function_exists('asset')) {
    /**
     * 转发静态文件
     *      \Conf\Config::getInstance()->getConf('ForwardingDomain')  转发域名
     * @param string $path 静态文件路径
     * @return string
     */
    function asset($path = '')
    {
        return \Conf\Config::getInstance()->getConf('ForwardingDomain') . '/' . $path;
    }
}

if ( !function_exists('sign')) {
    function sign($params)
    {
        $sign = new \Lcobucci\JWT\Signer\Hmac\Sha256();
        $token = (new \Lcobucci\JWT\Builder())->setIssuer(\Conf\Config::getInstance()->getConf('SIGN.iss'))
            ->setAudience(\Conf\Config::getInstance()->getConf('SIGN.aud'))
            ->setId($params, true)
            ->setIssuedAt(time())
            ->setExpiration(\Conf\Config::getInstance()->getConf('SIGN.exp'))
            ->sign($sign, \Conf\Config::getInstance()->getConf('SIGN.secret'))
            ->getToken();
        return $token . "";
    }
}

if ( !function_exists('csrf_token')) {
    /**
     * 返回token
     * @return mixed|null
     */
    function csrf_token()
    {
        return \Core\Http\Request::getInstance()->session()->get('_token');
    }
}
