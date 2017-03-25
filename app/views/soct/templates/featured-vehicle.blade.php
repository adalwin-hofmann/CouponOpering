<div class="item list used-car" itemtype="http://data-vocabulary.org/Product" itemscope="">
    <div class="item-type used-car"></div>
    <meta itemprop="productID" content="{{$vehicle->vin}}">
    <div class="row">
        <div class="col-xs-5 col-md-4 col-lg-3 btn-used-car-quote pointer" data-vehicle_id="{{$vehicle->id}}">
            <img alt="{{$vehicle->year}} {{$vehicle->make}} {{$vehicle->model}}" class="main-img img-responsive" src="{{$vehicle->display_image}}" onerror="master_control.BrokenVehicleImage(this,{{$vehicle->id}},'used')">
        </div>
                    <div class="item-info col-xs-7 col-md-8 col-lg-9">
                    <div class="margin-top-20 visible-xs"></div>
            <div class="margin-top-10 hidden-xs"></div>
            <span class="h3" itemprop="name">{{$vehicle->year}} {{$vehicle->make}} {{$vehicle->model}}</span>
            <div class="row margin-bottom-10">
                <div class="col-sm-6" itemtype="http://schema.org/Offer" itemscope="" itemprop="offers">
                    <p>Price: <strong><span itemtype="price">{{$vehicle->internet_price != 0 ? '$'.$vehicle->internet_price : 'Please Call'}}</span></strong></p>
                    <p>Mileage: <strong>{{$vehicle->mileage != 0 ? $vehicle->mileage : 'Please Call'}}</strong></p>
                    <link href="http://schema.org/UsedCondition" itemprop="itemCondition">
                    <link href="http://schema.org/InStock" itemprop="availability">
                </div>
                <div class="col-sm-6" itemtype="http://schema.org/Organization" itemscope="" itemprop="brand">
                    <p>@if($vehicle->dealer_name != 'WHOLESALE PARTNER')<span itemprop="name">{{$vehicle->dealer_name == 'WHOLESALE PARTNER' ? '' : ucwords(strtolower($vehicle->dealer_name))}}</span><br>@endif
                        {{ucwords($vehicle->city)}}, {{$vehicle->state}} {{$vehicle->zipcode}}
                    </p>
                </div>
            </div>
            <div itemtype="http://schema.org/Organization" itemscope="" itemprop="manufacturer" class="hidden">
                <span itemprop="name">{{$vehicle->make}}</span>
            </div>
            <span itemprop="model" class="hidden">{{$vehicle->model}}</span>
            <span itemprop="description" class="hidden">{{$vehicle->year}} {{$vehicle->make}} {{$vehicle->model}} {{$vehicle->body_type}} {{$vehicle->dealer_comments}}</span>
                    </div>
                <div class="col-xs-7 col-md-8 col-lg-9 hidden-xs margin-bottom-10">
            <button class="btn btn-blue btn-used-car-quote" data-vehicle_id="{{$vehicle->id}}">More Info</button>
            <a href="{{URL::abs('/').'/cars/used/'.strtolower($geoip->region_name).'/'.SoeHelper::getSlug($geoip->city_name).'/'.SoeHelper::getSlug($vehicle->make)}}" class="btn btn-link">Find {{$vehicle->make}}</a>
            <a href="{{URL::abs('/').'/cars/used/'.strtolower($geoip->region_name).'/'.SoeHelper::getSlug($geoip->city_name).'/'.SoeHelper::getSlug($vehicle->make).'?model='.SoeHelper::getSlug($vehicle->model)}}" class="btn btn-link">Find {{$vehicle->model}}</a>
        </div>
        <div class="col-xs-12 visible-xs">
            <div class="btn-group">
                <button class="btn btn-blue btn-used-car-quote" data-vehicle_id="{{$vehicle->id}}">More Info</button>
                <a href="{{URL::abs('/').'/cars/used/'.strtolower($geoip->region_name).'/'.SoeHelper::getSlug($geoip->city_name).'/'.SoeHelper::getSlug($vehicle->make)}}" class="btn btn-blue">Find {{$vehicle->make}}</a>
                <a href="{{URL::abs('/').'/cars/used/'.strtolower($geoip->region_name).'/'.SoeHelper::getSlug($geoip->city_name).'/'.SoeHelper::getSlug($vehicle->make).'?model='.SoeHelper::getSlug($vehicle->model)}}" class="btn btn-blue">Find {{$vehicle->model}}</a>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
<div class="margin-bottom-15"></div>