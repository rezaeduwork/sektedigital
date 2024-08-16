<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserHasStoreMiddleware
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
      return redirect('/profile');
    }
    if (!auth()->user()->store()->exists()) {
      session()->flash('page', 'Kios Saya');
      return redirect('/profile');
    }
    return $next($request);
  }
}
