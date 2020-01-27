<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use GuzzleHttp\Exception\ServerException;
use Symfony\Component\HttpKernel\Exception\HttpException;

use HttpStatusCode;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
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
     * @param  \Exception  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function render($request, Exception $e)
    {
        $message = trans('resource.server_error');
        $httpStatusCode = HttpStatusCode::HTTP_INTERNAL_SERVER_ERROR;
        $errors = [];
       
        switch (true) {
            case $e instanceof TokenExpiredException:
                $message = trans('auth.session.expired');
                $httpStatusCode = HttpStatusCode::HTTP_UNAUTHORIZED; // 401
                break;         
            case $e instanceof TokenInvalidException:
                $message = trans('auth.session.invalid');
                $httpStatusCode = HttpStatusCode::HTTP_UNAUTHORIZED; // 401
                break;
            case $e instanceof JWTException:
                $message = trans('auth.session.invalid');
                $httpStatusCode = HttpStatusCode::HTTP_FORBIDDEN; // 403
                break; 
            case $e instanceof ModelNotFoundException:
                $message = trans('resource.not_found');
                $httpStatusCode = HttpStatusCode::HTTP_NOT_FOUND; // 404
                break;
            case $e instanceof NotFoundHttpException:
                $message = trans('resource.not_found');
                $httpStatusCode = HttpStatusCode::HTTP_NOT_FOUND; // 404
                break;
            case $e instanceof MethodNotAllowedHttpException: // 405
                $message = trans('resource.method_not_allowed');
                $httpStatusCode = HttpStatusCode::HTTP_METHOD_NOT_ALLOWED;
                break;
            case $e instanceof ValidationException: // 422
                $message = trans('validation.invalid');
                $httpStatusCode = HttpStatusCode::HTTP_UNPROCESSABLE_ENTITY;
                $errors = $e->validator->getMessageBag();
                break;
            case $e instanceof ServerException: // 500
                $message = trans('resource.server_error');
                $httpStatusCode = HttpStatusCode::HTTP_INTERNAL_SERVER_ERROR;
                break;
            case $e instanceof HttpException: // 500
                break;
                
            default:
            if (config('app.debug'))
            {
                return $this->renderExceptionWithWhoops($e);
            }
            return parent::render($request, $e);
            //     $message = trans('resource.server_error');
            //     $httpStatusCode = HttpStatusCode::HTTP_INTERNAL_SERVER_ERROR;
        }   
        return response()->json([
            'message' => $message, 
            'errors' => $errors
        ], $httpStatusCode);
    }

    /**
     * Render an exception using Whoops.
     * 
     * @param  \Exception $e
     * @return \Illuminate\Http\Response
     */
    protected function renderExceptionWithWhoops(Exception $e)
    {
        $whoops = new \Whoops\Run;
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());

        return new \Illuminate\Http\Response(
            $whoops->handleException($e),
            $e->getStatusCode(),
            $e->getHeaders()
        );
    }
}
