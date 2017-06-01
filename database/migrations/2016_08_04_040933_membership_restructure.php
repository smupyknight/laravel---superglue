<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MembershipRestructure extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::drop('membership_features');
		Schema::drop('membership_items');

		Schema::create('plans', function(Blueprint $table) {
			$table->increments('id');
			$table->string('name', 255);
			$table->integer('num_seats')->unsigned();
			$table->integer('credit_per_renewal')->unsigned();
			$table->decimal('cost', 6, 2)->unsigned();
			$table->enum('frequency', ['day','week','fortnight','month','year']);
			$table->decimal('setup_cost', 6, 2)->unsigned();
			$table->datetime('created_at');
			$table->datetime('updated_at');
		});

		Schema::table('memberships', function(Blueprint $table) {
			$table->integer('plan_id')->unsigned()->after('id');
			$table->integer('num_seats')->unsigned()->after('email');
			$table->integer('credit_per_renewal')->unsigned()->after('num_seats');
			$table->decimal('cost', 6, 2)->unsigned()->after('credit_per_renewal');
			$table->enum('frequency', ['day','week','fortnight','month','year'])->after('cost');
			$table->datetime('renewal_date')->after('frequency');
			$table->integer('credit_balance')->unsigned()->after('renewal_date');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::create('membership_features', function(Blueprint $table) {
			$table->increments('id');
			$table->string('title', 100);
			$table->decimal('cost', 6, 2);
			$table->enum('frequency', ['day','week','fortnight','month','year']);
			$table->datetime('created_at');
			$table->datetime('updated_at');
		});

		Schema::create('membership_items', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('membership_id')->unsigned();
			$table->integer('feature_id')->unsigned();
			$table->decimal('cost', 6, 2);
			$table->enum('frequency', ['day','week','fortnight','month','year']);
			$table->date('expiry');
			$table->datetime('created_at');
			$table->datetime('updated_at');
		});

		Schema::drop('plans');

		Schema::table('memberships', function(Blueprint $table) {
			$table->dropColumn('plan_id');
			$table->dropColumn('num_seats');
			$table->dropColumn('credit_per_renewal');
			$table->dropColumn('cost');
			$table->dropColumn('frequency');
			$table->dropColumn('renewal_date');
			$table->dropColumn('credit_balance');
		});
	}

}
