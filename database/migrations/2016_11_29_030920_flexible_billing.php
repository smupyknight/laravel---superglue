<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FlexibleBilling extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('billing_items', function (Blueprint $table) {
			$table->date('next_billing_date')->after('start_date');
		});

		DB::statement('UPDATE billing_items SET next_billing_date = start_date');

		DB::statement("ALTER TABLE invoices MODIFY status ENUM('pending','paid','expired')");

		Schema::table('invoice_items', function (Blueprint $table) {
			$table->date('date')->after('invoice_id');
		});

		Schema::drop('settings');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('billing_items', function (Blueprint $table) {
			$table->dropColumn('next_billing_date');
		});

		DB::statement("ALTER TABLE invoices MODIFY status ENUM('pending','paid')");

		Schema::table('invoice_items', function (Blueprint $table) {
			$table->dropColumn('date');
		});

		Schema::create('settings', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name')->index();
			$table->string('value');
			$table->datetime('created_at');
			$table->datetime('updated_at');
		});
	}

}
