<?php

namespace App\Logic\WebSocket;


use Conf\Config;

class UserChatLogic
{
    /**
     * 监听用户关闭连接
     * @param $params
     */
    public function userClose($params)
    {
        $server = $params['server'];
        $title = Config::getInstance()->getConf("PUBLIC_USER_LIST");
        cache()->hdel('userlist', $params['fd']);
        $content = array_unique(cache()->hvals('userlist'));
        $user_list = [];
        foreach ($content as $data) {
            array_push($user_list, json_decode($data, true));
        }
        $user_last['action'] = $title;
        $user_last['content'] = $user_list;
        $user_list = json_encode($user_last);
        foreach (cache()->hkeys('userlist') as $fd) {
            $work = $server->connection_info($fd);
            if ($work['websocket_status']) {
                $server->push($fd, $user_list);
            }
        }
    }
}