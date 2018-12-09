<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/9/18
 * Time: 6:09 PM
 */

namespace Core\Traits;

use Core\Constants\StatusConstant;
use Core\Responses\StatusResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use DB;
use Illuminate\Support\Str;


/*
 * A base controller class that gives sorting,
 *  filtering, eager loading and pagination for our endpoints
 * ***/

trait BuilderTrait
{
    protected function apply_resource_options(Builder $query_builder, array $options = [])
    {
        if (empty($options)) {
            return $query_builder;
        }
        extract($options);
        if (isset($includes)) {
            if (!is_array($includes)) {
                $response = new StatusResponse();
                return $response->state_output_format(0, 8006, null,
                    400, StatusConstant::JSON_MEDIA_TYPE);
            }
            $query_builder->with($includes);
        }
        if (isset($filter_groups)) {
            $filter_joins = $this->apply_filter_groups($query_builder, $filter_groups);
        }
        if (isset($sort)) {
            if (!is_array($sort)) {
                $response = new StatusResponse();
                return $response->state_output_format(0, 8008, null,
                    400, StatusConstant::JSON_MEDIA_TYPE);
            }
            if (!isset($filter_joins)) {
                $filter_joins = [];
            }
            $sorting_joins = $this->apply_sorting($query_builder, $sort, $filter_joins);
        }
        if (isset($limit)) {
            $query_builder->limit($limit);
        }
        if (isset($page)) {
            $query_builder->offset($page * $limit);
        }
        if (isset($distinct)) {
            $query_builder->distinct();
        }
        return $query_builder;
    }

    protected function apply_sorting(Builder $query, array $sorting, array $previous_joined)
    {
        $joins = [];
        foreach ($sorting as $sorting_rule) {
            if (is_array($sorting_rule)) {
                $key = $sorting_rule['key'];
                $direction = mb_strtolower($sorting_rule['direction'] === 'asc' ? 'ASC' : 'DESC');
            } else {
                $key = $sorting_rule;
                $direction = 'ASC';
            }
            $custom_sorting_method = $this->has_custom_method('sort', $key);
            if ($custom_sorting_method) {
                $joins[] = $key;
                call_user_func([$this, $custom_sorting_method], $query, $direction);
            } else {
                $query->orderBy($key, $direction);
            }
        }
        foreach (array_diff($joins, $previous_joined) as $join) {
            $this->join_related_model_if_exists($query, $join);
        }
        return $joins;
    }

    protected function apply_filter_groups(Builder $query, array $filter_groups = [], array $previously_joined = [])
    {
        $joins = [];
        foreach ($filter_groups as $group) {
            $or = $group['or'];
            $filters = $group['filters'];
            $query->where(function (Builder $query) use ($filters, $or, &$joins) {
                foreach ($filters as $filter) {
                    $this->apply_filter($query, $filter, $or, $joins);
                }
            });
        }
        foreach (array_diff($joins, $previously_joined) as $join) {
            $this->join_related_model_if_exists($query, $join);
        }
        return $joins;
    }

    protected function apply_filter(Builder $query, array $filter, $or = false, array &$joins)
    {
        // $value,$not,$key,$operator
        if (!array_key_exists('key', $filter) && count($filter) >= 3) {
            $filter = [
                'key' => ($filter[0] ?: null),
                'operator' => ($filter[1] ?: null),
                'value' => ($filter[2] ?: null),
                'not' => (array_key_exists(3, $filter) ? $filter[3] : null),
            ];
        }
        extract($filter);
        $database_type = $query->getConnection()->getDriverName();
        $table = $query->getModel()->getTable();
        if ($value === 'null' || $value === '') {
            $method = $not ? 'WhereNotNull' : 'WhereNull';
            call_user_func([$query, $method], sprintf('%s.%s', $table, $key));
        } else {
            $method = filter_var($or, FILTER_VALIDATE_BOOLEAN) ? 'orWhere' : 'where';
            $clause_operator = null;
            $database_field = null;
            switch ($operator) {
                case 'con': // contains
                case 'stw': // startwith
                case 'enw': // endwith
                    $value_string = [
                        'con' => '%' . $value . '%', // contains
                        'stw' => '%' . $value . '%', // startwith
                        'enw' => $value . '%' // endwith
                    ];
                    $cast_to_text = (($database_type === 'postgres') ? 'TEXT' : 'CHAR');
                    $database_field = DB::raw(sprintf('CAST(%s.%s AS' . $cast_to_text . ')', $table, $key));
                    $clause_operator = ($not ? 'NOT' : '') . (($database_type === 'postgres') ? 'ILIKE' : 'LIKE');
                    $value = $value_string[$operator];
                    break;
                case 'eql': // equals
                default:
                    $clause_operator = $not ? '!=' : '=';
                    break;
                case 'gtd': // greater than
                    $clause_operator = $not ? '<' : '>';
                    break;
                case 'ltd': // less than
                    $clause_operator = $not ? '>' : '<';
                    break;
                case 'lte': // less than or equal too
                    $clause_operator = $not ? ">" : "<=";
                    break;
                case 'gte': //
                    $clause_operator = $not ? "<" : ">=";
                    break;
                case 'in': // in
                    if ($or === true) {
                        $method = $not === true ? 'orWhereNotIn' : 'orWhereIn';
                    } else {
                        $method = $not === true ? 'whereNotIn' : 'whereIn';
                    }
                    $clause_operator = false;
                    break;
                case 'btw':
                    if ($or === true) {
                        $method = $not === true ? 'orWhereNotBetween' : 'orWhereBetween';
                    } else {
                        $method = $not === true ? 'whereNotBetween' : 'whereBetween';
                    }
                    $clause_operator = false;
                    break;
            }
            // check if database field is still null
            if (is_null($database_field)) {
                $database_field = sprintf('%s.%s', $table, $key);
            }
            // let do our custom filter
            $custom_filter_method = $this->has_custom_method('filter', $key);
            if ($custom_filter_method) {
                call_user_func_array([$this, $custom_filter_method], [
                    $query,
                    $method,
                    $clause_operator,
                    $value,
                    $operator === 'in'
                ]);
                // column to join
                $joins[] = $key;
            } else {
                if (in_array($operator, ['in', 'btw'])) {
                    call_user_func_array([$query, $method],
                        [$database_field, $value]);
                } else {
                    call_user_func_array([$query, $method], [$database_field, $clause_operator, $value]);
                }
            }
        }
    }

    private function has_custom_method($type, $key)
    {
        $method_name = sprintf('%s%s', $type, Str::studly($key));
        if (method_exists($this, $method_name)) {
            return $method_name;
        }
        return false;
    }

    private function join_related_model_if_exists(Builder $query, $key)
    {
        $model = $query->getModel();
        // relationship exits, join to make special sort
        if (method_exists($model, $key)) {
            $relation = $model->$key();
            $type = 'inner';
            if ($relation instanceof BelongsTo) {
                $query->join($relation->getRelated()->getTable(),
                    $model->getTable() . '.' . $relation->getForeignKey(),
                    '=',
                    $relation->getRelated()->getTable() . '.' . $relation->getOtherKey(),
                    $type);
            } else if ($relation instanceof BelongsToMany) {
                $query->join($relation->getRelated()->getTable(),
                    $relation->getQualifiedParentKeyName(),
                    '=',
                    $relation->getForeignKey(),
                    $type);
                $query->join($relation->getRelated()->getTable(),
                    $relation->getRelated()->getTable() . '.' . $relation->getRelated()->getTable()->getKeyName(),
                    '=',
                    $relation->getOtherKey(),
                    $type);
            } else {
                $query->join($relation->getRelated()->getTable(),
                    $relation->getQualifiedParentKeyName(),
                    '=',
                    $relation->getForeignKey(),
                    $type);
            }
            $table = $model->getTable();
            $query->select(sprintf('%s.*', $table));
        }
    }
}