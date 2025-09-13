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
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'pharmacien' => \App\Http\Middleware\PharmacienMiddleware::class,
            'pharmacien.licence' => \App\Http\Middleware\EnsurePharmacienHasLicence::class, // Add this line
            'patient' => \App\Http\Middleware\PatientMiddleware::class,
            'docteur' => \App\Http\Middleware\DocteurMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
