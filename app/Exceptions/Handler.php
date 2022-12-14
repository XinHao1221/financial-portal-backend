<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
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

    public function render($request, Throwable $e)
    {
        // Throw if email already registered
        if ($e instanceof UserExistException) {
            return response()->json([
                "message" => "Email already registered. Please login",
            ]);
        }

        if ($e instanceof NotFoundHttpException) {
            return response()->json([
                "message" => "Not found."
            ], 404);
        }

        if ($e instanceof ModelNotFoundException) {
            $modelName = (new \ReflectionClass($e->getModel()))->getShortName();

            return response()->json([
                "message" => "$modelName Not found."
            ], 404);
        }

        return parent::render($request, $e);
    }
}
