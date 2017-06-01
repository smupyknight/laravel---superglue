<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserInviteTest extends TestCase
{

	public function testInvitationCreate()
	{
		$user = factory(App\User::class)->create([
			'type' => 'Admin',
		]);

		$account = factory(App\Account::class)->create();

		$this->actingAs($user);

		$this->json('POST', '/admin/users/invite', [
			'first_name'       => 'John',
			'last_name'        => 'Doe',
			'email'            => 'johndoe@buckhamduffy.com',
			'is_account_admin' => 1,
			'account_id'       => $account->id,
		]);

		$this->seeInDatabase('users', [
			'first_name'       => 'John',
			'last_name'        => 'Doe',
			'email'            => 'johndoe@buckhamduffy.com',
			'is_account_admin' => 1,
			'account_id'       => $account->id,
		]);

		$invited_user = DB::table('users')->select('id')->whereEmail('johndoe@buckhamduffy.com')->get();

		$this->seeInDatabase('timeline', [
			'created_by' => $user->id,
			'user_id'    => $invited_user[0]->id,
			'account_id' => $account->id,
			'title'      => 'Invited User',
			'message'    => 'Invited user John Doe (johndoe@buckhamduffy.com)',
			'type'       => 'info',
		]);

		$this->seeInDatabase('invitations', [
			'user_id'    => $invited_user[0]->id,
		]);
	}

	/**
	 * A basic test example.
	 *
	 * @return void
	 */
	public function testUserInviteCreate()
	{
		$user = factory(App\User::class)->create([
			'type' => 'Admin',
		]);

		$account = factory(App\Account::class)->create();

		$this->actingAs($user);
		$this->visit('/admin/users/invite');
		$this->type('John', 'first_name');
		$this->type('Doe', 'last_name');
		$this->type('johndoe@buckhamduffy.com', 'email');
		$this->select($account->id, 'account_id');
		$this->check('is_account_admin');
		$this->press('Send Invitation');
		$this->seePageIs('/admin/users');

		$this->seeInDatabase('users', [
			'first_name' => 'John',
			'last_name'  => 'Doe',
			'email'      => 'johndoe@buckhamduffy.com',
			'account_id' => $account->id,
		]);

		$invited_user = DB::table('users')->select('id')->whereEmail('johndoe@buckhamduffy.com')->get();

		$this->seeInDatabase('timeline', [
			'created_by' => $user->id,
			'user_id'    => $invited_user[0]->id,
			'account_id' => $account->id,
			'title'      => 'Invited User',
			'message'    => 'Invited user John Doe (johndoe@buckhamduffy.com)',
			'type'       => 'info',
		]);

		$this->seeInDatabase('invitations', [
			'user_id'    => $invited_user[0]->id,
		]);
	}

}
