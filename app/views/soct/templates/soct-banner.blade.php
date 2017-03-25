<?php
    $features = App::make('FeatureRepositoryInterface');
    $full_detroit_only = $features->findByName('full_soct_detroit_only');
    $full_detroit_only = empty($full_detroit_only) ? 120 : $full_detroit_only->value;
    if($full_detroit_only)
    {
        $distance = GeometryHelper::getDistance($geoip->latitude, $geoip->longitude, 42.38, -83.10);
        $full_detroit_only = ($distance > $full_detroit_only || $geoip->region_name != 'MI') ? 1 : 0;
    }
?>
<div class="hidden-xs city-banner soct banner-menu">
    <div class="city-banner-img" style="background-image: url(http://s3.amazonaws.com/saveoneverything_assets/assets/images/soct/soct_banner.jpg)">
        <div class="fade-left"></div>
        <div class="fade-right"></div>
            <span class="h1 fancy">Cars &amp; Trucks</span>
            <p class="spaced">Make Cost Effective Car Buying Decisions.</p>
        <div class="btn-row text-center">
            <div class="">
                @if(!$full_detroit_only)
                <a href="{{URL::abs('/')}}/cars" class="btn btn-burgundy">Cars &amp; Trucks</a>
                <a href="{{URL::abs('/')}}/cars/new/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}" class="btn btn-burgundy">New Cars</a>
                <a href="{{URL::abs('/')}}/cars/used" class="btn btn-burgundy">Used Cars</a>
                <a href="{{URL::abs('/')}}/cars/auto-services" class="btn btn-burgundy">Service & Lease Specials</a>
                <a href="{{URL::abs('/')}}/cars/featured-dealers" class="btn btn-burgundy">Featured Dealers</a>
                @endif
                @if(!Auth::check())<button class="btn btn-red soct-banner" data-toggle="modal" data-target="#signUpModal">Sign up for FREE <span class="glyphicon glyphicon-chevron-right"></span></button>@endif
            </div>
        </div>
        
    </div>
</div>