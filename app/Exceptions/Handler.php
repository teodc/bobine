<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Foundation\Http\Exceptions\MaintenanceModeException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

class Handler extends ExceptionHandler
{
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
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Exception $exception
     * @return JsonResponse
     */
    public function render($request, Exception $exception): JsonResponse
    {
        // Prepare the JSON response to return
        $response = new JsonResponse();

        // Set the default error details
        $statusCode = 500;
        $errorCode = Str::snake(class_basename($exception));
        $errorMessage = 'An error has occurred.';
        $responseHeaders = [];
        $responseMeta = [];

        // When not in production, add more details about the exception to the response meta
        if (! app()->environment('production'))
        {
            $responseMeta = [
                'type'    => get_class($exception),
                'code'    => $exception->getCode(),
                'message' => $exception->getMessage(),
                'file'    => $exception->getFile(),
                'line'    => $exception->getLine(),
            ];
        }

        // Set specific error details depending on the exception
        switch (get_class($exception))
        {
            case MaintenanceModeException::class:
                $statusCode = 503;
                $errorMessage = 'Service under maintenance.';
                break;
            case HttpException::class:
                $statusCode = $exception->getStatusCode();
                //$errorMessage = $exception->getMessage();
                $responseHeaders = $exception->getHeaders();
                break;
            case ModelNotFoundException::class:
            case NotFoundHttpException::class:
                $statusCode = 404;
                $errorMessage = 'Resource not found.';
                break;
            case AuthorizationException::class:
                $statusCode = 403;
                $errorMessage = 'Forbidden.';
                break;
            case AuthenticationException::class:
                $statusCode = 401;
                $errorMessage = 'Unauthenticated.';
                break;
            case BasicAuthenticationException::class:
                $statusCode = 401;
                $errorMessage = 'Unauthenticated.';
                $responseHeaders['WWW-Authenticate'] = 'Basic';
                break;
            case MethodNotAllowedHttpException::class:
            case MethodNotAllowedException::class:
                $statusCode = 405;
                $errorMessage = 'Method not allowed.';
                break;
            case ValidationException::class:
                $statusCode = 422;
                $errorMessage = 'Invalid data.';
                $responseMeta['validation_errors'] = $exception->errors();
                break;
        }

        // Prepare the response's body
        $responseData = [
            'data'  => null,
            'error' => [
                'code'    => $errorCode,
                'message' => $errorMessage,
            ],
        ];

        if (filled($responseMeta))
        {
            $responseData['meta'] = $responseMeta;
        }

        //dd($exception->getTraceAsString());

        // Return the JSON response set with the data defined above
        return $response->setStatusCode($statusCode)
                        ->setData($responseData)
                        ->withHeaders($responseHeaders);
    }

    /**
     * Report or log an exception.
     *
     * @param Exception $exception
     * @return void
     */
    public function report(Exception $exception): void
    {
        parent::report($exception);
    }
}
