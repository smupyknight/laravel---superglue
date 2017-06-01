<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Event;
use DateTime;

class AppServiceProvider extends ServiceProvider
{

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		Event::listen('auth.login', function($user) {
			$user->last_login_at = new DateTime;
			$user->save();
		});
	}

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->alias('bugsnag.multi', \Illuminate\Contracts\Logging\Log::class);
		$this->app->alias('bugsnag.multi', \Psr\Log\LoggerInterface::class);

		$this->app->singleton('xero', function() {
			$xero = new \XeroPHP\Application\PrivateApplication([
				'oauth' => [
					'callback' => 'http://localhost/',
					'consumer_key' => env('XERO_CONSUMER_KEY'),
					'consumer_secret' => env('XERO_CONSUMER_SECRET'),
					'rsa_private_key' => 'file://' . storage_path('app/xero/private-key.pem'),
				],
			]);

			return $xero;
		});
	}

}
