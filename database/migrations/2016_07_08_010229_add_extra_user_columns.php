<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtraUserColumns extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function ($table) {
			$table->integer('company_id')->unsigned()->references('id')->on('companies')->after('email');
			$table->string('address', 250)->after('company_id');
			$table->string('suburb', 40)->after('address');
			$table->string('postcode', 4)->after('suburb');
			$table->string('state', 20)->after('postcode');
			$table->string('country', 40)->after('state');
			$table->text('bio')->after('country');
			$table->string('phone', 50)->after('bio');
			$table->boolean('is_public')->after('phone');
			$table->boolean('is_looking_for_connections')->after('is_public');
			$table->enum('type', ['Member', 'Mentor', 'Admin'])->after('is_looking_for_connections');
			$table->string('linkedin_token')->after('type');
			$table->string('timezone', 100)->after('linkedin_token');
			$table->dateTime('last_login')->after('timezone');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users', function (Blueprint $table) {
			$table->dropColumn('bio');
			$table->dropColumn('phone');
			$table->dropColumn('company_id');
			$table->dropColumn('is_public');
			$table->dropColumn('is_looking_for_connections');
			$table->dropColumn('type');
			$table->dropColumn('linkedin_token');
			$table->dropColumn('timezone');
			$table->dropColumn('last_login');
		});
	}

}
