<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use App\Models\User;
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
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
         
        view()->composer([
           
            'index'
        
        ], function($view){
        
            if(auth()->check()) {

                $user = User::select(['users.id'])
                        ->where('users.id', auth()->user()->id)
                        ->first();
            }else{

                $user = null;
            }

            $store = session()->get('store_name'); 
            
            $view->with([
                'store' => $store,
                'user' => $user->id
            ]);


        });

        if (env('APP_FORCE_HTTPS', false)) {
            $this->app['request']->server->set('HTTPS','on');
            URL::forceScheme('https');
        }

    }
    
}
