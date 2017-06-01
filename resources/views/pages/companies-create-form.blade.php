
    {{csrf_field()}}

    <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
        <label class="col-lg-2 control-label">Name</label>
        <div class="col-lg-10"><input type="text" placeholder="Name" name="name" class="form-control" id="name" value="{{old('name')}}">
            <span class="help-block"></span>
            @if ($errors->has('name'))
                <span class="help-block"><strong>{{ $errors->first('name') }}</strong></span>
            @endif
        </div>
    </div>
    <div class='form-group {{ $errors->has('membership_id') ? ' has-error' : '' }}'>
        <label class="col-lg-2 control-label">Membership Level</label>
        <div class="col-lg-10">
            <select class='form-control m-b' placeholder='Parent' name="membership_id" id="membership_id">
                <option disabled selected="selected">Select Membership Level</option>
                @if(count($accounts)<=0)
                    <option disabled>No Membership</option>
                @else

                    @foreach($accounts as $key => $value)

                        @if(old('membership_id'))
                            <option value="{{$key}}" selected>{{$value}}</option>

                        @else
                            <option value="{{$key}}">{{$value}}</option>

                        @endif

                    @endforeach

                @endif

            </select>
            <span class="help-block"></span>
            @if ($errors->has('membership_id'))
                <span class="help-block"><strong>{{ $errors->first('membership_id') }}</strong></span>
            @endif
        </div>
    </div>
    <div class='form-group{{ $errors->has('space_id') ? ' has-error' : '' }}'>
        <label class="col-lg-2 control-label">Spaces</label>
        <div class="col-lg-10">
            <select class='form-control m-b' placeholder='Parent' name="space_id" id="space_id">
                <option disabled selected="selected">Select Space</option>
                @if (count($spaces) <= 0)
                    <option disabled>No Spaces</option>
                @else
                    @foreach($spaces as $key => $value)

                        @if(old('space_id'))
                            <option value="{{$key}}" selected>{{$value}}</option>

                        @else
                            <option value="{{$key}}">{{$value}}</option>

                        @endif

                    @endforeach
                @endif
            </select>
            <span class="help-block"></span>
            @if ($errors->has('space_id'))
                <span class="help-block"><strong>{{ $errors->first('space_id') }}</strong></span>
            @endif
        </div>
    </div>
    <div class='form-group{{ $errors->has('industry') ? ' has-error' : '' }}'>
        <label class="col-lg-2 control-label">Industry</label>
        <div class="col-lg-10">
            <select class='form-control m-b' placeholder='Parent' name="industry" id="industry">
                <option disabled selected="selected">Select Industry</option>
                <option @if(old('industry')) selected @endif>IT</option>
                <option @if(old('industry')) selected @endif>Creative</option>
                <option @if(old('industry')) selected @endif>Manufacturing</option>
                <option @if(old('industry')) selected @endif>Professional Services</option>
                <option @if(old('industry')) selected @endif>IT</option>
                <option @if(old('industry')) selected @endif>Creative</option>
                <option @if(old('industry')) selected @endif>Manufacturing</option>
                <option @if(old('industry')) selected @endif>Professional Services</option>
            </select>
            <span class="help-block"></span>
            @if ($errors->has('industry'))
                <span class="help-block"><strong>{{ $errors->first('industry') }}</strong></span>
            @endif
        </div>
    </div>
    <div class="form-group{{ $errors->has('skills') ? ' has-error' : '' }}">
        <label class="col-lg-2 control-label">Skills</label>
        <div class="col-lg-10">
            <input type="text" class="form-control" placeholder="Areas; of; expertise;" name="skills" id="skills" value="{{old('skills')}}">
            <span class="help-block"></span>
            @if ($errors->has('skills'))
                <span class="help-block"><strong>{{ $errors->first('skills') }}</strong></span>
            @endif
        </div>
    </div>
    <div class="form-group{{ $errors->has('abn') ? ' has-error' : '' }}">
        <label class="col-lg-2 control-label">ABN</label>
        <div class="col-lg-10">
            <input type="text" class="form-control" placeholder="ABN" name="abn" id="abn" value="{{old('abn')}}">
            <span class="help-block"></span>
            @if ($errors->has('abn'))
                <span class="help-block"><strong>{{ $errors->first('abn') }}</strong></span>
            @endif
        </div>
    </div>
    <div class="form-group{{ $errors->has('date_started') ? ' has-error' : '' }}">
        <label class="col-lg-2 control-label">Date Started</label>
        <div class="col-lg-10">
            <input placeholder="Date company was started" name="date_started" class="form-control" type="text" onfocus="(this.type='date')"  id="date_started">
            <span class="help-block"></span>
            @if ($errors->has('date_started'))
                <span class="help-block"><strong>{{ $errors->first('date_started') }}</strong></span>
            @endif
        </div>
    </div>
    <div class="form-group{{ $errors->has('employees') ? ' has-error' : '' }}">
        <label class="col-lg-2 control-label">Number of Employees</label>
        <div class="col-lg-10">
            <input type="text" class="form-control" placeholder="# of Employees" name="employees" id="employees" value="{{  old('employees')}}">
            <span class="help-block"></span>
            @if ($errors->has('employees'))
                <span class="help-block"><strong>{{ $errors->first('employees') }}</strong></span>
            @endif
        </div>
    </div>
    <div class='form-group{{ $errors->has('investment') ? ' has-error' : '' }}'>
        <label class="col-lg-2 control-label">Investment</label>
        <div class="col-lg-10">
            <select class='form-control m-b' placeholder='Parent' name="investment" id="investment">
                <option disabled selected="selected">Select Investment To Date</option>
                <option @if(old('investment')) selected @endif>>$30,000</option>
                <option @if(old('investment')) selected @endif>$30,000&ndash;$50,000</option>
                <option @if(old('investment')) selected @endif>$50,000&ndash;$100,000</option>
                <option @if(old('investment')) selected @endif>$100,000&ndash;$200,000</option>
                <option @if(old('investment')) selected @endif>$200,000&ndash;$500,000</option>
                <option @if(old('investment')) selected @endif>$500,000&ndash;$1,000,000</option>
                <option @if(old('investment')) selected @endif>$1,000,000+</option>
            </select>
            <span class="help-block"></span>
            @if ($errors->has('investment'))
                <span class="help-block"><strong>{{ $errors->first('investment') }}</strong></span>
            @endif
        </div>
    </div>
    <div class='form-group{{ $errors->has('revenue') ? ' has-error' : '' }}'>
        <label class="col-lg-2 control-label">Turnover</label>
        <div class="col-lg-10">
            <select class='form-control m-b' placeholder='Parent' name="revenue" id="revenue">
                <option disabled selected="selected">Select Current Turnover</option>
                <option @if(old('revenue')) selected @endif>>$30,000</option>
                <option @if(old('revenue')) selected @endif>$30,000&ndash;$50,000</option>
                <option @if(old('revenue')) selected @endif>$50,000&ndash;$100,000</option>
                <option @if(old('revenue')) selected @endif>$100,000&ndash;$200,000</option>
                <option @if(old('revenue')) selected @endif>$200,000&ndash;$500,000</option>
                <option @if(old('revenue')) selected @endif>$500,000&ndash;$1,000,000</option>
                <option @if(old('revenue')) selected @endif>$1,000,000+</option>
            </select>
            <span class="help-block"></span>
            @if ($errors->has('revenue'))
                <span class="help-block"><strong>{{ $errors->first('revenue') }}</strong></span>
            @endif
        </div>
    </div>
