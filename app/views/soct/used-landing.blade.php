@extends('master.templates.master')
<?php
$geoip = json_decode(GeoIp::getGeoIp('json'));
?>

@section('city-banner')
    @include('soct.templates.soct-banner')
@stop

@section('page-title')
<h1>SaveOn Used Cars</h1>
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

<div class="visible-xs">
    @include('soct.templates.search-vehicles-mobile')
</div>

<div class="content-bg">
    <p class="spaced pull-left margin-right-20">Select a State:</p>
    <ul class="list-inline pull-left">
        <li><a href="{{ URL::abs('/cars/used/mi') }}">Michigan</a></li>
        <li><a href="{{ URL::abs('/cars/used/il') }}">Illinois</a></li>
        <li><a href="{{ URL::abs('/cars/used/mn') }}">Minnesota</a></li>
    </ul>
    <div class="clearfix"></div>
    <hr class="margin-top-5 blue">
    <form>
        <div class="row">
            <div class="form-group col-xs-12 col-sm-6 margin-bottom-0">
                <select class="form-control" id="usedState">
                    <option value="all">Or Choose Other States</option>
                    <?php foreach ($states as $abbr => $name) { ?>
                        <option value="{{ URL::abs('/cars/used').'/'.strtolower($abbr) }}">{{ucwords(strtolower($name))}}</option>
                    <?php } ?>
                </select>
            </div>
        </div>
    </form>
</div>

<div class="content-bg margin-top-20">
	<div class="row">
		<div class="col-xs-12">
			<p class="spaced"><a href="{{ URL::abs('/cars/used/under') }}" class="btn btn-blue">Cars Under $15,000 <span class="glyphicon glyphicon-chevron-right"></span></a></p>
			<hr class="blue margin-top-0">
		</div>
	</div>
	<div class="">
		<div class="js-masonry soct-masonry masonry-single-line">
        @foreach($lows as $low)
			<!-- <div class="item used-car">
			  <div class="item-type used-car"></div>
			    <div class="top-pic btn-view-used-car" data-id="{{ $low->id }}">
			            <div class="expired-banner"><span class="h3 spaced">Expired</span></div>
			            <img alt="{{ $low->year }} {{ $low->make }} {{ $low->model }}" class="img-responsive" src="{{ $low->display_image }}" onerror="master_control.BrokenVehicleImage(this)">
			            <div class="special-info">
			              <p class="merchant-name"><a href="{{ URL::abs('/coupons/'.strtolower($low->state).'/'.SoeHelper::getSlug($low->city).'/auto-transportation/auto-dealers/'.SoeHelper::getSlug($low->dealer_name)) }}">{{ $low->dealer_name }}</a></p>
			              <p class="expires_at"><strong>Price:</strong> {{ $low->internet_price ? '$'.$low->internet_price : 'Call'}}</p>
			              <p class="expires_at"><strong>Mileage:</strong> {{ $low->mileage ? $low->mileage : 'Call'}}</p>
			            </div>
			    </div>
			    <div class="item-info btn-view-used-car" data-id="{{ $low->id }}">
			      <span class="h3">{{ $low->year }} {{ $low->make }} {{ $low->model }}</span>
			    </div>
			    <div class="btn-group">
			        <button class="btn btn-default btn-view-used-car" data-id="{{ $low->id }}"><img src="http://s3.amazonaws.com/saveoneverything_assets/images/masonry-icons/get_it_save_today.png" alt="View It" class="img-circle"><br>View It</button>
			        <button class="btn btn-default btn-used-car-quote" data-vehicle_id="{{ $low->id }}"><img src="http://s3.amazonaws.com/saveoneverything_assets/images/masonry-icons/quote_it_save_today.png" alt="Quote It" class="img-circle"><br>Quote It</button>
			        <button class="btn btn-default btn-used-share" data-vehicle_id="{{ $low->id }}"><img src="http://s3.amazonaws.com/saveoneverything_assets/images/masonry-icons/share_it_save_today.png" alt="Share It" class="img-circle"><br>Share It</button>
			    </div>
			</div> -->
            @include('soct.templates.grid-vehicle-entity', array('vehicle' => $low))
        @endforeach
        <div class="clearfix"></div>
		</div>
	</div>
</div>

<div class="content-bg margin-top-20">
	<div class="section-type featured"></div>
	<div class="row">
		<div class="col-md-4 used-cars-list">
			<p class="spaced"><a href="{{ URL::abs('/cars/used/'.strtolower($geoip->region_name).'/'.strtolower($geoip->city_name).'?mileage=50000')}}" class="btn btn-blue">Low Mileage Cars <span class="glyphicon glyphicon-chevron-right"></span></a></p>
			<hr class="margin-top-5 margin-bottom-5 blue">
    		@foreach($lowMiles as $lowMile)
            <div class="row used-car">
    			<div class="col-xs-4">
                    <div class="pointer btn-used-car-quote" data-vehicle_id="{{$lowMile->id}}">
                        <img src="{{ $lowMile->display_image }}" class="img-responsive" onerror="master_control.BrokenVehicleImage(this)">
                    </div>
    			</div>
    			<div class="col-xs-8">
                    @if($lowMile->dealer_name != 'WHOLESALE PARTNER')
    				<a href="{{ URL::abs('/coupons/'.strtolower($lowMile->state).'/'.SoeHelper::getSlug($lowMile->city).'/auto-transportation/auto-dealers/'.SoeHelper::getSlug($lowMile->dealer_name)).'?showeid='.$lowMile->id.'&eidtype=usedquote' }}" class="blue">
                    @else
                    <div class="pointer btn-used-car-quote blue" data-vehicle_id="{{$lowMile->id}}">
                    @endif
                        {{ $lowMile->year }} {{ $lowMile->make }} {{ $lowMile->model }}
                    @if($lowMile->dealer_name != 'WHOLESALE PARTNER')
                    </a>
                    @else
                    </div>
                    @endif
    				<p>Price: <strong>{{ $lowMile->internet_price ? '$'.$lowMile->internet_price : 'Call'}}</strong></p>
    				<p>Mileage: <strong>{{ $lowMile->mileage }}</strong></p>
    			</div>
    		</div>
			<hr class="margin-top-5 margin-bottom-5">
            @endforeach
            <div class="margin-bottom-20 visible-xs visible-sm"></div>
		</div>
		<div class="col-md-4 used-cars-list">
			<p class="spaced"><a href="{{ URL::abs('/cars/used/'.strtolower($geoip->region_name).'/'.strtolower($geoip->city_name).'/all/100000/convertible')}}" class="btn btn-blue">Convertibles <span class="glyphicon glyphicon-chevron-right"></span></a></p>
			<hr class="margin-top-5 margin-bottom-5 blue">
            @foreach($convertibles as $convertible)
    		<div class="row used-car">
    			<div class="col-xs-4">
                    <div class="pointer btn-used-car-quote" data-vehicle_id="{{$convertible->id}}">
                        <img src="{{ $convertible->display_image }}" class="img-responsive" onerror="master_control.BrokenVehicleImage(this)">
                    </div>
    			</div>
    			<div class="col-xs-8">
                    @if($convertible->dealer_name != 'WHOLESALE PARTNER')
    				<a href="{{ URL::abs('/coupons/'.strtolower($convertible->state).'/'.SoeHelper::getSlug($convertible->city).'/auto-transportation/auto-dealers/'.SoeHelper::getSlug($convertible->dealer_name)).'?showeid='.$convertible->id.'&eidtype=usedquote' }}" class="blue">
                    @else
                    <div class="pointer btn-used-car-quote blue" data-vehicle_id="{{$convertible->id}}">
                    @endif
                    {{ $convertible->year }} {{ $convertible->make }} {{ $convertible->model }}
                    @if($convertible->dealer_name != 'WHOLESALE PARTNER')
                    </a>
                    @else
                    </div>
                    @endif
    				<p>Price: <strong>{{ $convertible->internet_price ? '$'.$convertible->internet_price : 'Call' }}</strong></p>
                    <p>Mileage: <strong>{{ $convertible->mileage ? $convertible->mileage : 'Call' }}</strong></p>
    			</div>
    		</div>
			<hr class="margin-top-5 margin-bottom-5">
            @endforeach
            <div class="margin-bottom-20 visible-xs visible-sm"></div>
		</div>
		<div class="col-md-4 used-cars-list">
			<p class="spaced"><a href="{{ URL::abs('/cars/used/'.strtolower($geoip->region_name).'/'.strtolower($geoip->city_name).'/all/100000/truck')}}" class="btn btn-blue">Trucks <span class="glyphicon glyphicon-chevron-right"></span></a></p>
			<hr class="margin-top-5 margin-bottom-5 blue">
            @foreach($trucks as $truck)
    		<div class="row used-car">
    			<div class="col-xs-4">
                    <div class="pointer btn-used-car-quote" data-vehicle_id="{{$truck->id}}">
                        <img src="{{ $truck->display_image }}" class="img-responsive" onerror="master_control.BrokenVehicleImage(this)">
                    </div>
    			</div>
    			<div class="col-xs-8">
                    @if($truck->dealer_name != 'WHOLESALE PARTNER')
    				<a href="{{ URL::abs('/coupons/'.strtolower($truck->state).'/'.SoeHelper::getSlug($truck->city).'/auto-transportation/auto-dealers/'.SoeHelper::getSlug($truck->dealer_name)).'?showeid='.$truck->id.'&eidtype=usedquote' }}" class="blue">
                    @else
                    <div class="pointer btn-used-car-quote blue" data-vehicle_id="{{$truck->id}}">
                    @endif
                        {{ $truck->year }} {{ $truck->make }} {{ $truck->model }}
                    @if($truck->dealer_name != 'WHOLESALE PARTNER')
                    </a>
                    @else
                    </div>
                    @endif
    				<p>Price: <strong>{{ $truck->internet_price ? '$'.$truck->internet_price : 'Call' }}</strong></p>
    				<p>Mileage: <strong>{{ $truck->mileage ? $truck->mileage : 'Call' }}</strong></p>
    			</div>
    		</div>
			<hr class="margin-top-5 margin-bottom-5">
            @endforeach
		</div>
	</div>
</div>

<script>
    category_id = '2';
    type = 'all';
    page = 0;
</script>

@stop
