<?php

namespace App\Traits;
trait ApiResponse
{
    public function sendResponse($result, $message, $token = null, $code = 200): \Illuminate\Http\JsonResponse
    {
        if ($result instanceof \Illuminate\Http\Resources\Json\AnonymousResourceCollection && $result->resource instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            $paginator = $result->resource;
            $response = [
                'success' => true,
                'data' => $result,
                'message' => $message,
                'pagination' => [
                    'total'        => $paginator->total(),
                    'per_page'     => $paginator->perPage(),
                    'current_page' => $paginator->currentPage(),
                    'last_page'    => $paginator->lastPage(),
                    'from'         => $paginator->firstItem(),
                    'to'           => $paginator->lastItem(),
                ],
            ];
        } else {
            $response = [
                'success' => true,
                'data'    => $result,
                'message' => $message,
            ];
        }

        if ($token) {
            $response['access_token'] = $token;
            $response['token_type'] = 'bearer';
        }

        return response()->json($response, $code);
    }


    public function sendError(string $error, array $errorMessages = [], int $code = 404): \Illuminate\Http\JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];
        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }
        return response()->json($response, $code);
    }
}
