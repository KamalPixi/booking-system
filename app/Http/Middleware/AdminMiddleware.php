<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Enums\UserEnum;

class AdminMiddleware {

    public function handle(Request $request, Closure $next) {
        $allowedType = [
            UserEnum::TYPE['ADMIN'],
            UserEnum::TYPE['ADMIN_USER'],
        ];
        
        if (in_array(auth()->user()->type, $allowedType)) {
            return $next($request);
        }

        return route('admin.login');
    }

}
