<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PowerUpsTest extends TestCase
{

	/**
	 * A basic form submission.
	 *
	 * @return void
	 */
	public function testBasicCreation()
	{
		$user = factory(App\User::class)->make([
			'type' => 'Admin',
		]);

		$this->actingAs($user);
		$this->visit('/admin/powerups/create');
		$this->type('Demo PowerUp', 'title');
		$this->type('This is a demo powerup', 'description');
		$this->type('$HD22d', 'coupon_code');
		$this->type('http://google.com', 'link');
		$this->press('Create');
		$this->seePageIs('/admin/powerups');

		$this->seeInDatabase('power_ups', [
			'title'       => 'Demo PowerUp',
			'description' => 'This is a demo powerup',
			'link'        => 'http://google.com',
			'coupon_code' => '$HD22d',
		]);
	}

	/**
	 * A form validation.
	 *
	 * @return void
	 */
	public function testCreateValidation()
	{
		$user = factory(App\User::class)->make([
			'type' => 'Admin',
		]);

		$this->actingAs($user);
		$this->visit('/admin/powerups/create');
		$this->type('', 'title');
		$this->type('', 'description');
		$this->type('123', 'link');
		$this->press('Create');

		$this->see('The title field is required.');
		$this->see('The description field is required.');
		$this->see('The link format is invalid.');
	}

	/**
	 * Test edit validation
	 *
	 * @return void
	 */
	public function testEditSubmission()
	{
		$powerup = factory(App\PowerUp::class)->create();

		$user = factory(App\User::class)->make([
			'type' => 'Admin',
		]);

		$this->actingAs($user);
		$this->visit('/admin/powerups/edit/' . $powerup->id);
		$this->see('Edit PowerUp');

		$this->type('Demo PowerUp', 'title');
		$this->type('This is a demo powerup', 'description');
		$this->type('$HD22d', 'coupon_code');
		$this->type('http://google.com', 'link');

		$this->press('Update');

		$this->seeInDatabase('power_ups', [
			'title'       => 'Demo PowerUp',
			'description' => 'This is a demo powerup',
			'link'        => 'http://google.com',
			'coupon_code' => '$HD22d',
		]);
	}

	/**
	 * Test edit validation
	 *
	 * @return void
	 */
	public function testEditValidation()
	{
		$powerup = factory(App\PowerUp::class)->create();

		$user = factory(App\User::class)->make([
			'type' => 'Admin',
		]);

		$this->actingAs($user);
		$this->visit('/admin/powerups/edit/' . $powerup->id);
		$this->see('Edit PowerUp');

		$this->type('', 'title');
		$this->type('', 'description');
		$this->type('123', 'link');
		$this->press('Update');

		$this->see('The title field is required.');
		$this->see('The description field is required.');
		$this->see('The link format is invalid.');
	}

}
