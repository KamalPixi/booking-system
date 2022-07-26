<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            $parts = explode('.', $request->getHost());
            if (!isset($parts[0])) {
                return route('b2b.login');
            }

            if ($parts[0] == 'admin') {
                return route('admin.login');
            }

            return route('b2b.login');
        }
    }
}
