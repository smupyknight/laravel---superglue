<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class Settings extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('settings', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name')->index();
			$table->string('value');
			$table->datetime('created_at');
			$table->datetime('updated_at');
		});

		DB::insert("INSERT INTO settings (name, value) VALUES ('next_billing_date', ?)", [
			$this->getNextBillingDate()->format('Y-m-d')
		]);
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('settings');
	}

	private function getNextBillingDate()
	{
		$ref = env('BILLING_DATE_REFERENCE');

		if (!$ref) {
			$ref = date('Y-m-d', strtotime('Friday'));
		}

		$today = Carbon::today('Australia/Brisbane');
		$billing_date = new Carbon(env('BILLING_DATE_REFERENCE'), 'Australia/Brisbane');

		while ($billing_date <= $today) {
			$billing_date->addWeeks(2);
		}

		return $billing_date;
	}

}
