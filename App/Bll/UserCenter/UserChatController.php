<?php

namespace App\Bll\UserCenter;


use App\Bll\IndexController;
use App\Com\Response\FrameWorkCode;
use App\Com\Traits\AbstractControllerTraits;
use App\Repository\UserCenter\Contracts\UserChatContract;
use Core\Component\Logger;
use Core\Swoole\Server;
use Illuminate\Support\Arr;

class UserChatController extends IndexController
{
    use AbstractControllerTraits;

    /**
     * 群聊的聊天记录
     * @return bool
     */
    public function POSTChatList()
    {
        $params = request_data();
        $rule = [
            'paging' => 'array',
            'paging.page' => 'int|min:1',
            'paging.limit' => 'int|between:1,2000',
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
                'action' => 'PUBLIC',
            ],
            'fields' => ['chat_content', 'user_id', 'action', 'name', 'avatar', 'email', 'id'],
        ];
        $result = app(UserChatContract::class)->lists($args);
        $last_chat = [];
        collect($result['data'])->map(function ($data) use (&$last_chat) {
            $chat['action'] = $data['action'];
            $chat['content'] = [
                'fd' => Arr::get($data, 'id', '') . time(),
                'user_id' => Arr::get($data, 'user_id', ''),
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
     * 获取所有的fds
     */
    public function POSTgetFds()
    {
        $list = [];
        foreach (Server::getInstance()->getServer()->connections as $connection) {
            array_push($list, $connection);
        }
        $this->response()->writeJson(200, $list, "this is all websocket list");
    }

    /**
     * 获取在线用户列表
     * @return bool
     */
    public function POSTgetUserOnlineList()
    {
        $list = [];
        foreach (Server::getInstance()->getServer()->connections as $connection) {
            $work = Server::getInstance()->getServer()->connection_info($connection);
            if ($work['websocket_status']) {
                array_push($list, $connection);
            }
        }
        $result = [];
        $data = cache()->hgetall('userlist');
        collect($list)->each(function ($q) use ($data, &$result) {
            array_push($result, $data[$q]);
        });
        $result = array_values(array_unique($result));
        foreach ($result as &$data){
            $data=json_decode($data);
        }
        return response()->success($result);
    }
}