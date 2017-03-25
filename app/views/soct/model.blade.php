@extends('master.templates.master')

@section('city-banner')
    @include('soct.templates.soct-banner')
@stop

@section('page-title')
<h1>SaveOn {{$model->make_name}} {{$model->name}}</h1>
@stop

@section('breadcrumbs')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}" itemprop="url"><span itemprop="title">Home</span></a>
    </li>
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}/cars" itemprop="url"><span itemprop="title">Cars &amp; Trucks</span></a>
    </li>
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}/cars/research/{{($model->make_slug)?$model->make_slug:$model->make_id}}" itemprop="url"><span itemprop="title">{{$model->make_name}}</span></a>
    </li>
    <li class="active">{{$model->name}}</li>
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

<div class="visible-xs">
    @include('soct.templates.search-vehicles-mobile')
</div>

<div class="content-bg">
    @if($featuredCar)
	<div class="row">
		<div class="col-sm-5">
			<img class="img-responsive" src="{{$featuredCar->path}}">
		</div>
		<div class="col-sm-7">
			<h2 class="margin-bottom-10">About {{$featuredCar->year}} {{$featuredCar->make_name}} {{$featuredCar->model_name}}</h2>
			<?php foreach ($featuredCar->vehicleStyles as $style) { ?>
				<p><a href="{{URL::abs('/')}}/cars/research/{{$style->year}}/{{($style->make_slug)?$style->make_slug:$style->make_id}}/{{($style->model_slug)?$style->model_slug:$style->model_id}}/{{$style->id}}">{{$style->name}}</a></p>
			<?php } ?>
		</div>
	</div>
    @endif
</div>
<?php
	$arrayCount = 0;
	foreach ($model_years as $model_year) {
		$arrayCount++;
		if ($arrayCount == 1) {
			continue;
		}
?>
	<div class="content-bg margin-top-20">
		@if ($arrayCount == 2)
		<h2>Other Model Years &amp; Trims</h2>
		<hr>
		@endif
		<div class="row">
			<div class="col-md-3 col-sm-4">
				<img class="img-responsive" src="{{$model_year->path}}">
			</div>
			<div class="col-md-9 col-sm-8">
				<h3 class="margin-bottom-10">{{$model_year->year}} {{$model_year->make_name}} {{$model_year->model_name}}</h3>
				<?php foreach ($model_year->vehicleStyles as $style) { ?>
					<p><a href="{{URL::abs('/')}}/cars/research/{{$style->year}}/{{($style->make_slug && $style->model_slug)?$style->make_slug.'/'.$style->model_slug.'/':''}}{{$style->id}}">{{$style->name}}</a></p>
				<?php } ?>
			</div>
		</div>
	</div>
<?php
	} 
?>
@stop

