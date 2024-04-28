<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Throwable;

class Handler extends ExceptionHandler {
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
    public function register(): void {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Force a json answer when the user is not authenticated
     * 
     */
    public function render($request, Throwable $exception) {
        if ($exception instanceof AuthenticationException) {
            return response()->json(["error" => 401, "message" => "authentication failed"], 401);
        }
        return parent::render($request, $exception);
    }
}
