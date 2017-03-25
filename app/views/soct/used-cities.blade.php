@extends('master.templates.master')

@section('page-title')
@if ($page_type == 'used')
<h1>Used Cars for Sale in {{ucwords(strtolower($stateName))}}</h1>
@else
<h1>{{$page_type == 'used' || $page_type == 'new' ? ucwords($page_type).' Cars' : ($page_type == 'auto-services' ? 'Service & Lease Specials' : 'Featured Dealers')}} by City</h1>
@endif
@stop

@section('breadcrumbs')
    @include('soct.templates.breadcrumbs')
@stop

@section('sidebar')
@if(count($premium['objects']))
@include('master.templates.advertisement', array('advertisement' => $premium['objects'][0]))
@endif
    <div class="content-bg margin-bottom-15">
        <form>
            <select class="form-control" id="usedState">
                <option value="all">Choose Another State</option>
                <?php foreach ($states as $abbr => $name) { ?>
                    <option value="{{ URL::abs('/cars/used').'/'.strtolower($abbr) }}">{{ucwords(strtolower($name))}}</option>
                <?php } ?>
            </select>
        </form>
    </div>
    <div class="panel panel-default hidden-xs">
        <div class="panel-heading">
          <span class="panel-title h4 hblock">
            <a data-toggle="collapse" href="#collapseOne">Cities <span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span></a>
            <div class="clearfix"></div>
          </span>
        </div>
        <div id="collapseOne" class="panel-collapse">
            <div class="panel-body explore-links">
                <ul>
                    @foreach($cities['objects'] as $city)
                    <li><a href="{{URL::abs('/')}}/cars/{{$page_type}}/{{strtolower($city->state)}}/{{SoeHelper::getSlug(strtolower($city->city))}}">{{ucwords(strtolower($city->city))}}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    
@stop

@section('body')

<script>
    type = "{{$page_type}}";
</script>

<script type="text/ejs" id="template_recommendation">
<% list(entities, function(entity){
    if(type == 'new')
    { %>
        <%== can.view.render('template_new_car', {vehicles: [entity]}); %>
    <% }
    else if(type == 'used')
    { %>
        <%== can.view.render('template_grid_vehicle', {vehicles: [entity]}); %>
    <% }
 }); %>
</script>
    
    <div class="clearfix"></div>

    <div class="merchant-results-holder">
        @if(!empty($city_image))
        @if($city_image->path != '')
        <!--<div class="region-banner" style="background-image: url('{{$city_image->path}}')">
        </div>
        <hr class="dark">-->
        @endif
        @endif
        <div class="content-bg margin-bottom-20">
            <p class="spaced margin-bottom-20"><strong>{{$stateName}}</strong> - Find Local Used Cars</p>
            <div id="state-text">
            @if(!empty($city_image))
                @if($city_image->used_cars_about)
                {{$city_image->used_cars_about}}
                @else
                {{isset($seoContent['Page-About']) && $seoContent['Page-About'] != '' ? SoeHelper::cityStateReplace($seoContent['Page-About'], $geoip) : $city_image->about}}
                @endif
            @endif
            </div>
        </div>
        
    </div>
    <div class="clearfix"></div>

    <div class="content-bg margin-bottom-15 visible-xs">
        <form>
            <select class="form-control" id="usedCity">
                <option value="all">Choose a City</option>
                @foreach($cities['objects'] as $city)
                    <option value="{{URL::abs('/')}}/cars/{{$page_type}}/{{strtolower($city->state)}}/{{SoeHelper::getSlug(strtolower($city->city))}}">{{ucwords(strtolower($city->city))}}</option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="content-bg">
        <div class="row">
            <div class="col-xs-12">
                <p class="spaced"><a href="{{ URL::abs('/cars/used/under') }}" class="btn btn-blue">Cars Under $15,000 <span class="glyphicon glyphicon-chevron-right"></span></a></p>
                <hr class="blue margin-top-0">
            </div>
        </div>
        <div class="">
            <div class="js-masonry soct-masonry masonry-single-line">
            @foreach($lows as $low)
                @include('soct.templates.grid-vehicle-entity', array('vehicle' => $low))
            @endforeach
            <div class="clearfix"></div>
            </div>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="content-bg margin-top-20">
        <div class="section-type featured"></div>
        <div class="row">
            <div class="col-md-4 used-cars-list">
                <p class="spaced"><a href="{{ URL::abs('/cars/used/'.strtolower($top_state).'/'.strtolower($top_city).'?mileage=50000')}}" class="btn btn-blue">Low Mileage Cars <span class="glyphicon glyphicon-chevron-right"></span></a></p>
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
                <p class="spaced"><a href="{{ URL::abs('/cars/used/'.strtolower($top_state).'/'.strtolower($top_city).'/all/100000/convertible')}}" class="btn btn-blue">Convertibles <span class="glyphicon glyphicon-chevron-right"></span></a></p>
                <hr class="margin-top-5 margin-bottom-5 blue">
                @foreach($convertables as $convertable)
                <div class="row used-car">
                    <div class="col-xs-4">
                        <div class="pointer btn-used-car-quote" data-vehicle_id="{{$convertable->id}}">
                            <img src="{{ $convertable->display_image }}" class="img-responsive" onerror="master_control.BrokenVehicleImage(this)">
                        </div>
                    </div>
                    <div class="col-xs-8">
                        @if($convertable->dealer_name != 'WHOLESALE PARTNER')
                        <a href="{{ URL::abs('/coupons/'.strtolower($convertable->state).'/'.SoeHelper::getSlug($convertable->city).'/auto-transportation/auto-dealers/'.SoeHelper::getSlug($convertable->dealer_name)).'?showeid='.$convertable->id.'&eidtype=usedquote' }}" class="blue">
                        @else
                        <div class="pointer btn-used-car-quote blue" data-vehicle_id="{{$convertable->id}}">
                        @endif
                        {{ $convertable->year }} {{ $convertable->make }} {{ $convertable->model }}
                        @if($convertable->dealer_name != 'WHOLESALE PARTNER')
                        </a>
                        @else
                        </div>
                        @endif
                        <p>Price: <strong>{{ $convertable->internet_price ? '$'.$convertable->internet_price : 'Call' }}</strong></p>
                        <p>Mileage: <strong>{{ $convertable->mileage ? $convertable->mileage : 'Call' }}</strong></p>
                    </div>
                </div>
                <hr class="margin-top-5 margin-bottom-5">
                @endforeach
                <div class="margin-bottom-20 visible-xs visible-sm"></div>
            </div>
            <div class="col-md-4 used-cars-list">
                <p class="spaced"><a href="{{ URL::abs('/cars/used/'.strtolower($top_state).'/'.strtolower($top_city).'/all/100000/truck')}}" class="btn btn-blue">Trucks <span class="glyphicon glyphicon-chevron-right"></span></a></p>
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
@stop