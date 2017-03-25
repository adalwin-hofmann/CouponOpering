@extends('master.templates.master')

@section('city-banner')
    @include('soct.templates.soct-banner')
@stop

@section('page-title')
    <h1>SaveOn Used Cars <br class="visible-xs"><small>in <span class="current-location-city">{{ucwords(SoeHelper::unSlug($city))}}</span>, <span class="current-location-state">{{strtoupper($state)}}</span></small></h1>
    <button class="btn btn-green btn-sm search-nearby-modal" data-toggle="modal" data-target="#changeLocationModal"><span class="glyphicon glyphicon-map-marker"></span> <img alt="Edit Location" src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/edit-location-text.png"> <span class="glyphicon glyphicon-chevron-right"></span></button>
@stop

@section('breadcrumbs')
    @include('soct.templates.breadcrumbs')
@stop

@section('sidebar')

@if(count($premium['objects']))
@include('master.templates.advertisement', array('advertisement' => $premium['objects'][0]))
@endif
@include('soct.templates.quote')
<div class="hidden-xs">
@include('soct.templates.search-vehicles')
</div>
@include('soct.templates.used-makes')
@include('soct.templates.explore')
@include('soct.templates.questions')
@include('soct.templates.become-dealer')

@stop
@section('body')
<script>
    currentPage = 0;
    year = '{{$search_year}}';
    make = '{{$search_make}}';
    model = '{{$search_model}}';
    vehicleCount = {{count($vehicles)}};
</script>

<div class="visible-xs">
    @include('soct.templates.search-vehicles-mobile')
</div>

<div class="banner-offer">

</div>

<div class="banner-vehicle">
    @if(!empty($featuredVehicle) && isset($featuredVehicle))
    @include('soct.templates.featured-vehicle', array('vehicle' => $featuredVehicle))
    @endif
</div>

<div class="featured-dealer">
    @if(isset($featuredDealer) && count($featuredDealer['objects']))
    @include('soct.templates.featured-dealers', array('featured' => $featuredDealer['objects'][0]))
    @endif
</div>

<div class="clearfix"></div>

@if($search_make != 'all')
<div class="row">
    <div class="col-sm-push-5 col-sm-7 col-md-push-4 col-md-8 {{($search_make == 'all')?'hidden':''}}">
        <p class="margin-top-5"><span class=""><strong>Cars Under:</strong></span> 
        <a href="{{URL::abs('/')}}/cars/used/{{strtolower($state)}}/{{strtolower($city)}}/{{$search_make}}/50000{{$params ? '?'.$params : ''}}">$50,000</a> | 
        <a href="{{URL::abs('/')}}/cars/used/{{strtolower($state)}}/{{strtolower($city)}}/{{$search_make}}/45000{{$params ? '?'.$params : ''}}">$45,000</a> | 
        <a href="{{URL::abs('/')}}/cars/used/{{strtolower($state)}}/{{strtolower($city)}}/{{$search_make}}/40000{{$params ? '?'.$params : ''}}">$40,000</a> | 
        <a href="{{URL::abs('/')}}/cars/used/{{strtolower($state)}}/{{strtolower($city)}}/{{$search_make}}/35000{{$params ? '?'.$params : ''}}">$35,000</a> | 
        <a href="{{URL::abs('/')}}/cars/used/{{strtolower($state)}}/{{strtolower($city)}}/{{$search_make}}/30000{{$params ? '?'.$params : ''}}">$30,000</a> | 
        <a href="{{URL::abs('/')}}/cars/used/{{strtolower($state)}}/{{strtolower($city)}}/{{$search_make}}/25000{{$params ? '?'.$params : ''}}">$25,000</a> | 
        <a href="{{URL::abs('/')}}/cars/used/{{strtolower($state)}}/{{strtolower($city)}}/{{$search_make}}/20000{{$params ? '?'.$params : ''}}">$20,000</a>
        </p>
    </div>
    <div class="col-sm-pull-7 col-sm-5 col-md-pull-8 col-md-4 margin-bottom-10 view-change">
        @if(count($vehicles))
    	<a type="button" class="btn btn-large spaced btn-white" href="#listView" data-toggle="tab"><span class="glyphicon glyphicon-th-list"></span> List</a>
    	<a type="button" class="btn btn-large spaced btn-green" href="#container" data-toggle="tab"><span class="glyphicon glyphicon-th"></span> Grid</a>
        @endif
    </div>
    
</div>
@else
<div class="view-change content-bg margin-bottom-20">
    <a type="button" class="btn btn-large spaced btn-green-border tab-toggle list-view" href="#listView" data-toggle="tab"><span class="glyphicon glyphicon-th-list"></span> List</a>
    <a type="button" class="btn btn-large spaced btn-green tab-toggle grid-view" href="#gridView" data-toggle="tab"><span class="glyphicon glyphicon-th"></span> Grid</a>
    <a type="button" class="btn btn-large spaced btn-green-border tab-toggle map-view" href="#mapView" data-toggle="tab"><span class="glyphicon glyphicon-globe"></span> Map</a>
</div>
@endif

<div class="tab-content">
	<div id="listView" class="tab-pane content-bg">
        <div class="offer-results-list">
            @foreach($vehicles as $vehicle)
                @include('soct.templates.list-vehicle-entity', array('vehicle'=>$vehicle))
            @endforeach
        </div>
        <div class="margin-top-20">
            <button class="btn btn-block btn-lg btn-grey view-more center-block" data-loading-text="Loading...">View More</button>
        </div>
	</div>
  	<div id="gridView" class="tab-pane active">
        <div id="container" class="js-masonry soct-masonry offer-results-grid" style="position: relative;">
    		@foreach($vehicles as $vehicle)
                @include('soct.templates.grid-vehicle-entity', array('vehicle'=>$vehicle))
            @endforeach
        </div>
        <div class="clearfix"></div>
        @if(count($vehicles) >= 12)
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
            <a href="{{ URL::abs('/cars/used/') }}" class="btn btn-block btn-lg btn-grey view-more center-block" data-loading-text="Loading...">See Used Cars</a>
        </div>
    </div>
</div>
@if(!count($vehicles))
<div class="content-bg no-results">
    <p class="spaced"><strong>Oh Fiddle Sticks, We Have Couldn't Find that Car...</strong></p>
    <p>Please try searching again with a different year, make, or model.</p>
    <p class="related-text">Did you mean to search for one of these?</p>
    <div id="containerRelated" class="js-masonry soct-masonry">
        <p class="ajax-loader"><img alt="Loading..." src="/img/loader-transparent.gif"></p>
        <div class="clearfix"></div>
    </div>
</div>
@endif
@stop

