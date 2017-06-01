<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Account;
use App\BillingItem;
use App\Desk;
use App\Office;
use App\Plan;
use App\Space;
use Carbon\Carbon;
use Exception;

class Import extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'import';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Import accounts and users from CSV files.';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$this->importAccounts('import/accounts.csv');
		$this->importUsers('import/users.csv');
	}

	/**
	 * Imports accounts.
	 *
	 * If an account exists in the same space with the same name then it is
	 * updated, other it is created.
	 */
	public function importAccounts($filename)
	{
		$fp = fopen($filename, 'r');
		$headings = fgetcsv($fp);
		$headings = array_map('trim', $headings);

		while ($row = fgetcsv($fp)) {
			if (count(array_filter($row)) <= 1) {
				continue;
			}

			// Index fields by heading name
			$row = array_combine(array_slice($headings, 0, count($row)), $row);

			$space = $this->getSpaceByName($row['Space']);

			$account = Account::firstOrNew([
				'space_id' => $space->id,
				'name'     => $row['Billing Name'],
			]);

			$account->billing_name = $row['Billing Name'];
			$account->address = $row['Address'];
			$account->suburb = $row['Suburb'];
			$account->state = $row['State'];
			$account->postcode = $row['Postcode'];
			$account->country = $row['Country'];
			$account->abn = $row['ABN'];
			$account->email = $row['Email'];
			$account->start_date = $row['Customer Start Date'] ? Carbon::createFromFormat('m/d/Y', $row['Customer Start Date'])->format('Y-m-d') : date('Y-m-d');
			$account->space_id = $space->id;
			$account->status = 'active';
			$account->save();
		}

		fclose($fp);
	}

	public function importUsers($filename)
	{
		$fp = fopen($filename, 'r');

		$headings = fgetcsv($fp);
		$headings = array_map('trim', $headings);

		while ($row = fgetcsv($fp)) {
			if (count(array_filter($row)) <= 1) {
				continue;
			}

			// Index fields by heading name
			$row = array_combine(array_slice($headings, 0, count($row)), $row);

			$space = $this->getSpaceByName($row['Space']);

			$account = Account::firstOrNew([
				'space_id' => $space->id,
				'name'     => $row['Account'],
			]);

			$user = $account->users()->firstOrNew([
				'first_name' => $row['First Name'],
				'last_name'  => $row['Last Name'],
			]);

			$user->twitter_handle = $row['Twitter Handle'];
			$user->instagram_handle = $row['Instagram Handle'];
			$user->email = $row['Email'];
			$user->dob = $row['D.O.B'] ? Carbon::createFromFormat('m/d/Y', $row['D.O.B'])->format('Y-m-d') : null;
			$user->job_title = $row['Job Title'];
			$user->industry = $row['Industry'];
			$user->phone = $this->formatPhone($row['Phone']);
			$user->company_name = $row['Company Name'];
			$user->address = $row['Address'];
			$user->bio = $row['Bio'];
			$user->type = $row['Type'];
			$user->is_account_admin = $row['Account Admin'] == 'Yes';
			$user->timezone = 'Australia/Brisbane';
			$user->security_card_number = $row['Security Card Number'];
			$user->save();
		}

		fclose($fp);
	}

	private function getSpaceByName($name)
	{
		$space = Space::where('name', $name)->first();

		if (!$space) {
			throw new Exception("Couldn't find space: '$name'");
		}

		return $space;
	}

	private function formatPhone($phone)
	{
		$phone = preg_replace('/[^0-9]/', '', $phone);

		if (strlen($phone) == 9 && $phone{0} == '4') {
			$phone = '0' . $phone;
		}

		return $phone;
	}

}
