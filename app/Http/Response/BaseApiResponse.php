<?php

namespace App\Http\Response;

trait BaseApiResponse
{
    public function responseSuccess($data = '', $message = '')
    {
        $response = [
            'code' => 1,
            'success' => true
        ];
        if($data) $response['data'] = $data;
        if($data) $response['message'] = $message;

        return response()->json($response, 200);
    }

    public function responseError()
    {

    }

    public function responseForbidden()
    {
        $response = [
            'code' => -1,
            'success' => false,
            'message' => 'forbidden'
        ];

        return response()->json($response, 403);
    }

    public function responseValidateError()
    {
        //$response = [
        //    'code' => -1,
        //    'success' => false,
        //    'message' => 'forbidden'
        //];
        //return response()->json($response, 400);
    }
}
