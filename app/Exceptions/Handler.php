<?php

namespace App\Exceptions;

use Illuminate\Validation\ValidationException;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
            //
        });
    }

    public function render($request, \Throwable $exception)
    {
        if ($request->is('api/*') || $request->wantsJson()) {

            if ($exception instanceof HttpException) {
                $statusCode = $exception->getStatusCode();
            } else if ($exception instanceof ValidationException) {
                $errors = $exception->errors();
                // 使用 reset 函数获得第一个键名
                $keys = array_keys($errors);
                $firstKey = is_array($keys) && !empty($keys) ? reset($keys) : '未知字段Key';
                $subarray = array_shift($errors);
                $value = is_array($subarray) && !empty($subarray) ? reset($subarray) : '未知错误值';
                return error($exception->getMessage() . "[$firstKey:$value]", 422);
            } else if ($exception instanceof AuthenticationException) {
                $statusCode = 401;
            } else {
                $statusCode = 500;
            }

            return error($exception->getMessage(), $statusCode);
        }

        return parent::render($request, $exception);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return error('认证失败', 401);
    }
}
