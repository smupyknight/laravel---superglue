<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Invitation;
use App\User;
use App\Timeline;

class InvitePendingUsers extends Command
{

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'invite-pending-users';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Invite users who have been added to the system through an importer, but have not been sent an invite';

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
		$users = User::where('password', '=', '')->get();

		foreach ($users as $user) {
			// Don't invite users who already have an invite
			if ($user->invitations()->count() >= 1) {
				continue;
			}

			$invitation = new Invitation;
			$invitation->token = substr(md5(microtime()), 0, 10);
			$invitation->user_id = $user->id;
			$invitation->save();

			$invitation->send();

			$this->addTimeline([
				'created_by' => null,
				'user_id'    => $user->id,
				'account_id' => $user->account_id,
				'title'      => 'Invited User',
				'message'    => 'Invited user '.ucwords($user->first_name.' '.$user->last_name).' ('.$user->email.')',
				'type'       => 'info',
			]);
			$this->info('Invited user: ' . $user->email);
		}
	}

	/**
	 * Create timeline entry
	 */
	private function addTimeline(array $info)
	{
		$info = collect($info);

		$timeline = new Timeline;

		$timeline->created_by = $info->get('created_by', '');
		$timeline->user_id = $info->get('user_id', '');
		$timeline->account_id = $info->get('account_id', '');
		$timeline->title = $info->get('title', '');
		$timeline->message = $info->get('message', '');
		$timeline->type = $info->get('type', '');

		$timeline->save();
	}

}
