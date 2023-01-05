<?php
namespace Zprefe\CommonService;

use Illuminate\Support\ServiceProvider;

class CommonServiceProvider extends ServiceProvider {

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/config.php' => config_path('common_service.php'), // 发布配置文件到 laravel 的config 下
        ], 'common_service');
    }

    public function register()
    {
        
    }

}