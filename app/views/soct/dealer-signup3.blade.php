@extends('master.templates.master', array('width'=>'full', 'hideSubmenu'=>'true'))

@section('page-title')
<h1>Cars &amp; Trucks <small>Become a Dealer</small></h1>
@stop
@section('body')

<div class="row margin-top-20">
    <div class="col-xs-12">
        <div class="btn-group steps">
          <button type="button" class="disabled spaced btn btn-default"><strong>1.</strong> <span class="hidden-xs">Create Your Account</span> <div class="forward-arrow"></div></button>
          <button type="button" class="disabled spaced btn btn-default"><strong>2.</strong> <span class="hidden-xs">Driven Local Leads</span> <div class="forward-arrow"></div></button>
          <button type="button" class="disabled spaced active btn btn-default"><strong>3.</strong> <span class="hidden-xs">Next Steps</span> <div class="forward-arrow"></div></button>
        </div>
    </div>
</div>

<div class="content-bg margin-top-20">
    <h2>Thank you!</h2>
    <h3 class="red spaced">The Next Steps</h3>
    <p>Please expect an email from us in the next 2 - 3 business days with more information regarding the start of your SaveOn Cars &amp; Trucks dealership experience. Once again, thank you for signing up to become a part of the SaveOn community!</p>
</div>


@stop