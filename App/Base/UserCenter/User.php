<?php

namespace App\Base\UserCenter;

use App\Base\Model;

class User extends Model
{
    protected $table = 'users';
    protected $hidden = ['password'];
    protected $guarded = ['id'];

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = password_hash($password, PASSWORD_BCRYPT);
    }
}