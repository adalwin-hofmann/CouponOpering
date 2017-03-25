@extends('master.templates.master')
@section('page-title')
<h1>The Coupons You Love, Anywhere!</h1>
@stop
@section('breadcrumbs')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}" itemprop="url"><span itemprop="title">Home</span></a>
    </li>
    <li class="active">Take Your Coupons with You</li>
@stop
@section('sidebar')
<div class="panel panel-default">
    <div class="panel-heading">
      <span class="h4 hblock panel-title">
        <a data-toggle="collapse" href="#collapseOne">Explore {{!isset($type) || $type == 'coupon' ? 'Coupons' : ($type == 'dailydeal' ? 'Daily Deals' : 'Contests')}} <span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span></a>
        <div class="clearfix"></div>
      </span>
    </div>
    <div id="collapseOne" class="panel-collapse collapse in">
	    <div class="panel-body explore-links">
	      	<ul>
                 @include('master.templates.explore', array('active' => 'all', 'type' => 'coupon'))
			</ul>
	    </div>
    </div>
</div>
@stop
@section('body')
<div class="content-bg relative">
	<div class="row margin-bottom-20">
        <div class="col-sm-4">
            <h2>1. Save A Coupon</h2>
            <p>Open a coupon you want to save for later and click on &quot;Save It&quot;</p>
            <p><img class="img-responsive" src="/img/desktop-mobile-step1.png" alt="Step 1"></p>
        </div>
        <div class="col-sm-4">
            <h2>2. Sign In</h2>
            <p>Grab your smartphone, go to SaveOn.Com, and sign in.</p>
            <p><img class="img-responsive" src="/img/desktop-mobile-step2.png" alt="Step 2"></p>
        </div>
        <div class="col-sm-4">
            <h2>3. Take Your Coupons with You</h2>
            <p>Once signed in, you can easily access all of your saved coupons.</p>
            <p><img class="img-responsive" src="/img/desktop-mobile-step3.png" alt="Step 3"></p>
        </div>
    </div>
    <h2>Note:</h2>
    <p>You must be a member to save coupons. Not a member? <a class="mobile-banner" href="#" data-toggle="modal" data-target="#signUpModal">Sign Up for Free!</a></p>
</div>
@stop