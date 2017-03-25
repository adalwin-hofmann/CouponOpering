@extends('master.templates.master')
@section('page-title')
<h1>My Cruises</h1>
@stop

@section('breadcrumbs')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}" itemprop="url"><span itemprop="title">Home</span></a>
    </li>
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}/members/mysettings" itemprop="url"><span itemprop="title">Account Settings</span></a>
    </li>
    <li class="active">My Cruises</li>
@stop

@section('sidebar')
<div class="panel panel-default explore-sidebar">
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
	@include('master.templates.member-sidebar')
	@include('master.templates.sidebar-offers')

@stop

@section('body')
<div class="content-bg">	
    <div class="offer-results-list">
        <div class="item list coupon " itemscope="" itemtype="http://schema.org/Organization">
            <div class="row margin-bottom-10 margin-top-10">
                <div class="col-xs-5 col-sm-3">
                    <div class="list-img">
                        <img alt="" class="img-responsive center-block" src="http://cruise.expedia.com/pictures/db/Ship/2452.jpg" itemprop="image">
                    </div>
                </div>
                <div class="col-xs-7 col-sm-9" style="color:#444">
                    <div class="h3" style="margin-top: 0">
                        7-night Western Caribbean Cruise from Miami (Roundtrip)
                    </div>
                    <p>Purchased for: <strong>$800</strong></p>
                    <p>Referrals Sent: <strong>3</strong> <a href="#">Send more referrals</a></p>
                    <p>Referrals Purchased: <strong>2</strong></p>
                    <div class="h3">Your Cost: <span class="green">$600</span></div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop


