<?php

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

if (!function_exists('result')) {
    /**
     * 返回数据
     *
     * @param object|array|string|null $data 数据
     * @param int $statusCode 状态码
     * @param string $message 消息
     * @return JsonResponse
     */
    function result(object|array|string $data = null, string $message = "success", int $statusCode = 200): JsonResponse
    {
        if ($data instanceof LengthAwarePaginator || $data instanceof AnonymousResourceCollection) {
            return new JsonResponse([
                'success' => true,
                'message' => $message,
                'data' => [
                    'items' => $data->items(),
                    'meta' => [
                        'current_page' => $data->currentPage(),
                        'from' => $data->firstItem(),
                        'per_page' => $data->perPage(),
                        'to' => $data->lastItem(),
                        'last_page' => $data->lastPage(),
                        'total' => $data->total(),
                    ],
                ],
            ], $statusCode);
        } else {
            return new JsonResponse([
                'success' => true,
                'message' => $message,
                'data' => $data ? $data : []
            ], $statusCode);
        }
    }
}

if (!function_exists('error')) {
    /**
     * 返回错误
     *
     * @param string $msg 错误信息
     * @param integer $statusCode 状态码
     * @return JsonResponse
     */
    function error(string $msg = 'fail', int $statusCode = 500): JsonResponse
    {
        return response()->json(
            ['success' => false, 'message' => $msg],
            $statusCode
        );
    }
}

