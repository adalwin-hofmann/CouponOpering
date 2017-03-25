@extends('master.templates.master')
@section('page-title')
<h1>My Contest Entries</h1>
@stop

@section('breadcrumbs')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}" itemprop="url"><span itemprop="title">Home</span></a>
    </li>
    <li class="active">My Contest Entries</li>
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
<div class="panel panel-default">
    <div class="panel-heading">
      <span class="h4 hblock panel-title">
        <a data-toggle="collapse" href="#collapseTwo">My Stuff <span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span></a>
        <div class="clearfix"></div>
      </span>
    </div>
    <div id="collapseTwo" class="panel-collapse collapse in">
	    <div class="panel-body explore-links">
	      	<ul>
				<li><a href="{{URL::abs('/')}}/members/dashboard">Dashboard</a></li>
				<li><a href="{{URL::abs('/')}}/members/mycoupons">My Coupons</li>
				<!--<li><a href="{{URL::abs('/')}}/members/mysavetodays">My Save Todays</a></li>-->
				<li>My Contest Entries</li>
				<li><a href="{{URL::abs('/')}}/members/myfavoritemerchants">My Favorite Merchants</a></li>
				<li><a href="{{URL::abs('/')}}/members/mysettings">My Account Settings</a></li>
			</ul>
	    </div>
    </div>
</div>
@include('master.templates.sidebar-offers')
<div class="ad"><div class="" style="background-color:#CCCCCC; width: 100%; min-height: 100px"><p>Advertising</p></div></div>

@stop

@section('body')
<div class = "content-bg">

<div class="text-divider1"></div>
<h2 class="margin-bottom-20">RECOMMENDED FOR YOU</h2>
<div id="containerOpen" class= "js-masonry offer-results-grid">
    
</div> 
<div class="clearfix"></div>
<div class="text-divider1"></div>
<h2 class="margin-bottom-20">ENTERED</h2>
	<div id="containerEntered" class= "js-masonry offer-results-grid">
		<?php foreach ($enteredContests['objects'] as $enteredContest) { ?>
		<div class="item contest btn-get-contest">
	        <div class="top-pic">
	            <a>
	                <div class="item-type contest"></div>
	                <img alt="" class="img-responsive" src="{{$enteredContest->path}}">
	            </a>
	            <button class="btn btn-default btn-block btn-get-contest">ALREADY ENTERED</button>
	        </div>
	    </div>
		<?php } ?>
	</div>

<div class="clearfix"></div>

<div class="text-divider1"></div>
<h2 class="margin-bottom-20">ENDED</h2>
	<div id="containerExpired" class= "js-masonry offer-results-grid">
		<p class="ajax-loader"><img src="/img/loader-transparent.gif" alt=""></p>
	</div>
	<div class="clearfix"></div>
	<p><a href="{{URL::abs('/')}}/winnerscircle" class="btn btn-red">See All Contest Winners</a></p>

<div class="clearfix"></div>

</div>
<script>
    category_id = '0';
    type = 'contest';
    page = 0;
</script>
@stop