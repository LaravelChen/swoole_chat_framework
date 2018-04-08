<?php

namespace App\Base\UserCenter;


use App\Base\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;

class Notification extends Model
{
    use SoftDeletes;
    protected $table = 'notification';
    protected $guarded = ['id'];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function getAvatarAttribute()
    {
        return Arr::get($this->user, 'avatar', '');
    }

    public function getNameAttribute()
    {
        return Arr::get($this->user, 'name', '');
    }

    public function getActionNameAttribute()
    {
        if ($this->attributes['action'] == 'RECEIVE') {
            return '已接受';
        }
        if ($this->attributes['action'] == 'REFUSE') {
            return '已拒绝';
        }
        return 'DEFAULT';
    }
}