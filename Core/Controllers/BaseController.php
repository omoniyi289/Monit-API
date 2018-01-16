<?php
/**
 * Created by PhpStorm.
 * User: funmi
 * Date: 1/8/18
 * Time: 11:15 PM
 */

namespace Core\Controllers;

use Core\Constants\StatusConstant;
use Core\Lib\Libtect;
use Core\Responses\StatusResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

abstract class BaseController extends Controller
{
    protected $defaults = [];

    protected function response($statuses, $code, $message = null, $data, $status_code = JsonResponse::HTTP_OK, $media_type = "application/json", array $headers = [])
    {
        $response_with_status = new StatusResponse();
        return $response_with_status->state_output_format($statuses, $code, $message, $data, $status_code, $media_type, $headers);
    }

    protected function parse_data($data, array $options, $key = null)
    {
        $libtect = new Libtect();
        return $libtect->parse_data($data, $options['modes'], $key);
    }

    protected function parse_sort(array $sort)
    {
        return array_map(function ($sort) {
            if (!isset($sort['direction'])) {
                $sort['direction'] = 'asc';
            }
        }, $sort);
    }

    protected function parse_includes(array $includes)
    {
        $return = ['includes' => [], 'modes' => []];
        foreach ($includes as $include) {
            $explode = explode(':', $include);
            if (!isset($explode[1])) {
                $explode[1] = $this->defaults['mode'];
            }
            $return["includes"][] = $explode[0];
            $return["modes"][$explode[0]] = $explode[1];
        }
        return $return;
    }

    protected function parse_filter_groups(array $filter_groups)
    {
        $return = [];
        foreach ($filter_groups as $group) {
            if (!array_key_exists("filters", $group)) {
                $response = new StatusResponse();
                return $response->state_output_format(0, 8004, null, 400,
                    StatusConstant::JSON_MEDIA_TYPE);
            }
            $filters = array_map(function ($filter) {
                if (!isset($filter['not'])) {
                    $filter['not'] = false;
                }
            }, $group['filters']);
            $return[] = [
                'filters' => $filters,
                'or' => isset($group['or']) ? $group['or'] : false,
            ];
        }
        return $return;
    }

    /*
     * Parse GET parameters into resources options
     * **/
    protected function parse_resource_options($request = null)
    {
        if ($request === null) {
            $request = request();
        }

        $this->defaults = array_merge([
            'includes' => [],
            'sort' => [],
            'limit' => null,
            'page' => null,
            'mode' => 'embed',
            'filter_groups' => []
        ], $this->defaults);
        $includes = $this->parse_includes($request->get('includes', $this->defaults['includes']));
        $sort = $this->parse_sort($request->get('sort', $this->defaults['sort']));
        $limit = $request->get('limit', $this->defaults['limit']);
        $page = $request->get('page', $this->defaults['page']);
        $filter_groups = $this->parse_filter_groups($request->get('filter_groups', $this->defaults['filter_groups']));
        if ($page !== null && $limit === null) {
            $response = new StatusResponse();
            return $response->state_output_format(0, 8005, null, null,400,
                StatusConstant::JSON_MEDIA_TYPE);
        }
        return [
            'includes' => $includes['includes'],
            'modes' => $includes['modes'],
            'sort' => $sort,
            'limit' => $limit,
            'page' => $page,
            'filter_pages' => $filter_groups,
        ];
    }
}