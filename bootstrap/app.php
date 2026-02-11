<?php

use App\Constants\ApiCodes;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //alias middleware of spatie
        $middleware->alias([
           'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
           'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
           'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Authentication Exception
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*') || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'code'    => ApiCodes::UNAUTHORIZED_EXCEPTION,
                    'locale'  => 'en',
                    'message' => 'Unauthenticated or Token Expired.',
                    'data'    => null
                ], 401);
            }
        });
        // Solve validation problem
        $exceptions->render(function (ValidationException $exception, Request $request) {
            return ResponseBuilder::asError(ApiCodes::VALIDATION_EXCEPTION)
                ->withData($exception->errors())
                ->withHttpCode(422)
                ->build();
        });
        //handle 404 not found
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\ResolverNotFoundException $exception) {
           return ResponseBuilder::asError(ApiCodes::HTTP_NOT_FOUND)
           ->withHttpCode(404)
           ->build();
        });
        //handle UNCAUGHT
        $exceptions->render(function(Throwable $exception, $request) {
            return ResponseBuilder::asError(ApiCodes::UNCAUGHT_EXCEPTION)
                ->withMessage($exception->getMessage()) // turn on when APP_DEBUG = true
                ->withHttpCode(500)
                ->build();
        });

    })->create();
