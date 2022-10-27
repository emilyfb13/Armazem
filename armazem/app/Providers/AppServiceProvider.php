<?php

namespace App\Providers;
use GuzzleHttp\Client;

use App\Helpers\Helper;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;


class AppServiceProvider extends ServiceProvider
{
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
     * @return void
     */
    public function register()
    {
        // teste   => http://192.168.101.237:8014/api/ 
        // Oficial => http://192.168.101.3:8025/rest
        $this->app->singleton('GuzzleHttp\Client', function(){
            return new Client ([
                'base_uri' => 'http://18.230.177.237:8080/rest/'
            ]);
        });
    }

}
