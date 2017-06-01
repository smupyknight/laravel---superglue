<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PaymentFixes extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('ALTER TABLE payments CHANGE stripe_transaction_id stripe_charge_id VARCHAR(32)');
		DB::statement('ALTER TABLE payments DROP payment_date');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement('ALTER TABLE payments CHANGE stripe_charge_id stripe_transaction_id INT(11)');
		DB::statement('ALTER TABLE payments ADD payment_date DATETIME NOT NULL AFTER method');
	}

}
