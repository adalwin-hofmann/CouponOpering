<?php
for($j=0; $j<count($featured->merchant->eager_assets); $j++)
{
    if($j==0)
        $featured->display_image = $featured->merchant->eager_assets[0]['path'];
    if($featured->merchant->eager_assets[$j]['name'] == 'logo1')
        $featured->display_image = $featured->merchant->eager_assets[$j]['path'];
}
?>
<div class="item list">
    <div class="margin-20">
        <div class="row">
            <div class="col-sm-5 col-md-4 col-lg-3" data-id="{{$featured->id}}">
                <a href="{{URL::abs('/')}}/coupons/auto-transportation/auto-dealers/{{$featured->merchant->slug}}">
                    <img class="img-responsive relative center-block" src="{{$featured->display_image}}">
                </a>
            </div>
            <div class="col-sm-7 col-md-5 col-md-5">
                <a class="item-info" href="{{URL::abs('/')}}/coupons/auto-transportation/auto-dealers/{{$featured->merchant->slug}}">
                    <h2>{{$featured->merchant->name}}</h2>
                    <p class="featured-label spaced">Featured Dealership</p>
                    <p>{{$featured->address}} {{$featured->address2}}<br>
                        {{$featured->city}}, {{$featured->state}} {{$featured->zip}}</p>
                </a>
                <p>
                    <a href="{{URL::abs('/')}}/coupons/auto-transportation/auto-dealers/{{$featured->merchant->slug}}?writereview" class="">
                        <span class="glyphicon glyphicon-pencil"></span> Write a Review
                    </a>
                </p>
            </div>
            <div class="col-sm-12 col-md-3 hidden-xs margin-bottom-10 dealer-buttons">
                <a type="button" class="btn btn-sm spaced btn-grey" href="{{URL::abs('/')}}/coupons/auto-transportation/auto-dealers/{{$featured->merchant->slug}}#newCars">New Cars</a>
                <a type="button" class="btn btn-sm spaced btn-grey" href="{{URL::abs('/')}}/coupons/auto-transportation/auto-dealers/{{$featured->merchant->slug}}#usedCars">Used Cars</a>
                <a type="button" class="btn btn-sm spaced btn-grey" href="{{URL::abs('/')}}/coupons/auto-transportation/auto-dealers/{{$featured->merchant->slug}}#autoServices">Specials</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 visible-xs">
            <div class="btn-group">
                <a type="button" class="btn spaced btn-default" href="{{URL::abs('/')}}/coupons/auto-transportation/auto-dealers/{{$featured->merchant->slug}}#newCars">New Cars</a>
                <a type="button" class="btn spaced btn-default" href="{{URL::abs('/')}}/coupons/auto-transportation/auto-dealers/{{$featured->merchant->slug}}#usedCars">Used Cars</a>
                <a type="button" class="btn spaced btn-default" href="{{URL::abs('/')}}/coupons/auto-transportation/auto-dealers/{{$featured->merchant->slug}}#autoServices">Specials</a>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>