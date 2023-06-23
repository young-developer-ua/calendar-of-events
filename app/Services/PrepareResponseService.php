<?php

namespace App\Services;

use Illuminate\Http\Response;

class PrepareResponseService
{
    public function getSuccessResponse($data, $code = Response::HTTP_OK): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'data' => $data,
            'status' => $code
        ])->setStatusCode($code);
    }

    public function getClearResponse($data, $code = Response::HTTP_OK): \Illuminate\Http\JsonResponse
    {
        return response()->json($data)->setStatusCode($code);
    }

    public function getDeleteResponse($data = '', $code = Response::HTTP_NO_CONTENT): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'data' => $data,
            'status' => $code
        ]);
    }

    public function getErrorResponse($messages, $code = Response::HTTP_INTERNAL_SERVER_ERROR): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'errors' => $messages,
            'status' => $code
        ])->setStatusCode($code);
    }
}
