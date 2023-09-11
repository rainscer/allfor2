<?php namespace App\Providers;

use App\Models\CatalogCategory;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    /**
     * @var array
     */
    private $singletons = [
        'Catalog',
        'Review',
        'Order',
        'Cart',
        'Setting',
        'CallMeService'
    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register any application services.
     *
     * This service provider is a great spot to register your various container
     * bindings with the application. As you can see, we are registering our
     * "Registrar" implementation here. You can add your own bindings too!
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'Illuminate\Contracts\Auth\Registrar',
            'App\Services\Registrar'
        );

        foreach ($this->singletons as $singleton) {

            $this->app->singleton($singleton, 'App\Services\\' . $singleton);
        }

    }

}
