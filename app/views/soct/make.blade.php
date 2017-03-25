@extends('master.templates.master')

@section('city-banner')
    @include('soct.templates.soct-banner')
@stop

@section('page-title')
<h1>SaveOn {{$make->name}} Cars</h1>
@stop

@section('breadcrumbs')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}" itemprop="url"><span itemprop="title">Home</span></a>
    </li>
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}/cars" itemprop="url"><span itemprop="title">Cars &amp; Trucks</span></a>
    </li>
    <li class="active">{{$make->name}}</li>
@stop

@section('sidebar')
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
<?php
$geoip = json_decode(GeoIp::getGeoIp('json'));
$features = App::make('FeatureRepositoryInterface');
$full_detroit_only = $features->findByName('full_soct_detroit_only');
$full_detroit_only = empty($full_detroit_only) ? 120 : $full_detroit_only->value;
if($full_detroit_only)
{
    $distance = GeometryHelper::getDistance($geoip->latitude, $geoip->longitude, 42.38, -83.10);
    $full_detroit_only = ($distance > $full_detroit_only || $geoip->region_name != 'MI') ? 1 : 0;
}
?>

<div class="visible-xs">
    @include('soct.templates.search-vehicles-mobile')
</div>

<script>
    currentNewPage = 0;
    currentUsedPage = 0;
    make_id = '{{$make->id}}';
</script>

<div class="content-bg">
    @if($featuredCar)
	<div class="row">
		<div class="col-sm-5">
			<img class="img-responsive" src="{{$featuredCar->path}}">
		</div>
		<div class="col-sm-7">
			<h2>About {{$featuredCar->year}} {{$featuredCar->make_name}} {{$featuredCar->model_name}}</h2>
			<p>{{$featuredCar->about}}
			<br><a href="{{URL::abs('/')}}/cars/research/{{$featuredCar->year}}/{{($featuredCar->make_slug)?$featuredCar->make_slug:$featuredCar->make_id}}/{{($featuredCar->model_slug)?$featuredCar->model_slug:$featuredCar->model_id}}/{{$featuredCar->id}}">Read More</a></p>
		</div>
	</div>
    @endif
</div>
@if(isset($full_detroit_only) && !$full_detroit_only)
<div class="row margin-top-20 hidden-xs" id="tabs">
	<div class="col-xs-6">
		<a class="btn btn-white btn-block spaced btnNew" href="#new_models" data-toggle="tab">CURRENT MODELS</a>
	</div>
	<div class="col-xs-6">
		<a class="btn btn-white btn-block spaced btnOld" href="#older_models" data-toggle="tab">OLDER MODELS</a>
	</div>
</div>

<div class="dropdown visible-xs mobile-menu margin-top-20">
	<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown">
    	Menu <span class="caret"></span>
  	</button>
  	<ul class="dropdown-menu model-menu">
    	<li class="active"><a href="#new_models" data-toggle="tab">Current Models</a></li>
  		<li><a href="#older_models" data-toggle="tab">Older Models</a></li>
</div>
@endif
<br>
<div class="tab-content">
	<div class="tab-pane active new-car" id="new_models">
	  	<div id="new-cars" class="js-masonry soct-masonry">
			<p class="ajax-loader"><img src="/img/loader-transparent.gif"></p>
		</div>
		<div class="clearfix"></div>
	    <div class="">
	        <button class="btn btn-block btn-lg btn-grey view-more center-block view-more-new" data-loading-text="Loading...">View More</button>
	    </div>
	    <div class="content-bg no-results new hidden">
		    <p class="spaced"><strong>Oh Fiddle Sticks, There Aren't Any New {{$make->name}} Vehicles...</strong></p>
		</div>
	</div>

	<div class="tab-pane" id="older_models">
	  	<div id="used-cars" class="js-masonry soct-masonry">

		</div>
		<div class="clearfix"></div>
	    <div class="">
	        <button class="btn btn-block btn-lg btn-grey view-more center-block view-more-used" data-loading-text="Loading...">View More</button>
	    </div>
	    <div class="content-bg no-results used hidden">
		    <p class="spaced"><strong>Oh Fiddle Sticks, There Aren't Any Used {{$make->name}} Vehicles...</strong></p>
		</div>
	</div>
</div>
@stop

