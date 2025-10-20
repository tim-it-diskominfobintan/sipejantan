<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AllowOnlyAjax
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->ajax()) {
            return response()->json([
                'message' => 'Forbidden: AJAX requests only.'
            ], 403);
        }

        return $next($request);
    }
}
