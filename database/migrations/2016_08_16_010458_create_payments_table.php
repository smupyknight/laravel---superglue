<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payments', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('account_id');
			$table->integer('invoice_id')->unsigned();
			$table->integer('stripe_transaction_id')->nullable();
			$table->decimal('amount', 7, 2);
			$table->enum('method', ['credit card','bank deposit','cash','cheque']);
			$table->dateTime('payment_date');
			$table->dateTime('created_at');
			$table->dateTime('updated_at');

			$table->foreign('invoice_id')->references('id')->on('invoices');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('payments');
	}

}
