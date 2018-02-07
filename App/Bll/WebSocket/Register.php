<?php

namespace App\Bll\WebSocket;


use App\Repository\UserCenter\Contracts\UserChatContract;
use App\Repository\UserCenter\Contracts\UserContract;
use Conf\Config;
use Core\Component\Socket\AbstractInterface\AbstractCommandRegister;
use Core\Component\Socket\Client\TcpClient;
use Core\Component\Socket\Common\Command;
use Core\Component\Socket\Common\CommandList;
use Core\Swoole\Server;
use function GuzzleHttp\Psr7\uri_for;
use Illuminate\Support\Arr;

class Register extends AbstractCommandRegister
{

    function register(CommandList $commandList)
    {
        // TODO: Implement register() method.
        $commandList->addCommandHandler(Config::getInstance()->getConf('PUBLIC_CHAT'), function (Command $command, TcpClient $tcpClient) {
            $this->sendPublicMessage($command, $tcpClient);
        });

        $commandList->addCommandHandler(Config::getInstance()->getConf('PUBLIC_USER_LIST'), function (Command $command, TcpClient $tcpClient) {
            $this->sendPublicUserList($command, $tcpClient);
        });
    }

    /*
     * 群发消息
     */
    public function sendPublicMessage(Command $command, TcpClient $tcpClient)
    {
        $message = $command->getMessage();
        $message['content']['fd'] = $tcpClient->getFd() . time();
        $content = json_encode($message);
        #存储聊天记录
        $args = [
            'user_id' => $message['content']['user_id'],
            'action' => $message['action'],
            'chat_content' => $message['content']['message'],
        ];
        $result = app(UserChatContract::class)->insert($args);

        #群发消息
        if ($result) {
            foreach (Server::getInstance()->getServer()->connections as $fd) {
                Server::getInstance()->getServer()->push($fd, $content);
            }
        }
    }

    /*
     * 群聊的在线用户列表
     */
    public function sendPublicUserList(Command $command, TcpClient $tcpClient)
    {
        $message = $command->getMessage();
        $content = json_encode($message);
        $title = Config::getInstance()->getConf("PUBLIC_USER_LIST");
        cache()->hset('hash1', $tcpClient->getFd(), $content);
        $content = array_unique(cache()->hvals('hash1'));
        $user_list = [];
        foreach ($content as $data) {
            array_push($user_list, json_decode($data, true));
        }
        $user_last['action'] = $title;
        $user_last['content'] = $user_list;
        $user_list = json_encode($user_last);
        foreach (Server::getInstance()->getServer()->connections as $fd) {
            Server::getInstance()->getServer()->push($fd, $user_list);
        }
    }
}