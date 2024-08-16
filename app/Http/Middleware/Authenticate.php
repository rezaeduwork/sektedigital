<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
  /**
   * Get the path the user should be redirected to when they are not authenticated.
   */
  protected function redirectTo(Request $request): ?string
  {
    if (!auth()->check() && !$request->expectsJson()) {
      session()->flash('unauthenticate', true);
    }
    return $request->expectsJson() ? null : url('/errors/unauthenticated');
  }
}
