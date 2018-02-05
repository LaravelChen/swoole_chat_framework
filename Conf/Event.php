<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2017/1/23
 * Time: 上午12:06
 */

namespace Conf;


use App\Bll\WebSocket\Parser;
use App\Bll\WebSocket\Register;
use App\Com\DataBase\DataBaseInit;
use App\Com\FrameInit\AutoLoad;
use App\Com\Providers\UserCenterProvider;
use App\Middleware\CORSMiddleware;
use App\Middleware\SignValidationMiddleware;
use App\Middleware\TokenValidationMiddleware;
use Core\AbstractInterface\AbstractEvent;
use Core\Component\Socket\Dispatcher;
use Core\Component\Version\Control;
use Core\Http\Request;
use Core\Http\Response;

class Event extends AbstractEvent
{
    function frameInitialize()
    {
        // TODO: Implement frameInitialize() method.
        AutoLoad::getInstance();  #初始化加载
    }

    function frameInitialized()
    {
        // TODO: Implement frameInitialized() method.
        //使用Laravel数据库Model形式
        DataBaseInit::getInstance();
        #注册Di(依赖注入)
        UserCenterProvider::getInstance();
    }


    function beforeWorkerStart(\swoole_server $server)
    {
        // TODO: Implement beforeWorkerStart() method.
        $server->on("message", function (\swoole_websocket_server $server, \swoole_websocket_frame $frame) {
            Dispatcher::getInstance(Register::class, Parser::class)->dispatchWEBSOCK($frame);
        });
    }

    function onStart(\swoole_server $server)
    {
        // TODO: Implement onStart() method.
    }

    function onShutdown(\swoole_server $server)
    {
        // TODO: Implement onShutdown() method.
    }

    function onWorkerStart(\swoole_server $server, $workerId)
    {
        // TODO: Implement onWorkerStart() method.
    }

    function onWorkerStop(\swoole_server $server, $workerId)
    {
        // TODO: Implement onWorkerStop() method.
    }

    function onRequest(Request $request, Response $response)
    {
        // TODO: Implement onRequest() method.
        CORSMiddleware::getInstance()->handle($request, $response);  #跨域中间件处理
        SignValidationMiddleware::getInstance()->handle($request, $response);  #签名验证
        TokenValidationMiddleware::getInstance()->handle($request, $response);  #token验证
    }

    function onDispatcher(Request $request, Response $response, $targetControllerClass, $targetAction)
    {
        // TODO: Implement onDispatcher() method.
    }

    function onResponse(Request $request, Response $response)
    {
        // TODO: Implement afterResponse() method.
    }

    function onTask(\swoole_server $server, $taskId, $workerId, $taskObj)
    {
        // TODO: Implement onTask() method.
    }

    function onFinish(\swoole_server $server, $taskId, $taskObj)
    {
        // TODO: Implement onFinish() method.
    }

    function onWorkerError(\swoole_server $server, $worker_id, $worker_pid, $exit_code)
    {
        // TODO: Implement onWorkerError() method.
    }
}
