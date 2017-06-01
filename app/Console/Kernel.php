<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		Commands\Billing::class,
		Commands\HubspotImporter::class,
		Commands\SendPushNotifications::class,
		Commands\PayInvoices::class,
		Commands\Import::class,
		Commands\InvitePendingUsers::class,
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule	$schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		$schedule->command('billing')->daily()->withoutOverlapping();
		$schedule->command('send-push-notifications')->withoutOverlapping();
	}

}
