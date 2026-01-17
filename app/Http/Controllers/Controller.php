<?php

namespace App\Http\Controllers;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
abstract class Controller
{
    use AuthorizesRequests;
    /**
     * Success response
     */
    protected function success($data = null, string $message = 'Operation completed successfully', int $statusCode = 200)
    {
        $response = [
            'success' => true,
            'message' => $message,
            'timestamp' => now()->toISOString(),
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Success response with meta (for pagination)
     */
    protected function successWithMeta($data, $meta, string $message = 'Data retrieved successfully', int $statusCode = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'meta' => $meta,
            'timestamp' => now()->toISOString(),
        ], $statusCode);
    }

    /**
     * Error response
     */
    protected function error(string $message = 'An error occurred', $errors = null, int $statusCode = 400)
    {
        $response = [
            'success' => false,
            'message' => $message,
            'timestamp' => now()->toISOString(),
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Not found response
     */
    protected function notFound(string $message = 'Resource not found')
    {
        return $this->error($message, null, 404);
    }

    /**
     * Unauthorized response
     */
    protected function unauthorized(string $message = 'Unauthorized access')
    {
        return $this->error($message, null, 401);
    }

    /**
     * Forbidden response
     */
    protected function forbidden(string $message = 'Forbidden')
    {
        return $this->error($message, null, 403);
    }

    /**
     * Validation error response
     */
    protected function validationError($errors, string $message = 'Validation failed')
    {
        return $this->error($message, $errors, 422);
    }
}

