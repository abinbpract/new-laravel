<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Http\Middleware\CheckAbilities;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // $middleware->alias([
        //     'abilities' => CheckAbilities::class,
        //     'ability' => CheckForAnyAbility::class,
        // ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (NotFoundHttpException $e, $request) {
            return response()->json(['status' => false, 'message' => 'Page not found'], 404);
        });
        $exceptions->render(function (AuthenticationException $e, $request) {
            return response()->json(['status' => false, 'message' => 'Unauthorized'], 401);
        });
        $exceptions->render(function (ValidationException $e, $request) {
            return response()->json(['status' => false, 'message' => 'Validation Error', 'errors' => $e->errors()], 422);
        });
        $exceptions->render(function (MethodNotAllowedHttpException $e, $request) {
            return response()->json(['status' => false, 'message' => 'Method Not Allowed'], 405);
        });
        $exceptions->render(function (HttpException $e, $request) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], $e->getStatusCode());
        });
        $exceptions->render(function (\Exception $e, $request) {
            return response()->json(['status' => false, 'message' => 'Internal Server Error'], 500);
        });
    })->create();
