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
        $middleware->redirectTo(
            guests: fn (Request $request) => $request->is('api/*') ? null : route('login')
        );
        //alias middleware of spatie
        $middleware->alias([
           'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
           'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
           'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {

        // 1. Authentication Exception
        $exceptions->render(function (AuthenticationException $exception, Request $request) {
            return ResponseBuilder::asError(ApiCodes::UNAUTHENTICATED_EXCEPTION)
                ->withHttpCode(Response::HTTP_UNAUTHORIZED)
                ->withMessage(__('Unauthenticated or Token expired.'))
                ->build();
        });
        // 2. Solve validation problem
        $exceptions->render(function (ValidationException $exception, Request $request) {
            return ResponseBuilder::asError(ApiCodes::VALIDATION_EXCEPTION)
                ->withData(['errors' => $exception->errors()])
                ->withMessage($exception->getMessage())
                ->withHttpCode(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->build();
        });
        // 3. handle 404 not found
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\ResolverNotFoundException $exception) {
           return ResponseBuilder::asError(ApiCodes::HTTP_NOT_FOUND)
           ->withHttpCode(Response::HTTP_NOT_FOUND)
               ->withMessage($exception->getMessage() ?: __('Resource not found.'))
           ->build();
        });
        // 4. 403 Forbidden
        $exceptions->render(function (\Symfony\Component\Finder\Exception\AccessDeniedException $exception, Request $request) {
           return ResponseBuilder::asError(ApiCodes::HTTP_FORBIDDEN)
           ->withHttpCode(Response::HTTP_FORBIDDEN)
           ->withMessage($exception->getMessage() ?: __('Access denied.'))
           ->build();
        });
        // 5. Internal Server Error
        $exceptions->render(function(Throwable $exception, Request $request) {
            if ($request->is('api/*')) {
                $debug = config('app.debug');
                return ResponseBuilder::asError(ApiCodes::UNCAUGHT_EXCEPTION)
                    ->withMessage($exception->getMessage() ?: __('Internal server error.'))
                    ->withHttpCode(Response::HTTP_INTERNAL_SERVER_ERROR)
                    ->withData($debug ? [
                        'exception' => get_class($exception),
                        'file' => $exception->getFile(),
                        'line' => $exception->getLine(),
                        'trace' => collect($exception->getTrace())->take(5),
                    ] : null)
                    ->build();
            }
        });

    })->create();
