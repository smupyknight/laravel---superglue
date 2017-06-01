<?php

namespace App\Jobs;

use App\Jobs\Job;
use Carbon\Carbon;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use XeroPHP\Models\Accounting\Account as XeroAccount;
use XeroPHP\Models\Accounting\Contact as XeroContact;
use XeroPHP\Models\Accounting\Invoice as XeroInvoice;
use XeroPHP\Models\Accounting\Invoice\LineItem as XeroLineItem;
use XeroPHP\Models\Accounting\Payment as XeroPayment;

class NotifyXeroOfPayment extends Job implements ShouldQueue
{
	use InteractsWithQueue, SerializesModels;

	private $invoice;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct($invoice)
	{
		$this->invoice = $invoice;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle()
	{
		$xero = app('xero');

		$xero_bank_account = $xero->load('Accounting\Account')->where('Code', '111')->execute()[0];

		$xero_invoice = $xero->loadByGUID('Accounting\Invoice', $this->invoice->xero_invoice_guid);

		// Must add a row to the Xero invoice for processing fees as Xero
		// calculates the total of the invoice based on the SUM of the line items
		//
		// The Xero account code and value of the processing fee is dependant
		// on whether the fees are charged to the user (on-top) or absorbed by SuperGlue.
		if ($this->invoice->processing_fee > 0) {
			// Fees on-charged
			$xero_item = new XeroLineItem($xero);
			$xero_item->setDescription('Processing Fees');
			$xero_item->setQuantity(1);
			$xero_item->setUnitAmount($this->invoice->processing_fee);
			$xero_item->setTaxType('OUTPUT');
			$xero_item->setAccountCode(265);
			$xero_invoice->addLineItem($xero_item);
			$xero_invoice->save();
		}

		$xero_payment = new XeroPayment($xero);
		$xero_payment->setInvoice($xero_invoice);
		$xero_payment->setDate((new Carbon)->setTimezone('Australia/Brisbane'));
		$xero_payment->setAmount($this->invoice->total);

		$xero_payment->setStatus(XeroPayment::PAYMENT_STATUS_AUTHORISED);
		$xero_payment->setAccount($xero_bank_account);
		$xero_payment->save();
	}

}
