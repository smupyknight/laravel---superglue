@extends('layouts.default')

@section('content')
	<div class="wrapper wrapper-content">
		<div class="row">
			<div class="col-sm-12">
				<div class="ibox">
					<div class="ibox-title">
						<h5>{{ $title or '' }}</h5>
						<div class="ibox-tools">
							<a href="#" class="btn btn-primary btn-xs btn-add-workhistory">Add New Work History</a>
						</div>
					</div>
					<div class="ibox-content">
						<table class="table table-striped">
							<thead>
								<tr>
									<th>Job Title</th>
									<th>Company</th>
									<th>Description</th>									
									<th>City</th>
									<th>Start Date</th>
									<th>End Date</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								@if(count($workhistories)!==0)
								@foreach ($workhistories as $workhistory)
										<tr data-id="{{ $workhistory->id }}" data-job-title="{{ $workhistory->job_title }}" data-company="{{ $workhistory->company }}" data-city="{{ $workhistory->city }}" data-start-date ="{{$workhistory->start_date}}" data-end-date ="{{$workhistory->end_date}}" data-description ="{{$workhistory->description}}">
										<td>{{ $workhistory->job_title }}</td>
										<td>{{ $workhistory->company }}</td>
										<td>{{ $workhistory->description }}</td>
										<td>{{ $workhistory->city }}</td>
										<td>{{ $workhistory->start_date }}</td>
										<td>{{ $workhistory->end_date ? $workhistory->end_date : 'Still Working' }}</td>
										<td>
											<div class="btn-group">
													<button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle">Action <span class="caret"></span></button>
													<ul class="dropdown-menu">
														{{-- <li><a href="/admin/work-history/view/{{$workhistory->id}}">View</a></li> --}}
														<li><a href="#" class="btn-edit-workhistory">Edit</a></li>
														<li class="divider"></li>
														<li><a href="#" class="btn-delete-workhistory">Delete</a></li>
													</ul>
												</div>
										</td>
									</tr>
								@endforeach
								@else
									<tr>
										<td colspan="7">No work history</td>
									</tr>
								@endif
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('scripts')
<script src="/js/modalform.js"></script>
<script type="text/javascript">

	var modal_work_history = ''+
				'<form action="/work-history/create" method="post" class="form-horizontal">'+
					'<div class="form-group">'+
						'<label class="col-md-3 control-label">Job Title</label>'+
						'<div class="col-md-9"><input type="text" name="job_title" class="form-control"></div>'+
					'</div>'+
					'<div class="form-group">'+
						'<label class="col-md-3 control-label">Company</label>'+
						'<div class="col-md-9"><input type="text" name="company" class="form-control"></div>'+
					'</div>'+
					'<div class="form-group">'+
						'<label class="col-md-3 control-label">Description</label>'+
						'<div class="col-md-9"><textarea name="description" class="form-control"></textarea></div>'+
					'</div>'+
					'<div class="form-group">'+
						'<label class="col-md-3 control-label">City</label>'+
						'<div class="col-md-9"><input type="text" name="city" class="form-control"></div>'+
					'</div>'+
					'<div class="form-group">'+
						'<label class="col-md-3 control-label">Start Date</label>'+
						'<div class="col-md-9"><input type="text" name="start_date" class="date-input form-control"></div>'+
					'</div>'+
					'<div class="form-group" id="end_date">'+
						'<label class="col-md-3 control-label">End Date</label>'+
						'<div class="col-md-9"><input type="text" name="end_date" class="date-input form-control"></div>'+
					'</div>'+
					'<div class="form-group">'+
						'<label class="col-md-4 control-label">I\'m still working here</label>'+
						'<div class="col-md-7"><input type="checkbox" name="still_working" id="still_working"></div>'+
					'</div>'+
					'{{ csrf_field() }}'+
				'</form>';


		$('.btn-add-workhistory').on('click', function() {
			modalform.dialog({
				bootbox: {
					title: 'Add Work History',
					message: modal_work_history,
					buttons: {
						cancel: {
							label: 'Cancel',
							className: 'btn-default'
						},
						submit: {
							label: 'Add Work History',
							className: 'btn-primary',
						}
					}
				},
				after_init : function() {
					$('.date-input').datetimepicker({
						format: 'YYYY-MM-DD',
						useCurrent: false,
					});
					
					$("#still_working").on("click",function()
					{
						$('#end_date').toggle(!$(this).is(':checked'));
					});
				}
			});
		});

		$('.btn-edit-workhistory').on('click', function(event) {
			event.preventDefault();

			var tr = $(this).closest('tr');

			modalform.dialog({
				bootbox: {
					title: 'Edit Work History',
					message: modal_work_history,
					buttons: {
						cancel: {
							label: 'Cancel',
							className: 'btn-default',
						},
						submit: {
							label: 'Save Changes',
							className: 'btn-primary'
						}
					}
				},
				after_init: function() {
					$('.date-input').datetimepicker({
						format: 'YYYY-MM-DD',
						useCurrent: false,
					});
					$("#still_working").on("click",function()
					{
						$('#end_date').toggle(!$(this).is(':checked'));
					});
					$('.modal input[name="job_title"]').val(tr.data('job-title'));
					$('.modal input[name="company"]').val(tr.data('company'));
					$('.modal input[name="city"]').val(tr.data('city'));
					$('.modal input[name="start_date"]').val(tr.data('start-date'));
					if(!tr.data('end-date'))
					{
						$('.modal input[name="still_working"]').attr("checked","checked");
						$('#end_date').hide();
					}
					else
						$('.modal input[name="end_date"]').val(tr.data('end-date'));
					$('.modal textarea[name="description"]').val(tr.data('description'));
					$('.modal form').attr('action', '/work-history/edit/' + tr.data('id'));
				}
			});
		});


		$('.btn-delete-workhistory').on('click', function(event) {
			event.preventDefault();
			var workhistory_id = $(this).closest('tr').data('id');
			bootbox.confirm('Are you sure you want to delete this work history?', function(response){
				if (response) {
					$.ajax({
						type: "post",
						url: "/work-history/delete/" + workhistory_id,
						data: {_token: '{{ csrf_token() }}', workhistory_id: workhistory_id},
					}).done(function(response) {
						if (response) {
							window.location.reload();
						}
					});
				}
			});
		});

	</script>

@endsection
