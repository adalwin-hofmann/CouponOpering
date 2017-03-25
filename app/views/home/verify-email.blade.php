@extends('master.templates.master')
@section('page-title')
<h1>Email Verification</h1>
@stop
@section('sidebar')

<div class="panel panel-default">
    <div class="panel-heading">
      <span class="h4 hblock panel-title">
        <a data-toggle="collapse" href="#collapseOne" class="collapsed">Explore Coupons <span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span></a>
        <div class="clearfix"></div>
      </span>
    </div>
    <div id="collapseOne" class="panel-collapse collapse">
        <div class="panel-body explore-links">
            <ul>
                @include('master.templates.explore', array('active' => 'all', 'type' => 'coupon'))
            </ul>
        </div>
    </div>
</div>

@stop

@section('body')
<script>
    email = "{{$email}}";
</script>
<div class="content-bg">
    <div class="row">
        <div class="col-xs-12 verify-box">
            @if($valid)
                <span class="h1 fancy green">Welcome to SaveOn!</span>
                <p>Your email has been verified.</p>
                <p class="margin-top-20">Get started by going to your <a href="{{URL::abs('/')}}">member dashboard</a>. <br><br>
                    <strong>Have questions on how the site works?</strong><br>
                    Take the tour located at the bottom of your dashboard under the "Get In Touch" heading.
                <br><br>
                We hope you enjoy all the great ways to Save!
                <br><br>
                <strong>- The SaveOn<sup>&reg;</sup> Team</strong></p>
                <br>
                <a class="btn btn-green" href="{{URL::abs('/')}}">GO TO MY DASHBOARD <span class="glyphicon glyphicon-chevron-right"></span></a>
            @else
                <span class="h1 fancy green">Whoops! </span>
                </p>This email verification link is no longer valid!<br>
                Request a new one by clicking <a class="new-verify" href="#">here.</a></p>
                <br>
                <a class="new-verify btn btn-green" href="#">REQUEST NEW LINK<span class="glyphicon glyphicon-chevron-right"></span></a>
            @endif
        </div>
        <div class="col-xs-12 new-box" style="display:none;">
            <span class="h1 fancy green">Thank You!</span>
            <p>A new verification email has been sent. If you do not see it, please check your spam folder.</p>
        </div>
    </div>
</div>
@stop