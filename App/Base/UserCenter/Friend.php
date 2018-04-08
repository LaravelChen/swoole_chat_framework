<?php

namespace App\Base\UserCenter;


use App\Base\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Friend extends Model
{
    use SoftDeletes;
    protected $table = 'friends';
    protected $guarded = ['id'];
}