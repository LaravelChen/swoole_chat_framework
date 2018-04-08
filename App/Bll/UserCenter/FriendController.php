<?php

namespace App\Bll\UserCenter;


use App\Bll\IndexController;
use App\Com\Response\FrameWorkCode;
use App\Com\Traits\AbstractControllerTraits;
use App\Repository\UserCenter\Contracts\FriendContract;
use App\Repository\UserCenter\Contracts\NotificationContract;
use App\Repository\UserCenter\Contracts\UserChatContract;
use Core\Component\Logger;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class FriendController extends IndexController
{
    use AbstractControllerTraits;

    /**
     * 获取好友列表
     * @return bool
     */
    public function POSTgetFriendList()
    {
        $params = request_data();
        $rule = [
            'user_id' => 'required',
        ];
        $valid = $this->com_validate($params, $rule);
        if ( !$valid['is_valid']) {
            Logger::getInstance()->console($valid['errors']);
            return response()->exception(FrameWorkCode::PARAMETER_ERROR);
        }
        $args = [
            'user_id' => $params['user_id'],
        ];
        $result = app(FriendContract::class)->getFriendList($args);
        return response()->success($result);
    }

    /**
     * 获取好友信息
     * @return bool
     */
    public function POSTgetFriendInfo()
    {
        $params = request_data();
        $rule = [
            'user_id' => 'required',
        ];
        $valid = $this->com_validate($params, $rule);
        if ( !$valid['is_valid']) {
            Logger::getInstance()->console($valid['errors']);
            return response()->exception(FrameWorkCode::PARAMETER_ERROR);
        }
        $args = [
            'user_id' => $params['user_id'],
        ];
        $result = app(FriendContract::class)->getFriendInfo($args);
        return response()->success($result);
    }

    /**
     * 私聊记录
     * @return bool
     */
    public function POSTgetPrivateChatList()
    {
        $params = request_data();
        $rule = [
            'user_id' => 'required',
        ];
        $valid = $this->com_validate($params, $rule);
        if ( !$valid['is_valid']) {
            Logger::getInstance()->console($valid['errors']);
            return response()->exception(FrameWorkCode::PARAMETER_ERROR);
        }
        $args = [
            'page' => Arr::get($params, 'paging.page', 1),
            'page_size' => Arr::get($params, 'paging.limit', 100),
            'where' => [
                'action' => 'PRIVATE',
                'user_id' => [$params['user_id'], $params['to_user_id']],
                'to_user_id'=>[$params['user_id'], $params['to_user_id']],
            ],
            'fields' => ['chat_content', 'user_id', 'action', 'name', 'avatar', 'email', 'id', 'to_user_id'],
        ];
        $result = app(UserChatContract::class)->lists($args);
        $last_chat = [];
        collect($result['data'])->map(function ($data) use (&$last_chat, $params) {
            $chat['action'] = $data['action'];
            $chat['content'] = [
                'fd' => Arr::get($data, 'id', '') . time(),
                'user_id' => Arr::get($data, 'user_id', ''),
                'to_user_id' => Arr::get($data, 'to_user_id', ''),
                'name' => Arr::get($data, 'name', ''),
                'avatar' => Arr::get($data, 'avatar', ''),
                'email' => Arr::get($data, 'email', ''),
                'message' => Arr::get($data, 'chat_content', ''),
            ];
            array_push($last_chat, $chat);
        });
        $result['data'] = $last_chat;
        return response()->success($result);
    }

    /**
     * 接受好友请求
     * @return bool
     */
    public function POSTReceivce()
    {
        $params = request_data();
        $rule = [
            'user_id' => 'required',
            'to_user_id' => 'required',
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
            ],
            'update' => [
                'action' => 'RECEIVE',
                'is_read' => 'YES',
            ],
        ];
        try {
            Manager::connection()->beginTransaction();
            $result = app(NotificationContract::class)->update($args);
            //存储好友
            $args = [
                'user_id' => $params['user_id'],
                'to_user_id' => $params['to_user_id'],
            ];
            $args2 = [
                'user_id' => $params['to_user_id'],
                'to_user_id' => $params['user_id'],
            ];
            app(FriendContract::class)->insert($args);
            app(FriendContract::class)->insert($args2);
            Manager::connection()->commit();
            return response()->success($result);
        } catch (\Exception $e) {
            Manager::connection()->rollBack();
            return response()->exception(FrameWorkCode::STATUS_EXCEPTION);
        } catch (\Throwable $e) {
            return response()->exception(FrameWorkCode::STATUS_EXCEPTION);
            Manager::connection()->rollBack();
        };
    }

    /**
     * 拒绝好友请求
     * @return bool
     */
    public function POSTRefuse()
    {
        $params = request_data();
        $rule = [
            'user_id' => 'required',
            'to_user_id' => 'required',
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
            ],
            'update' => [
                'action' => 'REFUSE',
                'is_read' => 'YES',
            ],
        ];
        $result = app(NotificationContract::class)->update($args);
        return response()->success($result);
    }
}