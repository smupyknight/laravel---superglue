<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Invoice;
use DB;
use Mail;

class PayInvoices extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'pay-invoices';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description';

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
		$this->payInvoices();
		$this->expireAccounts();
	}

	/**
	 * Loops through all unpaid invoices and attempts to pay them.
	 */
	private function payInvoices()
	{
		$today = Carbon::today('Australia/Brisbane');

		$invoices = Invoice::whereRaw('due_date <= ?', [$today->format('Y-m-d')])
		                   ->whereStatus('pending')
		                   ->get();

		foreach ($invoices as $invoice) {
			if ($invoice->pay()) {
				$this->info('Payment made for invoice #' . $invoice->id);
			} else {
				$this->sendFailureEmail($invoice);
				$this->error('Payment failed for invoice #' . $invoice->id);
			}
		}
	}

	private function sendFailureEmail(Invoice $invoice)
	{
		$data = ['invoice' => $invoice];

		Mail::send('emails.payment-failed', $data, function ($mail) use ($invoice) {
			$mail->to($invoice->account->email);
			$mail->subject('Invoice payment failed');
		});
	}

	/**
	 * Sets account statuses to expired if they have an unpaid invoice over 14
	 * days old.
	 */
	private function expireAccounts()
	{
		DB::table('invoices AS i')
		  ->join('accounts AS a', 'i.account_id', '=', 'a.id')
		  ->whereRaw('i.due_date <= DATE_SUB(CURDATE(), INTERVAL 14 DAY)')
		  ->where('i.status', '=', 'pending')
		  ->update([
		      'a.status' => 'expired',
		      'i.status' => 'expired',
		  ]);
	}

}
