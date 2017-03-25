@extends('master.templates.master', array('width'=>'full', 'hideSubmenu'=>'true'))

@section('page-title')
<h1>Home Improvement: <small>Customer Survey</small></h1>
@stop
@section('body')
<div id="survey-thankyou" class="content-bg margin-top-20">
    <span class="h1">Thank you!</span>
    <p class="margin-top-20">We appreciate your feedback!<br>
    We want to ensure you have the best experience possible.  We'll be checking back in after a few weeks <br/>
    to see how things are going and give you a chance to review the contractor you are working with.
    <br><br>
    Good luck with your project!
    <br><br>
    <strong>The SaveOn Team</strong></p>
    <br>
    <a class="btn btn-green" href="{{URL::abs('/')}}/homeimprovement">BACK TO HOME IMPROVEMENT <span class="glyphicon glyphicon-chevron-right"></span></a>
</div>

@stop