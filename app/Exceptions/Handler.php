<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            // Log the exception or send to an external service
        });

        $this->renderable(function (Throwable $e, $request) {
            if ($e instanceof ModelNotFoundException) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Resource not found'
                ], 404);
            }

            if ($e instanceof NotFoundHttpException) {
                return response()->json([
                    'status' => 404,
                    'message' => 'Not found'
                ], 404);
            }

            if ($e instanceof MethodNotAllowedHttpException) {
                return response()->json([
                    'status' => 405,
                    'message' => 'Method not allowed'
                ], 405);
            }

            if ($e instanceof AccessDeniedHttpException) {
                return response()->json([
                    'status' => 403,
                    'message' => 'Access denied'
                ], 403);
            }

            if ($e instanceof AuthenticationException) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Unauthenticated'
                ], 401);
            }

            if ($e instanceof ValidationException) {
                return response()->json([
                    'status' => 422,
                    'message' => 'Validation error',
                    'errors' => $e->errors()
                ], 422);
            }

            if ($e instanceof HttpException) {
                return response()->json([
                    'status' => $e->getStatusCode(),
                    'message' => $e->getMessage()
                ], $e->getStatusCode());
            }

            return response()->json([
                'status' => 500,
                'message' => 'Internal Server Error'
            ], 500);
        });
    }

    /**
     * Convert a validation exception into a JSON response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Validation\ValidationException $exception
     * @return \Illuminate\Http\JsonResponse
     */
    protected function invalidJson($request, ValidationException $exception)
    {
        return response()->json([
            'status' => 422,
            'message' => $exception->getMessage(),
            'errors' => $exception->errors(),
        ], $exception->status);
    }
}
