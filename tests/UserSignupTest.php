<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserSignupTest extends TestCase
{

	/**
	 * A basic test example.
	 *
	 * @return void
	 */
	public function testBasicSubmission()
	{
		$this->prepareSpaces();
		$space = DB::table('spaces')->get();

		$this->json('POST', '/api/v1/users/create', [
			'first_name'            => 'John',
			'last_name'             => 'Smith',
			'twitter_handle'        => 'john.smith',
			'instagram_handle'      => 'john.smith',
			'email'                 => 'john.smith@gmail.com',
			'dob'                   => '1990-09-23',
			'salutation'            => 'Mr',
			'address'               => '1 Infinite Loop',
			'city'                  => 'Great City',
			'suburb'                => 'Suburb',
			'postcode'              => '4173',
			'state'                 => 'QLD',
			'country'               => 'Australia',
			'bio'                   => 'This is an example bio.',
			'phone'                 => '12341234',
			'company_name'          => 'The Greatest Company Ever',
			'job_title'             => 'The Greatest CEO',
			'industry'              => 'The Greatest Industry',
			'website'               => 'www.reallygreatwebsite.com',
			'is_public'             => '1',
			'has_visited'           => '1',
			'like_tour'             => '1',
			'accepts_terms'         => '1',
			'timezone'              => 'Australia/Brisbane',
			'password'              => 'password',
			'password_confirmation' => 'password',
			'space_id'              => $space[0]->id,
		]);

		$this->assertEquals(200, $this->response->status());

		$this->seeInDatabase('accounts', [
			'name'     => 'John Smith',
			'email'    => 'john.smith@gmail.com',
			'space_id' => $space[0]->id,
		]);

		$account = DB::table('accounts')->get();

		$this->seeInDatabase('users', [
			'first_name'       => 'John',
			'last_name'        => 'Smith',
			'twitter_handle'   => 'john.smith',
			'instagram_handle' => 'john.smith',
			'email'            => 'john.smith@gmail.com',
			'dob'              => '1990-09-23',
			'salutation'       => 'Mr',
			'address'          => '1 Infinite Loop',
			'city'             => 'Great City',
			'suburb'           => 'Suburb',
			'postcode'         => '4173',
			'state'            => 'QLD',
			'country'          => 'Australia',
			'bio'              => 'This is an example bio.',
			'phone'            => '12341234',
			'company_name'     => 'The Greatest Company Ever',
			'job_title'        => 'The Greatest CEO',
			'industry'         => 'The Greatest Industry',
			'website'          => 'www.reallygreatwebsite.com',
			'is_public'        => '1',
			'has_visited'      => '1',
			'like_tour'        => '1',
			'accepts_terms'    => '1',
			'timezone'         => 'Australia/Brisbane',
			'account_id'       => $account[0]->id,
		]);

		$user = DB::table('users')->get();

		$this->seeInDatabase('timeline', [
			'created_by' => null,
			'user_id'    => $user[0]->id,
			'account_id' => $account[0]->id,
			'title'      => 'User Signup',
			'message'    => 'John Smith (john.smith@gmail.com) signed up.',
			'type'       => 'info',
		]);
	}

	public function testValidation()
	{
		$this->json('POST', '/api/v1/users/create');

		$this->assertEquals(422, $this->response->status());

		$this->seeJsonEquals([
			'accepts_terms' => ['The accepts terms field is required.'],
			'email'         => ['The email field is required.'],
			'first_name'    => ['The first name field is required.'],
			'last_name'     => ['The last name field is required.'],
			'password'      => ['The password field is required when linkedin token is not present.'],
			'timezone'      => ['The timezone field is required.'],
			'space_id'      => ['The space id field is required.'],
		]);

		// Test other validation rules
		$this->json('POST', '/api/v1/users/create', [
			'email'         => 'invalid', // fails email
			'dob'           => '23-09-1990', // fails date_format:Y-m-d
			'postcode'      => 'invalid', // fails numeric
			'phone'         => 'invalid', // fails numeric
			'website'       => 'www.reallygreatwebsite.com',
			'is_public'     => 'invalid', // fails boolean
			'has_visited'   => 'invalid', // fails boolean
			'like_tour'     => 'invalid', // fails boolean
			'accepts_terms' => 'invalid', // fails boolean
		]);

		$this->seeJson([
			'accepts_terms' => ['The accepts terms field must be true or false.'],
			'dob'           => ['The dob does not match the format Y-m-d.'],
			'email'         => ['The email must be a valid email address.'],
			'first_name'    => ['The first name field is required.'],
			'has_visited'   => ['The has visited field must be true or false.'],
			'is_public'     => ['The is public field must be true or false.'],
			'last_name'     => ['The last name field is required.'],
			'like_tour'     => ['The like tour field must be true or false.'],
			'password'      => ['The password field is required when linkedin token is not present.'],
			'phone'         => ['The phone must be a number.'],
			'postcode'      => ['The postcode must be 4 digits.'],
			'timezone'      => ['The timezone field is required.']
		]);
	}

	private function prepareSpaces()
	{
		DB::table('spaces')->insert([
			'name'       => 'Demo Space',
			'address'    => 'Demo Address',
			'suburb'     => 'Suburb',
			'postcode'   => '1234',
			'state'      => 'QLD',
			'country'    => 'Australia',
			'timezone'   => 'Australia/Brisbane',
			'site_code'  => 'DS',
		]);
	}

}
