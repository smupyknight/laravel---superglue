@extends('layouts.default')
@section('content')
	<div class="wrapper wrapper-content">
			<div class="row">
				<div class="col-sm-12">
					<div class="ibox">
						<div class="ibox-title">
							<h5>{{ $title or '' }}</h5>
							<div class="ibox-tools">
								<a href="/admin/mentors-schedule/create" class="btn btn-primary btn-xs">Add Mentor Schedule</a>
							</div>
						</div>
						<div class="ibox-content">
							@if ($schedules->count())
								<table class="table table-striped">
									<thead>
										<tr>
											<th>Mentor</th>
											<th>Space</th>
											<th>Date From</th>
											<th>Date To</th>
											<th>Bookings</th>
											<th>Actions</th>
										</tr>
									</thead>
									<tbody>

									@foreach($schedules as $schedule)
										<tr>
											<td>{{ $schedule->mentor->first_name . ' ' . $schedule->mentor->last_name }}</td>
											<td>{{ $schedule->space->name }}</td>
											<td>{{ $schedule->start_date->setTimezone($schedule->space->timezone)->format('d/m/Y H:i') }}</td>
											<td>{{ $schedule->end_date->setTimezone($schedule->space->timezone)->format('d/m/Y H:i') }}</td>
											<td>{{ $schedule->bookings->count() }}</td>
											<td>
												<div class="btn-group">
													<button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle">Actions <span class="caret"></span></button>
													<ul class="dropdown-menu">
														<li><a href="/admin/mentors-schedule/edit/{{ $schedule->id }}">Edit</a></li>
														<li class="divider"></li>
														<li><a href="" onclick="delete_mentor_schedule('{{ $schedule->id }}');return false;">Delete</a></li>
													</ul>
												</div>
											</td>
										</tr>
									@endforeach
										@if ($schedules->total() > 10)
										<tr>
											<td colspan="7" align="right">{{ $schedules->render() }}</td>
										</tr>
										@endif
									</tbody>
								</table>
							@else
								<div class="text-center">
									<p>No mentor schedules found in the system, please <a href="/admin/mentors-schedule/create">create</a> one.</p>
								</div>
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>
@endsection

@section('scripts')
	<script src="/js/modalform.js"></script>
	<script>
		function delete_mentor_schedule(mentor_schedule_id)
		{
			console.log('asd');
			var delete_schedule_html = ''+
				'Are you sure you want to delete this schedule?'+
				'<form class="form-horizontal" action="/admin/mentors-schedule/delete/'+mentor_schedule_id+'" method="POST">'+
					'{{ csrf_field() }}'+
				'</form>';

			modalform.dialog({
				bootbox: {
					title: 'Delete schedule',
					message: delete_schedule_html,
					buttons: {
						cancel: {
							label: 'Cancel',
							className: 'btn-default'
						},
						submit: {
							label: 'Delete',
							className: 'btn-primary'
						}
					}
				}
			});
		}
	</script>
@endsection