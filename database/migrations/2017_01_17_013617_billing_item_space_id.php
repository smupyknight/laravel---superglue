<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BillingItemSpaceId extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('billing_items', function (Blueprint $table) {
			$table->integer('space_id')->unsigned()->nullable()->after('account_id');
		});

		DB::statement('
			UPDATE billing_items bi
			INNER JOIN accounts a ON bi.account_id = a.id
			SET bi.space_id = a.space_id
			WHERE bi.plan_id IS NOT NULL
		');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('billing_items', function (Blueprint $table) {
			$table->dropColumn('space_id');
		});
	}

}
