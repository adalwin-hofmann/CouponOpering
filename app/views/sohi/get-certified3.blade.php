@extends('master.templates.master', array('width'=>'full', 'hideSubmenu'=>'true'))

@section('page-title')
<h1>Home Improvement: <small>Get Save Certified</small></h1>
@stop
@section('body')

<div class="row margin-top-20">
    <div class="col-xs-12">
        <div class="btn-group steps four-steps">
          <button type="button" class="disabled spaced btn btn-default"><strong>1.</strong> <span class="hidden-xs">Create Your Account</span> <div class="forward-arrow"></div></button>
          <button type="button" class="disabled spaced btn btn-default"><strong>2.</strong> <span class="hidden-xs">Pick Your Leads</span> <div class="forward-arrow"></div></button>
          <button type="button" class="disabled spaced active btn btn-default"><strong>3.</strong> <span class="hidden-xs">Get Save Certified</span> <div class="forward-arrow"></div></button>
          <button type="button" class="disabled spaced btn btn-default"><strong>4.</strong> <span class="hidden-xs">Next Steps</span> <div class="forward-arrow"></div></button>
        </div>
    </div>
</div>
<div class="content-bg margin-top-20">
    <h2 class="red spaced">Become Save Certified</h3>
    <p>Complete the form below and hit submit to become Save Certified!</p>
    <hr>
    <form action="/homeimprovement/get-certified3" method="POST">
        <input name="application_id" type="hidden" value="{{Session::has('application_id') ? Session::get('application_id') : Input::old('application_id')}}">
        <div class="row">
            <div class="col-md-4 col-sm-6">
                <div class="row">
                    <div class="col-xs-5"><span class="h3 hblock">Are You Licensed?</span></div>
                    <div class="col-xs-7 row">
                        <div class="col-xs-6">
                            <div class="radio margin-top-0">
                                <label>
                                    <input type="radio" name="isLicensed" value="1" {{Input::old('isLicensed', 1) == 1 ? 'checked' : ''}}>
                                    Yes
                                </label>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="radio margin-top-0">
                                <label>
                                    <input type="radio" name="isLicensed" value="0" {{Input::old('isLicensed', 1) == 0 ? 'checked' : ''}}>
                                    No
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 col-sm-6 {{$errors->has('license_number') ? 'has-error' : ''}}">
                <span class="h3 hblock control-label">License Number</span>
                <div class="relative">
                    <input type="text" name="license_number" class="form-control" value="{{Input::old('license_number')}}"/>
                </div>
            </div>
        </div>
        <hr>

        <div class="row">
            <div class="col-md-4 col-sm-6">
                <div class="row">
                    <div class="col-xs-5"><span class="h3 hblock">Are You Bonded?</span></div>
                    <div class="col-xs-7 row">
                        <div class="col-xs-6">
                            <div class="radio margin-top-0">
                                <label>
                                    <input type="radio" name="isBonded" id="isBonded" value="1" {{Input::old('isBonded', 1) == 1 ? 'checked' : ''}}>
                                    Yes
                                </label>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="radio margin-top-0">
                                <label>
                                    <input type="radio" name="isBonded" id="isBonded" value="0" {{Input::old('isBonded', 1) == 0 ? 'checked' : ''}}>
                                    No
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 col-sm-6 {{$errors->has('bond_number') ? 'has-error' : ''}}">
                <span class="h3 hblock control-label">Bond Number</span>
                <div class="relative">
                    <input type="text" name="bond_number" class="form-control" value="{{Input::old('bond_number')}}"/>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-4 col-sm-6">
                <div class="row">
                    <div class="col-xs-5"><span class="h3 hblock">Are You Insured?</span></div>
                    <div class="col-xs-7 row">
                        <div class="col-xs-6">
                            <div class="radio margin-top-0">
                                <label>
                                    <input type="radio" name="isInsured" id="isInsured" value="1" {{Input::old('isInsured', 1) == 1 ? 'checked' : ''}}>
                                    Yes
                                </label>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="radio margin-top-0">
                                <label>
                                    <input type="radio" name="isInsured" id="isInsured" value="0" {{Input::old('isInsured', 1) == 0 ? 'checked' : ''}}>
                                    No
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 {{$errors->has('insurance_company') ? 'has-error' : ''}}">
                <span class="h3 hblock control-label">Insurance Company</span>
                <div class="relative">
                    <input type ="text" name="insurance_company" class = "form-control" />
                </div>
            </div>
            <div class="col-md-4 {{$errors->has('policy_number') ? 'has-error' : ''}}">
                <span class="h3 hblock control-label">Policy Number</span>
                <div class="relative">
                    <input type ="text" name="policy_number" class = "form-control" />
                </div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-4 {{$errors->has('agent') ? 'has-error' : ''}}">
                <span class="h3 hblock control-label">Agent's Name</span>
                <div class="relative">
                    <input type ="text" name="agent" class = "form-control" />
                </div>
            </div>
            <div class="col-md-4 {{$errors->has('policy_number') ? 'has-error' : ''}}">
                <span class="h3 hblock control-label">Agent's Phone Number</span>
                <div class="relative">
                    <input type ="text" name="agent_phone" class = "form-control" />
                </div>
            </div>
        </div>
        <hr>

        <div>
            <span class="h3 pull-left margin-top-10">Do you hire outside laborers?</span>
            <div class="row pull-left margin-left-20">
                <div class="col-xs-6">
                    <div class="radio margin-top-10">
                        <label>
                            <input type="radio" name="has_outside_labor" value="1" {{Input::old('has_outside_labor', 0) == 1 ? 'checked' : ''}}>
                            Yes
                        </label>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="radio margin-top-10">
                        <label>
                            <input type="radio" name="has_outside_labor" value="0" {{Input::old('has_outside_labor', 0) == 0 ? 'checked' : ''}}>
                            No
                        </label>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div id="divOutsideInsured" style="{{Input::old('has_outside_labor', 0) == 0 ? 'display:none;' : ''}}">
            <span class="h3 pull-left margin-top-10">If yes, are they insured/have workman's comp?</span>
            <div class="row pull-left margin-left-20">
                <div class="col-xs-6">
                    <div class="radio margin-top-10">
                        <label>
                            <input type="radio" name="is_outside_insured" value="1" {{Input::old('is_outside_insured', 0) == 1 ? 'checked' : ''}}>
                            Yes
                        </label>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="radio margin-top-10">
                        <label>
                            <input type="radio" name="is_outside_insured" value="0" {{Input::old('is_outside_insured', 0) == 0 ? 'checked' : ''}}>
                            No
                        </label>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <hr>

        <div>
            <span class="h3 pull-left margin-top-10">Are you accredited with the Better Business Bureau?</span>
            <div class="row pull-left margin-left-20">
                <div class="col-xs-6">
                    <div class="radio margin-top-10">
                        <label>
                            <input type="radio" name="is_bbb_accredited" value="1" {{Input::old('is_bbb_accredited', 0) == 1 ? 'checked' : ''}}>
                            Yes
                        </label>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="radio margin-top-10">
                        <label>
                            <input type="radio" name="is_bbb_accredited" value="0" {{Input::old('is_bbb_accredited', 0) == 0 ? 'checked' : ''}}>
                            No
                        </label>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <hr>

        <div>
            <span class="h3 pull-left margin-top-10">Do you run background check on all employees and subcontractors?</span>
            <div class="row pull-left margin-left-20">
                <div class="col-xs-6">
                    <div class="radio margin-top-10">
                        <label>
                            <input type="radio" name="does_background_checks" value="1" {{Input::old('does_background_checks', 1) == 1 ? 'checked' : ''}}>
                            Yes
                        </label>
                    </div>
                </div>
                <div class="col-xs-6">
                    <div class="radio margin-top-10">
                        <label>
                            <input type="radio" name="does_background_checks" value="0" {{Input::old('does_background_checks', 1) == 0 ? 'checked' : ''}}>
                            No
                        </label>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div id="divExplaination" class="{{$errors->has('background_explaination') ? 'has-error' : ''}}" style="{{Input::old('does_background_checks', 1) == 1 ? 'display:none;' : ''}}">
            <span class="h3 hblock control-label">If no, please explain.</span>
            <textarea class="form-control margin-top-10" rows="3" name="background_explaination">{{Input::old('background_explaination')}}</textarea>
        </div>
        <hr>
        <span class="h3 hblock">Please list any additional certification</span>
        <textarea class="form-control margin-top-10" rows="3" name="additional_info">{{Input::old('additional_info')}}</textarea>
        <div class="row margin-top-20">
            <div class="col-xs-6">
                <a href="{{URL::abs('/')}}/homeimprovement/get-certified2?application_id={{Session::has('application_id') ? Session::get('application_id') : Input::old('application_id')}}" class="btn btn-grey pull-left"><span class="glyphicon glyphicon-chevron-left"></span> Back to Step 2</a>
            </div>
            <div class="col-xs-6">
                <button class="btn btn-green pull-right" type="submit">SUBMIT <span class="glyphicon glyphicon-chevron-right"></span></button>
            </div>
        </div>
    </form>
</div>


@stop