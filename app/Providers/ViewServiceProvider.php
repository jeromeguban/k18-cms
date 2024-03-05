<?php

namespace App\Providers;

use App\Models\Store;
use App\Models\AccountExecutive;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', 'App\Http\View\Composers\DarkModeComposer');
        View::composer([
            'index',
        ], function ($view) {

            if (auth()->check()) {

                $validate = AccountExecutive::where('user_id', auth()->user()->id)->first();

                $store = Store::where('id', session()->get('store_id'))
                            ->first();

                $account_executive = $validate->id ?? null;
                $name = ($store->store_name).' - '.auth()->user()->first_name;



            } else {

                $account_executive = null;
                $name = null;
            }

            $view->with([
                'account_executive' => $account_executive,
                'developer' => auth()->user()->isDeveloper(),
                'name'  => $name,
                'url' => env('APP_ENV') == 'local' ? 'https://hmrph.hmrbid.com' : (env('APP_ENV') == 'production' ? 'https://hmr.ph' : null),
            ]);
        });

    }
}
