@extends('master.templates.master')
@section('page-title')
<h1>The Coupons You Love, Anywhere!</h1>
@stop
@section('breadcrumbs')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}" itemprop="url"><span itemprop="title">Home</span></a>
    </li>
    <li class="active">SaveOn<sup>&reg;</sup> is Now On Your Phone</li>
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
            <h2>1. Become a Member</h2>
            <p>Becoming a member is quick and easy. Once a member, you gain access to contests, save coupons, and so much more!</p>
            <p><img class="img-responsive" src="/img/mobile_banner_large1.png" alt="Step 1"></p>
        </div>
        <div class="col-sm-4">
            <h2>2. Choose</h2>
            <p>Sort by category or brand to find all the money-saving coupons that you want on your mobile device. Simply choose a coupon to view more details.</p>
            <p><img class="img-responsive" src="/img/mobile_banner_large2.png" alt="Step 2"></p>
        </div>
        <div class="col-sm-4">
            <h2>3. Redeem</h2>
            <p>After you have chosen the coupon you'd like to use, present it to a staff member at one of our participating locations. Upon viewing your offer, this staff member will hit the &quot;Redeem&quot; button, granting you access to your coupon's great savings!</p>
            <p><img class="img-responsive" src="/img/mobile_banner_large3.png" alt="Step 3"></p>
        </div>
    </div>
    <h2>Note:</h2>
    <p>Once your coupon has been redeemed, you will not longer be able to use it again. Don't worry though, continue to search on SaveOn.com to find hundreds of FREE coupons to use on all your favorite businesses.</p>
</div>
@stop