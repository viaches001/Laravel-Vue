<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;

class VerifyRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, String $roles)
    {
        $roles = explode('|', $roles);

        if (Auth::check())
        {
            $user_role = Auth::user()->role;
            if(in_array($user_role, $roles)) return $next($request);
            if ($user_role == 'installer' && Auth::user()->service_calls) $user_role = 'service_call_installer';
            if(in_array($user_role, $roles)) return $next($request);
    
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
        else return redirect('/login');
    }
}
