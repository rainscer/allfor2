<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		// For Webmoney and Api don`t check CSRF token
		if ((str_contains($request->getRequestUri(), '/payment/')) ||
			(str_contains($request->getRequestUri(), '/api/'))) {
			return $next($request);
		}

		return parent::handle($request, $next);
	}

}
