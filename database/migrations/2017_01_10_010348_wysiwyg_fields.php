<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WysiwygFields extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('ALTER TABLE announcements CHANGE content content TEXT NOT NULL');
		DB::statement('ALTER TABLE events CHANGE description description TEXT NOT NULL');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement('ALTER TABLE announcements CHANGE content content VARCHAR(500) NOT NULL');
		DB::statement('ALTER TABLE events CHANGE description description VARCHAR(500) NOT NULL');
	}

}
