<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeedTables extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('feeds', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('created_by')->unsigned()->nullable();
			$table->string('title');
			$table->text('description');
			$table->dateTime('created_at');
			$table->dateTime('updated_at');

			$table->foreign('created_by')->references('id')->on('users');
		});

		Schema::create('posts', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('created_by')->unsigned()->nullable();
			$table->integer('feed_id')->unsigned();
			$table->string('unique_id', 10)->unique();
			$table->string('title');
			$table->text('content');
			$table->integer('num_likes')->unsigned();
			$table->dateTime('created_at');
			$table->dateTime('updated_at');

			$table->foreign('created_by')->references('id')->on('users');
			$table->foreign('feed_id')->references('id')->on('feeds');
		});

		Schema::create('likes', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->unsigned()->nullable();
			$table->integer('post_id')->unsigned()->nullable();
			$table->dateTime('created_at');
			$table->dateTime('updated_at');

			$table->foreign('user_id')->references('id')->on('users');
			$table->foreign('post_id')->references('id')->on('posts');
			$table->unique(['user_id','post_id']);
		});

		Schema::create('comments', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->unsigned()->nullable();
			$table->integer('post_id')->unsigned()->nullable();
			$table->text('content');
			$table->dateTime('created_at');
			$table->dateTime('updated_at');

			$table->foreign('user_id')->references('id')->on('users');
			$table->foreign('post_id')->references('id')->on('posts');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('feeds', function($table) {
		    $table->dropForeign('feeds_created_by_foreign');
		});

		Schema::table('posts', function($table) {
		    $table->dropForeign('posts_created_by_foreign');
		    $table->dropForeign('posts_feed_id_foreign');
		});

		Schema::table('likes', function($table) {
		    $table->dropForeign('likes_user_id_foreign');
		    $table->dropForeign('likes_post_id_foreign');
		});

		Schema::table('comments', function($table) {
		    $table->dropForeign('comments_user_id_foreign');
		    $table->dropForeign('comments_post_id_foreign');
		});

		Schema::drop('feeds');
		Schema::drop('posts');
		Schema::drop('likes');
		Schema::drop('comments');
	}

}
