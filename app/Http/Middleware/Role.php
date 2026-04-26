<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Role
{
    public function handle($request, Closure $next, ...$roles)
    {
        // I included this check because you have it, but it really should be part of your 'auth' middleware, most likely added as part of a route group.
        if (! Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();

        if ($user->isSuperuser()) {
            return $next($request);
        }

        // dump($user->roles->pluck('name')->toArray());

        foreach ($roles as $role) {
            // dump($user->hasRole($role), $role);
            // Check if user has the role This check will depend on how your roles are set up
            if ($user->hasRole($role)) {
                // dd('has this role: '.$role);
                return $next($request);
            }
        }

        // return redirect('login');
        return abort(403, 'Tidak memiliki akses.');
    }
}
