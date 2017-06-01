<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class XeroFields extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('accounts', function (Blueprint $table) {
			$table->char('xero_contact_id', 36)->nullable()->after('space_id');
			$table->string('xero_contact_name')->nullable()->after('xero_contact_id');
		});

		Schema::table('invoices', function (Blueprint $table) {
			$table->string('xero_invoice_number')->nullable()->after('account_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('accounts', function (Blueprint $table) {
			$table->dropColumn('xero_contact_id');
			$table->dropColumn('xero_contact_name');
		});

		Schema::table('invoices', function (Blueprint $table) {
			$table->dropColumn('xero_invoice_number');
		});
	}

}
