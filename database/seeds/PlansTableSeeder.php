<?php

use Illuminate\Database\Seeder;

class PlansTableSeeder extends Seeder
{

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('plans')->insert([
			'name'               => 'Ronin',
			'num_seats'          => '1',
			'credit_per_renewal' => '10',
			'cost'               => '55',
			'setup_cost'         => '88'
		]);

		DB::table('plans')->insert([
			'name'               => 'Mansuri',
			'num_seats'          => '1',
			'credit_per_renewal' => '20',
			'cost'               => '109',
			'setup_cost'         => '198'
		]);

		DB::table('plans')->insert([
			'name'               => 'Debu',
			'num_seats'          => '3',
			'credit_per_renewal' => '100',
			'cost'               => '770',
			'setup_cost'         => '380'
		]);
	}

}
