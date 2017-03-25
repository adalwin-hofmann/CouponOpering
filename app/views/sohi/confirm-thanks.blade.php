@extends('master.templates.master', array('width'=>'full', 'hideSubmenu'=>'true'))

@section('page-title')
<h1>Home Improvement: <small>Get A Quote</small></h1>
@stop
@section('body')
<script>
    quote_type = "{{$quote->project_tag_slug}}";
    quote_id = "{{$quote->id}}";
</script>
<div class="row margin-top-20">
    <div class="col-xs-12">
        <div class="btn-group steps">
          <button type="button" class="disabled spaced btn btn-default"><strong>1.</strong> <span class="hidden-xs">Project Type</span> <div class="forward-arrow"></div></button>
          <button type="button" class="disabled spaced btn btn-default"><strong>2.</strong> <span class="hidden-xs">Project Brief</span> <div class="forward-arrow"></div></button>
          <button type="button" class="disabled spaced active btn btn-default"><strong>3.</strong> <span class="hidden-xs">Review &amp; Submit</span> <div class="forward-arrow"></div></button>
        </div>
    </div>
</div>

<div id="confirm-thankyou" class="content-bg margin-top-20">
    <span class="h1">Thank you!</span>
    <p class="margin-top-20">Your request has been successfully sent to our Save Certified Home Improvement Contractors.<br>
    They will contact you directly with more details and pricing information.
    <br><br>
    Good luck with your project!
    <br><br>
    <strong>The SaveOn Team</strong></p>
    <br>
    <a class="btn btn-green" href="{{URL::abs('/')}}/homeimprovement">BACK TO HOME IMPROVEMENT <span class="glyphicon glyphicon-chevron-right"></span></a>
</div>

@stop