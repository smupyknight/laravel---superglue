<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Memberships extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('invoices', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('membership_id')->unsigned()->index();
			$table->decimal('total', 7, 2);
			$table->enum('status', ['pending','paid']);
			$table->datetime('created_at');
			$table->datetime('updated_at');
		});

		Schema::table('membership_items', function(Blueprint $table) {
			$table->decimal('cost_override', 6, 2)->nullable()->change();
			$table->datetime('expiry')->nullable()->change();
		});

		Schema::table('users', function(Blueprint $table) {
			$table->datetime('last_login_at')->nullable()->after('remember_token');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('invoices');

		Schema::table('membership_items', function(Blueprint $table) {
			$table->decimal('cost_override', 6, 2)->change();
			$table->datetime('expiry')->change();
		});

		Schema::table('users', function(Blueprint $table) {
			$table->dropColumn('last_login_at');
		});
	}

}
