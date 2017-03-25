@extends('master.templates.master')

@section('city-banner')
    @include('soct.templates.soct-banner')
@stop

@section('page-title')
<h1>SaveOn Service & Lease Specials</h1>
@stop

@section('breadcrumbs')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}" itemprop="url"><span itemprop="title">Home</span></a>
    </li>
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}/cars" itemprop="url"><span itemprop="title">Cars &amp; Trucks</span></a>
    </li>
    <li class="active">Service &amp; Lease Specials</li>
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
    subcategory_id = "{{$subcategory->id}}";
    category_id = "{{$subcategory->parent_id}}";
</script>

<div class="visible-xs">
    @include('soct.templates.search-vehicles-mobile')
</div>

<div class="featured-dealer">
    @if(isset($featuredDealer) && count($featuredDealer['objects']))
    @include('soct.templates.featured-dealers', array('featured' => $featuredDealer['objects'][0]))
    @endif
</div>

<div class="view-change content-bg margin-bottom-20">
    <a type="button" class="btn btn-large spaced btn-green-border tab-toggle list-view" href="#listView" data-toggle="tab"><span class="glyphicon glyphicon-th-list"></span> List</a>
    <a type="button" class="btn btn-large spaced btn-green tab-toggle grid-view" href="#gridView" data-toggle="tab"><span class="glyphicon glyphicon-th"></span> Grid</a>
    <a type="button" class="btn btn-large spaced btn-green-border tab-toggle map-view" href="#mapView" data-toggle="tab"><span class="glyphicon glyphicon-globe"></span> Map</a>
</div>

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
        <div id="container" class="js-masonry offer-results-grid">
    @foreach($entities as $entity)
	    @include('master.templates.entity', array('entity'=>$entity))
    @endforeach
       </div>
        <div class="clearfix"></div>
        <div class="">
        @if(count($entities) >= 12)
            <button class="btn btn-block btn-lg btn-grey view-more center-block" data-loading-text="Loading...">View More</button>
        @endif
        </div>
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

