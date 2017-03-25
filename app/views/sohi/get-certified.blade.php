@extends('master.templates.master', array('width'=>'full', 'hideSubmenu'=>'true'))

@section('page-title')
<h1>Home Improvement: <small>Get Save Certified</small></h1>
@stop
@section('body')

<div class="row margin-top-20">
    <div class="col-xs-12">
        <div class="btn-group steps four-steps">
          <button type="button" class="disabled spaced active btn btn-default"><strong>1.</strong> <span class="hidden-xs">Create Your Account</span> <div class="forward-arrow"></div></button>
          <button type="button" class="disabled spaced btn btn-default"><strong>2.</strong> <span class="hidden-xs">Pick Your Leads</span> <div class="forward-arrow"></div></button>
          <button type="button" class="disabled spaced btn btn-default"><strong>3.</strong> <span class="hidden-xs">Get Save Certified</span> <div class="forward-arrow"></div></button>
          <button type="button" class="disabled spaced btn btn-default"><strong>4.</strong> <span class="hidden-xs">Next Steps</span> <div class="forward-arrow"></div></button>
        </div>
    </div>
</div>
<div class="content-bg margin-top-20">
    <div class="row">
        <div class="col-sm-2">
            <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sohi/save_certified.png">
        </div>
        <div class="col-sm-10">
            <h2 class="margin-bottom-20">Get Save Certified!</h2>
            <div class="row">
                <div class="col-lg-4 border-right">
                    <ul class="check_list">
                        <li><strong>Licensed</strong> - Not every industry requires a license</li>
                        <li><strong>Bonded</strong> - When needed</li>
                        <li><strong>Insured</strong> - Workman's Comp / Company Liability</li>
                        <li><strong>Background checks of workers, by the company</strong></li>
                        <li><strong>Better Business Bureau</strong></li>
                    </ul>
                </div>
                <div class="col-lg-8">
                    <p>New customers drive business. SaveOn<sup>&reg;</sup> Home Improvement<sup>sm</sup> delivers online lead generation tools so that you can grow your business at a pace that suits you best. Regardless of your industry, we will find the leads that match what you are looking for by location and type, and send them directly to your inbox.</p>
                </div>
            </div>    
        </div>
        
    </div>
    <hr style="margin-left:-20px; margin-right:-20px;">
    <div class="alert alert-danger fade {{Session::has('system_error') ? 'in' : ''}}">
        Sorry, the was an error processing your application.  Please try again.
        <button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
    </div>
    <h3 class="red spaced">Create Your Account to Become Save Certified</h3>
    <!--<p>By completing the fields below you will automatically become a member of <strong>SaveOn.com</strong>, enabling you to keep track of your leads and become certified!</p>-->
    <form action="/homeimprovement/get-certified" method="POST">
            <div class="row">
                <div class="col-md-4 col-sm-6 {{$errors->has('business_name') ? 'has-error' : ''}}">
                    <span class="h3 hblock control-label">Business Name</span>
                    <div class="relative">
                        <input type="text" name="business_name" class="form-control" value="{{Input::old('business_name') ? Input::old('business_name') : (isset($app->business_name) ? $app->business_name : '')}}"/>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 {{$errors->has('primary_contact') ? 'has-error' : ''}}">
                    <span class="h3 hblock control-label">Contact Name</span>
                    <div class="relative">
                        <input type="text" name="primary_contact" class="form-control" value="{{Input::old('primary_contact') ? Input::old('primary_contact') : (isset($app->primary_contact) ? $app->primary_contact : '')}}"/>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-4 col-sm-6 {{$errors->has('contact_email') ? 'has-error' : ''}}">
                    <span class="h3 hblock control-label">Email</span>
                    <div class="relative">
                        <input type="email" name="contact_email" class="form-control" value="{{Input::old('contact_email') ? Input::old('contact_email') : (isset($app->contact_email) ? $app->contact_email : '')}}"/>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 {{$errors->has('contact_phone') ? 'has-error' : ''}}">
                    <span class="h3 hblock control-label">Phone</span>
                    <div class="relative">
                        <input type="phone" name="contact_phone" class="form-control" value="{{Input::old('contact_phone') ? Input::old('contact_phone') : (isset($app->contact_phone) ? $app->contact_phone : '')}}"/>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-4 col-sm-6 {{$errors->has('lead_email') ? 'has-error' : ''}}">
                    <span class="h3 hblock control-label">Lead Email</span>
                    <div class="relative">
                        <input type="email" name="lead_email" class="form-control" value="{{Input::old('lead_email') ? Input::old('lead_email') : (isset($app->lead_email) ? $app->lead_email : '')}}"/>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 {{$errors->has('lead_phone') ? 'has-error' : ''}}">
                    <span class="h3 hblock control-label">Lead Phone</span>
                    <div class="relative">
                        <input type="phone" name="lead_phone" class="form-control" value="{{Input::old('lead_phone') ? Input::old('lead_phone') : (isset($app->lead_phone) ? $app->lead_phone : '')}}"/>
                    </div>
                </div>
            </div>
            <br>
            <div class="legend">
                <span class="h3 hblock">Business Address</span>
                <hr>
            </div>
            <div class="row">
                <div class="col-md-4 {{$errors->has('address1') ? 'has-error' : ''}}">
                    <p class="margin-bottom-0 control-label">Street Address</p>
                    <div class="relative">
                        <input type ="text" name="address1" class = "form-control" value="{{Input::old('address1') ? Input::old('address1') : (isset($app->address1) ? $app->address1 : '')}}"/>
                    </div>
                </div>
                <div class="col-md-4 {{$errors->has('address2') ? 'has-error' : ''}}">
                    <p class="margin-bottom-0 control-label">Address Line 2</p>
                    <div class="relative">
                        <input type ="text" name="address2" class = "form-control" value="{{Input::old('address2') ? Input::old('address2') : (isset($app->address2) ? $app->address2 : '')}}"/>
                    </div>
                </div>
                <div class="col-md-4 {{$errors->has('city') ? 'has-error' : ''}}">
                    <p class="margin-bottom-0 control-label">City</p>
                    <div class="relative">
                        <input type ="text" name="city" class = "form-control" value="{{Input::old('city') ? Input::old('city') : (isset($app->city) ? $app->city : '')}}"/>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-4 {{$errors->has('state') ? 'has-error' : ''}}">
                    <p class="margin-bottom-0 control-label">State / Province / Region</p>
                    <div class="relative">
                        <input type ="text" name="state" class = "form-control" value="{{Input::old('state') ? Input::old('state') : (isset($app->state) ? $app->state : '')}}"/>
                    </div>
                </div>
                <div class="col-md-4 {{$errors->has('zipcode') ? 'has-error' : ''}}">
                    <p class="margin-bottom-0 control-label">Postal / Zip Code</p>
                    <div class="relative">
                        <input type ="text" name="zipcode" class = "form-control" value="{{Input::old('zipcode') ? Input::old('zipcode') : (isset($app->zipcode) ? $app->zipcode : '')}}"/>
                    </div>
                </div>
                <div class="col-md-4 {{$errors->has('country') ? 'has-error' : ''}}">
                    <p class="margin-bottom-0 control-label">Country</p>
                    <select name="country" class="form-control">
                        <option value="usa" {{Input::old('country') == 'usa' || (isset($app->country) && $app->country == 'usa') ? 'selected="selected"' : ''}}>United States</option>
                        <option value="canada" {{Input::old('country') == 'canada' || (isset($app->country) && $app->country == 'canada') ? 'selected="selected"' : ''}}>Canada</option>
                    </select>
                </div>
            </div>
            <hr>
            <div class="row">
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
                <a href="/homeimprovement" class="btn btn-grey pull-left"><span class="glyphicon glyphicon-chevron-left"></span> BACK</a>
            </div>
            <div class="col-xs-6">
                <button class="btn btn-green pull-right" type="submit">CONTINUE <span class="glyphicon glyphicon-chevron-right"></span></button>
            </div>
        </div>
        <input type="hidden" name="application_id" value="{{Input::old('application_id') ? Input::old('application_id') : (isset($app->id) ? $app->id : 0)}}">
    </form>
</div>


@stop