<?php
/**
 * Created by PhpStorm.
 * User: Master
 * Date: 2017/2/24
 * Time: 14:42
 */

namespace App\Com\Traits;

use Illuminate\Database\Eloquent\Builder;

trait OrderByTrait
{

    public function scopeSort(Builder $query, $order_by)
    {
        if (empty($order_by))
            return $query;

        //TODO 标准化order_by传参
        if (is_string($order_by))
            return $query->orderByRaw($order_by);
        else {
            foreach ($order_by as $order) {
                list($field, $dir) = $order_by;

                $query->orderBy($field, $dir);
            }
            return $query;
        }

    }

}

