<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            switch ( auth()->user()->privilege ) {
                case 'teacher':
                    return redirect()->route('teacher.management.terms');
                case 'administrator':
                    return redirect()->route('terms.index');
                case 'student':
                    return redirect()->route('student_access.terms');
                case 'headmaster':
                    return redirect()->route('headmaster_access.terms');
                default:
                    return redirect('/home');
            }
        }

        return $next($request);
    }
}
