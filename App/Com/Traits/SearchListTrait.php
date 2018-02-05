<?php

/**
 * Created by PhpStorm.
 * User: Master
 * Date: 2017/2/24
 * Time: 14:42
 */

namespace App\Com\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\Paginator;

trait SearchListTrait
{

    public function searchList(Model $model, $filters, $fields = '', $order_by = '')
    {
        $class = get_class($model);
        $cls = new \ReflectionClass($class);

        if (is_string($fields))
            $fields = array_map('trim', explode(',', $fields));

        $query = $class::query();
        if ( !empty($fields) && method_exists($model, 'tryAppend')) {
            $appendable_fields = array_filter($fields, function ($field) use ($model) {
                return $model->isAppendable($field);
            });

            $preload_relations_no_trash = array_reduce($appendable_fields, function ($carry, $field) use ($cls) {
                $preload_const_name = strtoupper('preload_' . snake_case($field));
                if ($preload_relations = $cls->getConstant($preload_const_name)) {
                    $carry = array_merge($carry, explode(',', $preload_relations));
                }
                return $carry;
            }, []);
            $preload_relations_no_trash = array_values(array_unique($preload_relations_no_trash));

            $preload_relations_with_trash = array_reduce($appendable_fields, function ($carry, $field) use ($cls) {
                $preload_const_name = strtoupper('preload_' . snake_case($field) . '_with_trash');
                if ($preload_relations = $cls->getConstant($preload_const_name)) {
                    $carry = array_merge($carry, explode(',', $preload_relations));
                }
                return $carry;
            }, []);
            $to_load_relations_with_trash = [];
            foreach ($preload_relations_with_trash as $preload_relation) {
                $to_load_relations_with_trash[$preload_relation] = function ($q) {
                    $q->withTrashed();
                };
            }

            if ($preload_relations = array_merge($preload_relations_no_trash, $to_load_relations_with_trash)) {
                $query = call_user_func([$query, 'with'], $preload_relations);
            }
        }

        $query = $query->filter($filters);

        if ( !empty($order_by))
            $query = $query->sort($order_by);

        if ( !empty($fields)) {
            $fields = array_filter($fields, function ($field) use ($model) {
                return !$model->isAppendable($field);
            });

            $query = $query->addSelect($fields);
        }

        return $query;
    }

    public function pagedReturn(Builder $query, $current_page, $page_size = 20, $fields = ['*'], $to_array = true)
    {
        if ($page_size == -1) {
            $page_size = 99999;
        }
        Paginator::currentPageResolver(function () use ($current_page) {
            return $current_page;
        });

        $page = $query->paginate($page_size, $fields);
//        $page = $query->simplePaginate($page_size, $fields);

        if ( !empty($fields)) {
            if (is_string($fields))
                $fields = explode(',', $fields);

            foreach ($page as $result) {
                if (method_exists($result, 'tryAppend')) {
                    foreach ($fields as $field) {
                        $result->tryAppend($field);
                    }
                }
            }
        }

        if ($to_array) {
            $result = $page->toArray();
            $result['count'] = array_get($result, 'total', 0);
            $result['page'] = array_get($result, 'current_page', 1);
            $result['page_size'] = array_get($result, 'per_page', 0);
        }

        return $result;
    }

    //支持点语法取需要的字段
    protected function getFieldsValue(array $datas = [])
    {
        $fields = \Input::get('fields', '');
        if ( !empty($fields)) {
            $results = [];
            $fields = explode(',', $fields);
            foreach ($datas as $key => $one) {
                foreach ($fields as $field) {
                    $field = trim($field);
                    array_set($results[$key], $field, array_get($one, $field));
                }
            }
            $datas = $results;
        }
        return $datas;
    }


    public function syncMany(Model &$target, $relation_type, array $relation_arrays, $force_append = false)
    {
        if (empty($relation_arrays)) return;

        $rl = array_keys($relation_type)[0];
        $rc = array_values($relation_type)[0];

        if ( !$force_append) {
            $missed_relation = [];
            if ( !$force_append) {
                foreach ($target->$rl as $stored_relation) {
                    $missed = true;
                    foreach ($relation_arrays as $linkman) {
                        if (array_get($linkman, 'id', null) == $stored_relation->id) {
                            $missed = false;
                            break;
                        }
                    }

                    if ($missed) {
                        $missed_relation[] = $stored_relation;
                    }
                }
            }

            foreach ($missed_relation as $relation) {
                $relation->delete();
            }
        }

        $relation_objs = array_map(function ($relation) use ($rc, $force_append) {

            // remove possible ids from parents, may cause problem
            $relation_id = $force_append ? 0 : array_get($relation, 'id', 0);

            $relation_obj = $rc::findOrNew($relation_id);

            $relation_obj->fill($relation);

            return $relation_obj;
        }, $relation_arrays);

        $target->$rl()->saveMany($relation_objs);

        $target->load($rl);
    }

}