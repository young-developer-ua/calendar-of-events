<?php

namespace App\Exceptions;

use App\Facades\PrepareResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\ErrorHandler\Error\FatalError;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof AuthorizationException) {
            return PrepareResponse::getErrorResponse([$exception->getMessage()], Response::HTTP_FORBIDDEN);
        }
        if ($exception instanceof ModelNotFoundException) {
            return PrepareResponse::getErrorResponse(['Resource not found'], Response::HTTP_NOT_FOUND);
        }
        if ($exception instanceof ValidationException) {
            return PrepareResponse::getErrorResponse($exception->errors(), Response::HTTP_BAD_REQUEST);
        }
        if ($exception instanceof NotFoundHttpException) {
            return PrepareResponse::getErrorResponse(['Route not found']);
        }
        if ($exception instanceof ErrorsException) {
            return PrepareResponse::getErrorResponse([$exception->getMessage()]);
        }
        if ($request->isJson()) {
            return PrepareResponse::getErrorResponse([$exception->getMessage()]);
        }
        Log::error($exception->getMessage(), $exception->getTrace());

        return parent::render($request, $exception);
    }
}
