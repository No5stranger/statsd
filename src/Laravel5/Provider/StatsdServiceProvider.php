<?php

namespace League\StatsD\Laravel5\Provider;

use Illuminate\Support\ServiceProvider;
use League\StatsD\Client as Statsd;

/**
 * StatsD Service provider for Laravel
 *
 * @author Aran Wilkinson <aran@aranw.net>
 */
class StatsdServiceProvider extends ServiceProvider
{

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
		// Publish config files
		$this->publishes([
			__DIR__.'/../../../config/config.php' => config_path('statsd.php'),
		]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerStatsD();
    }

    /**
     * Register Statsd
     *
     * @return void
     */
    protected function registerStatsD()
    {
        $this->app['statsd'] = $this->app->share(
            function ($app) {
                $statsd = new Statsd();
                $config = $app['config'];
                $statsd->configure(isset($config['statsd']) ? $config['statsd'] : array());
                return $statsd;
            }
        );

        $this->app->bind('League\StatsD\Client', function ($app) {
            return $app['statsd'];
        });
    }
}
