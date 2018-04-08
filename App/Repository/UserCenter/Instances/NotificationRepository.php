<?php

namespace App\Repository\UserCenter\Instances;


use App\Repository\UserCenter\Contracts\NotificationContract;
use App\Vendor\Invoker\Invoker;

class NotificationRepository implements NotificationContract
{
    public function insert($params)
    {
        $class = 'App.Logic.UserCenter.NotificationLogic.insert';
        return Invoker::execute($class, $params);
    }

    public function send($params)
    {
        $class = 'App.Logic.UserCenter.NotificationLogic.send';
        return Invoker::execute($class, $params);
    }

    public function getNotificationCount($params)
    {
        $class = 'App.Logic.UserCenter.NotificationLogic.getNotificationCount';
        return Invoker::execute($class, $params);
    }

    public function getNotificationList($params)
    {
        $class = 'App.Base.UserCenter.Notification.lists';
        return Invoker::execute($class, $params);
    }

    public function update($params)
    {
        $class = 'App.Base.UserCenter.Notification.saveOne';
        return Invoker::execute($class, $params);
    }

    public function getOne($params)
    {
        $class = 'App.Base.UserCenter.Notification.getOne';
        return Invoker::execute($class, $params);
    }
}