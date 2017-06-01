<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RedoNotifications extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('push_notification_queue', function (Blueprint $table) {
			$table->dropColumn('alert');
			$table->dropColumn('badge');
			$table->dropColumn('link_url');

			$table->string('title', 255)->after('device_id');
			$table->string('body', 255)->after('title');
			$table->text('url')->after('body')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('push_notification_queue', function (Blueprint $table) {
			$table->dropColumn('title');
			$table->dropColumn('body');
			$table->dropColumn('url');

			$table->string('alert', 255)->after('device_id');
			$table->string('link_url', 255)->after('alert');
			$table->string('badge')->after('link_url');
		});
	}

}
