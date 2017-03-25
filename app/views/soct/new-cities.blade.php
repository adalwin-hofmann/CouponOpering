@extends('master.templates.master')

@section('page-title')
<h1>New Cars in {{ucwords(strtolower($stateName))}}</h1>
@stop

@section('breadcrumbs')
    @include('soct.templates.breadcrumbs')
@stop

@section('sidebar')
    <div class="content-bg margin-bottom-15">
        <form>
            <select class="form-control" id="usedState">
                <option value="all">Choose Another State</option>
                <?php foreach ($states as $abbr => $name) { ?>
                    <option value="{{ URL::abs('/cars/new').'/'.strtolower($abbr) }}">{{ucwords(strtolower($name))}}</option>
                <?php } ?>
            </select>
        </form>
    </div>
    <div class="panel panel-default">
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
            <p class="spaced margin-bottom-20"><strong>{{$stateName}}</strong> - Find New Cars</p>
            <div id="state-text">
            @if(!empty($city_image))
                @if($city_image->new_cars_about)
                {{$city_image->new_cars_about}}
                @else
                {{isset($seoContent['Page-About']) && $seoContent['Page-About'] != '' ? SoeHelper::cityStateReplace($seoContent['Page-About'], $geoip) : $city_image->about}}
                @endif
            @endif
            </div>
        </div>
        
    </div>
    <div class="clearfix"></div>

    <div class="content-bg">
        <div class="row">
            <div class="col-xs-12">
                <p class="spaced"><a href="{{ URL::abs('/cars/used/under') }}" style="color:#444">Cars Under $15,000</a></p>
                <hr class="burgundy margin-top-0">
            </div>
        </div>
        <div class="">
            <div class="js-masonry soct-masonry masonry-single-line">
            @foreach($lows as $low)
                @include('soct.templates.new-car', array('vehicle' => $low))
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
                <p class="spaced">Luxury Cars:</p>
                <hr class="margin-top-5 margin-bottom-5 burgundy">
                @foreach($luxury as $vehicle)
                <div class="row used-car">
                    <div class="col-xs-4">
                        <div class="pointer btn-new-car-quote" data-vehicle_id="{{$vehicle->id}}">
                            <img alt="{{$vehicle->year}} {{$vehicle->make_name}} {{$vehicle->model_name}}" class="img-responsive" src="{{$vehicle->display_image ? $vehicle->display_image : 'http://s3.amazonaws.com/saveoneverything_assets/assets/images/soct/soct-not-found.jpg'}}" onerror="master_control.BrokenVehicleImage(this)">
                        </div>
                    </div>
                    <div class="col-xs-8">
                        <a href="{{URL::abs('/')}}/cars/research/{{$vehicle->year}}/{{($vehicle->make_slug && $vehicle->model_slug) ? $vehicle->make_slug.'/'.$vehicle->model_slug.'/' : ''}}{{$vehicle->id}}" class="burgundy">
                            {{$vehicle->year}} {{$vehicle->make_name}} {{$vehicle->model_name}}
                        </a>
                        <p>Price: <strong>{{ $vehicle->price ? '$'.$vehicle->price : 'Call' }}</strong></p>
                        <p>{{count($vehicle->incentives) ? $vehicle->incentives[0]['name'] : ''}}</p>
                    </div>
                </div>
                <hr class="margin-top-5 margin-bottom-5">
                @endforeach
                <div class="margin-bottom-20 visible-xs visible-sm"></div>
            </div>
            <div class="col-md-4 used-cars-list">
                <p class="spaced">SUVs</p>
                <hr class="margin-top-5 margin-bottom-5 burgundy">
                @foreach($suv as $vehicle)
                <div class="row used-car">
                    <div class="col-xs-4">
                        <div class="pointer btn-new-car-quote" data-vehicle_id="{{$vehicle->id}}">
                            <img alt="{{$vehicle->year}} {{$vehicle->make_name}} {{$vehicle->model_name}}" class="img-responsive" src="{{$vehicle->display_image ? $vehicle->display_image : 'http://s3.amazonaws.com/saveoneverything_assets/assets/images/soct/soct-not-found.jpg'}}" onerror="master_control.BrokenVehicleImage(this)">
                        </div>
                    </div>
                    <div class="col-xs-8">
                        <a href="{{URL::abs('/')}}/cars/research/{{$vehicle->year}}/{{($vehicle->make_slug && $vehicle->model_slug) ? $vehicle->make_slug.'/'.$vehicle->model_slug.'/' : ''}}{{$vehicle->id}}" class="burgundy">
                            {{$vehicle->year}} {{$vehicle->make_name}} {{$vehicle->model_name}}
                        </a>
                        <p>Price: <strong>{{ $vehicle->price ? '$'.$vehicle->price : 'Call' }}</strong></p>
                        <p>{{count($vehicle->incentives) ? $vehicle->incentives[0]['name'] : ''}}</p>
                    </div>
                </div>
                <hr class="margin-top-5 margin-bottom-5">
                @endforeach
                <div class="margin-bottom-20 visible-xs visible-sm"></div>
            </div>
            <div class="col-md-4 used-cars-list">
                <p class="spaced">Trucks</p>
                <hr class="margin-top-5 margin-bottom-5 burgundy">
                @foreach($trucks as $vehicle)
                <div class="row used-car">
                    <div class="col-xs-4">
                        <div class="pointer btn-new-car-quote" data-vehicle_id="{{$vehicle->id}}">
                            <img alt="{{$vehicle->year}} {{$vehicle->make_name}} {{$vehicle->model_name}}" class="img-responsive" src="{{$vehicle->display_image ? $vehicle->display_image : 'http://s3.amazonaws.com/saveoneverything_assets/assets/images/soct/soct-not-found.jpg'}}" onerror="master_control.BrokenVehicleImage(this)">
                        </div>
                    </div>
                    <div class="col-xs-8">
                        <a href="{{URL::abs('/')}}/cars/research/{{$vehicle->year}}/{{($vehicle->make_slug && $vehicle->model_slug) ? $vehicle->make_slug.'/'.$vehicle->model_slug.'/' : ''}}{{$vehicle->id}}" class="burgundy">
                            {{$vehicle->year}} {{$vehicle->make_name}} {{$vehicle->model_name}}
                        </a>
                        <p>Price: <strong>{{ $vehicle->price ? '$'.$vehicle->price : 'Call' }}</strong></p>
                        <p>{{count($vehicle->incentives) ? $vehicle->incentives[0]['name'] : ''}}</p>
                    </div>
                </div>
                <hr class="margin-top-5 margin-bottom-5">
                @endforeach
            </div>
        </div>
    </div>
@stop