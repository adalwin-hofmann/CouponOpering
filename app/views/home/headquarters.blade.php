@extends('master.templates.master')
@section('page-title')
<h1>Headquarters <small> Troy, Michigan</small></h1>
@stop

@section('breadcrumbs')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}" itemprop="url"><span itemprop="title">Home</span></a>
    </li>
    <li class="active">Headquarters</li>
@stop

@section('sidebar')
@include('master.templates.corporatesidebar')
@stop

@section('body')
<div class = "content-bg">
    <p class="spaced"><strong>1000 West Maple Road, Troy MI 48084</strong></p>
    <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/SAVE-building.jpg">
</div>
@stop

