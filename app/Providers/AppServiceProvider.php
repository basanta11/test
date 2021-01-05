<?php

namespace App\Providers;

use App\Helpers\Datatable\NotificationQuery;
use App\Helpers\Datatable\StudentQuery;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }

        $this->app->singleton(StudentQuery::class, function($app) {
            $request = app(\Illuminate\Http\Request::class);
            return new StudentQuery($request);
        });
        $this->app->singleton(NotificationQuery::class, function($app) {
            $request = app(\Illuminate\Http\Request::class);
            return new NotificationQuery($request);
        });
        
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::if('can', function ($permission) {
            return auth()->user()->hasPermission($permission);
        });

        Blade::if('canany', function ($permissions) {
            return auth()->user()->hasAnyPermission($permissions);
        });

        Blade::if('hasrole', function ($role) {
            return auth()->user()->hasRole($role);
        });

        Blade::if('plan', function ($plan) {
            return tenant()->plan == $plan || tenant()->plan == "large";
        });

        Blade::directive('displaylink', function ($link) {
            return "<?php echo strpos($link, 'http') !== false ? $link : '//'.$link ?>";
        });
    }
}
