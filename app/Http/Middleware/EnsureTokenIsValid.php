<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureTokenIsValid
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('sanctum')->check()) {
            return response()->json(['message' => 'Token không hợp lệ. Vui lòng đăng nhập lại'], 401);
        }

        return $next($request);
    }
}
