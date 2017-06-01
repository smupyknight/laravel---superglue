<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use Carbon\Carbon;

class HubspotImporter extends Command
{

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'hubspot:import';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Import all existing contacts from HubSpot and invite users';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$this->getContacts();
	}

	private function getContacts()
	{
		$offset = null;

		do {
			$curl = curl_init();
			$params = ['count' => '100', 'vidOffset' => $offset];

			if (isset($params) && is_array($params)) {
				$paramString = '&' . http_build_query($params);
			} else {
				$paramString = null;
			}

			$url = 'https://api.hubapi.com/contacts/v1/lists/348/contacts/all?' . ltrim($paramString, '&') . '&hapikey=' . env('HUBSPOT_API_KEY');

			$this->info($url);

			curl_setopt_array($curl, [
				CURLOPT_URL            => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING       => '',
				CURLOPT_MAXREDIRS      => 10,
				CURLOPT_TIMEOUT        => 30,
				CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST  => 'GET',
				CURLOPT_HTTPHEADER     => [
					'cache-control: no-cache'
				],
			]);

			$response = json_decode(curl_exec($curl));
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
				$this->info('cURL Error #:' . $err);
			} else {
				foreach ($response->contacts as $contact) {
					$first_name = isset($contact->properties->firstname) ? $contact->properties->firstname->value : '';
					$last_name = isset($contact->properties->lastname) ? $contact->properties->lastname->value : '';

					if ($first_name == '' && $last_name == '') {
						continue;
					}

					$this->info($first_name . ' ' . $last_name);
					$this->getProperties($contact->vid);
				}
			}

			$offset = $response->{'vid-offset'};
		} while ($response->{'has-more'} == true);
	}

	private function getProperties($contact_id)
	{
		$curl = curl_init();

		if (isset($params) && is_array($params)) {
			$paramString = '&' . http_build_query($params);
		} else {
			$paramString = null;
		}

		$url = 'https://api.hubapi.com/contacts/v1/contact/vid/'.$contact_id.'/profile?&hapikey=' . env('HUBSPOT_API_KEY');

		$this->info($url);

		curl_setopt_array($curl, [
			CURLOPT_URL            => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING       => '',
			CURLOPT_MAXREDIRS      => 10,
			CURLOPT_TIMEOUT        => 30,
			CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST  => 'GET',
			CURLOPT_HTTPHEADER     => [
				'cache-control: no-cache'
			],
		]);

		$response = json_decode(curl_exec($curl));
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			$this->info('cURL Error #:' . $err);
		} else {
			if (User::where('email', $response->properties->email->value)->first()) {
				$this->info('User already exists');
				return;
			}

			$user = new User;
			$user->hubspot_id = $contact_id;
			$user->first_name = isset($response->properties->firstname->value) ? $response->properties->firstname->value : '';
			$user->last_name = isset($response->properties->lastname->value) ? $response->properties->lastname->value : '';
			$user->twitter_handle = isset($response->properties->twitterhandle->value) ? $response->properties->twitterhandle->value : '';
			$user->email = isset($response->properties->email->value) ? $response->properties->email->value : '';
			$user->company_name = isset($response->properties->company->value) ? $response->properties->company->value : '';
			$user->website = isset($response->properties->website->value) ? $response->properties->website->value : '';
			$user->phone = isset($response->properties->phone->value) ? $response->properties->phone->value : '';
			$user->industry = isset($response->properties->industry->value) ? $response->properties->industry->value : '';
			$user->salutation = isset($response->properties->salutation->value) ? $response->properties->salutation->value : '';
			$user->address = isset($response->properties->address->value) ? $response->properties->address->value : '';
			$user->job_title = isset($response->properties->jobtitle->value) ? $response->properties->jobtitle->value : '';
			$user->bio = isset($response->properties->message->value) ? $response->properties->message->value : '';
			$user->source = 'HubSpot';
			$user->created_at = strtotime(Carbon::createFromTimestamp($response->properties->createdate->value / 1000));
			$user->save();
		}
	}

}
