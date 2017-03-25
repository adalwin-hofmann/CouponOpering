@extends('master.templates.master')
<?php
$geoip = json_decode(GeoIp::getGeoIp('json'));
?>
@stop
@section('page-title')
<h1>Cities</h1>
@stop
@section('sidebar')
	<div class="panel panel-default">
	    <div class="panel-heading">
	      <span class="h4 hblock panel-title">
	        <a data-toggle="collapse" href="#collapseOne">Explore {{$type == 'coupon' ? 'Coupons' : ($type == 'dailydeal' ? 'Daily Deals' : 'Contests')}} <span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span></a>
	        <div class="clearfix"></div>
	      </span>
	    </div>
	    <div id="collapseOne" class="panel-collapse">
		    <div class="panel-body explore-links">
		      	<ul>
                    @include('master.templates.explore', array('active' => 'all', 'type' => 'coupon'))
				</ul>
		    </div>
	    </div>
	</div>
@stop
@section('body')
<div class="content-bg">
<?php foreach ($states as $state) { ?>
	<h2>{{$state}}</h2>
	<ul>
	<?php foreach ($cities as $city) { ?>
		@if($city->state == $state)
		<li><a href="{{URL::abs('/')}}/city/{{strtolower($city->state)}}/{{$city->name}}">{{$city->city}}, {{$city->state}}</a></li>
		@endif
	<?php } ?>
	</ul>
	<hr>
<?php } ?>
</div>
@stop