@extends('master.templates.master')
@section('page-title')
<h1>Restaurants that Sponsor Eaton Rapids School District</h1>
@stop
@section('breadcrumbs')
	<!-- Needs to be updated -->
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}" itemprop="url"><span itemprop="title">Home</span></a>
    </li>
    <li class="active">Sponsor</li>
	<li class="active">{{$district->name}}</li>
@stop
@section('sidebar')
	<div class="panel panel-default explore-sidebar is_dealer_hide">
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
    @if(Auth::check())
	@include('master.templates.sidebar-offers')
    @endif
@stop
@section('body')
    <script type="text/javascript">
        district_slug = "{{$district_slug}}";
        page = 0;
        user_id = {{$user_id}};
    </script>
    @if($banner)
	<div class="merchant-header banner">
        <a href="/coupons/{{SoeHelper::getSlug($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/{{$banner->cat_slug}}/{{$banner->subcat_slug}}/{{$banner->slug}}">
    		<img src="{{$banner->sponsor_banner}}" class="img-responsive" alt="District Name">
        </a>
	</div>
    @endif
	<div class="view-change content-bg margin-bottom-20">
		<a type="button" class="btn btn-large spaced btn-green-border tab-toggle list-view" href="#listView" data-toggle="tab"><span class="glyphicon glyphicon-th-list"></span> List</a>
	    <a type="button" class="btn btn-large spaced btn-green tab-toggle grid-view" href="#gridView" data-toggle="tab"><span class="glyphicon glyphicon-th"></span> Grid</a>
	    <a type="button" class="btn btn-large spaced btn-green-border tab-toggle map-view" href="#mapView" data-toggle="tab"><span class="glyphicon glyphicon-globe"></span> Map</a>
	</div>
	<div class="clearfix"></div>
	<div class="tab-content">
		<div id="listView" class="tab-pane content-bg ">
			<div class="offer-results-list">
				<p class="ajax-loader"><img alt="Loading..." src="/img/loader-transparent.gif" alt=""></p>
			</div>

			<div class="margin-top-20">
				<button class="btn btn-block btn-lg btn-grey view-more center-block" data-loading-text="Loading...">View More</button>
			</div>
		</div>
		<div id="gridView" class="tab-pane active">
			<div id="container" class="js-masonry offer-results-grid">
		        @foreach($entities as $entity)
                    @include('master.templates.entity', array('entity'=>$entity))
                @endforeach
			</div>
			@if(count($entities) >= 12)
            <div class="clearfix"></div>
            <div class="">
                <button class="btn btn-block btn-lg btn-grey view-more center-block" data-loading-text="Loading...">View More</button>
            </div>
            @endif
		</div>
		<div id="mapView" class="tab-pane content-bg merchant-search">
			<div class="row margin-bottom-20">
				<div class="col-sm-8">
					<div id="map"></div>
				</div>
				<div class="col-sm-4 map-list">
					<div class="h3 spaced margin-top-0 margin-bottom-10 results-header">Results</div>
					<div class="merchant-result-map">
						<div class="offer-results-map">
							<p class="ajax-loader"><img alt="Loading..." src="/img/loader-transparent.gif" alt=""></p>
						</div>
						
					</div>
				</div>
			</div>

			<div class="clearfix"></div>

			<div>
				<button class="btn btn-lg btn-block btn-grey view-more center-block" data-loading-text="Loading...">View More</button>
			</div>
		</div>
	</div>
@stop