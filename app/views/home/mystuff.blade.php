@extends('master.templates.master')
@section('page-title')
<h1>My Stuff</h1>
@stop

@section('breadcrumbs')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}" itemprop="url"><span itemprop="title">Home</span></a>
    </li>
    <li class="active">My Stuff</li>
@stop

@section('sidebar')

<div class="panel panel-default explore-sidebar">
	    <div class="panel-heading">
	      <span class="h4 panel-title">
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

	<p>From your saved coupons to your settings, quickly access all of your stuff below.</p>

	<hr>

	<ul class="check_list my-stuff-list">
        <li><a href="{{URL::abs('/')}}/members/dashboard">Dashboard</a> - Get offer recommendations just for you.</li>
        <li><a href="{{URL::abs('/')}}/members/mycoupons/">My Saved Coupons</a> - Find the coupons that you have saved.</li>
       	<!--<li><a href="{{URL::abs('/')}}/members/mysavetodays">My Save Todays</a> - Find the deals that you have saved before they run out.</li>-->
        <li><a href="{{URL::abs('/')}}/members/mycontestentries">My Contest Entries</a> - Check out your contest that you've entered and see if you've won. </li>
        <li><a href="{{URL::abs('/')}}/members/myfavoritemerchants">My Favorite Merchants</a> - Easily check out your favorite merchants.</li>
        <?php $soct_redirect = Feature::findByName('soct_redirect'); ?>
        @if((!empty($soct_redirect)) && (Feature::findByName('soct_redirect')->value == 1))
        <li><a href="{{URL::abs('/')}}/members/mycars">My Cars</a> - Check out your favorite cars.</li>
        @endif
        <li><a href="{{URL::abs('/')}}/members/mysettings">My Account Settings</a>
	        <ul>
	            <li><a href="{{URL::abs('/')}}/members/myinterests">My Interests</a></li>
	            <li><a href="{{URL::abs('/')}}/members/mylocations">My Favorite Locations</a></li>
	            <li><a href="{{URL::abs('/')}}/members/mynotifications">My Notifications</a></li>
	        </ul>
        </li>
    </ul>

</div>

@stop