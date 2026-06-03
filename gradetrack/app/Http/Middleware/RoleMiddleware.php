<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Usage: middleware('role:admin')
     *        middleware('role:admin,teacher')
     */
    public function handle(Request $request, Closure $next, string ...$roles): mixed
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Must be approved before accessing any protected page
        if (!$user->isApproved() && !$user->isAdmin()) {
            Auth::logout();
            return redirect()->route('login')
                ->withErrors(['email' => 'Your account is pending admin approval.']);
        }

        if (!in_array($user->role, $roles)) {
            abort(403, 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}
