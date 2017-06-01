<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use DB;
use App\Booking;
use App\Room;
use App\Exceptions\ServiceValidationException;
use Carbon\Carbon;

class BookingsService extends Service
{

	public function create(Request $request)
	{
		$this->validate($request, [
			'room_ids' => 'required',
			'start'    => 'required|date_format:"D, d M Y H:i:s O"',
			'end'      => 'required|date_format:"D, d M Y H:i:s O"',
			'name'     => 'required',
			'reminder' => 'numeric',
		], ['room_ids.required' => 'At least one room must be selected.']);

		if (is_array($request->room_ids)) {
			$request->room_ids = implode(',', $request->room_ids);
		}

		$user = $request->api_token ? Auth::guard('api')->user() : Auth::user();

		$account = $user->account;

		$room = Room::find(explode(',', $request->room_ids)[0]);
		$start = new Carbon($request->start, $room->space->timezone);
		$end = new Carbon($request->end, $room->space->timezone);

		$start->setTimezone('UTC');
		$end->setTimezone('UTC');

		if ($start->isPast()) {
			throw new ServiceValidationException('The start time must be in the future.', 'start');
		}

		if ($end->lte($start)) {
			throw new ServiceValidationException('The end time must be after the start time.', 'start');
		}

		if ($start->copy()->addMinutes(30) > $end) {
			throw new ServiceValidationException('The booking must go for at least 30 minutes.', 'end');
		}

		// Check for conflicting rooms/times
		$has_conflict = DB::table('bookings AS b')
		                  ->join('booking_rooms AS br', 'b.id', '=', 'br.booking_id')
		                  ->where('b.start_date', '<', $end)
		                  ->where('b.end_date', '>', $start)
		                  ->whereIn('br.room_id', explode(',', $request->room_ids))
		                  ->exists();

		if ($has_conflict) {
			throw new ServiceValidationException('Sorry, those times conflict with another booking.', 'start');
		}

		// Build array of rooms and calculate the credits
		$rooms = [];
		$total_credits = 0;
		$num_hours = $start->diffInMinutes($end) / 60;

		foreach (explode(',', $request->room_ids) as $room_id) {
			$room = Room::find($room_id);
			$rooms[] = $room;

			$total_credits += $room->credits_per_hour * $num_hours;
		}

		// This will throw an exception if there's not enough credit
		$account->debit($total_credits, 'Booking for ' . $start->format('D j M \a\t g:ia'));

		// Create the booking
		$booking = new Booking;
		$booking->user_id = $user->id;
		$booking->name = $request->name;
		$booking->is_private = (bool) $request->is_private;
		$booking->start_date = $start;
		$booking->end_date = $end;
		$booking->credit_cost = $total_credits;
		$booking->reminder = (int) $request->reminder;
		$booking->save();

		// Insert booking_rooms
		foreach ($rooms as $room) {
			$booking->rooms()->save($room, [
				'credits_per_hour' => $room->credits_per_hour,
			]);
		}

		$booking->updateReminder();

		return $booking;
	}

	public function edit(Request $request)
	{
		$this->validate($request, [
			'room_ids' => 'required',
			'start'    => 'required|date_format:"D, d M Y H:i:s O"',
			'end'      => 'required|date_format:"D, d M Y H:i:s O"',
			'name'     => 'required',
			'reminder' => 'numeric',
		], ['room_ids.required' => 'At least one room must be selected.']);

		if (is_array($request->room_ids)) {
			$request->room_ids = implode(',', $request->room_ids);
		}

		$user = $request->api_token ? Auth::guard('api')->user() : Auth::user();
		$account = $user->account;

		$room = Room::find(explode(',', $request->room_ids)[0]);
		$start = new Carbon($request->start, $room->space->timezone);
		$end = new Carbon($request->end, $room->space->timezone);

		$start->setTimezone('UTC');
		$end->setTimezone('UTC');

		// Validation for existing booking
		$booking = Booking::find($request->booking_id);

		if (!$booking || $booking->user->account_id != $user->account_id) {
			throw new ServiceValidationException('This booking either doesn\'t exist or doesn\'t belong to someone in your account');
		}

		if ($booking->start_date->isPast()) {
			throw new ServiceValidationException('This booking can not be edited because the event has already occurred.');
		}

		// Validation for new fields
		if ($start->isPast()) {
			throw new ServiceValidationException('The start time must be in the future.', 'start');
		}

		if ($end->lte($start)) {
			throw new ServiceValidationException('The end time must be after the start time.', 'start');
		}

		if ($start->copy()->addMinutes(30) > $end) {
			throw new ServiceValidationException('The booking must go for at least 30 minutes.', 'end');
		}

		// Check for conflicting rooms/times
		$has_conflict = DB::table('bookings AS b')
		                  ->join('booking_rooms AS br', 'b.id', '=', 'br.booking_id')
		                  ->where('b.id', '!=', $booking->id)
		                  ->where('b.start_date', '<', $end)
		                  ->where('b.end_date', '>', $start)
		                  ->whereIn('br.room_id', explode(',', $request->room_ids))
		                  ->exists();

		if ($has_conflict) {
			throw new ServiceValidationException('Sorry, those times conflict with another booking.', 'start');
		}

		// Build array of rooms and calculate the credits
		$rooms = [];
		$new_cost = 0;
		$num_hours = $start->diffInMinutes($end) / 60;

		foreach (explode(',', $request->room_ids) as $room_id) {
			$room = Room::find($room_id);
			$rooms[] = $room;

			$new_cost += $room->credits_per_hour * $num_hours;
		}

		// This will throw an exception if there's not enough credit
		$amount_paid = $booking->credit_cost;
		$account->debit($new_cost - $amount_paid, 'Booking adjustment for ' . $start->format('D j M \a\t g:ia'));

		// Edit the booking
		$booking->name = $request->name;
		$booking->is_private = (bool) $request->is_private;
		$booking->start_date = $start;
		$booking->end_date = $end;
		$booking->credit_cost = $new_cost;
		$booking->reminder = (int) $request->reminder;
		$booking->save();

		// Update booking_rooms
		$booking->rooms()->detach();

		foreach ($rooms as $room) {
			$booking->rooms()->attach($room->id, [
				'credits_per_hour' => $room->credits_per_hour,
			]);
		}

		$booking->updateReminder();

		return $booking;
	}

	public function delete(Request $request)
	{
		$user = $request->api_token ? Auth::guard('api')->user() : Auth::user();
		$account = $user->account;

		$booking = Booking::find($request->booking_id);

		if (!$booking || $booking->user->account_id != $user->account_id) {
			throw new ServiceValidationException('This booking either doesn\'t exist or doesn\'t belong to someone in your account');
		}

		if ($booking->start_date->isPast()) {
			throw new ServiceValidationException('This booking can not be deleted because the event has already occurred.');
		}

		// Determine how much has been paid
		$num_hours = $booking->start_date->diffInHours($booking->end_date);
		$amount_paid = 0;

		foreach ($booking->rooms as $room) {
			$amount_paid += $room->pivot->credits_per_hour * $num_hours;
		}

		$account->debit(-$amount_paid, 'Refund for deleted booking for ' . $booking->start_date->format('D j M \a\t g:ia'));

		DB::table('booking_reminders')->whereBookingId($booking->id)->delete();
		$booking->rooms()->detach();
		$booking->delete();

		return true;
	}

}
