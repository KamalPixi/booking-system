<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Enums\UserEnum;

class AgentMiddleware {

    public function handle(Request $request, Closure $next) {
        $allowedType = [
            UserEnum::TYPE['AGENT'],
            UserEnum::TYPE['AGENT_USER']
        ];

        if (!auth()->check()) {
            return redirect()->route('b2b.login');
        }

        if (in_array(auth()->user()->type, $allowedType)) {
            return $next($request);
        }

        return redirect()->route('b2b.login');
    }

}
