@extends('master.templates.master')
@section('page-title')
<h1>My Favorite Merchants</h1>
@stop

@section('breadcrumbs')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}" itemprop="url"><span itemprop="title">Home</span></a>
    </li>
    <li class="active">My Favorite Merchants</li>
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
@include('master.templates.member-sidebar')
@include('master.templates.sidebar-offers')
<div class="ad"><div class="" style="background-color:#CCCCCC; width: 100%; min-height: 100px"><p>Advertising</p></div></div>

@stop

@section('body')
<script>
    page = 0;
</script>
<script type="text/ejs" id="template_merchants">
<% list(locations, function(location)
{ %>
	<hr>
    <div class="row">
        <div class="col-md-2 col-sm-3"><img class = "img-responsive" src = "<%= location.logo %>"></div>
        <div class="col-md-8 col-sm-6">
	        <a href = "/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= location.favoritable.merchant.category.slug %>/<%= location.favoritable.merchant.subcategory.slug %>/<%= location.favoritable.merchant_slug %>/<%= location.favoritable.id %>">
	        <p class="merchant-name"><%= location.favoritable.merchant_name %></p>
	        <p class="offer-count"><strong><%= location.favoritable.offer_count %></strong> Offers</p>
	        </a>
	    </div>
        <div class="col-md-2 col-sm-3"><button class="btn btn-link btn-remove-favorite" data-location_id="<%= location.favoritable_id %>"><strong>Remove</strong></button></div>
    </div>
<% }); %>
</script>

<div class="content-bg">
	
	<p>Adding Merchants to your favorites list will help make sure you get offers that are interesting to you in your <a href="{{URL::abs('/')}}/members/dashboard">recommended list.</a></p>
	<div id="favoritesArea" class = "">
		<p class="text-center"><img src="/img/loader-transparent.gif" alt=""></p>
	</div>
<!--<br>
<br>
<br> <div class = "text-divider1"></div>
<br>
<span class="h3 hblock">Want to find a merchant?</span>
<div class = "row">
	<div class="col-sm-4 col-xs-12 col col-md-8 <?php if((!isset($subheadDropdown)) || (($subheadDropdown != 'true') && ($subheadDropdown != 'sort'))) ?>">
		<div class="input-group searchbar searchbar-bottom">
	      	<input type="text" class="form-control inptSearch" placeholder="Search by Keyword">
			
		    <div class="input-group-btn">
		        <button "btnFavoriteSearch" class="btn btn-green search" type="button">Go</button>
		    </div>
	    </div>
	</div>
</div>-->
</div>
@stop