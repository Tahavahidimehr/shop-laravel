<?php

use App\Traits\ApiResponse;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Illuminate\Console\Scheduling\Schedule; // ← اضافه شده
use App\Jobs\ReleaseExpiredOrders; // ← اضافه شده

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )

    ->withSchedule(function (Schedule $schedule) {
        $schedule->job(new ReleaseExpiredOrders())->everyMinute();
    })

    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->statefulApi();
    })

    ->withExceptions(function ($exceptions): void {
        $exceptions->render(function (Throwable $e, Request $request) {
            $api = new class { use ApiResponse; };

            if ($request->is('api/*') || $request->expectsJson()) {

                if ($e instanceof ValidationException) {
                    return $api->validationErrorResponse($e->validator);
                }

                if ($e instanceof AuthenticationException) {
                    return $api->errorResponse('Unauthenticated.', null, 401);
                }

                if ($e instanceof AuthorizationException || $e instanceof AccessDeniedHttpException) {
                    return $api->errorResponse('You are not authorized to access this resource.', null, 403);
                }

                if ($e instanceof ModelNotFoundException || $e instanceof NotFoundHttpException) {
                    return $api->errorResponse('Resource not found.', null, 404);
                }

                if ($e instanceof MethodNotAllowedHttpException) {
                    return $api->errorResponse('Method not allowed.', null, 405);
                }

                if ($e instanceof HttpException) {
                    return $api->errorResponse(
                        $e->getMessage() ?: 'HTTP error occurred.',
                        null,
                        $e->getStatusCode()
                    );
                }

                if ($e instanceof \Illuminate\Session\TokenMismatchException) {
                    return $api->errorResponse('CSRF token mismatch.', null, 419);
                }

                return $api->errorResponse('Internal server error.', null, 500);
            }

            return null;
        });
    })

    ->create();
