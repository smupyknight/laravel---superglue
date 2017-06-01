<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Auth;
use DB;
use Carbon\Carbon;
use App\Account;
use App\Booking;
use App\Space;
use App\Services\BookingsService;
use App\Exceptions\NotEnoughCreditException;

class BookingsController extends Controller
{

	public function getInit(Request $request)
	{
		$account = Auth::guard('api')->user()->account;

		$space = Space::find($request->get('space', $account->space_id));

		try {
			$month = Carbon::createFromFormat('Y-m', $request->get('month'), $space->timezone);
		} catch (\Exception $e) {
			$month = Carbon::today($space->timezone);
		}

		$response = [
			'spaces'   => [],
			'rooms'    => [],
			'bookings' => $this->getBookings($space, $month, $account),
			'month'    => $month->format('Y-m'),
		];

		foreach (Space::all() as $loop_space) {
			$response['spaces'][] = [
				'id'          => $loop_space->id,
				'name'        => $loop_space->name,
				'is_selected' => $loop_space->id == $space->id,
			];
		}

		foreach ($space->rooms as $room) {
			$response['rooms'][] = [
				'id'               => $room->id,
				'group_id'         => $room->group_id,
				'name'             => $room->name,
				'description'      => $room->description,
				'capacity'         => $room->capacity,
				'credits_per_hour' => $room->credits_per_hour,
			];
		}

		return response()->json($response);
	}

	public function getCalendar(Request $request)
	{
		$account = Auth::guard('api')->user()->account;

		$space = Space::find($request->get('space', $account->space_id));

		try {
			$month = Carbon::createFromFormat('Y-m', $request->get('month'), $space->timezone);
		} catch (\Exception $e) {
			$month = Carbon::today($space->timezone);
		}

		return response()->json([
			'bookings' => $this->getBookings($space, $month, $account),
			'month'    => $month->format('Y-m'),
		]);
	}

	private function getBookings(Space $space, Carbon $month, Account $account)
	{
		$month_start = $month->copy()->startOfMonth()->setTimezone('UTC');
		$month_end = $month->copy()->endOfMonth()->setTimezone('UTC');

		$bookings = Booking::join('booking_rooms AS br', 'bookings.id', '=', 'br.booking_id')
		                   ->join('rooms AS r', 'r.id', '=', 'br.room_id')
		                   ->join('users AS u', 'u.id', '=', 'bookings.user_id')
		                   ->join('accounts AS a', 'a.id', '=', 'u.account_id')
		                   ->where('r.space_id', $space->id)
		                   ->where('bookings.start_date', '>=', $month_start)
		                   ->where('bookings.end_date', '<=', $month_end)
		                   ->groupBy('bookings.id')
		                   ->orderBy('bookings.start_date', 'asc')
		                   ->selectRaw("bookings.*, GROUP_CONCAT(br.room_id SEPARATOR ',') AS room_ids, u.account_id, a.name AS account_name")
		                   ->get();

		$results = [];

		foreach ($bookings as $booking) {
			$tmp_booking = [
				'id'          => $booking->id,
				'account_id'  => $booking->account_id,
				'room_ids'    => $booking->room_ids,
				'is_private'  => $booking->is_private,
				'is_editable' => $booking->account_id == $account->id && $booking->start_date->isFuture(),
				'start'       => $booking->start_date->setTimezone($space->timezone)->format('r'),
				'end'         => $booking->end_date->setTimezone($space->timezone)->format('r'),
				'reminder'    => $booking->reminder,
			];

			if (!$booking->is_private || $booking->account_id == $account->id) {
				$tmp_booking['name'] = $booking->name;
				$tmp_booking['account_name'] = $booking->account_name;
			}

			$results[] = $tmp_booking;
		}

		return $results;
	}

	public function getList(Request $request)
	{
		$this->validate($request, [
			'start'    => 'required|date_format:Y-m-d',
			'end'      => 'date_format:Y-m-d',
			'space_id' => 'numeric',
			'room_id'  => 'numeric',
		]);

		$start = (new Carbon($request->start))->startOfDay()->setTimezone('UTC');

		$query = Booking::join('booking_rooms AS br', 'bookings.id', '=', 'br.booking_id')
						->join('rooms AS r', 'br.room_id', '=', 'r.id')
						->join('users AS u', 'bookings.user_id', '=', 'u.id')
						->join('accounts AS a', 'a.id', '=', 'u.account_id')
						->join('spaces AS s', 'r.space_id', '=', 's.id')
						->where('bookings.start_date', '>=', $start)
						->groupBy('bookings.id')
						->orderBy('bookings.start_date', 'asc')
						->selectRaw("
							bookings.*,
							GROUP_CONCAT(br.room_id SEPARATOR ',') AS room_ids,
							u.account_id,
							a.name AS account_name,
							s.timezone
						");

		if ($request->end) {
			$end = (new Carbon($request->end))->endOfDay()->setTimezone('UTC');
			$query->where('bookings.end_date', '<=', $end);
		}

		if ($request->space_id) {
			$query->where('r.space_id', $request->space_id);
		}

		if ($request->room_id) {
			$query->where('br.room_id', $request->room_id);
		}

		$account_id = Auth::guard('api')->user()->account_id;
		$results = [];

		foreach ($query->get() as $booking) {
			$tmp_booking = [
				'id'          => $booking->id,
				'account_id'  => $booking->account_id,
				'room_ids'    => $booking->room_ids,
				'is_private'  => $booking->is_private,
				'is_editable' => $booking->account_id == $account_id && $booking->start_date->isFuture(),
				'start'       => $booking->start_date->setTimezone($booking->timezone)->format('c'),
				'end'         => $booking->end_date->setTimezone($booking->timezone)->format('c'),
				'reminder'    => $booking->reminder,
			];

			if (!$booking->is_private || $booking->account_id == $account_id) {
				$tmp_booking['name'] = $booking->name;
				$tmp_booking['account_name'] = $booking->account_name;
			}

			$results[] = $tmp_booking;
		}

		return $results;
	}

	public function getMy(Request $request)
	{
		$query = Booking::join('booking_rooms AS br', 'bookings.id', '=', 'br.booking_id')
						->join('rooms AS r', 'br.room_id', '=', 'r.id')
						->join('spaces AS s', 'r.space_id', '=', 's.id')
						->where('bookings.user_id', '=', Auth::guard('api')->id())
						->groupBy('bookings.id')
						->select([
							'bookings.id',
							's.name AS space_name',
							DB::raw("GROUP_CONCAT(r.name SEPARATOR '|') AS rooms"),
							'bookings.name',
							'bookings.is_private',
							DB::raw('bookings.start_date > NOW() AS is_editable'),
							'bookings.start_date AS start',
							'bookings.end_date AS end',
							'bookings.credit_cost',
							'bookings.reminder',
							's.timezone',
						]);

		if ($request->type == 'past') {
			$query->where('bookings.start_date', '<', Carbon::now());
			$query->orderBy('bookings.start_date', 'desc');
		} else {
			$query->where('bookings.start_date', '>', Carbon::now());
			$query->orderBy('bookings.start_date', 'asc');
		}

		$results = $query->paginate(10);

		foreach ($results as $result) {
			$result->rooms = explode('|', $result->rooms);
			$result->start = (new Carbon($result->start))->setTimezone($result->timezone)->format('r');
			$result->end = (new Carbon($result->end))->setTimezone($result->timezone)->format('r');

			unset($result->timezone);
		}

		return response()->json($results);
	}

	public function postCreate(Request $request)
	{
		$service = new BookingsService;
		$booking = $service->create($request);

		return response()->json(['booking_id' => $booking->id]);
	}

	public function postEdit(Request $request, $booking_id)
	{
		$request->merge(['booking_id' => $booking_id]);

		$service = new BookingsService;
		$service->edit($request);
	}

	public function postDelete(Request $request, $booking_id)
	{
		$request->merge(['booking_id' => $booking_id]);

		$service = new BookingsService;
		$service->delete($request);
	}

}
