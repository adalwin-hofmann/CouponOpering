@extends('master.templates.master', array('width'=>'full', 'hideSubmenu'=>'true'))

@section('page-title')
<h1>Cars &amp; Trucks <small>Become a Dealer</small></h1>
@stop
@section('body')

<div class="row margin-top-20">
    <div class="col-xs-12">
        <div class="btn-group steps">
          <button type="button" class="disabled spaced active btn btn-default"><strong>1.</strong> <span class="hidden-xs">Create Your Account</span> <div class="forward-arrow"></div></button>
          <button type="button" class="disabled spaced btn btn-default"><strong>2.</strong> <span class="hidden-xs">Driven Local Leads</span> <div class="forward-arrow"></div></button>
          <button type="button" class="disabled spaced btn btn-default"><strong>3.</strong> <span class="hidden-xs">Next Steps</span> <div class="forward-arrow"></div></button>
        </div>
    </div>
</div>

<div class="content-bg margin-top-20">
    <div class="row">
        <div class="col-sm-2">
            <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/soct/soct_vert_logo.png">
            
        </div>
        <div class="col-sm-10">
            <h2>Feature your dealership on SaveOn!</h2>
            <p>SaveOn Cars &amp; Trucks is driven locally to deliver online lead generation tools so that you can grow your business. We find the leads that match what you are looking for by locale and type, all you need to do is to check your inbox.</p>
        </div>
    </div>
    <hr style="margin-left:-20px; margin-right:-20px;">
    <div class="alert alert-danger fade {{Session::has('system_error') ? 'in' : ''}} {{!Session::has('system_error') ? 'hidden' : ''}}">
        Sorry, the was an error processing your application.  Please try again.
        <button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
    </div>
    <h3 class="red spaced">Create Your Account</h3>
    <p>By completing the fields below you will automatically become a member of <strong>SaveOn.com</strong>, enabling you to keep track of your leads.</p>
    <form action="/cars/dealer-signup" method="POST">
            <div class="row form-group">
                <div class="col-md-4 col-sm-6 {{$errors->has('business_name') ? 'has-error' : ''}}">
                    <span class="h3 hblock control-label">Dealership Name</span>
                    <div class="relative">
                        <input type="text" name="business_name" class="form-control" value="{{Input::old('business_name')}}"/>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 {{$errors->has('website') ? 'has-error' : ''}}">
                    <span class="h3 hblock control-label">Website</span>
                    <div class="relative">
                        <input type="text" name="website" class="form-control" value="{{Input::old('website')}}"/>
                    </div>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-4 col-sm-6 {{$errors->has('primary_contact') ? 'has-error' : ''}}">
                    <span class="h3 hblock control-label">Contact Name</span>
                    <div class="relative">
                        <input type="text" name="primary_contact" class="form-control" value="{{Input::old('primary_contact')}}"/>
                    </div>
                </div>
                <div class="col-sm-4  {{$errors->has('contact_email') ? 'has-error' : ''}}">
                    <span class="h3 hblock control-label">Primary Email</span>
                    <div class="relative">
                        <input type="email" name="contact_email" class="form-control" value="{{Input::old('contact_email')}}"/>
                    </div>
                </div>
                <div class="col-sm-4  {{$errors->has('contact_phone') ? 'has-error' : ''}}">
                    <span class="h3 hblock control-label">Phone</span>
                    <div class="relative">
                        <input type="phone" name="contact_phone" class="form-control" value="{{Input::old('contact_phone')}}"/>
                    </div>
                </div>
            </div>
            <div class="row">
                
            </div>
            <div class="legend">
                <span class="h3 hblock">Business Address</span>
                <hr>
            </div>
            <div class="row">
                <div class="col-md-4 {{$errors->has('address1') ? 'has-error' : ''}}">
                    <p class="margin-bottom-0 control-label">Street Address</p>
                    <div class="relative">
                        <input type ="text" name="address1" class = "form-control" value="{{Input::old('address1')}}"/>
                    </div>
                </div>
                <div class="col-md-4 {{$errors->has('address2') ? 'has-error' : ''}}">
                    <p class="margin-bottom-0 control-label">Address Line 2</p>
                    <div class="relative">
                        <input type ="text" name="address2" class = "form-control" value="{{Input::old('address2')}}"/>
                    </div>
                </div>
                <div class="col-md-4 {{$errors->has('city') ? 'has-error' : ''}}">
                    <p class="margin-bottom-0 control-label">City</p>
                    <div class="relative">
                        <input type ="text" name="city" class = "form-control" value="{{Input::old('city')}}"/>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-4 {{$errors->has('state') ? 'has-error' : ''}}">
                    <p class="margin-bottom-0 control-label">State / Province / Region</p>
                    <div class="relative">
                        <input type ="text" name="state" class = "form-control" value="{{Input::old('state')}}"/>
                    </div>
                </div>
                <div class="col-md-4 {{$errors->has('zipcode') ? 'has-error' : ''}}">
                    <p class="margin-bottom-0 control-label">Postal / Zip Code</p>
                    <div class="relative">
                        <input type ="text" name="zipcode" class = "form-control" value="{{Input::old('zipcode')}}"/>
                    </div>
                </div>
                <div class="col-md-4 {{$errors->has('country') ? 'has-error' : ''}}">
                    <p class="margin-bottom-0 control-label">Country</p>
                    <select name="country" class="form-control">
                        <option value="usa" {{Input::old('country') == 'usa' ? 'selected="selected"' : ''}}>United States</option>
                        <option value="canada" {{Input::old('country') == 'canada' ? 'selected="selected"' : ''}}>Canada</option>
                    </select>
                </div>
            </div>
            <hr>

            <div class="form-group">
                <span class="h3 hblock control-label">Hours</span>
                <textarea name="hours" class="form-control" rows="3"></textarea>
            </div>

            <div class="row form-group">
                <div class="col-md-4 col-sm-6 {{$errors->has('account_password') ? 'has-error' : ''}}">
                    <span class="h3 hblock control-label">Create a Password</span>
                    <div class="relative">
                        <input type ="password" name="account_password" class = "form-control" />
                        <span class="help-block" style="color:red;">{{$errors->first('account_password')}}</span>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 {{$errors->has('account_password_confirmation') ? 'has-error' : ''}}">
                    <span class="h3 hblock control-label">Confirm Password</span>
                    <div class="relative">
                        <input type ="password" name="account_password_confirmation" class = "form-control" />
                        <span class="help-block" style="color:red;">{{$errors->first('account_password_confirmation')}}</span>
                    </div>
                </div>
            </div>
    
        <div class="row margin-top-20">
            <div class="col-xs-6">
            </div>
            <div class="col-xs-6">
                <button class="btn btn-green pull-right" type="submit">CONTINUE <span class="glyphicon glyphicon-chevron-right"></span></button>
            </div>
        </div>
    </form>
</div>


@stop