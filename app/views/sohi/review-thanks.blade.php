@extends('master.templates.master', array('width'=>'full', 'hideSubmenu'=>'true'))

@section('page-title')
<h1>Home Improvement: <small>Merchant Review</small></h1>
@stop
@section('body')
<div id="review-thankyou" class="content-bg margin-top-20">
    <span class="h1">Thank you!</span>
    <p class="margin-top-20">We appreciate your feedback!<br>
    In an effort to ensure SaveOn members receive the best service possible, we take your reviews seriously! <br/>
    Please let us know if you have any additional feedback.
    <br><br>
    Thanks!
    <br><br>
    <strong>The SaveOn Team</strong></p>
    <br>
    <a class="btn btn-green" href="{{URL::abs('/')}}/homeimprovement">BACK TO HOME IMPROVEMENT <span class="glyphicon glyphicon-chevron-right"></span></a>
</div>

@stop