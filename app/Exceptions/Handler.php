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
     * @param \Throwable $exception
     * @return void
     */
    public function report(Throwable $e)
    {
        if ($this->shouldReport($e)) {
            // Log the exception using Laravel's logging system
            Log::error($e->getMessage(), ['exception' => $e]);

            // Optionally, send the exception to an external reporting service
            // ... (Add code for external reporting)
        }
    }

    /**
     * Render an exception into an HTTP response.
     *
     * This method is called after the report method.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable $exception
     * @return \Illuminate\Http\JsonResponse|null
     */
    public function render($request, Throwable $e)
    {
        if (in_array($e, $this->dontReport)) {
            return null;
        }

        if ($e instanceof UnauthorizedException) {
            $statusCode = 403;
            $message = $e->getMessage() ?: 'Unauthorized.';
        } elseif ($e instanceof ValidationException) {
            $statusCode = 422;
            $message = $e->getMessage() ?: 'Validation failed.';

            // errors as array
            $validations = $e->errors();

            $errors = collect($validations)->map(function ($error, $key) {
                    return [
                        'field' => $key,
                        'messages' => $error
                    ];
                })->values()->all();
        } elseif ($e instanceof ModelNotFoundException) {
            $statusCode = 404;
            $message = $e->getMessage() ?: 'The requested resource could not be found.';
        } elseif ($e instanceof AuthenticationException) {
            $statusCode = 401;
            $message = $e->getMessage() ?: 'Invalid login credentials.';
        } elseif ($e instanceof HttpException) {
            $statusCode = $e->getStatusCode();
            $message = $e->getMessage();
        } else {
            $statusCode = 500;
            $message = config('app.debug') ? $e->getMessage() : 'An unexpected error occurred.';

            if (config('app.debug')) {
                $errors = [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTrace()
                ];
            }
        }


        return response()->json([
            'message' => $message,
            'errors' => $errors ?? []
        ], $statusCode);

    }
}
