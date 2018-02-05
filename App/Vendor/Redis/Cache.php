<?php

namespace App\Vendor\Redis;

use Conf\Config;
use Illuminate\Cache\RedisStore;

class Cache
{
    /**
     * @var void
     */
    private static $_instance = null;

    /**
     * @return Cache
     */
    static public function getInstance()
    {
        if (is_null(self::$_instance) || isset (self::$_instance)) {
            self::$_instance = new self ();
        }
        return self::$_instance;
    }

    /**
     * @param string $connection
     * @param string $driver phpredis/predis
     * @param string $prefix
     * @return \Predis\ClientInterface
     */
    public function redisConnect($connection=null, $driver=null, $prefix=null)
    {
        $config = Config::getInstance()->getConf('REDIS');
        $connection = !is_null($connection) ? $connection : 'default';
        $driver = !is_null($driver) ? $driver : 'predis';
        $prefix = !is_null($prefix) ? $prefix : 'es';
        $redis = new \Illuminate\Redis\RedisManager($driver, $config);
        $cache = new RedisStore($redis, $prefix, $connection);
        return $cache;
    }
}