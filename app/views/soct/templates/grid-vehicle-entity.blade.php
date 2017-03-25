<div class="item used-car" itemtype="http://data-vocabulary.org/Product" itemscope="">
    <meta itemprop="productID" content="{{$vehicle->vin}}">
    <div class="item-type used-car"></div>
    <div class="top-pic btn-used-car-quote pointer" data-id="{{$vehicle->id}}" data-vehicle_id="{{$vehicle->id}}">
        <div class="expired-banner"><span class="h3 spaced">Expired</span></div>
        <img title="Used {{$vehicle->year}} {{$vehicle->make}} {{$vehicle->model}}" alt="Used {{$vehicle->year}} {{$vehicle->make}} {{$vehicle->model}}" itemprop="image" class="img-responsive" src="{{$vehicle->display_image == '' ? 'http://s3.amazonaws.com/saveoneverything_assets/assets/images/soct/soct-not-found.jpg' : $vehicle->display_image}}">
    </div>
    <div class="item-info">
        <p itemtype="http://schema.org/Organization" itemscope="" itemprop="brand" class="merchant-name">
            @if($vehicle->dealer_name != 'WHOLESALE PARTNER')<a itemprop="url" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/auto-transportation/auto-dealers/{{$vehicle->dealer_slug}}">@endif
                <span itemprop="name">{{$vehicle->dealer_name == 'WHOLESALE PARTNER' ? '' : ucwords(strtolower($vehicle->dealer_name))}}&nbsp;</span>
            @if($vehicle->dealer_name != 'WHOLESALE PARTNER')</a>@endif
        </p>
        <div class="btn-used-car-quote" data-vehicle_id="{{$vehicle->id}}">
            <span class="h3" itemprop="name">{{$vehicle->year}} {{$vehicle->make}} {{$vehicle->model}}</span>
            <div class="special-info">
                <div itemtype="http://schema.org/Offer" itemscope="" itemprop="offers">
                    <p class="expires_at"><strong>Price</strong> <span itemtype="price">{{($vehicle->internet_price != 0)?'$'.$vehicle->internet_price:'N/A'}}</span></p>
                    <link href="http://schema.org/UsedCondition" itemprop="itemCondition">
                    <link href="http://schema.org/InStock" itemprop="availability">
                </div>
                <p class="expires_at"><strong>Mileage</strong> {{$vehicle->mileage != 0 ? $vehicle->mileage : 'Call'}}</p>
            </div>
            <div itemtype="http://schema.org/Organization" itemscope="" itemprop="manufacturer" class="hidden">
                <span itemprop="name">{{$vehicle->make}}</span>
            </div>
            <span itemprop="model" class="hidden">{{$vehicle->model}}</span>
            <span itemprop="description" class="hidden">{{($vehicle->dealer_comments)?$vehicle->dealer_comments:'Used '.$vehicle->year.' '.$vehicle->make.' '.$vehicle->model}}</span>
        </div>
    </div>
    <div class="btn-group">
        <button class="btn btn-default btn-used-car-quote" data-vehicle_id="{{$vehicle->id}}" data-toggle="tooltip" data-placement="top" title="Get More Info on This Used {{$vehicle->year}} {{$vehicle->make}} {{$vehicle->model}}"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-more-info.png" alt="Get More Info on This Used {{$vehicle->year}} {{$vehicle->make}} {{$vehicle->model}}"></button>
        <button class="btn btn-default btn-used-share" data-vehicle_id="{{$vehicle->id}}" data-toggle="tooltip" data-placement="top" title="Share This Used {{$vehicle->year}} {{$vehicle->make}} {{$vehicle->model}}"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-share-it.png" alt="Share This Used {{$vehicle->year}} {{$vehicle->make}} {{$vehicle->model}}"></button>
        <button type="button" class="btn btn-default btn-used-save {{$vehicle->is_saved == true ? 'disabled' : ''}}" data-vehicle_id="{{$vehicle->id}}" data-toggle="tooltip" data-placement="top" title="Add This Used {{$vehicle->year}} {{$vehicle->make}} {{$vehicle->model}} to Your Favorites"><img src="{{$vehicle->is_saved == true ? 'http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-favorited.png' : 'http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-favorite-it.png'}}" alt="Add This Used {{$vehicle->year}} {{$vehicle->make}} {{$vehicle->model}} to Your Favorites"></button>
    </div>
</div>