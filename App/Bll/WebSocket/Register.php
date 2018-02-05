<?php

namespace App\Bll\WebSocket;


use Core\Component\Socket\AbstractInterface\AbstractCommandRegister;
use Core\Component\Socket\Client\TcpClient;
use Core\Component\Socket\Common\Command;
use Core\Component\Socket\Common\CommandList;
use Core\Swoole\Server;

class Register extends AbstractCommandRegister
{

    function register(CommandList $commandList)
    {
        // TODO: Implement register() method.
        $commandList->addCommandHandler("public", function (Command $command, TcpClient $tcpClient) {
            $this->sendPublicMessage($command, $tcpClient);
        });
    }

    /*
     * 群发消息
     */
    public function sendPublicMessage(Command $command, TcpClient $tcpClient)
    {
        $message = $command->getMessage();
        $message['content']['fd'] = $tcpClient->getFd() . time();
        $message=json_encode($message);
        foreach (Server::getInstance()->getServer()->connections as $fd) {
            Server::getInstance()->getServer()->push($fd, $message);
        }
    }
}