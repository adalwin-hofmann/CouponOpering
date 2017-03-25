@extends('master.templates.master')
@section('page-title')
<h1>Merchant Starter Kit Survey</h1>
@stop

@section('breadcrumbs')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}" itemprop="url"><span itemprop="title">Home</span></a>
    </li>
    <li class="active">Merchant Starter Kit Survey</li>
@stop

@section('sidebar')
<div class="panel panel-default">
    <div class="panel-heading">
      <span class="panel-title h4 hblock">
        <a data-toggle="collapse" href="#collapseOne">Local Coupons <span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span></a>
        <div class="clearfix"></div>
      </span>
    </div>
    <div id="collapseOne" class="panel-collapse collapse in">
	    <div class="panel-body explore-links">
	      	<ul>
                 @include('master.templates.explore', array('active' => 'all', 'type' => 'coupon'))
			</ul>
	    </div>
    </div>
</div>
@stop
@section('body')
<div class="content-bg relative">
	<iframe class="" id="" frameborder="0" height="930px" width="100%" name="" style="overflow: hidden; margin: 0px auto; padding: 0px; border:none; scroll:no; display:block;" src="https://saveoneverything.wufoo.com/forms/r1h510v60o598e2/"></iframe>
</div>
@stop