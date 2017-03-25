@extends('master.templates.master')

@section('city-banner')
    @include('soct.templates.soct-banner')
@stop

@section('page-title')
<h1>SaveOn New Cars <br class="visible-xs"><small><span class="current-location-city">{{ucwords(strtolower($geoip->city_name))}}</span>, <span class="current-location-state">{{$geoip->region_name}}</span></small></h1>
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
@include('soct.templates.makes')
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

<div class="featured-dealer">
    @if(isset($featuredDealer) && count($featuredDealer['objects']))
    @include('soct.templates.featured-dealers', array('featured' => $featuredDealer['objects'][0]))
    @endif
</div>

<div class="view-change content-bg margin-bottom-20">
    <a type="button" class="btn btn-large spaced btn-green-border tab-toggle list-view" href="#listView" data-toggle="tab"><span class="glyphicon glyphicon-th-list"></span> List</a>
    <a type="button" class="btn btn-large spaced btn-green tab-toggle grid-view" href="#gridView" data-toggle="tab"><span class="glyphicon glyphicon-th"></span> Grid</a>
</div>
<div class="clearfix"></div>

<div class="tab-content">
    <div id="listView" class="tab-pane content-bg">
        <div class="offer-results-list">
            <p class="ajax-loader"><img alt="Loading..." src="/img/loader-transparent.gif" alt=""></p>
        </div>

        <div class="margin-top-20">
            <button class="btn btn-block btn-lg btn-grey view-more center-block" data-loading-text="Loading...">View More</button>
        </div>
    </div>
    <div id="gridView" class="tab-pane active">
	  	<div id="container" class="js-masonry soct-masonry offer-results-grid">
            @foreach($vehicles as $vehicle)
                @include('soct.templates.new-car', array('vehicle'=>$vehicle))
            @endforeach
		</div>
    	<div class="clearfix"></div>
        @if(count($vehicles) >= 12)
        <div class="">
            <button class="btn btn-block btn-lg btn-grey view-more-new view-more center-block" data-loading-text="Loading...">View More</button>
        </div>
        @endif
    </div>
</div>
@if(!count($vehicles))
<div class="content-bg no-results">
    <p class="spaced"><strong>Oh Fiddle Sticks, We Have Couldn't Find that Car...</strong></p>
    <p>Please try searching again with a different year, make, or model.</p>
    <p>Did you mean to search for one of these?</p>
    <div id="containerRelated" class="js-masonry soct-masonry">
        <p class="ajax-loader"><img alt="Loading..." src="/img/loader-transparent.gif"></p>
        <div class="clearfix"></div>
    </div>
    <div class-"clearfix"></div>
</div>
@endif
@stop

