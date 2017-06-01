<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Billing extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('invoices', function(Blueprint $table) {
			$table->date('due_date')->after('status');
		});

		Schema::create('invoice_items', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('invoice_id')->unsigned();
			$table->string('description', 255);
			$table->decimal('amount', 6, 2);
			$table->tinyInteger('is_plan');
			$table->tinyInteger('is_recurring');
			$table->datetime('created_at');
			$table->datetime('updated_at');
		});

		Schema::table('memberships', function(Blueprint $table) {
			$table->enum('status', ['active','expired'])->after('credit_balance');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('invoices', function(Blueprint $table) {
			$table->dropColumn('due_date');
		});

		Schema::drop('invoice_items');

		Schema::table('memberships', function(Blueprint $table) {
			$table->dropColumn('status');
		});
	}

}
