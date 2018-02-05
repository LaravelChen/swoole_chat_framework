<?php

namespace App\Logic\UserCenter;

use App\Base\UserCenter\User;
use App\Com\Response\FrameWorkCode;
use App\Com\Response\MessageType;

use Core\Component\Logger;
use Illuminate\Database\Capsule\Manager;
use Overtrue\EasySms\EasySms;

class UserLogic
{
    public function insert($params)
    {
        try {
            Manager::connection()->beginTransaction();
            $result = User::create($params);
            if ( !$result) {
                Manager::connection()->rollBack();
                return response()->exception(FrameWorkCode::ADD_ERROR);
            }
            Manager::connection()->commit();
            return response()->success($result);
        } catch (\Exception $e) {
            Manager::connection()->rollBack();
            Logger::getInstance()->log($e);
            return response()->exception(FrameWorkCode::ADD_ERROR);
        } catch (\Throwable $e) {
            Manager::connection()->rollBack();
            Logger::getInstance()->log($e);
            return response()->exception(FrameWorkCode::STATUS_EXCEPTION);
        };
    }

    public function sendCode($params)
    {
        $config = \config()->getConf('MESSAGE');
        $easySms = new EasySms($config);
        #发送短信
        $code = mt_rand(10000, 99999);
        if ($params['type'] == 'register') {
            $key = MessageType::REGISTER_SEND_CODE . $params['phone'];
        }
        if ($params['type'] == 'login') {
            $key = MessageType::LOGIN_SEND_CODE . $params['phone'];
        }
        cache()->set($key, $code);
        cache()->expire($key, 60 * 60);
        #发送短信
//        $easySms->send(intval($params['phone']), [
//            'template' => MessageType::SEND_MESSAGE_TEMPLATE,
//            'data' => [
//                'code' => $code,
//            ],
//        ]);
        return response()->success(['code' => $code]);
    }
}