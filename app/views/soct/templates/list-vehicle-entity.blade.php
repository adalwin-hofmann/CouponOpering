<div class="item list used-car" itemtype="http://data-vocabulary.org/Product" itemscope="">
    <div class="item-type used-car"></div>
    <meta itemprop="productID" content="{{$vehicle->vin}}">
    <div class="row margin-bottom-10 margin-top-10">
        <div class="col-xs-5 col-sm-3 btn-used-car-quote pointer" data-vehicle_id="{{$vehicle->id}}">
            <img alt="{{$vehicle->year}} {{$vehicle->make}} {{$vehicle->model}}" class="img-responsive" src="{{$vehicle->display_image == '' ? 'http://s3.amazonaws.com/saveoneverything_assets/assets/images/soct/soct-not-found.jpg' : $vehicle->display_image}}">
        </div>
        @if($vehicle->dealer_name != 'WHOLESALE PARTNER')
            <a itemprop="url" class="item-info col-xs-7 col-sm-5 col-lg-4" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/auto-transportation/auto-dealers/{{$vehicle->dealer_slug}}">
        @else
            <div class="item-info col-xs-7 col-sm-5 col-lg-4">
        @endif
            <!--<div class="margin-top-20 visible-xs"></div>
            <div class="margin-top-10 hidden-xs"></div>-->
            <span class="h3" itemprop="name">{{$vehicle->year}} {{$vehicle->make}} {{$vehicle->model}}</span>
            <div class="more-info">
                <div itemtype="http://schema.org/Offer" itemscope="" itemprop="offers">
                    <p>Price: <strong><span itemtype="price">{{($vehicle->internet_price != 0)?'$'.$vehicle->internet_price:'N/A'}}</span></strong></p>
                    <p>Mileage: <strong>{{$vehicle->mileage != 0 ? $vehicle->mileage : 'Call'}}</strong></p>
                    <link href="http://schema.org/UsedCondition" itemprop="itemCondition">
                    <link href="http://schema.org/InStock" itemprop="availability">
                </div>
                @if($vehicle->address != '')
                <div itemtype="http://schema.org/Organization" itemscope="" itemprop="brand">
                    <p><span itemprop="name">{{$vehicle->dealer_name == 'WHOLESALE PARTNER' ? '' : ucwords(strtolower($vehicle->dealer_name))}}</span><br>
                        {{ucwords($vehicle->city)}}, {{$vehicle->state}} {{$vehicle->zipcode}}<br>
                        {{ceil($vehicle->distance / 1609)}} Miles Away</p>
                </div>
                @endif
            </div>
            <div itemtype="http://schema.org/Organization" itemscope="" itemprop="manufacturer" class="hidden">
                <span itemprop="name">{{$vehicle->make}}</span>
            </div>
            <span itemprop="model" class="hidden">{{$vehicle->model}}</span>
            <span itemprop="description" class="hidden">{{($vehicle->dealer_comments)?$vehicle->dealer_comments:'Used '.$vehicle->year.' '.$vehicle->make.' '.$vehicle->model}}</span>
        @if($vehicle->dealer_name != 'WHOLESALE PARTNER')
            </a>
        @else
            </div>
        @endif
        <div class="col-md-4 visible-lg btn-col">
            <div class="margin-top-10"></div>
            <button class="btn btn-default btn-used-car-quote" data-vehicle_id="{{$vehicle->id}}" data-toggle="tooltip" data-placement="top" title="Get More Info on This Used {{$vehicle->year}} {{$vehicle->make}} {{$vehicle->model}}"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-more-info.png" alt="Get More Info on This Used {{$vehicle->year}} {{$vehicle->make}} {{$vehicle->model}}"></button><!--
            --><button class="btn btn-default btn-used-share" data-vehicle_id="{{$vehicle->id}}" data-toggle="tooltip" data-placement="top" title="Share This Used {{$vehicle->year}} {{$vehicle->make}} {{$vehicle->model}}"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-share-it.png" alt="Share This Used {{$vehicle->year}} {{$vehicle->make}} {{$vehicle->model}}"></button><!--
            --><button type="button" class="btn btn-default btn-used-save {{$vehicle->is_saved == true ? 'disabled' : ''}}" data-vehicle_id="{{$vehicle->id}}" data-toggle="tooltip" data-placement="top" title="Add This Used {{$vehicle->year}} {{$vehicle->make}} {{$vehicle->model}} to Your Favorites"><img src="{{$vehicle->is_saved == true ? 'http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-favorited.png' : 'http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-favorite-it.png'}}" alt="Add This Used {{$vehicle->year}} {{$vehicle->make}} {{$vehicle->model}} to Your Favorites"></button>
        </div>
        <div class="btn-group hidden-lg">
            <button class="btn btn-default btn-used-car-quote" data-vehicle_id="{{$vehicle->id}}" data-toggle="tooltip" data-placement="top" title="Get More Info on This Used {{$vehicle->year}} {{$vehicle->make}} {{$vehicle->model}}"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-more-info.png" alt="Get More Info on This Used {{$vehicle->year}} {{$vehicle->make}} {{$vehicle->model}}"></button>
            <button class="btn btn-default btn-used-share" data-vehicle_id="{{$vehicle->id}}" data-toggle="tooltip" data-placement="top" title="Share This Used {{$vehicle->year}} {{$vehicle->make}} {{$vehicle->model}}"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-share-it.png" alt="Share This Used {{$vehicle->year}} {{$vehicle->make}} {{$vehicle->model}}"></button>
            <button type="button" class="btn btn-default btn-used-save {{$vehicle->is_saved == true ? 'disabled' : ''}}" data-vehicle_id="{{$vehicle->id}}" data-toggle="tooltip" data-placement="top" title="Add This Used {{$vehicle->year}} {{$vehicle->make}} {{$vehicle->model}} to Your Favorites"><img src="{{$vehicle->is_saved == true ? 'http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-favorited.png' : 'http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-favorite-it.png'}}" alt="Add This Used {{$vehicle->year}} {{$vehicle->make}} {{$vehicle->model}} to Your Favorites"></button>
        </div>
        <!--<div class="col-xs-7 col-md-8 col-lg-9 hidden-xs margin-bottom-10">
            <button class="btn btn-default btn-used-car-quote" data-vehicle_id="{{$vehicle->id}}"><img src="http://s3.amazonaws.com/saveoneverything_assets/images/masonry-icons/quote_it_save_today.png" alt="Get More Info on This Used {{$vehicle->year}} {{$vehicle->make}} {{$vehicle->model}}" class="img-circle"><br>More Info</button>
            <button class="btn btn-default btn-used-share" data-vehicle_id="{{$vehicle->id}}"><img src="http://s3.amazonaws.com/saveoneverything_assets/images/masonry-icons/share_it_save_today.png" alt="Share This Used {{$vehicle->year}} {{$vehicle->make}} {{$vehicle->model}}" class="img-circle"><br>Share It</button>
            <button type="button" class="btn btn-default btn-used-save {{$vehicle->is_saved == true ? 'disabled' : ''}}" data-vehicle_id="{{$vehicle->id}}"><img src="http://s3.amazonaws.com/saveoneverything_assets/images/masonry-icons/save_it_save_today.png" alt="Add This Used {{$vehicle->year}} {{$vehicle->make}} {{$vehicle->model}} to Your Favorites" class="img-circle"><br><span class="save-vehicle-text">{{$vehicle->is_saved == true ? 'Favorited!' : 'Favorite It'}}</span></button>
        </div>
        <div class="col-xs-12 visible-xs">
            <div class="btn-group">
                <button class="btn btn-default btn-used-car-quote" data-vehicle_id="{{$vehicle->id}}"><img src="http://s3.amazonaws.com/saveoneverything_assets/images/masonry-icons/quote_it_save_today.png" alt="Get More Info on This Used {{$vehicle->year}} {{$vehicle->make}} {{$vehicle->model}}" class="img-circle"><br>More Info</button>
                <button class="btn btn-default btn-used-share" data-vehicle_id="{{$vehicle->id}}"><img src="http://s3.amazonaws.com/saveoneverything_assets/images/masonry-icons/share_it_save_today.png" alt="Share This Used {{$vehicle->year}} {{$vehicle->make}} {{$vehicle->model}}" class="img-circle"><br>Share It</button>
                <button type="button" class="btn btn-default btn-used-save {{$vehicle->is_saved == true ? 'disabled' : ''}}" data-vehicle_id="{{$vehicle->id}}"><img src="http://s3.amazonaws.com/saveoneverything_assets/images/masonry-icons/save_it_save_today.png" alt="Add This Used {{$vehicle->year}} {{$vehicle->make}} {{$vehicle->model}} to Your Favorites" class="img-circle"><br><span class="save-vehicle-text">{{$vehicle->is_saved == true ? 'Favorited!' : 'Favorite It'}}</span></button>
            </div>
        </div>-->
    </div>
    <div class="clearfix"></div>
</div>