@extends('layouts.public')
@section('content')
   <div class="middle-box text-center loginscreen   animated fadeInDown">
		<div>
			<div>
				<h1 class="logo-name">LT2</h1>
			</div>
			<h3>Create Superglue Account</h3>

 <form class="m-t" role="form" action="login.html">
				<div class="form-group">
					<input type="text" class="form-control" placeholder="Name" required="">
				</div>
				<div class="form-group">
					<input type="email" class="form-control" placeholder="Email" required="">
				</div>
				<div class="form-group">
					<input type="password" class="form-control" placeholder="Password" required="">
				</div>
				<div class='form-group'>
					<select class='form-control m-b' placeholder='Parent'>
						<option disabled selected="selected">Select Parent Account</option>
						<option>LT2</option>
						<option>BWCC</option>
						</select>
				</div>
				<div class='form-group'>
					<select class='form-control m-b' placeholder='Parent'>
						<option disabled selected="selected">Select Membership Level</option>
						<option>Kyo</option>
						<option>Ronin</option>
						<option>Mansuri</option>
						</select>
				</div>
				<div class='form-group'>
					<select class='form-control m-b' placeholder='Parent'>
						<option disabled selected="selected">Select Industry</option>
						<option>IT</option>
						<option>Creative</option>
						<option>Manufacturing</option>
						<option>Professional Services</option>
						<option>IT</option>
						<option>Creative</option>
						<option>Manufacturing</option>
						<option>Professional Services</option>
						</select>
				</div>
				<div class="form-group">
					<input type="text" class="form-control" placeholder="Areas; of; expertise;" required="">
				</div>
				<div class="form-group">
					<input type="text" class="form-control" placeholder="ABN" required="">
				</div>
				<div class="form-group">
					<input placeholder="Date Founded" class="form-control" type="text" onfocus="(this.type='date')"  id="date">
				</div>
				<div class="form-group">
					<input type="text" class="form-control" placeholder="# of Directors" required="">
				</div>
				<div class="form-group">
					<input type="text" class="form-control" placeholder="# of Employees" required="">
				</div>
				<div class='form-group'>
					<select class='form-control m-b' placeholder='Parent'>
						<option disabled selected="selected">Select Investment To Date</option>
						<option>>$30,000</option>
						<option>$30,000&ndash;$50,000</option>
						<option>$50,000&ndash;$100,000</option>
						<option>$100,000&ndash;$200,000</option>
						<option>$200,000&ndash;$500,000</option>
						<option>$500,000&ndash;$1,000,000</option>
						<option>$1,000,000+</option>
						</select>
				</div>
				 <div class='form-group'>
					<select class='form-control m-b' placeholder='Parent'>
						<option disabled selected="selected">Select Current Turnover</option>
						<option>>$30,000</option>
						<option>$30,000&ndash;$50,000</option>
						<option>$50,000&ndash;$100,000</option>
						<option>$100,000&ndash;$200,000</option>
						<option>$200,000&ndash;$500,000</option>
						<option>$500,000&ndash;$1,000,000</option>
						<option>$1,000,000+</option>
						</select>
				</div>
				<div class="form-group">
						<div class="checkbox i-checks"><label> <input type="checkbox"><i></i> Hiring? </label></div>
				</div>
				<div class="form-group">
						<div class="checkbox i-checks"><label> <input type="checkbox"><i></i> Need Connections? </label></div>
				</div>
				<div class="form-group">
						<div class="checkbox i-checks"><label> <input type="checkbox"><i></i> Agreed to the terms and policy </label></div>
				</div>
				<button type="submit" class="btn btn-primary block full-width m-b">Create</button>
			</form>
			</div>
	</div>
@endsection