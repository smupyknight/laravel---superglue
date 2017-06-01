<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class InvitationTest extends TestCase
{

	public function testInvitationValidation()
	{
		$invitation = factory(App\Invitation::class)->create();

		$this->visit('/invitations/accept/' . $invitation->token);
		$this->press('Finish Setup');
		$this->seePageIs('/invitations/accept/' . $invitation->token);

		$this->see('The password field is required.');
		$this->see('The password confirmation field is required.');
		$this->see('The phone field is required.');
		$this->see('The postcode field is required.');
		$this->see('The job title field is required.');
		$this->see('The company name field is required.');

		$this->type('invalid', 'postcode');
		$this->press('Finish Setup');
		$this->see('The postcode must be a number.');
	}

	public function testInvitationSubmission()
	{
		$invitation = factory(App\Invitation::class)->create();

		$this->visit('/invitations/accept/' . $invitation->token);
		$this->type('John', 'first_name');
		$this->type('Doe', 'last_name');
		$this->type('password', 'password');
		$this->type('password', 'password_confirmation');
		$this->type('0421004140', 'phone');
		$this->type('4102', 'postcode');
		$this->type('Director of Memes', 'job_title');
		$this->type('Meme Corp.', 'company_name');
		$this->select('Professional Services', 'industry');
		$this->select('Australia/Brisbane', 'timezone');
		$this->type('@john.doe', 'twitter_handle');
		$this->type('@john.doe', 'instagram_handle');
		$this->type('I like memes. Memes are my business. If you need help with your memes, come and see me.', 'bio');

		$this->press('Finish Setup');

		$this->seeInDatabase('users', [
			'first_name' => 'John',
			'last_name' => 'Doe',
			'phone' => '0421004140',
			'postcode' => '4102',
			'job_title' => 'Director of Memes',
			'company_name' => 'Meme Corp.',
			'industry' => 'Professional Services',
			'timezone' => 'Australia/Brisbane',
			'twitter_handle' => '@john.doe',
			'instagram_handle' => '@john.doe',
			'bio' => 'I like memes. Memes are my business. If you need help with your memes, come and see me.',
		]);

		$this->seeInDatabase('timeline', [
			'created_by' => null,
			'user_id'    => $invitation->user->id,
			'account_id' => $invitation->user->account,
			'message'    => 'User completed invite: John Doe (' . $invitation->user->email . ')',
			'type'       => 'info'
		]);
	}

}
