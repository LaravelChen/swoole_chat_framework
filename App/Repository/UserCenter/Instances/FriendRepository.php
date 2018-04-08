<?php

namespace App\Repository\UserCenter\Instances;


use App\Repository\UserCenter\Contracts\FriendContract;
use App\Vendor\Invoker\Invoker;

class FriendRepository implements FriendContract
{
    public function getFriendList($params)
    {
        $class = 'App.Logic.UserCenter.FriendLogic.getFriendlist';
        return Invoker::execute($class, $params);
    }

    public function getFriendInfo($params)
    {
        $class = 'App.Logic.UserCenter.FriendLogic.getFriendInfo';
        return Invoker::execute($class, $params);
    }

    public function insert($params)
    {
        $class = 'App.Logic.UserCenter.FriendLogic.insert';
        return Invoker::execute($class, $params);
    }
}