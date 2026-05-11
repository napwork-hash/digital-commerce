<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ApiResponse
{
    public static function success(
        mixed $data = null,
        string $message = 'Success',
        int $status = 200
    ): JsonResponse {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    public static function paginated(
        ResourceCollection $resource,
        string $message = 'Success',
        int $status = 200
    ): JsonResponse {
        $response = $resource->response()->getData(true);

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $response['data'],
            'meta' => [
                'current_page' => $response['meta']['current_page'] ?? null,
                'last_page' => $response['meta']['last_page'] ?? null,
                'per_page' => $response['meta']['per_page'] ?? null,
                'total' => $response['meta']['total'] ?? null,
            ],
            'links' => $response['links'] ?? null,
        ], $status);
    }

    public static function error(
        string $message = 'Error',
        int $status = 400,
        mixed $errors = null
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }
}
