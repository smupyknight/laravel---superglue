<?php

use Illuminate\Database\Seeder;

class TestingUsersSeeder extends Seeder
{
	// Front-end manual testing purpose seeder

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		// Add an admin user
		$user = factory(App\User::class)->create([
			'type'     => 'Admin',
			'email'    => 'testadmin@com.com',
			'password' => bcrypt('tester'),
		]);

		// Add bulk users.
		factory(App\User::class, 10)->create([
			'type' => 'Member',
		]);
	}

}
