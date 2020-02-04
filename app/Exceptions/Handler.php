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
use Illuminate\Database\QueryException;

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
        if ($e instanceof ValidationException) { // 422
            return response()->json([
                'message' => trans('validation.invalid'),
                'errors' => $e->validator->getMessageBag()
            ], HttpStatusCode::HTTP_UNPROCESSABLE_ENTITY);
        }

        $message = 'resource.server_error';
        $httpStatusCode = 500;

        switch (true) {
            case $e instanceof TokenExpiredException:
                $message = 'auth.session.expired';
                $httpStatusCode = HttpStatusCode::HTTP_UNAUTHORIZED; // 401
                break;

            case $e instanceof TokenInvalidException:
            case $e instanceof JWTException:
                $message = 'auth.session.invalid';
                $httpStatusCode = HttpStatusCode::HTTP_UNAUTHORIZED; // 401
                break;

            case $e instanceof ModelNotFoundException: // 404
                $message = 'resource.not_found_register';
                $httpStatusCode = HttpStatusCode::HTTP_NOT_FOUND;
                break;

            case $e instanceof NotFoundHttpException: // 404
                $message = 'resource.not_found';
                $httpStatusCode = $e->getStatusCode();
                break;

            case $e instanceof HttpException:
                $httpStatusCode = $e->getStatusCode();
                if ($httpStatusCode == HttpStatusCode::HTTP_FORBIDDEN) { // 404
                    $message = empty($e->getMessage()) ? 'auth.session.not_permission' : $e->getMessage();
                } else {
                    $message = $e->getMessage();
                }
                break;

            default:
            if (config('app.debug')) {
                return $this->renderExceptionWithWhoops($e);
            }
            return parent::render($request, $e);
        }

        return response()->json([
            'message' => trans($message),
        ], $httpStatusCode);
    


        // dd($e instanceof QueryException);

        
        // $message = empty($e->getMessage()) ? 'resource.server_error' : $e->getMessage();
        // $httpStatusCode = NULL;

        // switch (true) {
        //     case $e instanceof ValidationException: // 422
        //         SEND_ERROR([
        //             'message' => 'validation.invalid',
        //             'errors' => $e->validator->getMessageBag()
        //         ], HttpStatusCode::HTTP_UNPROCESSABLE_ENTITY);
        //         break;

        //     case $e instanceof TokenExpiredException:
        //         $message = 'auth.session.expired';
        //         $httpStatusCode = HttpStatusCode::HTTP_UNAUTHORIZED; // 401
        //         break;

        //     case $e instanceof TokenInvalidException:
        //     case $e instanceof JWTException:
        //         $message = 'auth.session.invalid';
        //         $httpStatusCode = HttpStatusCode::HTTP_UNAUTHORIZED; // 401
        //         break;

        //     case $e instanceof ModelNotFoundException:
        //         $message = 'resource.not_found';
        //         $httpStatusCode = HttpStatusCode::HTTP_NOT_FOUND; // 404
        //         break;

        //     case $e instanceof NotFoundHttpException:
        //         $message = 'resource.not_found';
        //         $httpStatusCode = HttpStatusCode::HTTP_NOT_FOUND; // 404
        //         break;

        //     case $e instanceof MethodNotAllowedHttpException: // 405
        //         $message = 'resource.method_not_allowed';
        //         $httpStatusCode = HttpStatusCode::HTTP_METHOD_NOT_ALLOWED;
        //         break;

        //     case $e instanceof ServerException: // 500
        //         $message = 'resource.server_error';
        //         $httpStatusCode = HttpStatusCode::HTTP_INTERNAL_SERVER_ERROR;
        //         break;

        //     case $e instanceof HttpException:
        //         $httpStatusCode = $e->getStatusCode();
        //         break;
                
        //     default:
        //     if (config('app.debug'))
        //     {
        //         return $this->renderExceptionWithWhoops($e);
        //     }
        //     return parent::render($request, $e);
        // }
        // // abort([
        // //     'message' => trans('validation.invalid'),
        // //     'errors' => $e->validator->getMessageBag()
        // // ], $httpStatusCode);
        // SEND_RESPONSE(['message' => trans($message)], $httpStatusCode);
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
