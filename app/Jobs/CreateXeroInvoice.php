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

class CreateXeroInvoice extends Job implements ShouldQueue
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

		$xero_contact = $this->findOrCreateXeroContact();

		$xero_bank_account = $xero->load('Accounting\Account')->where('Code', '111')->execute()[0];

		$xero_invoice = new XeroInvoice($xero);
		$xero_invoice->setType(XeroInvoice::INVOICE_TYPE_ACCREC);
		$xero_invoice->setContact($xero_contact);
		$xero_invoice->setDate($this->invoice->created_at->setTimezone('Australia/Brisbane'));
		$xero_invoice->setDueDate($this->invoice->due_date->setTimezone('Australia/Brisbane'));
		$xero_invoice->setLineAmountType(XeroInvoice::LINEAMOUNT_TYPE_INCLUSIVE);
		$xero_invoice->setStatus(XeroInvoice::INVOICE_STATUS_AUTHORISED);
		$xero_invoice->setSentToContact(true);
		$xero_invoice->setExpectedPaymentDate((new Carbon)->setTimezone('Australia/Brisbane'));

		foreach ($this->invoice->items as $item) {
			$xero_item = new XeroLineItem($xero);
			$xero_item->setDescription($item->description);
			$xero_item->setQuantity(1);
			$xero_item->setUnitAmount($item->cost);
			$xero_item->setTaxType('OUTPUT');

			if ($item->is_signup_fee) {
				$xero_item->setAccountCode(204);
			} else {
				$xero_item->setAccountCode(211);
			}

			$xero_invoice->addLineItem($xero_item);
		}

		$xero_invoice->save();

		$this->invoice->xero_invoice_number = $xero_invoice->getInvoiceNumber();
		$this->invoice->xero_invoice_guid = $xero_invoice->getInvoiceID();
		$this->invoice->save();
	}

	private function findOrCreateXeroContact()
	{
		$account = $this->invoice->account;
		$xero = app('xero');

		// If the account already has a xero_contact_id, use it
		if ($account->xero_contact_id) {
			return $xero->loadByGUID('Accounting\Contact', $account->xero_contact_id);
		}

		// If there's already a contact in Xero with the same name as the account, use it
		$xero_contacts = $xero->load('Accounting\Contact')->where('Name', $account->name)->execute();

		if (isset($xero_contacts[0])) {
			$account->xero_contact_id = $xero_contacts[0]->getContactID();
			$account->xero_contact_name = $xero_contacts[0]->getName();
			$account->save();

			return $xero_contacts[0];
		}

		// Create new Xero contact
		$xero_contact = new XeroContact($xero);
		$xero_contact->setName($account->name);
		$xero_contact->setEmailAddress($account->email);
		$xero_contact->setTaxNumber($account->abn);
		$xero_contact->save();

		$account->xero_contact_id = $xero_contact->getContactID();
		$account->xero_contact_name = $xero_contact->getName();
		$account->save();

		return $xero_contact;
	}

}
