<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This method is called by the exception dispatcher.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function report(Throwable $exception)
    {
        if ($this->shouldReport($exception)) {
            // Log the exception using Laravel's logging system
            Log::error($exception->getMessage(), ['exception' => $exception]);

            // Optionally, send the exception to an external reporting service
            // ... (Add code for external reporting)
        }
    }

    /**
     * Render an exception into an HTTP response.
     *
     * This method is called after the report method.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request, Throwable $exception)
    {
        if (in_array($exception, $this->dontReport)) {
            return;
        }

        if ($exception instanceof UnauthorizedException) {
            $statusCode = 403;
            $message =  $exception->getMessage() ?: 'Unauthorized.';
        } elseif ($exception instanceof ValidationException) {
            $statusCode = 422;
            $message = $exception->getMessage() ?: 'Validation failed.';
            $errors = $exception->errors();
        }elseif ($exception instanceof ModelNotFoundException) {
            $statusCode = 404;
            $message = $exception->getMessage() ?: 'The requested resource could not be found.';
        } elseif ($exception instanceof AuthenticationException) {
            $statusCode = 401;
            $message = $exception->getMessage() ?: 'Invalid login credentials.';
        } elseif ($exception instanceof HttpException) {
            $statusCode = $exception->getStatusCode();
            $message = $exception->getMessage();
        } else {
            $statusCode = 500;
            $message = config('app.debug') ? $exception->getMessage() : 'An unexpected error occurred.';
            if(config('app.debug')) {

            }
        }


        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => ['errors' => $errors ?? []],
            'summary' => null,
        ], $statusCode);

    }
}
