<?php namespace App\Http\Utils;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\MessageBag;

/**
 * Class ResponseBuilder
 */
class ResponseBuilder
{

    public function apiSuccess($data): JsonResponse
    {
        return response()->json([
            'error' => 0,
            'data' => $data,
        ], 200);
    }

    public function apiError(string $error,  string $message, int $code = 500): JsonResponse
    {
        return response()->json([
            'error' => $error,
            'message' => $message,
        ], $code);
    }

    public function apiRequestValidationError(string $error, MessageBag $validationErrors): JsonResponse
    {
        return response()->json([
            'error' => $error,
            'message' => $validationErrors,
        ], 422);
    }

}
