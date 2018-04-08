<?php

namespace App\Logic\UserCenter;


use App\Base\UserCenter\Notification;
use App\Com\Response\FrameWorkCode;
use Conf\Config;
use Core\Component\Logger;
use Core\Swoole\Server;
use function GuzzleHttp\Psr7\str;

class NotificationLogic
{
    /**
     * 存储通知
     * @param $params
     * @return bool
     */
    public function insert($params)
    {
        try {
            $result = Notification::create($params);
            if ( !$result) {
                return response()->exception(FrameWorkCode::ADD_ERROR);
            }
            return true;
        } catch (\Exception $e) {
            Logger::getInstance()->log($e);
            return response()->exception(FrameWorkCode::ADD_ERROR);
        } catch (\Throwable $e) {
            Logger::getInstance()->log($e);
            return response()->exception(FrameWorkCode::STATUS_EXCEPTION);
        };
    }


    /**
     * 发送信息
     * @param $params
     */
    public function send($params)
    {
        $data = cache()->hgetall('userlist');
        $keys = [];
        collect($data)->map(function ($value, $key) use ($params, &$keys) {
            if (strpos($value, $params['email'])) {
                $work = Server::getInstance()->getServer()->connection_info($key);
                if ($work['websocket_status']) {
                    array_push($keys, $key);
                }
            }
        });
        #获取通知数
        $args = [
            'to_user_id' => $params['to_user_id'],
            'is_read' => 'NO',
            'type' => 'ADDUSER',
        ];

        foreach ($keys as $fd) {
            $data = [
                'action' => Config::getInstance()->getConf('ADD_USER_NOTIFICATION'),
                'content' => [
                    'message' => $params['message'],
                    'count' => $this->getNotificationCount($args),
                ],
            ];
            Server::getInstance()->getServer()->push($fd, json_encode($data));
        }
    }

    /**
     * 获取通知数目
     * @param $params
     * @return mixed
     */
    public function getNotificationCount($params)
    {
        $count = Notification::where($params)->count();
        return $count;
    }

    /**
     * 获取消息通知列表
     * @param $params
     * @return mixed
     */
    public function getNotificationList($params)
    {
        $notifications = Notification::where('to_user_id', $params['to_user_id'])->get();
        return $notifications;
    }
}