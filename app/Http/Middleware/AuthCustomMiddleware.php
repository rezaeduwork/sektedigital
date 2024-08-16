<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthCustomMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
      if (!auth()->check()) {
        session()->flash('unauthenticate', true);
        // // session()->flash('prev_url', request()->url());
        // return redirect('/errors/unauthenticated');
        abort(401);
      }
      return $next($request);
    }
}
