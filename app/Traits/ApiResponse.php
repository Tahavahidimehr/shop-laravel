<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    public function successResponse($data = null, string $message = 'Success', int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'errors' => null,
        ], $status);
    }

    public function errorResponse(string $message = 'Error', $errors = null, int $status = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => null,
            'errors' => $errors,
        ], $status);
    }

    public function validationErrorResponse($validator, string $message = 'Validation failed', int $status = 422): JsonResponse
    {
        return $this->errorResponse(
            $message,
            $validator->errors(),
            $status
        );
    }
}
