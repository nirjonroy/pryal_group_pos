<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Info;
use App\CompanyType;
class AppServiceProvider extends ServiceProvider
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
        $comTypes =CompanyType::orderBy('name','asc')->get();
        $infos =Info::all();
        view()->share(compact('infos','comTypes'));
        Schema::defaultStringLength(191);
    }
}
