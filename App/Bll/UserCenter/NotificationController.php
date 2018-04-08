<?php

namespace App\Bll\UserCenter;


use App\Bll\IndexController;
use App\Com\Response\FrameWorkCode;
use App\Com\Traits\AbstractControllerTraits;
use App\Repository\UserCenter\Contracts\NotificationContract;
use Conf\Config;
use Core\Component\Logger;
use Illuminate\Support\Arr;

class NotificationController extends IndexController
{
    use AbstractControllerTraits;

    /**
     * 发送通知
     * @return bool
     */
    public function POSTsendNotification()
    {
        $params = request_data();
        $rule = [
            'type' => 'required|in:ADDUSER',
            'user_id' => 'required',
            'to_user_id' => 'required',
            'message' => 'required',
        ];
        $valid = $this->com_validate($params, $rule);
        if ( !$valid['is_valid']) {
            Logger::getInstance()->console($valid['errors']);
            return response()->exception(FrameWorkCode::PARAMETER_ERROR);
        }
        $args = [
            'where' => [
                'user_id' => $params['user_id'],
                'to_user_id' => $params['to_user_id'],
                'type' => 'ADDUSER',
            ],
        ];
        $result = app(NotificationContract::class)->getOne($args);
        if ($result['code'] != 1102) {
            return response()->exception(FrameWorkCode::NOTIFIACTION_EXIST);
        }
        $args = [
            'type' => $params['type'],
            'user_id' => $params['user_id'],
            'to_user_id' => $params['to_user_id'],
            'message' => $params['message'],
        ];
        $result = app(NotificationContract::class)->insert($args);
        #发消息
        $notice = [
            'email' => $params['email'],
            'message' => $params['message'],
            'to_user_id' => $params['to_user_id'],
        ];
        app(NotificationContract::class)->send($notice);
        return response()->success($result);
    }

    /**
     *获取通知数目
     */
    public function POSTgetNotificationCount()
    {
        $params = request_data();
        $rule = [
            'type' => 'required|in:ADDUSER',
            'to_user_id' => 'required',
            'is_read' => 'required',
        ];
        $valid = $this->com_validate($params, $rule);
        if ( !$valid['is_valid']) {
            Logger::getInstance()->console($valid['errors']);
            return response()->exception(FrameWorkCode::PARAMETER_ERROR);
        }
        $args = [
            'type' => $params['type'],
            'to_user_id' => $params['to_user_id'],
            'is_read' => "NO",
        ];
        $result = app(NotificationContract::class)->getNotificationCount($args);
        return response()->success($result);
    }

    /**
     * 获取通知列表
     * @return bool
     */
    public function POSTgetNotificationList()
    {
        $params = request_data();
        $rule = [
            'to_user_id' => 'required',
        ];
        $valid = $this->com_validate($params, $rule);
        if ( !$valid['is_valid']) {
            Logger::getInstance()->console($valid['errors']);
            return response()->exception(FrameWorkCode::PARAMETER_ERROR);
        }
        $args = [
            'page' => Arr::get($params, 'paging.page', 1),
            'page_size' => Arr::get($params, 'paging.limit', 20),
            'where' => [
                'to_user_id' => $params['to_user_id'],
            ],
            'fields' => ['to_user_id', 'user_id', 'id', 'message', 'avatar', 'name', 'action', 'action_name'],
        ];
        $result = app(NotificationContract::class)->getNotificationList($args);
        return response()->success($result);
    }

}