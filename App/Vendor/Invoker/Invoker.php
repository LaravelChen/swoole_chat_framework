<?php

namespace App\Vendor\Invoker;

class Invoker
{
    public static function execute($action, $params)
    {
        $action = explode('.', $action);
        $method = array_pop($action);
        $class = implode('\\', $action);
        $reflection = new \ReflectionClass ($class);
        $instance = $reflection->newInstanceArgs();
        $method = $reflection->getMethod($method);
        return $method->invokeArgs($instance, array($params));
    }
}