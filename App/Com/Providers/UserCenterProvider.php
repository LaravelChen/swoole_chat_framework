<?php

namespace App\Com\Providers;

use App\Repository\UserCenter\Contracts\FriendContract;
use App\Repository\UserCenter\Contracts\NotificationContract;
use App\Repository\UserCenter\Contracts\UserChatContract;
use App\Repository\UserCenter\Contracts\UserContract;
use App\Repository\UserCenter\Instances\FriendRepository;
use App\Repository\UserCenter\Instances\NotificationRepository;
use App\Repository\UserCenter\Instances\UserChatRepository;
use App\Repository\UserCenter\Instances\UserRepository;
use Core\Component\Di;

class UserCenterProvider
{
    private static $instance;
    private $di;

    function __construct()
    {
        $this->di = Di::getInstance();
        $this->conf();
    }

    static function getInstance()
    {
        if ( !isset(self::$instance)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    public function conf()
    {
        $this->di->set(UserContract::class, UserRepository::class);
        $this->di->set(UserChatContract::class, UserChatRepository::class);
        $this->di->set(NotificationContract::class, NotificationRepository::class);
        $this->di->set(FriendContract::class, FriendRepository::class);
    }
}