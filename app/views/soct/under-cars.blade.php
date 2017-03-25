@extends('master.templates.master')

@section('city-banner')
    @include('soct.templates.soct-banner')
@stop

@section('page-title')
<h1>SaveOn Used Cars</h1>
@stop

@section('breadcrumbs')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}" itemprop="url"><span itemprop="title">Home</span></a>
    </li>
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/').'/cars'}}" itemprop="url"><span itemprop="title">Cars &amp; Trucks</span></a>
    </li>
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}/cars/used" itemprop="url"><span itemprop="title">Used Cars</span></a>
    </li>
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}/cars/used/under" itemprop="url"><span itemprop="title">Under</span></a>
    </li>
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}/cars/used/under/{{$price}}" itemprop="url"><span itemprop="title">${{number_format($price)}}</span></a>
    </li>
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}/cars/used/under/{{$price}}/{{$make->slug}}" itemprop="url"><span itemprop="title">{{$make->name}}</span></a>
    </li>
    @if(isset($city))
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}/cars/used/under/{{$price}}/{{$make->slug}}/{{$state}}">{{strtoupper($state)}}</a></li>
    <li class="active">{{ucwords(SoeHelper::unSlug($city))}}</li>
    @else
    <li class="active">{{strtoupper($state)}}</li>
    @endif
@stop

@section('sidebar')

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
</script>

<div class="visible-xs">
    @include('soct.templates.search-vehicles-mobile')
</div>

<div class="banner-offer">

</div>

<div class="clearfix"></div>

@if(!isset($city))
<div class="content-bg margin-bottom-15">
    <div class="row">
        <ul class="col-sm-3">
            <?php $i=0; ?>
            @foreach($cities['objects'] as $city)
            <li><a href="{{URL::abs('/')}}/cars/used/under/{{$price}}/{{$make->slug}}/{{strtolower($city->state)}}/{{SoeHelper::getSlug(strtolower($city->city))}}">{{ucwords(strtolower($city->city))}}</a></li>
            @if(++$i % ceil(count($cities['objects']) / 4) == 0)
            </ul>
            <ul class="col-sm-3">
            @endif
            @endforeach
        </ul>
    </div>
</div>
@endif

<div class="margin-bottom-10 view-change">
	<a type="button" class="btn btn-large spaced btn-white" href="#listView" data-toggle="tab"><span class="glyphicon glyphicon-th-list"></span> List</a>
	<a type="button" class="btn btn-large spaced btn-green" href="#container" data-toggle="tab"><span class="glyphicon glyphicon-th"></span> Grid</a>
</div>

<div class="tab-content">
	<div id="listView" class="tab-pane">
        @foreach($vehicles as $vehicle)
            @include('soct.templates.list-vehicle-entity', array('vehicle'=>$vehicle))
        @endforeach
	</div>
  	<div id="container" class="js-masonry soct-masonry tab-pane active">
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

<div class="content-bg hidden no-results">
    <p class="spaced"><strong>Oh Fiddle Sticks, We Have Couldn't Find that Car...</strong></p>
    <p>Please try searching again with a different year, make, or model.</p>
    <p>Did you mean to search for one of these?</p>
    <div id="containerRelated" class="js-masonry soct-masonry">
    </div>
</div>
@stop

