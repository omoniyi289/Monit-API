<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/8/18
 * Time: 11:28 PM
 */

namespace Core\Responses;

use Core\Constants\CodesConstants;
use Core\Constants\StatusConstant;

class StatusResponse
{
    public function get_status_error()
    {
        $status_array = [
            1 => true,
            0 => false
        ];
        return $status_array;
    }

    public function response_error_or_success($statuses, $code,$success_message = null, $data = null, $http_status_code, array $headers = [])
    {
        $error_code = new CodesConstants();
        $status_arr = $this->get_status_error();
        $status = $status_arr[$statuses];
        $code_array = $error_code->error_and_success_codes($success_message);
        $message = $code_array[$code];
        return $this->response_helper($status, $code, $message, $data, $http_status_code, $headers);
    }

    private function response_helper($status, $code, $message, $data, $http_status_code, array $headers = [])
    {
        $response = response()->json([
            StatusConstant::STATUS => $status,
            StatusConstant::MESSAGE => $message,
            StatusConstant::CODE => $code,
            StatusConstant::DATA => $data
        ], $http_status_code, $headers);
        return $response;
    }

    public function state_output_format($statuses, $code, $message = null, $data = null, $http_status_code, $media_type, array $headers = [])
    {
        if ($media_type === StatusConstant::JSON_MEDIA_TYPE) {
            return $this->response_error_or_success($statuses, $code,$message, $data, $http_status_code, $headers);
        } else if ($media_type === StatusConstant::XML_MEDIA_TYPE) {
            // return xml format
        }
        return $this->response_error_or_success($statuses, $code, $data, $http_status_code, $headers);
    }


}