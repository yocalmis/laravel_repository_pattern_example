<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthMiddleware
{
    /**
 	* Run the request filter.
 	*
 	* @param  \Illuminate\Http\Request  $request
 	* @param  \Closure  $next
 	* @return mixed
 	*/
    public function handle($request, Closure $next)
    {
		
      if (!$request->session()->has('em')) {
        //
        return  redirect('login');
    }
         

    }

}
