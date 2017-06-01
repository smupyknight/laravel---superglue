<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Account;
use App\BillingItem;
use App\Invoice;
use App\InvoiceItem;
use App\App;
use Carbon\Carbon;
use DB;

class Billing extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'billing';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Process billing for all accounts.';

	/**
	 * Execute the console command.
	 *
	 * This command can be run on a billing day, as well as on every day in
	 * between. On a billing day it creates invoices and attempts to pay them.
	 * On non-billing days it just tries to process unpaid invoices.
	 *
	 * The billing period is the complete 14 days prior to today. So if the
	 * billing date falls on a Friday, it is Friday to Thursday.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$this->updateInvoices();
	}

	/**
	 * Finds billing items which are due to be paid and ensures they are entered
	 * on a pending invoice.
	 *
	 * The billing items' start dates are incremented so they won't be re-added
	 * on the following day.
	 *
	 * If the billing cron hasn't run for some reason, it will automatically
	 * catch up by adding a billing item multiple times if needed.
	 */
	private function updateInvoices()
	{
		// Select billing items due to be billed
		$items = BillingItem::whereRaw('next_billing_date <= CURDATE()')->get();

		foreach ($items as $item) {
			$invoice = Invoice::create([
				'account_id' => $item->account_id,
				'status'     => 'pending',
				'due_date'   => Carbon::now(),
			]);

			while ($item->next_billing_date->isPast()) {
				// Add the item to the invoice
				if ($item->plan_id) {
					$description = sprintf('%s - %s - %s to %s',
						$item->space->name,
						$item->plan->name,
						$item->next_billing_date->format('j M Y'),
						$item->next_billing_date->addDays(13)->format('j M Y')
					);
				} elseif ($item->office_id) {
					$description = sprintf('%s - %s - %s to %s',
						$item->office->space->name,
						$item->office->name,
						$item->next_billing_date->format('j M Y'),
						$item->next_billing_date->addDays(13)->format('j M Y')
					);
				} elseif ($item->desk_id) {
					$description = sprintf('%s - %s - %s to %s',
						$item->desk->space->name,
						$item->desk->name,
						$item->next_billing_date->format('j M Y'),
						$item->next_billing_date->addDays(13)->format('j M Y')
					);
				} else {
					$description = $item->name;
				}

				InvoiceItem::create([
					'invoice_id'  => $invoice->id,
					'date'        => $item->next_billing_date,
					'description' => $description,
					'cost'        => $item->cost,
					'num_credits' => $item->num_credits,
				]);

				// If the billing is recurring, update the next start date
				if (!$item->end_date || $item->next_billing_date->addWeeks(2)->lte($item->end_date)) {
					$item->next_billing_date = $item->next_billing_date->copy()->addWeeks(2);
					$item->save();
				} else {
					$item->delete();
					break;
				}
			}

			$invoice->recalculateTotal();

			dispatch(new \App\Jobs\CreateXeroInvoice($invoice));
		}
	}

}
