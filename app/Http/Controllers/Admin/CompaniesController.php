<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Company;
use App\CompanyStats;
use App\Account;
use App\Space;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CompaniesController extends Controller
{

	/**
	 * Show list of companies
	 * @return view
	 */
	public function getIndex()
	{
		$companies = Company::all();

		return view('pages.companies-list')
		     ->with('companies', $companies)
		     ->with('title', 'Companies List');
	}

	/**
	 * Show create company page
	 * @return view
	 */
	public function getCreate()
	{
		$spaces = Space::lists('name', 'id');
		$accounts = Account::lists('name', 'id');

		return view('pages.companies-create')
		     ->with('spaces', $spaces)
		     ->with('accounts', $accounts);
	}

	/**
	 * Get modal for create company
	 * @return view
	 */
	public function getCreateModal()
	{
		$spaces = Space::lists('name', 'id');
		$accounts = Account::lists('name', 'id');

		return view('pages.companies-create-form')
		     ->with('spaces', $spaces)
		     ->with('accounts', $accounts);
	}

	/**
	 * Store company details
	 * @param  Request $request
	 * @return response
	 */
	public function postStore(Request $request)
	{
		$this->validate($request, [
			'name'         => 'required',
			'account_id'   => 'required|exists:accounts,id',
			'space_id'     => 'required|exists:spaces,id',
			'industry'     => 'required',
			'abn'          => 'required',
			'date_started' => 'required|date_format:Y-m-d',
			'employees'    => 'required|numeric',
			'investment'   => 'required',
			'revenue'      => 'required',
		]);

		$company = new Company;
		$company->name = $request->name;
		$company->account_id = $request->account_id;
		$company->space_id = $request->space_id;
		$company->industry = $request->name;
		$company->abn = $request->abn;
		$company->date_started = $request->date_format;
		$company->save();

		$companyStats = new CompanyStats;
		$companyStats->company_id = $company->id;
		$companyStats->employees = $request->employees;
		$companyStats->investment = $request->investment;
		$companyStats->revenue = $request->revenue;
		$companyStats->save();

		if ($request->ajax()) {
			return response()->json(['company_id' => $company->id]);
		}

		return redirect('/companies');
	}

}
