<?php

namespace App\Base;

use App\Com\Response\FrameWorkCode;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Core\Http\Request;
use App\Com\Traits\AppendTrait;
use App\Com\Traits\FilterTrait;
use App\Com\Traits\OrderByTrait;
use App\Com\Traits\SearchListTrait;
use App\Com\Traits\ValidatorTrait;

class Model extends EloquentModel
{
    use ValidatorTrait;
    use FilterTrait;
    use AppendTrait;
    use OrderByTrait;
    use SearchListTrait;

//    protected static $logOnlyDirty = true;
    protected static $enableActivityLog = false;
    /**
     * The attributes that should be appended to current model.
     *
     * @var array
     */
    protected $appends = [];

    public function lists($params)
    {
        $filters = array_get($params, 'where', []);
        $fields = array_get($params, 'fields', '*');
        $orderby = array_get($params, 'order', '');

        $query = $this->searchList(new static(), $filters, $fields, $orderby);
        $result = $this->pagedReturn($query, array_get($params, 'page', 1), array_get($params, 'page_size', 20), $fields);

        return $result;
    }

    //更新第一个,若没有则报错
    public function saveOne($params)
    {
        if (empty($params['where']) || empty($params['update'])) {
            return FrameWorkCode::PARAMETER_ERROR;
        }
        $query = self::_generateQuery($params);
        $instance = $query->first();
        if (is_null($instance)) {
            return FrameWorkCode::NOT_FOUND;
        }
        foreach ($params['update'] as $k => $v) {
            $instance->$k = $v;
        }
        $instance->save();
        return $instance;
    }

    //获取第一个,若没有则报错
    public function getOne($params)
    {
        if (empty($params['where'])) {
            return FrameWorkCode::PARAMETER_ERROR;
        }
        $query = self::_generateQuery($params);
        $instance = $query->first();
        if (is_null($instance)) {
            return FrameWorkCode::NOT_FOUND;
        }
        if ( !empty($params['with'])) {
            $params['with'] = explode(',', $params['with']);
            foreach ($params['with'] as $v) {
                $instance->tryAppend($v);
            }
        }
        if ( !empty($params['appends'])) {
            if (is_string($params['appends']))
                $params['appends'] = explode(',', $params['appends']);

            foreach ($params['appends'] as $v) {
                $instance->tryAppend($v);
            }
        }
        return $instance;
    }

    //删除第一个,若没有则报错
    public function deleteOne($params)
    {
        if (empty($params['where'])) {
            return FrameWorkCode::PARAMETER_ERROR;
        }
        $query = self::_generateQuery($params);
        $instance = $query->first();
        if (is_null($instance)) {
            return FrameWorkCode::NOT_FOUND;
        }
        $status = $instance->delete();
        if ($status > 0) {
            return ResponseData::success(true);
        } else {
            return FrameWorkCode::DELETE_ERROR;
        }
    }

    //批量删除
    public function deleteBatch($params)
    {
        if (empty($params['where'])) {
            return FrameWorkCode::PARAMETER_ERROR;
        }
        $query = self::_generateQuery($params);
        $status = $query->delete();
        if ($status > 0) {
            return ResponseData::success(true);
        } else {
            return FrameWorkCode::DELETE_ERROR;
        }
    }

    protected function _generateQuery($params)
    {
        $query = static::query();
        if ( !empty($params['where']) && is_array($params['where'])) {
            foreach ($params['where'] as $k => $v) {
                if (is_array($v)) {
                    $query->whereIn($k, $v);
                } else {
                    $query->where($k, $v);
                }
            }
        }
        if ( !empty($params['order_by']) && is_array($params['order_by'])) {
            foreach ($params['order_by'] as $k => $v) {
                $query->orderBy($k, $v);
            }
        }
        return $query;
    }

    public function getDescriptionForEvent($eventName)
    {
        switch ($eventName) {
            case 'created':
                $desc = '创建数据';
                break;
            case 'updated':
                $desc = '修改数据';
                break;
            case 'deleted':
                $desc = '删除数据';
                break;
            default:
                $desc = '';
                break;
        }

        return $desc;
    }

    public function getCount($params)
    {
        $filters = array_get($params, 'where', []);

        $query = $this->searchList(new static(), $filters);
        $result = $query->count();

        return $result;
    }

    public function scopePage($query, $pageSize)
    {
        $page = Request::getInstance()->getRequestParam('page') ? Request::getInstance()->getRequestParam('page') : 1;
        $paginator = $query->paginate($pageSize, ['*'], 'page', $page);
        $paginator->setPath(\Core\Http\Request::getInstance()->getServerParams()['request_uri']);
        return $paginator;
    }
}