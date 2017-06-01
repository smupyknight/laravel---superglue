<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameMemberships extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('timeline', function(Blueprint $table) {
			$table->dropForeign('timeline_membership_id_foreign');
		});

		Schema::rename('memberships', 'accounts');

		Schema::table('accounts', function(Blueprint $table) {
			$table->dropColumn('plan_id');
			$table->dropColumn('num_seats');
			$table->dropColumn('credit_per_renewal');
			$table->dropColumn('cost');
		});

		DB::statement('ALTER TABLE companies CHANGE membership_id account_id INT(10) UNSIGNED NOT NULL');
		DB::statement('ALTER TABLE credit_transactions CHANGE membership_id account_id INT(10) UNSIGNED NOT NULL');
		DB::statement('ALTER TABLE invoices CHANGE membership_id account_id INT(10) UNSIGNED NOT NULL');
		DB::statement('ALTER TABLE users CHANGE membership_id account_id INT(10) UNSIGNED DEFAULT NULL');

		Schema::create('billing_items', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('account_id')->unsigned()->index();
			$table->integer('plan_id')->unsigned();
			$table->integer('office_id')->unsigned();
			$table->integer('desk_id')->unsigned();
			$table->string('name');
			$table->decimal('cost', 6, 2);
			$table->integer('num_credits')->unsigned();
			$table->date('start_date');
			$table->date('end_date')->nullable();
			$table->datetime('created_at');
			$table->datetime('updated_at');
		});

		Schema::table('invoice_items', function(Blueprint $table) {
			$table->dropColumn('is_plan');
			$table->dropColumn('is_recurring');
			$table->integer('num_credits')->unsigned()->after('amount');
			$table->renameColumn('amount', 'cost');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('accounts', function(Blueprint $table) {
			$table->integer('plan_id')->unsigned()->after('id');
			$table->integer('num_seats')->unsigned()->after('email');
			$table->integer('credit_per_renewal')->unsigned()->after('num_seats');
			$table->decimal('cost', 6, 2)->unsigned()->after('credit_per_renewal');
		});

		Schema::rename('accounts', 'memberships');

		Schema::table('timeline', function(Blueprint $table) {
			$table->foreign('membership_id')->references('id')->on('memberships');
		});

		DB::statement('ALTER TABLE companies CHANGE account_id membership_id INT(10) UNSIGNED NOT NULL');
		DB::statement('ALTER TABLE credit_transactions CHANGE account_id membership_id INT(10) UNSIGNED NOT NULL');
		DB::statement('ALTER TABLE invoices CHANGE account_id membership_id INT(10) UNSIGNED NOT NULL');
		DB::statement('ALTER TABLE users CHANGE account_id membership_id INT(10) UNSIGNED DEFAULT NULL');

		Schema::drop('billing_items');

		Schema::table('invoice_items', function(Blueprint $table) {
			$table->renameColumn('cost', 'amount');
			$table->dropColumn('num_credits');
		});
		Schema::table('invoice_items', function(Blueprint $table) {
			$table->tinyInteger('is_plan')->after('amount');
			$table->tinyInteger('is_recurring')->after('is_plan');
		});
	}

}
