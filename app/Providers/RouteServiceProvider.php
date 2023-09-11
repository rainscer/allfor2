<?php namespace App\Providers;

use Illuminate\Routing\Route;
use Illuminate\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider {

	/**
	 * This namespace is applied to the controller routes in your routes file.
	 *
	 * In addition, it is set as the URL generator's root namespace.
	 *
	 * @var string
	 */
	protected $namespace = 'App\Http\Controllers';

	/**
	 * Define your route model bindings, pattern filters, etc.
	 *
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	public function boot(Router $router)
	{

		parent::boot($router);

		$router->model('user', 'App\Models\User');

		$router->model('menu', 'App\Models\Menu');

		$router->model('category', 'App\Models\CatalogCategory');

		$router->model('articles', 'App\Models\CatalogArticle');

		$router->bind('order',function($value) {
			return \App\Models\Order::where('id','=',$value)->first();
		});

		$router->model('setting','App\Models\Settings');

		$router->bind('product', function($value, Route $route) {
			$upi_id = $route->parameter('upi_id');

			return \App\Models\CatalogProduct::where([
				'slug' => $value,
				'upi_id' => $upi_id,
			])
				->active()
				->first();
		});

		$router->bind('upi_id_route',function($value) {
			return \App\Models\CatalogProduct::where('upi_id','=',$value)
				->active()
				->first();
		});

        $router->bind('category_id', function($value) {

            return \App\Models\CatalogCategory::withDepth()
                ->find($value);
        });

        $router->bind('product_id', function($value) {

            return \App\Models\CatalogProduct::find($value);
        });
		//
	}

	/**
	 * Define the routes for the application.
	 *
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	public function map(Router $router)
	{
		$router->group(['namespace' => $this->namespace], function($router)
		{
			require app_path('Http/routes.php');
		});
	}

}
