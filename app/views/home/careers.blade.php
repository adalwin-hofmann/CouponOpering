@extends('master.templates.master')
@section('page-title')
<h1>Careers</h1>
@stop

@section('breadcrumbs')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}" itemprop="url"><span itemprop="title">Home</span></a>
    </li>
    <li class="active">Careers</li>
@stop

@section('sidebar')
@include('master.templates.corporatesidebar')
@stop

@section('body')
<div class="content-bg">
	<script id="gnewtonjs" type="text/javascript" src="//newton.newtonsoftware.com/career/iframe.action?clientId=8a699b9842d2671c0142e3cf44380c6e"></script>
    <!--<iframe id="gnewtonIframe" name="gnewtonIframe" 
width="100%" height="500px" frameBorder="0" scrolling ="auto" allowTransparency="true" 
src="http://newton.newtonsoftware.com/career/CareerHome.action?clientId=8a699b9842d2671c0142e3cf44380c6e&gnewtonResize=http://www.saveoneverything.com/corporate/GnewtonResize">Sorry, your browser doesn't seem to support Iframes!</iframe-->
</div>
@stop
