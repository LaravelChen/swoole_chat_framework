<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2017/6/30
 * Time: 下午11:55
 */

namespace App;


use Core\AbstractInterface\AbstractRouter;
use Core\Component\Logger;
use Core\Http\Response;
use FastRoute\RouteCollector;

class Router extends AbstractRouter
{
    function register(RouteCollector $routeCollector)
    {
    }
}