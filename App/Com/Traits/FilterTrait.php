<?php

/**
 * Created by PhpStorm.
 * User: Master
 * Date: 2017/2/24
 * Time: 14:41
 */

namespace App\Com\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

trait FilterTrait
{

    private function getFilterMethodPrefix()
    {
        return property_exists($this, 'FilterPrefix') ? self::FilterPrefix : 'filter';
    }

    public function scopeFilter(Builder $query, $filters)
    {
        if (is_string($filters))
            $filters = self::unSerializeFilter($filters);

        foreach ($filters as $field => $values) {
            if ($values == '') {
                continue;
            }

            $filter_method = $this->getFilterMethodPrefix() . studly_case($field);
            if (method_exists($this, $filter_method)) {
                $query = $this->$filter_method($query, $values, $filters);
            } else {
                // time between filter
                if ((strpos(strtolower($field), 'time') != false || strpos(strtolower($field), 'date') != false) && count($values) == 2) {
                    $query = $this->filterTimeBetween($query, $values, $field);
                } else if (in_array(strtolower($field), ['updated_at', 'created_at', 'deleted_at', 'pay_at'])) {
                    $query = $this->filterTimestampsAt($query, $values, strtolower($field));
                } else {
                    // common filter
                    if (is_array($values)) {
                        $query = $this->filterByArray($query, $field, $values);
                    } elseif (is_string($values)) {
                        $query = $this->filterByString($query, $field, $values);
                    }
                }
            }
        }

        return $query;
    }

    protected function filterId(Builder $query, $value)
    {
        if (is_array($value)) {
            return $query->whereIn('id', $value);
        }

        return $query->where('id', $value);
    }

    protected function filterByArray(Builder $query, $field, $arr_val)
    {
        return $query->whereIn($field, $arr_val);
    }

    protected function filterByString(Builder $query, $field, $str_val)
    {
        return $query->where($field, 'like', '%' . $str_val . '%');
    }

    protected function filterStatus(Builder $query, $status)
    {
        if (!is_array($status)) {
            $status = [$status];
        }

        $self = get_class($this);

        if (method_exists($self, 'getStatusCode')) {
            $status = array_map(function ($s) use ($self) {
                return $self::getStatusCode($s);
            }, $status);
        }

        if (count($status) == 1) {
            return $query->where('status', head($status));
        } else {
            return $query->whereIn('status', $status);
        }
    }

    protected function filterTimeBetween(Builder $query, $times, $field)
    {
        if (!is_array($times) || count($times) != 2) {
            throw new InvalidParameterException(json_encode($times), __FUNCTION__, 'time between filter not given proper arguments');
        }

        $times = array_map(function ($elem) {
            if (!is_numeric($elem)) {
                $elem = strtotime($elem);
            }

            if (!is_numeric($elem)) {
                $elem = null;
            }

            return Carbon::createFromTimestamp($elem);
        }, $times);


        return $query->whereBetween($field, $times);
    }

    protected function verifyTimes($times)
    {
        if (!is_array($times)) {
            $times = [$times, time()];
        }

        $times = array_map(function ($time) {
            return Carbon::createFromTimestamp(is_numeric($time) ? $time : strtotime($time));
        }, $times);

        if (!is_array($times) || count($times) != 2) {
//            throw new InvalidParameterException(json_encode($times), __FUNCTION__, 'timestamps_at filter not given proper arguments');
        }

        return $times;
    }

    protected function filterTimestampsAt(Builder $query, $times, $field)
    {
        $times = $this->verifyTimes($times);
        return $query->whereBetween($field, $times);
    }

    /**
     * unserialize the filter string
     * @param string $filters filter string
     * @return array unserialized filter array
     */
    private static function unSerializeFilter($filters = '')
    {
        $arr_filters = [];

        $filters = explode('|', strtolower($filters));
        foreach ($filters as $filter) {
            if (empty($filter)) {
                continue;
            }

            if (!is_string($filter)) {
//                throw new InvalidParameterException($filter, __FUNCTION__, 'filter is not string');
            }

            if (strpos($filter, ':') === false) {
//                throw new InvalidParameterException($filter, __FUNCTION__, 'filter has no value');
            }

            if (strpos($filter, ':') != strrpos($filter, ':')) {
//                throw new InvalidParameterException($filter, __FUNCTION__, 'filter contains more than one ":"');
            }

            list($field, $values) = explode(':', $filter);
            $values = explode(',', $values);

            if (is_array($values) && count($values) == 1) {
                $values = $values[0];
            }

            $arr_filters[$field] = $values;
        }

        return $arr_filters;
    }
}