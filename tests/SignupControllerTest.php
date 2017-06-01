<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Carbon\Carbon;

class SignupControllerTest extends TestCase
{
	// Unit Test SignupController

	/**
	 * Test Get index
	 * GET: /signup?plan_id=2
	 */
	public function testGetRoutesTest()
	{
		// Visit sign up page
		$this->visit('/signup?plan_id=2');
		$this->assertResponseStatus(200);
	}

	/**
	 * Test Post index
	 * POST: /signup
	 */
	public function testPostIndex()
	{
		// Create test-valid stripe token.
		$month_later = Carbon::today()->addMonths(2);

		$stripe = Stripe::make(env('STRIPE_KEY'));
		$token = $stripe->tokens()->create([
			'card' => [
				'number'    => '4242424242424242',
				'exp_month' => $month_later->format('n'),
				'exp_year'  => $month_later->format('Y'),
				'cvc'       => 314,
			],
		]);

		$stripe_token = $token['id'];;

		// Do post
		$this->post('/signup', [
			'plan_id'                  => '2',
			'space'                    => '1',
			'first_name'               => 'TestFirstName',
			'last_name'                => 'TestLastName',
			'mobile_number'            => '12345678',
			'email'                    => 'testemail1@com.com',
			'password'                 => 'tester',
			'password_confirmation'    => 'tester',
			'street_address'           => 'TestAddress',
			'state'                    => 'ACT',
			'postcode'                 => '1234',
			'dob'                      => '2017-10-11',
			'emergencyContactName'     => 'TestEmergencyName',
			'emergencyContactMobile'   => '23456789',
			'emergencyContactRelation' => 'TestRelation',
			'referrer'                 => 'Member',
			'referrer_detail'          => 'TestReferrer',
			'billing_company'          => 'TestBillingCompany',
			'company_abn'              => '2345',
			'company_industry'         => 'TestIndustry',
			'company_website'          => 'http://TestWebSite',
			'card_number'              => '4242424242424242',
			'card_expiry_month'        => $month_later->format('n'),
			'card_expiry_year'         => $month_later->format('Y'),
			'card_cvc'                 => '123',
			'stripeToken'              => $stripe_token,
		]);

		// Check db for creation.
		// Account creation.
		$this->seeInDatabase('accounts', [
			'space_id'       => '1',
			'name'           => 'TestFirstName TestLastName',
			'address'        => 'TestAddress',
			'postcode'       => '1234',
			'state'          => 'ACT',
			'billing_name'   => 'TestBillingCompany',
			'abn'            => '2345',
			'email'          => 'testemail1@com.com',
			'card_brand'     => 'Visa',
			'card_last_four' => '4242',
		]);

		// User creation.
		$this->seeInDatabase('users', [
			'first_name'   => 'TestFirstName',
			'last_name'    => 'TestLastName',
			'email'        => 'testemail1@com.com',
			'dob'          => '2017-10-11',
			'address'      => 'TestAddress',
			'postcode'     => '1234',
			'state'        => 'ACT',
			'phone'        => '12345678',
			'company_name' => 'TestBillingCompany',
			'industry'     => 'TestIndustry',
			'website'      => 'http://TestWebSite',
		]);

		// Billing item creation.
		$plan = App\Plan::find(2);
		$account = App\Account::where('email', 'testemail1@com.com')->first();
		$user = App\User::where('email', 'testemail1@com.com')->first();

		$this->seeInDatabase('billing_items', [
			'account_id'  => $account->id,
			'plan_id'     => 2,
			'name'        => 'Membership: ' . $plan->name,
			'cost'        => $plan->cost,
			'num_credits' => $plan->credit_per_renewal,
		]);

		$this->seeInDatabase('billing_items', [
			'account_id'  => $account->id,
			'plan_id'     => null,
			'name'        => 'Setup: ' . $plan->name,
			'cost'        => $plan->setup_cost,
			'num_credits' => 0,
		]);
	}

}
