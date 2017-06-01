@extends('layouts.default')
@section('content')
    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>{{ $title or '' }}</h5>
                        <div class="ibox-tools">
                            <a href="/admin/companies/create" class="btn btn-primary btn-xs">Create Company</a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="input-group">
                            <input type="text" placeholder="Search Users" class="input form-control">
							<span class="input-group-btn">
									<button type="button" class="btn btn btn-primary"> <i class="fa fa-search"></i> Search</button>
							</span>
                        </div>

                        @if(count($companies)>0)
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Membership</th>
                                    <th>Industry</th>
                                    <th>Date Started</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($companies as $company)
                                        <tr>
                                            <td>{{ $company->name }}</td>
                                            <td>{{ $company->account->name }}</td>
                                            <td>{{ $company->industry }}</td>
                                            <td>{{ $company->date_started->format('d M Y') }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <button data-toggle="dropdown" class="btn btn-default btn-xs dropdown-toggle">Action <span class="caret"></span></button>
                                                    <ul class="dropdown-menu">
                                                        <li><a href="/users/view/{{ $company->id }}">View</a></li>
                                                        <li><a href="#">Edit</a></li>
                                                        <li class="divider"></li>
                                                        <li><a href="#">Delete</a></li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="text-center">
                                <p>No Companies found in the system, please <a href="/companies/create">create</a> one.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
