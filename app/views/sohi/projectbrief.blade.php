@extends('master.templates.master', array('width'=>'full', 'hideSubmenu'=>'true'))

@section('page-title')
<h1>Home Improvement: <small>Get A Quote</small></h1>
@stop
@section('body')

<div class="row margin-top-20">
    <div class="col-xs-12">
        <div class="btn-group steps">
          <button type="button" class="disabled spaced btn btn-default"><strong>1.</strong> <span class="hidden-xs">Project Type</span> <div class="forward-arrow"></div></button>
          <button type="button" class="disabled spaced active btn btn-default"><strong>2.</strong> <span class="hidden-xs">Project Brief</span> <div class="forward-arrow"></div></button>
          <button type="button" class="disabled spaced btn btn-default"><strong>3.</strong> <span class="hidden-xs">Review &amp; Submit</span> <div class="forward-arrow"></div></button>
        </div>
    </div>
</div>
<div class="content-bg margin-top-20">
    <p class="margin-bottom-20">To help us find the right people for the job, please complete the form below and click continue:</p>
    @if($errors->any())<span style="color:red;">Please fill out the required fields.</span>@endif
    <br>
    <form action="/homeimprovement/projectbrief" method="POST">
        <input type="hidden" name="franchise_id" value="{{$franchise_id}}">
        <input type="hidden" name="quote_id" value="{{$quote_id}}">
        <input type="hidden" name="offer_id" value="{{$offer_id}}">
        <div class="form-group">
            <div class="row">
                <div class="col-md-4 col-sm-6 form-group {{$errors->has('project_tag_id') ? 'has-error' : ''}}">
                    <span class="h3 hblock control-label">Type of<!--  [Category] --> project</span>
                    <select name = "project_tag_id" class = "form-control">
                        <option value= = "">Choose One</option>
                        @foreach($tags as $tag)
                        <option value = "{{$tag->id}}" {{$projectType == $tag->id ? 'selected' : ''}}>{{$tag->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 col-sm-6 form-group {{$errors->has('timeframe') ? 'has-error' : ''}}">
                    <span class="h3 hblock control-label">When would you like to start your project?</span>
                    <div class="relative">
                        <select name = "timeframe" class = "form-control">
                            <option value = "">Choose One</option>
                            <option value = "asap" {{$timeframe == 'asap' ? 'selected' : ''}}>As Soon As Possible</option>
                            <option value = "this_week" {{$timeframe == 'this_week' ? 'selected' : ''}}>This Week</option>
                            <option value = "this_month" {{$timeframe == 'this_month' ? 'selected' : ''}}>This Month</option>
                            <option value = "3_months" {{$timeframe == '3_months' ? 'selected' : ''}}>Over 3 Months</option>
                        </select>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-xs-12 form-group {{$errors->has('description') ? 'has-error' : ''}}">
                    <span class="h3 hblock control-label">Tell Us About Your Project</span>
                    <textarea class="form-control" name = "description" rows="6">{{$description}}</textarea>
                </div>
            </div>
            <br>
            @if(!Auth::check())
            <span class="h3 hblock spaced red">Are you a SaveOn.com Member?</span>
            <p>If so, <a href="#">Login</a>! If not, by completing the fields below you will automatically become a member, enabling you to keep track of your projects and receive great deals!</p><br>
            @endif
            <div class="row">
                <div class="col-md-4 col-sm-6 {{$errors->has('first_name') ? 'has-error' : ''}}">
                    <span class="h3 hblock control-label">First Name</span>
                    <div class="relative">
                        <input type = "text" name="first_name" class = "form-control" value="{{$first_name}}"/>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 {{$errors->has('last_name') ? 'has-error' : ''}}">
                    <span class="h3 hblock control-label">Last Name</span>
                    <div class="relative">
                        <input type = "text" name="last_name" class = "form-control" value="{{$last_name}}"/>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-4 col-sm-6 {{$errors->has('email') ? 'has-error' : ''}}">
                    <span class="h3 hblock control-label">Your Email</span>
                    <div class="relative">
                        <input type ="email" name="email" class = "form-control" value="{{$email}}"/>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 {{$errors->has('phone') ? 'has-error' : ''}}">
                    <span class="h3 hblock control-label">Your Phone</span>
                    <div class="relative">
                        <input type ="text" name="phone" class = "form-control" value="{{$phone}}"/>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-4 {{$errors->has('address1') ? 'has-error' : ''}}">
                    <span class="h3 hblock control-label">Street Address</span>
                    <div class="relative">
                        <input type ="text" name="address1" class = "form-control" value="{{$address1}}"/>
                    </div>
                </div>
                <div class="col-md-4 {{$errors->has('address2') ? 'has-error' : ''}}">
                    <span class="h3 hblock">Address Line 2</span>
                    <div class="relative">
                        <input type ="text" name="address2" class = "form-control" value="{{$address2}}"/>
                    </div>
                </div>
                <div class="col-md-4 {{$errors->has('city') ? 'has-error' : ''}}">
                    <span class="h3 hblock control-label">City</span>
                    <div class="relative">
                        <input type ="text" name="city" class = "form-control" value="{{$city}}"/>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-4 {{$errors->has('state') ? 'has-error' : ''}}">
                    <span class="h3 hblock control-label">State / Province / Region</span>
                    <div class="relative">
                        <input type ="text" name="state" class = "form-control" value="{{$state}}"/>
                    </div>
                </div>
                <div class="col-md-4 {{$errors->has('zipcode') ? 'has-error' : ''}}">
                    <span class="h3 hblock control-label">Postal / Zip Code</span>
                    <div class="relative">
                        <input type ="text" name="zipcode" class = "form-control" value="{{$zipcode}}"/>
                    </div>
                </div>
                <div class="col-md-4 {{$errors->has('country') ? 'has-error' : ''}}">
                    <span class="h3 hblock control-label">Country</span>
                    <div class="relative">
                        <input type ="text" name="country" class = "form-control" value="{{$country}}"/>
                    </div>
                </div>
            </div>
            @if(!Auth::check())
            <hr>
            <div class="row">
                <div class="col-md-4 col-sm-6 {{$errors->has('password') ? 'has-error' : ''}}">
                    <span class="h3 hblock control-label">Create a Password</span>
                    <div class="relative">
                        <input type ="password" name="password" class = "form-control" />
                        @if($errors->has('password'))<span style="color: red">{{$errors->first('password')}}</span>@endif
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 {{$errors->has('password_confirmation') ? 'has-error' : ''}}">
                    <span class="h3 hblock control-label">Confirm Password</span>
                    <div class="relative">
                        <input type ="password" name="password_confirmation" class = "form-control" />
                        @if($errors->has('password_confirmation'))<span style="color: red">{{$errors->first('password_confirmation')}}</span>@endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="checkbox form-group {{$errors->has('terms') ? 'has-error' : ''}}">
                        <label class="control-label">
                            <input name="terms" type="checkbox">
                            * I have read and agree to the <a data-toggle="modal" data-target="#termsModal">Terms &amp; Conditions</a>
                        </label>
                    </div>
                </div>
            </div>
            @endif
        </div>
    
        <div class="row margin-top-20">
            <div class="col-xs-6">
                <a href="{{URL::abs('/')}}/homeimprovement/projecttype?franchise_id={{$franchise ? $franchise->id : 0}}&offer_id={{$offer_id}}" class="btn btn-grey pull-left"><span class="glyphicon glyphicon-chevron-left"></span> BACK</a>
            </div>
            <div class="col-xs-6">
                <button class="btn btn-green pull-right" type="submit">CONTINUE <span class="glyphicon glyphicon-chevron-right"></span></button>
            </div>
        </div>
    </form>
</div>


@stop