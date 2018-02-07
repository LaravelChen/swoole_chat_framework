<?php

namespace App\Base\UserCenter;

use App\Base\Model;

class User extends Model
{
    protected $table = 'users';
    protected $hidden = ['password'];
    protected $guarded = ['id'];

    /*
     * 该用户的聊天记录
     */
    public function userChats()
    {
        return $this->hasMany(UserChat::class, 'user_id', 'id');
    }

    /*
     * 接收方的聊天记录
     */
    public function toUserChats()
    {
        return $this->hasMany(UserChat::class, 'to_user_id', 'id');
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = password_hash($password, PASSWORD_BCRYPT);
    }
}