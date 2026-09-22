<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DemoAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->session()->get('demo_logged_in', false)) {
            return redirect()
                ->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        return $next($request);
    }
}