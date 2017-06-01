<?php

use Illuminate\Database\Seeder;

class SpacesSeeder extends Seeder
{

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('spaces')->insert([
			'name'     => 'SubStation',
			'address'  => '22 Petrie Terrace',
			'suburb'   => 'Brisbane',
			'state'    => 'QLD',
			'postcode' => '4000',
			'country'  => 'Australia'
		]);

		DB::table('spaces')->insert([
			'name'     => 'Spring Hill',
			'address'  => '36 Mein Street',
			'suburb'   => 'Spring Hill',
			'state'    => 'QLD',
			'postcode' => '4000',
			'country'  => 'Australia'
		]);

		DB::table('spaces')->insert([
			'name'     => 'Springfield - L3 World Knowledge Centre',
			'address'  => '37 Sinnathamby Boulevard',
			'suburb'   => 'Springfield Central',
			'state'    => 'QLD',
			'postcode' => '4300',
			'country'  => 'Australia'
		]);
	}

}
