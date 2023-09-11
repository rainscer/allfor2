<?php namespace App\Providers;

use App\Models\CatalogCategory;
use App\Models\Settings;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\App;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{

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
     * @param  \Illuminate\Routing\Router $router
     * @return void
     */
    public function boot(Router $router)
    {
        if ((App::getLocale()=='ua') or (App::getLocale()=='ru')){
            $curency_code = ' грн';
        }else{
            $curency_code = ' $';
        }
        view()->share('curency_code', $curency_code);

        /*$curency = 'curency_'.App::getLocale();
        Settings::getSettingValue($curency) ? $curency = Settings::getSettingValue($curency) : $curency = 1;
        view()->share('curency', $curency);*/

        $local = "name_" . App::getLocale();
        view()->share('local', $local);

        $local_article_title = "title_" . App::getLocale();
        view()->share('local_article_title', $local_article_title);
        $local_article_text = "text_" . App::getLocale();
        view()->share('local_article_text', $local_article_text);


        $localDescription = "description_" . App::getLocale();
        view()->share('local_description', $localDescription);

        parent::boot($router);
    }

    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router $router
     * @return void
     */
    public function map(Router $router)
    {
        $router->group(
            ['namespace' => $this->namespace],
            function ($router) {
                require app_path('Http/routes.php');
            }
        );
    }

}
