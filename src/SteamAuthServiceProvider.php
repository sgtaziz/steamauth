<?php namespace Sgtaziz\SteamAuth;

use Illuminate\Support\ServiceProvider;

class SteamAuthServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    // protected $defer = true;

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $configPath = __DIR__ . '/../config/sgtaziz.steamauth.php';
        $this->mergeConfigFrom($configPath, 'sgtaziz.steamauth');
        $this->publishes([$configPath => config_path('sgtaziz.steamauth.php')], 'config');

        $this->app->singleton('sgtaziz.steamauth', function ($app) {
            return new SteamAuth;
        });

        $this->app->alias('sgtaziz.steamauth', 'Sgtaziz\SteamAuth\SteamAuth');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['sgtaziz.steamauth'];
    }
}
