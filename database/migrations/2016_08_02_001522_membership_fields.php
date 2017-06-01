<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MembershipFields extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('membership_features', function(Blueprint $table) {
			$table->enum('frequency', ['day','week','fortnight','month','year'])->after('recurrance');
			$table->dropColumn('recurrance');
		});

		Schema::table('membership_items', function(Blueprint $table) {
			$table->date('expiry')->nullable()->change();
			$table->decimal('cost', 6, 2)->after('cost_override');
			$table->enum('frequency', ['day','week','fortnight','month','year'])->after('cost');
			$table->dropColumn('cost_override');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('membership_features', function(Blueprint $table) {
			$table->enum('recurrance', ['Daily','Weekly','Fortnightly','Monthly','Yearly'])->after('frequency');
			$table->dropColumn('frequency');
		});

		Schema::table('membership_items', function(Blueprint $table) {
			$table->dropColumn('expiry');
			$table->decimal('cost_override', 6, 2)->nullable()->after('feature_id');
			$table->dropColumn('cost');
			$table->dropColumn('frequency');
		});

		Schema::table('membership_items', function(Blueprint $table) {
			$table->datetime('expiry')->nullable();
		});
	}

}
