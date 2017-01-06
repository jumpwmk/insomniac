<?php namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Config;

class Sitestatus {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{

		if(Config::online() or Auth::isAdmin())
		{
			return $next($request);
		}
		return redirect('signin');
	}

}
