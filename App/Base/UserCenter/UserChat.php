<?php

namespace App\Base\UserCenter;


use App\Base\Model;
use Illuminate\Support\Arr;

class UserChat extends Model
{
    protected $table = 'chat_content';
    protected $guarded = ['id'];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function fromUser()
    {
        return $this->hasOne(User::class, 'id', 'to_user_id');
    }

    public function getNameAttribute()
    {
        return Arr::get($this->user, 'name', '');
    }

    public function getEmailAttribute()
    {
        return Arr::get($this->user, 'email', '');
    }

    public function getAvatarAttribute()
    {
        return Arr::get($this->user, 'avatar', '');
    }
}