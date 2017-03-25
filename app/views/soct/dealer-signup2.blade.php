@extends('master.templates.master', array('width'=>'full', 'hideSubmenu'=>'true'))

@section('page-title')
<h1>Cars &amp; Trucks <small>Become a Dealer</small></h1>
@stop
@section('body')

<div class="row margin-top-20">
    <div class="col-xs-12">
        <div class="btn-group steps">
          <button type="button" class="disabled spaced btn btn-default"><strong>1.</strong> <span class="hidden-xs">Create Your Account</span> <div class="forward-arrow"></div></button>
          <button type="button" class="disabled spaced active btn btn-default"><strong>2.</strong> <span class="hidden-xs">Driven Local Leads</span> <div class="forward-arrow"></div></button>
          <button type="button" class="disabled spaced btn btn-default"><strong>3.</strong> <span class="hidden-xs">Next Steps</span> <div class="forward-arrow"></div></button>
        </div>
    </div>
</div>

<div class="content-bg margin-top-20">
    <div class="alert alert-danger fade {{Session::has('system_error') ? 'in' : ''}} {{!Session::has('system_error') ? 'hidden' : ''}}">
        Sorry, the was an error processing your application.  Please try again.
        <button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
    </div>
    <h2 class="red spaced">Create Your Account</h2>
    <p>By completing the fields below you will automatically become a member of <strong>SaveOn.com</strong>, enabling you to keep track of your leads.</p>
    <form action="/cars/dealer-signup2" method="POST">
        <input name="application_id" type="hidden" value="{{Session::has('application_id') ? Session::get('application_id') : Input::old('application_id')}}">
            <div class="row form-group">
                <div class="col-sm-4  {{$errors->has('lead_email') ? 'has-error' : ''}}">
                    <span class="h3 hblock control-label">Lead Email</span>
                    <div class="relative">
                        <input type="email" name="lead_email" class="form-control" value="{{Input::old('lead_email')}}"/>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <span class="h3 hblock control-label">Short Description of Services Offerd</span>
                <textarea name="description" class="form-control" rows="3"></textarea>
            </div>
            <div class="row form-group">
                <div class="col-md-4 col-sm-6 {{$errors->has('new_inventory') ? 'has-error' : ''}}">
                    <span class="h3 hblock control-label">New Car Inventory Provider</span>
                    <div class="relative">
                        <input type="text" name="new_inventory_number" class="form-control" value="{{Input::old('new_inventory')}}"/>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6 {{$errors->has('used_inventory') ? 'has-error' : ''}}">
                    <span class="h3 hblock control-label">Used Car Inventory Provider</span>
                    <div class="relative">
                        <input type="text" name="used_inventory_number" class="form-control" value="{{Input::old('used_inventory')}}"/>
                    </div>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-sm-4  {{$errors->has('lead_amount') ? 'has-error' : ''}}">
                    <span class="h3 hblock control-label">Desired Amount of Leads Per Day</span>
                    <div class="relative">
                        <input type="text" name="lead_amount" class="form-control" value="{{Input::old('lead_amount')}}"/>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-6">
                    <div class="row">
                        <div class="col-sm-5"><span class="h3">Market?</span></div>
                        <div class="col-sm-7 row">
                            <div class="col-xs-4">
                                <div class="radio margin-top-0">
                                    <label>
                                        <input type="radio" name="market" value="MI" {{Input::old('market', 'MI') == 'MI' ? 'checked' : ''}}>
                                        MI
                                    </label>
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="radio margin-top-0">
                                    <label>
                                        <input type="radio" name="market" value="MN" {{Input::old('market', 'MI') == 'MN' ? 'checked' : ''}}>
                                        MN
                                    </label>
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="radio margin-top-0">
                                    <label>
                                        <input type="radio" name="market" value="IL" {{Input::old('market', 'MI') == 'IL' ? 'checked' : ''}}>
                                        IL
                                    </label>
                                </div>
                            </div>
                        </div>
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