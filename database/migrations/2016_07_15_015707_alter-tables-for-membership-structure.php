<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTablesForMembershipStructure extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('memberships', function ($table) {
			$table->string('address', 250)->after('name');
			$table->string('suburb', 40)->after('address');
			$table->string('postcode', 4)->after('suburb');
			$table->string('state', 20)->after('postcode');
			$table->string('country', 50)->after('state');
			$table->string('billing_name', 100)->after('country');
			$table->string('abn', 11)->after('billing_name');
			$table->string('email', 50)->after('abn');
		});

		Schema::create('membership_items', function (Blueprint $table) {
			$table->increments('id')->unsigned();
			$table->integer('membership_id')->unsigned()->references('id')->on('memberships');
			$table->integer('feature_id')->unsigned()->references('id')->on('membership_features');;
			$table->decimal('cost_override', 6, 2);
			$table->datetime('expiry');
			$table->datetime('created_at');
			$table->datetime('updated_at');
		});

		Schema::create('membership_features', function (Blueprint $table) {
			$table->increments('id')->unsigned();
			$table->string('title', 100);
			$table->decimal('cost', 6, 2);
			$table->enum('recurrance', ['Weekly', 'Fortnightly', 'Monthly', 'Yearly']);
			$table->datetime('created_at');
			$table->datetime('updated_at');
		});

		Schema::table('users', function ($table) {
			$table->dropColumn('company_id');
			$table->integer('membership_id')->unsigned()->references('id')->on('memberships');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('memberships', function (Blueprint $table) {
			$table->dropColumn('address');
			$table->dropColumn('suburb');
			$table->dropColumn('postcode');
			$table->dropColumn('state');
			$table->dropColumn('country');
			$table->dropColumn('email');
			$table->dropColumn('abn');
		});

		Schema::table('users', function (Blueprint $table) {
			$table->integer('company_id')->unsigned()->references('id')->on('companies')->after('email');
			$table->dropColumn('membership_id');
		});

		Schema::drop('membership_features');
		Schema::drop('membership_items');
	}

}
