<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($request->expectsJson()) {
            return $this->handleApiException($request, $exception);
        }

        return parent::render($request, $exception);
    }

    /**
     * Handle API exceptions.
     */
    private function handleApiException(Request $request, Throwable $exception): JsonResponse
    {
        // Handle JWT exceptions
        if ($exception instanceof TokenExpiredException) {
            return response()->json([
                'type' => 'about:blank',
                'title' => 'Unauthorized',
                'status' => 401,
                'detail' => 'Token has expired',
            ], 401);
        }

        if ($exception instanceof TokenInvalidException) {
            return response()->json([
                'type' => 'about:blank',
                'title' => 'Unauthorized',
                'status' => 401,
                'detail' => 'Token is invalid',
            ], 401);
        }

        if ($exception instanceof JWTException) {
            return response()->json([
                'type' => 'about:blank',
                'title' => 'Unauthorized',
                'status' => 401,
                'detail' => 'Token is missing',
            ], 401);
        }

        // Handle unauthorized exceptions
        if ($exception instanceof UnauthorizedHttpException) {
            return response()->json([
                'type' => 'about:blank',
                'title' => 'Unauthorized',
                'status' => 401,
                'detail' => 'Unauthorized access',
            ], 401);
        }

        // Handle validation exceptions
        if ($exception instanceof ValidationException) {
            return response()->json([
                'type' => 'about:blank',
                'title' => 'Unprocessable Entity',
                'status' => 422,
                'detail' => 'The given data was invalid.',
                'errors' => $exception->errors(),
            ], 422);
        }

        // Handle general HTTP exceptions
        if (method_exists($exception, 'getStatusCode')) {
            $statusCode = $exception->getStatusCode();
            $title = $this->getHttpStatusTitle($statusCode);

            return response()->json([
                'type' => 'about:blank',
                'title' => $title,
                'status' => $statusCode,
                'detail' => $exception->getMessage() ?: $title,
            ], $statusCode);
        }

        // Handle general exceptions
        return response()->json([
            'type' => 'about:blank',
            'title' => 'Internal Server Error',
            'status' => 500,
            'detail' => 'An error occurred while processing your request.',
        ], 500);
    }

    /**
     * Get HTTP status title.
     */
    private function getHttpStatusTitle(int $statusCode): string
    {
        $titles = [
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            409 => 'Conflict',
            422 => 'Unprocessable Entity',
            429 => 'Too Many Requests',
            500 => 'Internal Server Error',
            503 => 'Service Unavailable',
        ];

        return $titles[$statusCode] ?? 'Error';
    }
}
