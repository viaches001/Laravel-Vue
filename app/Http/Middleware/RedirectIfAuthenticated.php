<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user_role = Auth::user()->role;
                if ($user_role == 'installer' && Auth::user()->service_calls) $user_role = 'service_call_installer';
        
                switch ($user_role) {
                    case 'admin':
                        return redirect('/workorders');
                        break;
                    case 'service_call_installer':
                        return redirect('/timesheet_entries');
                        break; 
                    case 'installer':
                        return redirect('/timesheet_entries');
                        break; 

                    default:
                        return redirect(RouteServiceProvider::HOME); 
                        break;
                }
            }
        }

        return $next($request);
    }
}
