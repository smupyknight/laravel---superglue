<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfficesTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		 Schema::create('offices', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('space_id')->unsigned();
			$table->string('name', 255);
			$table->string('features', 500);
			$table->integer('capacity')->unsigned();
			$table->decimal('cost', 6, 2)->unsigned();
			$table->datetime('created_at');
			$table->datetime('updated_at');

			$table->foreign('space_id')->references('id')->on('spaces');
		 });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('offices');
	}

}
