<?php

namespace App\Repository\UserCenter\Instances;

use App\Repository\UserCenter\Contracts\UserContract;
use App\Vendor\Invoker\Invoker;

class UserRepository implements UserContract
{
    public function getOne($params)
    {
        $class = 'App.Base.UserCenter.User.getOne';
        return Invoker::execute($class, $params);
    }

    public function insert($params)
    {
        $class = 'App.Logic.UserCenter.UserLogic.insert';
        return Invoker::execute($class, $params);
    }

    public function sendCode($params)
    {
        $class = 'App.Logic.UserCenter.UserLogic.sendCode';
        return Invoker::execute($class, $params);
    }

    public function getFriendList($params)
    {
        $class = 'App.Logic.UserCenter.UserLogic.getFriendList';
        return Invoker::execute($class, $params);
    }
}