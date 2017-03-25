@extends('master.templates.master')
@section('page-title')
<h1>Contest Disclaimer Form</h1>
@if($contest)<h2>{{ $contest->display_name }}</h2>@endif
@stop

@section('body')
<div class="content-bg">
    @if($winner)
    <div class="row">
        <div class="col-md-12">
            <form action="/contest-verify" method="POST" role="form">
                <span class="form-group {{$errors->has('name') ? 'has-error' : ''}}">
                    <p>I, <input type="text" class="form-control" style="width:170px;display:inline;" name="name" id="name" placeholder="Name..." value="{{ Input::old('name') ? Input::old('name') : ucwords($winner->first_name.' '.$winner->last_name) }}"/> , do hereby certify that:</p>
                </span>
                <ol>
                    <li class="form-group checkbox">
                        <label class="{{$errors->has('check_1') ? 'error-text' : ''}}">
                            <input type="checkbox" {{ Input::old('check_1') ? 'checked="checked"' : '' }} id="check_1" name="check_1" value="check_1"> I am 18 years of age or older.
                        </label>
                    </li>
                    <li class="form-group checkbox">
                        <label class="{{$errors->has('check_2') ? 'error-text' : ''}}">
                            <input type="checkbox" {{ Input::old('check_2') ? 'checked="checked"' : '' }} id="check_2" name="check_2" value="check_2"> I am a resident of the state of Michigan, Illinois, or Minnesota.
                        </label>
                    </li>
                    <li class="form-group checkbox">
                        <label class="{{$errors->has('check_3') ? 'error-text' : ''}}">
                            <input type="checkbox" {{ Input::old('check_3') ? 'checked="checked"' : '' }} id="check_3" name="check_3" value="check_3"> I take full responsibility for payment of any taxes or fees related to the value of the prize I am being awarded. I release Save on Everything, LLC from any and all costs, fees, taxes or expenses related to this prize.
                        </label>
                    </li>
                    <li class="form-group checkbox">
                        <label class="{{$errors->has('check_4') ? 'error-text' : ''}}">
                            <input type="checkbox" {{ Input::old('check_4') ? 'checked="checked"' : '' }} id="check_4" name="check_4" value="check_4"> I certify that I have accepted the winning prize and in doing so, waive any future claims of any nature against Save on Everything, LLC, its affiliates, subsidiaries or employees.
                        </label>
                    </li>
                    <li class="form-group checkbox">
                        <label class="{{$errors->has('check_5') ? 'error-text' : ''}}">
                            <input type="checkbox" {{ Input::old('check_5') ? 'checked="checked"' : '' }} id="check_5" name="check_5" value="check_5"> I certify that I have committed no fraudulent acts to claim this prize, and that all information and materials provided in relation to this contest are valid, true and accurate. I agree to return all prizes money, goods and or services immediately if any verified proof of fraud or misrepresentation is presented against me.
                        </label>
                    </li>
                    <li class="form-group checkbox">
                        <label class="{{$errors->has('check_6') ? 'error-text' : ''}}">
                            <input type="checkbox" {{ Input::old('check_6') ? 'checked="checked"' : '' }} id="check_6" name="check_6" value="check_6"> I agree to all publicity (photos, video clips, etc) without prior consent.
                        </label>
                    </li>
                </ol>
                <div class="form-group {{$errors->has('verified_at') ? 'has-error' : ''}}">
                    <label for="verified_at">Date</label>
                    <input type="text" class="form-control" name="verified_at" id="verified_at" placeholder="MM/DD/YYYY" value="{{ Input::old('verified_at') ? Input::old('verified_at') : date('m/d/Y') }}"/>
                </div>
                <div class="form-group {{$errors->has('winner_name') ? 'has-error' : ''}}">
                    <label for="winner_name">Contest Winner</label>
                    <input type="text" class="form-control" name="winner_name" id="winner_name" placeholder="Name..." value="{{ Input::old('winner_name') ? Input::old('winner_name') : ucwords($winner->first_name.' '.$winner->last_name) }}"/>
                </div>
                <div class="form-group {{$errors->has('birth_date') ? 'has-error' : ''}}">
                    <label for="birth_date">Date of Birth</label>
                    <input type="text" class="form-control" name="birth_date" id="birth_date" placeholder="MM/DD/YYYY" value="{{ Input::old('birth_date') }}"/>
                </div>
                <div class="form-group {{$errors->has('address') ? 'has-error' : ''}}">
                    <label for="address">Address</label>
                    <input type="text" class="form-control" name="address" id="address" placeholder="Address..." value="{{ Input::old('address') }}"/>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group {{$errors->has('city') ? 'has-error' : ''}}">
                            <label for="city">City</label>
                            <input type="text" class="form-control" name="city" id="city" placeholder="Troy" value="{{ Input::old('city') }}"/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group {{$errors->has('state') ? 'has-error' : ''}}">
                            <label for="state">State</label>
                            <input type="text" class="form-control" name="state" id="state" placeholder="MI" value="{{ Input::old('state') }}"/>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group {{$errors->has('zip') ? 'has-error' : ''}}">
                            <label for="zip">Zip</label>
                            <input type="text" class="form-control" name="zip" id="zip" placeholder="48048" value="{{ Input::old('zip') }}"/>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {{$errors->has('phone') ? 'has-error' : ''}}">
                            <label for="phone">Phone Number</label>
                            <input type="text" class="form-control" name="phone" id="phone" placeholder="555-555-5555" value="{{ Input::old('phone') }}"/>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group {{$errors->has('email') ? 'has-error' : ''}}">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" name="email" id="email" placeholder="name@domain.com" value="{{ Input::old('email') }}"/>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="verify_key" value="{{ Input::old('verify_key') ? Input::old('verify_key') : $winner->verify_key }}">
                <button type="submit" class="btn btn-lg btn-blue pull-right">Submit Disclaimer</button>
                <span class="pull-right" style="color:red; padding-right: 10px; {{ count($errors->all()) == 0 ? 'display:none;' : '' }}">Please fill out all required fields.</span>
                <div class="clearfix"></div>
            </form>
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-md-12">
            <p>We're sorry, but this link is no longer valid.  Either a winner has already been selected, or you took too long to respond.<br>Good luck next time!</p>
        </div>
    </div>
    @endif
</div>
@stop