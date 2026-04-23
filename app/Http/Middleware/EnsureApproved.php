<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureApproved
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && !$user->is_approved) {
            return redirect()->route('dashboard')
                ->with('error', 'Please submit your payment receipt and wait for admin approval.');
        }

        return $next($request);
    }
}
