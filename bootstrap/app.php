<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Http\Exceptions\PostTooLargeException $e, \Illuminate\Http\Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Ukuran file terlalu besar. Pastikan ukuran file maksimal 2 MB dan konfigurasi PHP sudah diubah.',
                    'message' => 'Silakan ubah post_max_size dan upload_max_filesize di php.ini XAMPP menjadi minimal 12M, lalu restart Apache.'
                ], 413);
            }

            return redirect()->back()
                ->with('error', 'Ukuran file terlalu besar! Silakan ubah konfigurasi PHP di php.ini XAMPP: post_max_size dan upload_max_filesize menjadi minimal 12M, lalu restart Apache.');
        });
    })->create();
