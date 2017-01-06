<?php namespace App\Http\Middleware;

use Closure;
use App\Config;

class Registerstatus {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{

		if(Config::allow_register())
		{
			return $next($request);
		}
		return redirect('main');
	}

}
