@extends('master.templates.master')

@section('page-title')
<h1>Offers in {{ucwords(strtolower($stateName))}}</h1>
<?php $subheadDropdown = 'sort' ?>
@stop

@section('breadcrumbs')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}" itemprop="url"><span itemprop="title">Home</span></a>
    </li>
    <li class="active">{{strtoupper($state)}}</li>
@stop

@section('sidebar')
    <?php $adfeat = Feature::findByName('adlocalize'); $adfeat = $adfeat ? $adfeat->value : 0;?>
    @if($adfeat == 1)
    <div class="margin-bottom-15">
        <div class="ad-localize-root"><script src="http://adlocalize.com/placement/adlocalize.js?zid=12&type=URL&zip={{$userZip}}"></script></div>
    </div>
    @endif
    <div class="content-bg margin-bottom-15">
        <form>
            <select class="form-control" id="usedState">
                <option value="all">Choose Another State</option>
                <?php foreach ($states as $abbr => $name) { ?>
                    <option value="{{ URL::abs('/coupons').'/'.strtolower($abbr) }}">{{ucwords(strtolower($name))}}</option>
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
                    <li><a href="{{URL::abs('/')}}/coupons/{{strtolower($city->state)}}/{{SoeHelper::getSlug(strtolower($city->city))}}/all" title="{{$type == 'coupon' ? 'Coupons' : ($type == 'dailydeal' ? 'Daily Deals' : 'Contests')}} in {{ucwords(strtolower($city->city))}}, {{strtoupper($state)}}">{{ucwords(strtolower($city->city))}}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    
@stop

@section('body')

    <div class="clearfix"></div>

    <div class="merchant-results-holder">
        @if(!empty($city_image))
        @if($city_image->path != '')
        <div class="region-banner" style="background-image: url('{{$city_image->path}}')">
            <span class="fancy h1">{{ucwords(strtolower($city_image->display))}}</span>
        </div>
        <div class="margin-bottom-20"></div>
        @endif
        @endif
        @if(!empty($city_image))
        <div class="content-bg margin-bottom-20">
            {{isset($seoContent['Page-About']) && $seoContent['Page-About'] != '' ? SoeHelper::cityStateReplace($seoContent['Page-About'], $geoip) : $city_image->about}}
        </div>
        @endif

        @if($dailydeals)
        <div class="landing-banner">
            <div class="row">
                <div class="col-sm-6">
                    <div class="banner-image">

                    </div>
                </div>
                <hr class="visible-xs">
                <div class="col-sm-6 banner-offers">
                    @foreach($dailydeals as $dailydeal)
                    <div class="row">
                        <div class="col-xs-4">
                            <a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/{{$dailydeal->category_slug}}/{{$dailydeal->subcategory_slug}}/{{$dailydeal->merchant_slug.'/'.$dailydeal->location_id}}">
                                <img src="{{$dailydeal->path != $dailydeal->logo ? $dailydeal->path : ($dailydeal->about_img ? $dailydeal->about_img : $dailydeal->path)}}" alt="{{$dailydeal->merchant_name}} Coupons" class="img-responsive">
                            </a>
                        </div>
                        <div class="col-xs-8">
                            <a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/{{$dailydeal->category_slug}}/{{$dailydeal->subcategory_slug}}/{{$dailydeal->merchant_slug.'/'.$dailydeal->location_id}}" class="blue">
                                {{$dailydeal->merchant_name}}
                            </a>
                            <h3>{{$dailydeal->name}}</h3>
                        </div>
                    </div>
                    <hr class="margin-top-10 margin-bottom-10">
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        @if($contests)
        <div class="content-bg margin-top-20">
            <div class="row">
                <div class="col-xs-12">
                    <p class="spaced">Featured Contests in {{ucwords(strtolower($stateName))}}</p>
                    <hr style="margin-top:0;" class="red">
                </div>
            </div>
            <div>
            @foreach($contests as $entity)
                @include('master.templates.entity', array('entity'=>$entity))
            @endforeach
            <div class="clearfix"></div>
            </div>
        </div>
        @endif

        <div class="content-bg margin-top-20 margin-bottom-20">
            <div class="section-type featured"></div>
            <div class="row">
                <div class="col-sm-4">
                    <p class="spaced">Cars &amp; Trucks</p>
                    <hr class="margin-top-0 margin-bottom-10 green">
                    <img src="/img/landing-soct.jpg" class="img-responsive" alt="Cars &amp; Trucks in {{ucwords(strtolower($stateName))}}">
                    @foreach($soct as $soct)
                    <hr class="margin-top-10 margin-bottom-10">
                    <div class="row">
                        <div class="col-md-4 col-sm-12 col-xs-4">
                            <div class="pointer btn-used-car-quote" data-vehicle_id="{{$soct->id}}">
                                <img src="{{ $soct->display_image }}" class="img-responsive" onerror="master_control.BrokenVehicleImage(this)">
                            </div>
                        </div>
                        <div class="col-md-8 col-sm-12 col-xs-8">
                            @if($soct->dealer_name != 'WHOLESALE PARTNER')
                            <a href="{{ URL::abs('/coupons/'.strtolower($geoip->region_name).'/'.SoeHelper::getSlug($geoip->city_name).'/auto-transportation/auto-dealers/'.SoeHelper::getSlug($soct->dealer_name)).'?showeid='.$soct->id.'&eidtype=usedquote' }}" class="green">
                            @else
                            <div class="pointer btn-used-car-quote green" data-vehicle_id="{{$soct->id}}">
                            @endif
                                {{ $soct->year }} {{ $soct->make }} {{ $soct->model }}
                            @if($soct->dealer_name != 'WHOLESALE PARTNER')
                            </a>
                            @else
                            </div>
                            @endif
                            <p>Price: <strong>{{ $soct->internet_price ? '$'.$soct->internet_price : 'Call'}}</strong></p>
                            <p>Mileage: <strong>{{ $soct->mileage }}</strong></p>
                        </div>
                    </div>
                    @endforeach
                    
                </div>
                <div class="col-sm-4">
                    <p class="spaced">Travel &amp; Fun</p>
                    <hr class="margin-top-0 margin-bottom-10 green">
                    <img src="/img/landing-travel.jpg" class="img-responsive" alt="Travel &amp; Fun in {{ucwords(strtolower($stateName))}}">
                    @foreach($travel as $entity)
                    <hr class="margin-top-10 margin-bottom-10">
                    <div class="row">
                        <div class="col-md-4 col-sm-12 col-xs-4">
                            <a href="{{URL::abs('/')}}/coupons/{{strtolower($entity->state)}}/{{SoeHelper::getSlug($entity->location->city)}}/{{$entity->category_slug}}/{{$entity->subcategory_slug}}/{{$entity->merchant_slug.'/'.$entity->location_id}}">
                                <img src="{{$entity->path != $entity->logo ? $entity->path : ($entity->about_img ? $entity->about_img : $entity->path)}}" alt="{{$entity->merchant_name}} Coupons" class="img-responsive">
                            </a>
                        </div>
                        <div class="col-md-8 col-sm-12 col-xs-8">
                            <a href="{{URL::abs('/')}}/coupons/{{strtolower($entity->state)}}/{{SoeHelper::getSlug($entity->location->city)}}/{{$entity->category_slug}}/{{$entity->subcategory_slug}}/{{$entity->merchant_slug.'/'.$entity->location_id}}">
                                {{$entity->merchant_name}}
                            </a>
                            <p>{{$entity->name}}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="col-sm-4">
                    <p class="spaced">Food &amp; Dining</p>
                    <hr class="margin-top-0 margin-bottom-10 green">
                    <img src="/img/landing-food.jpg" class="img-responsive" alt="Food &amp; Dining in {{ucwords(strtolower($stateName))}}">
                    @foreach($food as $entity)
                    <hr class="margin-top-10 margin-bottom-10">
                    <div class="row">
                        <div class="col-md-4 col-sm-12 col-xs-4">
                            <a href="{{URL::abs('/')}}/coupons/{{strtolower($entity->state)}}/{{SoeHelper::getSlug($entity->location->city)}}/{{$entity->category_slug}}/{{$entity->subcategory_slug}}/{{$entity->merchant_slug.'/'.$entity->location_id}}">
                                <img src="{{$entity->path != $entity->logo ? $entity->path : ($entity->about_img ? $entity->about_img : $entity->path)}}" alt="{{$entity->merchant_name}} Coupons" class="img-responsive">
                            </a>
                        </div>
                        <div class="col-md-8 col-sm-12 col-xs-8">
                            <a href="{{URL::abs('/')}}/coupons/{{strtolower($entity->state)}}/{{SoeHelper::getSlug($entity->location->city)}}/{{$entity->category_slug}}/{{$entity->subcategory_slug}}/{{$entity->merchant_slug.'/'.$entity->location_id}}">
                                {{$entity->merchant_name}}
                            </a>
                            <p>{{$entity->name}}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        
    </div>
    <div class="clearfix"></div>
@stop