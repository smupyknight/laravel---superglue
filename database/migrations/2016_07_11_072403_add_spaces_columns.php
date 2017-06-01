<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSpacesColumns extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('spaces', function ($table) {
			$table->string('address', 250)->after('name');
			$table->string('suburb', 40)->after('address');
			$table->string('postcode', 4)->after('suburb');
			$table->string('state', 20)->after('postcode');
			$table->string('country', 50)->after('state');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('spaces', function (Blueprint $table) {
			$table->dropColumn('address');
			$table->dropColumn('suburb');
			$table->dropColumn('postcode');
			$table->dropColumn('state');
			$table->dropColumn('country');
		});
	}

}
