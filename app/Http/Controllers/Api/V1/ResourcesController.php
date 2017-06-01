<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Auth;
use \App\User;
use App\Industry;
use App\Space;

class ResourcesController extends Controller
{
	public function getIndustries()
	{
		$industries = Industry::$industries;

		return response()->json($industries);
	}

	public function getInvestment() {

		$investment_levels = array('<$30,000', '$30,000 - $50,000', '$50,000 - $100,000', '$100,000 - $200,000', '$200,000 - $500,000', '$500,000 - $1,000,000', '$1,000,000+');

		return response()->json($investment_levels);
	}

	public function getRevenue() {

		$revenue_levels = array('<$30,000', '$30,000 - $50,000', '$50,000 - $100,000', '$100,000 - $200,000', '$200,000 - $500,000', '$500,000 - $1,000,000', '$1,000,000+');

		return response()->json($revenue_levels);
	}

	public function getSpaces()
	{
		$spaces = Space::all();

		$results = [];

		foreach($spaces as $space) {
			$results[] = [
				'id' => $space->id,
				'name' => $space->name,
			];
		}

		return response()->json($results);
	}
}
