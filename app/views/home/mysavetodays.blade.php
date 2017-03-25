@extends('master.templates.master')
@section('page-title')
<h1>My Save Todays</h1>
@stop

@section('breadcrumbs')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}" itemprop="url"><span itemprop="title">Home</span></a>
    </li>
    <li class="active">My Save Todays</li>
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


<div class="ad"><-- Insert Image Here --></div>

@stop

@section('body')
<script>
    page = 0;
</script>
<div>

	<script type='text/ejs' id='template_banner'> 
	<% list(banner, function(banner)
	{ %>
		<a merchant="<%= banner.merchant_name %>" data-bannerid="<%= banner.id %>" href="#" nav="<%= banner.banner_link != '' ? banner.banner_link : '/coupons/'+banner.merchant_slug+'/'+banner.merchant_id+'/coupon' %>">
			<img class="img-responsive center-block" src="<%= banner.path %>">
		</a>
	<% }); %>
	</script>

		<div id="banner">
		</div>
		<div id="container" class="js-masonry offer-results-grid">
			<p class="ajax-loader"><img src="/img/loader-transparent.gif" alt=""></p>
		</div>

		<div class="">
			<button class="btn btn-block btn-lg btn-green view-more center-block" data-loading-text="Loading...">View More</button>
		</div>

		<div class="no-saved-offers">
			<p class="spaced"><strong>You currently have 0 saved offers!</strong></p>
			<p>Find some offers you may like <a href="{{URL::abs('/')}}">here</a>.</p>
		</div>
</div>
@stop