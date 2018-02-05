<?php

namespace App\Bll\WebSocket;


use Core\Component\Socket\AbstractInterface\AbstractClient;
use Core\Component\Socket\AbstractInterface\AbstractCommandParser;
use Core\Component\Socket\Common\Command;

class Parser extends AbstractCommandParser
{
    function parser(Command $result, AbstractClient $client, $rawData)
    {
        // TODO: Implement parser() method.
        $data = json_decode($rawData, 1);
        if (is_array($data)) {
            if (isset($data['action'])) {
                $result->setCommand($data['action']);
            }
            if (isset($data['content'])) {
                $result->setMessage($data);
            }
        }
    }


}