<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HandleInertiaRequests extends Middleware
{
    public function version(Request $request)
    {
        return parent::version($request);
    }
}
