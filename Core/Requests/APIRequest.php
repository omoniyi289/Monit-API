<?php
namespace Core\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

/**
 * Created by PhpStorm.
 * User: funmi ayinde
 * Date: 1/10/18
 * Time: 11:50 AM
 */

class APIRequest extends FormRequest
{
    /*
     * GET the validation rules that apply to
     *  request
     * */
//    abstract public function rules();
//
//    /*
//     * Get the failed validation response for the
//     * request
//     * */
//    public function response(array $errors){
//        $transformed = [];
//        foreach ($errors as $field => $message){
//            $transformed[] = [
//                'status' => false,
//                'field' => $field,
//                'message' => $message[0]
//            ];
//        }
//        return response()->json($transformed,JsonResponse::HTTP_BAD_REQUEST);
//    }


    protected function failedValidation(Validator $validator)
    {
        throw new UnprocessableEntityHttpException($validator->errors()->toJson());
    }

    protected function failedAuthorization()
    {
        throw new HttpException(403);
    }

}