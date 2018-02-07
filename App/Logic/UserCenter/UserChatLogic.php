<?php

namespace App\Logic\UserCenter;


use App\Base\UserCenter\UserChat;
use App\Com\Response\FrameWorkCode;
use Core\Component\Logger;
use Illuminate\Database\Capsule\Manager;

class UserChatLogic
{
    /*
     * 存储聊天记录
     */
    public function insert($params)
    {
        try {
            Manager::connection()->beginTransaction();
            $result = UserChat::create($params);
            if ( !$result) {
                Manager::connection()->rollBack();
                return response()->exception(FrameWorkCode::ADD_ERROR);
            }
            Manager::connection()->commit();
            return true;
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
}