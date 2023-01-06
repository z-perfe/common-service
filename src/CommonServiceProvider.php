<?php
namespace Zperfe\Common;

use Illuminate\Support\ServiceProvider;

class CommonServiceProvider extends ServiceProvider {

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes(
                [__DIR__.'/../config/config.php' => config_path('common_service.php'),], 
                'common_service'
            );
        }
    }

    public function register()
    {
        
    }

}