<?php

namespace App\Repository\UserCenter\Instances;


use App\Repository\UserCenter\Contracts\UserChatContract;
use App\Vendor\Invoker\Invoker;

class UserChatRepository implements UserChatContract
{
    public function insert($params)
    {
        $class = 'App.Logic.UserCenter.UserChatLogic.insert';
        return Invoker::execute($class, $params);
    }

    public function lists($params)
    {
        $class = 'App.Base.UserCenter.UserChat.lists';
        return Invoker::execute($class, $params);
    }
}