<?php

namespace App\Com\Providers;

use App\Repository\UserCenter\Contracts\UserContract;
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
    }
}