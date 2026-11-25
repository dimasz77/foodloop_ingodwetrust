<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class EnsureRole
{
    public function handle(Request $request, Closure $next, string $role)
    {
        if (! $request->user() || $request->user()->role !== $role) {
            abort(403, 'Halaman ini hanya untuk '.(User::ROLES[$role] ?? $role).'.');
        }

        return $next($request);
    }
}
