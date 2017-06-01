<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterBillingItemsTableNullableFields extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('billing_items', function (Blueprint $table) {
			$table->integer('plan_id')->unsigned()->nullable()->change();
			$table->integer('office_id')->unsigned()->nullable()->change();
			$table->integer('desk_id')->unsigned()->nullable()->change();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('billing_items', function (Blueprint $table) {
			$table->integer('plan_id')->unsigned()->change();
			$table->integer('office_id')->unsigned()->change();
			$table->integer('desk_id')->unsigned()->change();
		});
	}

}
