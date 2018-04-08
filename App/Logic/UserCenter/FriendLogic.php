<?php

namespace App\Logic\UserCenter;


use App\Base\UserCenter\Friend;
use App\Base\UserCenter\User;

class FriendLogic
{
    /**
     * 获取好友列表
     * @param $params
     * @return mixed
     */
    public function getFriendList($params)
    {
        $userIds = Friend::where('user_id', $params['user_id'])->pluck('to_user_id');
        $userList = User::whereIn('id', $userIds)->get();
        return $userList;
    }

    /**
     * 获取好友信息
     * @param $params
     * @return mixed
     */
    public function getFriendInfo($params)
    {
        $userInfo = User::where('id', $params['user_id'])->first();
        return $userInfo;
    }

    /**
     * 插入数据
     * @param $params
     * @return mixed
     */
    public function insert($params)
    {
        $userInfo = Friend::create($params);
        return $userInfo;
    }
}