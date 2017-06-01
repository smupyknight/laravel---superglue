<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
	return [
		'first_name'     => $faker->firstName,
		'last_name'      => $faker->lastName,
		'email'          => $faker->safeEmail,
		'password'       => bcrypt(str_random(10)),
		'remember_token' => str_random(10),
		'account_id'     => factory(App\Account::class)->create()->id,
	];
});

$factory->define(App\Invitation::class, function (Faker\Generator $faker) {
	return [
		'token'   => substr(md5(microtime()), 0, 10),
		'user_id' => factory(App\User::class)->create()->id,
	];
});


$factory->define(App\PowerUp::class, function (Faker\Generator $faker) {
	return [
		'title'       => $faker->word,
		'description' => $faker->sentence,
		'link'        => $faker->domainName,
		'coupon_code' => $faker->password,
	];
});

$factory->define(App\Account::class, function (Faker\Generator $faker) {
	return [
		'space_id'     => factory(App\Space::class)->create()->id,
		'name'         => $faker->word,
		'address'      => $faker->streetAddress,
		'suburb'       => $faker->city,
		'postcode'     => $faker->postcode,
		'state'        => $faker->state,
		'country'      => $faker->country,
		'billing_name' => $faker->word,
		'email'        => $faker->safeEmail,
	];
});

$factory->define(App\Space::class, function (Faker\Generator $faker) {
	return [
		'name'      => $faker->word,
		'address'   => $faker->streetAddress,
		'suburb'    => $faker->city,
		'postcode'  => $faker->postcode,
		'state'     => $faker->state,
		'country'   => $faker->country,
		'timezone'  => 'Australia/Brisbane',
		'site_code' => $faker->stateAbbr,
	];
});