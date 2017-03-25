@extends('master.templates.master', array('width'=>'full', 'special_merchant'=>$special_merchant))
@section('page-title')
<h1>{{$merchant->display}}</h1>
@stop

@section('breadcrumbs')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}" itemprop="url"><span itemprop="title">Home</span></a>
    </li>
@if($franchise->is_dealer)
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}/cars" itemprop="url"><span itemprop="title">Cars &amp; Trucks</span></a>
    </li>
@endif
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb" class="hidden-xs">
        <a href="{{URL::abs('/')}}/coupons/{{strtolower($locationZipcode->state)}}" itemprop="url"><span itemprop="title">{{strtoupper($locationZipcode->state)}}</span></a>
    </li>
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb" class="hidden-xs">
        <a href="{{URL::abs('/')}}/coupons/{{strtolower($locationZipcode->state)}}/{{SoeHelper::getSlug($locationZipcode->city)}}" itemprop="url"><span itemprop="title">{{ucwords(strtolower($locationZipcode->city))}}</span></a>
    </li>
@if($catName != '')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/{{$catSlug}}" itemprop="url"><span itemprop="title">{{$catName}}</span></a>
    </li>
@endif
@if($catSlug != $subcatSlug && $subcatSlug != '')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/{{$catSlug}}/{{$subcatSlug}}" itemprop="url"><span itemprop="title">{{$subcatName}}</span></a>
    </li>
@endif
    <li class="active">{{$merchant->display}}</li>
@stop

@section('body')

<script>
    merchant_name = "{{$merchant->display}}";
    merchant_id = '{{$merchant->id}}';
    merchant_slug = '{{$entity->merchant_slug}}';
    location_id = '{{$location->id}}';
    franchise_id = '{{$location->franchise_id}}';
    user_id = '{{$user->id}}';
    user_type = '{{$user->getType()}}';
    is_reviewed = '{{$is_reviewed}}';
    is_dealer = '{{$franchise->is_dealer}}';
    make_ids = '{{$make_ids}}';
    special_merchant = '{{$special_merchant}}';
    category_id = '{{$catId}}';
    category_slug = '{{$catSlug}}';
    subcategory_id = '{{$subcatId}}';
    subcategory_slug = '{{$subcatSlug}}';
    usedPage = 0;
    newPage = 0;
    new_car_leads = {{$franchise->is_new_car_leads}};
    used_car_leads = {{$franchise->is_used_car_leads}};
    <?php 
        if(isset($entity))
        {
    ?>
            entity_id = '{{$entity->id}}';
            entitable_type = '{{$entity->entitiable_type}}';
            entitable_id = '{{$entity->entitiable_id}}';
            entity_is_dailydeal = '{{$entity->is_dailydeal}}';
    <?php
        }
        if(isset($usedVehicle))
        {
    ?>
            usedVehicle_id = '{{$usedVehicle->id}}';
    <?php       
        }
    ?>
</script>

<div class="filler">

@if($entity->entitiable_type == 'Offer')
    @if(($entity->secondary_type == 'lease') || ($entity->secondary_type == 'purchase'))
    <div class="modal fade offer lease in" id="leaseModal" tabindex="-1" role="dialog" aria-labelledby="leaseModalLabel" aria-hidden="true" style="display:block">
      <div class="modal-dialog">
        <div class="modal-content" id="printThis">
          <div class="modal-header">
            <a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/{{$entity->category_slug}}/{{($entity->category_slug == $entity->subcategory_slug)?'':$entity->subcategory_slug.'/'}}{{$entity->merchant_slug.'/'.$entity->location_id}}" type="button" class="close">Close &times;</a>
            <span class="h1 modal-title fancy" id="leaseModalLabel">{{$entity->secondary_type == 'lease'?'Lease Special':''}}{{$entity->secondary_type == 'purchase'?'Purchase Offer Special':''}}</span>
            <p class="visible-xs mobile-code"><strong>Code: <span>1211da6f</span></strong></p>
            <div id="expired-alert" class="alert alert-danger margin-top-20 hidden">
              <p><strong>Fiddle Sticks! This lease special is expired...</strong><br>Don't worry though, we have many more amazing offers.</p>
              <br>
              <a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/all" class="btn btn-black">See More Offers</a>
            </div>
          </div>
          <div class="modal-body">
            <div class="row visible-xs margin-bottom-20">
                
              <div class="col-xs-12">
                  <span class="coupon-redemption-message"></span>
              </div>
                
              <div class="clearfix"></div>
                
                  <div class="col-xs-4">
                    <button type="button" class="btn btn-link btn-block btn-lg thumb-down btn-coupon-dislike"><img alt="Dislike" class="margin-bottom-10" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/icons/thumb-down.png"><br>Dislike <span class="red">(<span class="dislikes-count">4</span>)</span></button>
                  </div>
                  <div class="col-xs-4">
                    <button type="button" class="btn btn-link btn-block btn-lg thumb-up btn-coupon-like"><img alt="Like" class="margin-bottom-10" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/icons/thumb-up.png"><br>Like <span class="green">(<span class="likes-count">23</span>)</span></button>
                  </div>
                  <div class="col-xs-4">
                    <button type="button" class="btn btn-darkgrey btn-block btn-lg thumb-up btn-coupon-redeem"><img alt="Print" class="margin-bottom-10" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/icons/print-white.png"><br>Redeem</button>
                  </div>
                
            </div>
            <div class="printable">
                <div class="offer-info">
                  <div id="printed-alert" class="alert alert-success margin-top-20 hidden">
                    <p><strong>You have already printed this coupon</strong><br>You can only print a coupon once.</p>
                  </div>
                  <div class="member-print-alert alert alert-success margin-top-20 hidden">
                    <p><strong>You must be signed in to print or redeem this coupon</strong><br>Please <a data-dismiss="modal" data-toggle="modal" data-target="#signUpModal">sign up</a> or <a data-dismiss="modal" data-toggle="modal" data-target="#signInModal">sign in</a>.</p>
                  </div>
                  <div class="row" id="">
                  <div class="col-xs-12 col-sm-6">
                    <img class="logo img-responsive coupon-path" src="{{$entity->path != $entity->logo ? $entity->path : ($entity->about_img ? $entity->about_img : $entity->path)}}" alt="{{$entity->merchant_name}}" class="img-responsive">
                    <img class="img-responsive company-logo" src="/img/logo-small.png" alt="Save On">
                  </div>
                    <div class="col-xs-12 col-sm-6 border-left" id="">
                      <span class="h2 location-title hblock">{{$entity->merchant_name}}</span>
                      <span class="h1 coupon-title">{{$entity->name}}</span>
                      <div class="coupon-description">{{$offer->description}}</div>
                      <p><em><strong>Expires:</strong> <span class="coupon-expire">{{date('m-d-Y', strtotime($entity->expires_at))}}</span></em></p>
                      <p class="offer-code" style="display:none;"><strong>Offer Code: <span>1211da6f</span></strong></p>
                      <p class="coupon-code"><strong>Code: <span class="">1211da6f</span></strong></p>
                    </div>
                  </div>
                  <div class="row default-disclaimer" id="">
                    <div class="col-xs-12" id="">
                      <p class="small"><small><strong>RETAILER:</strong> Void if altered, copied, sold, purchased, transfered, exchanged or where prohibited or restricted by law. Do not accept after the expiration.<br>
                        <strong>CONSUMER:</strong> No other coupon may be used with this coupon. Consumer pays any sales tax.</small></p>
                    </div>
                  </div>
                </div>
            </div>
            <div class="row options">
              <div class="col-xs-6">
                <button type="button" class="btn btn-block btn-default btn-coupon-clip"><img src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/soct/masonry-icons/save_it_new_car.png" alt="Save It" class="img-circle"><span class="coupon-save-text">Save It</span></button>
              </div>
              <div class="col-xs-6">
                <button type="button" class="btn btn-block btn-default btn-coupon-share"><img src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/soct/masonry-icons/share_it_new_car.png" alt="Share It" class="img-circle">Share It</button>
              </div>
            </div>
            <div class="row options-gray">
              <div class="hidden-xs">
                <div class="col-xs-4">
                  <button type="button" class="btn btn-link btn-block btn-lg thumb-down btn-coupon-dislike"><img class="margin-bottom-10" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/icons/thumb-down.png"><br>Dislike <span class="red">(<span class="dislikes-count">4</span>)</span></button>
                </div>
                <div class="col-xs-4">
                  <button type="button" class="btn btn-link btn-block btn-lg thumb-up btn-coupon-like"><img class="margin-bottom-10" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/icons/thumb-up.png"><br>Like <span class="green">(<span class="likes-count">23</span>)</span></button>
                </div>
                <div class="col-xs-4">
                  <button type="button" class="btn btn-darkgrey btn-block btn-lg thumb-up btn-coupon-print" data-loading-text="Printing"><img class="margin-bottom-10" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/icons/print-white.png"><br><span class="print-text">Print</span></button>
                  <button type="button" class="btn btn-darkgrey btn-block btn-lg btn-mobile-only disabled" style="display:none;"><span class="glyphicon glyphicon-ban-circle"></span><br>Mobile Only<br>Offer</button>
                </div>
              </div>
                <div class="col-xs-12 footer clearfix margin-top-20 default-row">
                  <div class="row">
                    <div class="col-sm-6">
                      <a href="{{URL::abs('/')}}/directions/{{$merchant->slug}}" type="button" class="btn btn-block btn-burgundy btn-view-locations margin-bottom-20"><span style="margin-right: 7px;" class="glyphicon glyphicon-map-marker"></span>Other Locations</a>
                    </div>
                    <div class="clearfix visible-xs"></div>
                    <div class="col-sm-6">
                      <a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/{{$entity->category_slug}}/{{($entity->category_slug == $entity->subcategory_slug)?'':$entity->subcategory_slug.'/'}}{{$entity->merchant_slug.'/'.$entity->location_id}}" type="button" class="btn btn-block btn-darkgrey btn-view-business"><span style="margin-right: 7px;" class="glyphicon glyphicon-info-sign"></span>View Business</a>
                    </div>
                  </div>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    @elseif($entity->is_dailydeal == '1')
    <div class="modal fade offer save-today in" id="saveTodayModal" tabindex="-1" role="dialog" aria-labelledby="saveTodayModalLabel" aria-hidden="true" style="display:block">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/{{$entity->category_slug}}/{{($entity->category_slug == $entity->subcategory_slug)?'':$entity->subcategory_slug.'/'}}{{$entity->merchant_slug.'/'.$entity->location_id}}" type="button" class="close">Close &times;</a>
            <span class="h1 modal-title fancy save-today" id="saveTodayModalLabel">Save Today</span>
            <p class="visible-xs mobile-code"><strong>Code: <span>1211da6f</span></strong></p>
            <div id="expired-alert" class="alert alert-danger margin-top-20 hidden">
              <p><strong>Fiddle Sticks! This deal is expired...</strong><br>Don't worry though, we have many more amazing offers.</p>
              <br>
              <a href="{{URL::abs('/')}}/dailydeals/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/all" class="btn btn-black">See More Offers</a>
            </div>
          </div>
          <div class="modal-body save-today">
            <div class="row visible-xs margin-bottom-20">
                <div class="col-xs-12">
                  <span class="coupon-redemption-message"></span>
                </div>
                <div class="col-xs-4">
                  <button type="button" class="btn btn-link btn-block btn-lg thumb-down btn-coupon-dislike"><img alt="Dislike" class="margin-bottom-10" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/icons/thumb-down.png"><br>Dislike <span class="red">(<span class="dislikes-count">4</span>)</span></button>
                </div>
                <div class="col-xs-4">
                  <button type="button" class="btn btn-link btn-block btn-lg thumb-up btn-coupon-like"><img alt="Like" class="margin-bottom-10" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/icons/thumb-up.png"><br>Like <span class="green">(<span class="likes-count">23</span>)</span></button>
                </div>
                <div class="col-xs-4">
                  <button type="button" class="btn btn-darkgrey btn-block btn-lg thumb-up btn-coupon-redeem"><img alt="Redeem" class="margin-bottom-10" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/icons/print-white.png"><br>Redeem</button>
                </div>
            </div>
            <div class="printable">
                <div class="offer-info">
                  <div id="printed-alert" class="alert alert-success margin-top-20 hidden">
                    <p><strong>You have already printed this coupon</strong><br>You can only print a coupon once.</p>
                  </div>
                  <div class="member-print-alert alert alert-success margin-top-20 hidden">
                    <p><strong>You must be signed in to print or redeem this coupon</strong><br>Please <a data-dismiss="modal" data-toggle="modal" data-target="#signUpModal">sign up</a> or <a data-dismiss="modal" data-toggle="modal" data-target="#signInModal">sign in</a>.</p>
                  </div>
                  <div class="row">
                  <div class="col-xs-12">
                    <img class="img-responsive coupon-path" src="{{$entity->path != $entity->logo ? $entity->path : ($entity->about_img ? $entity->about_img : $entity->path)}}" alt="{{$entity->merchant_name}}" class="img-responsive">
                  </div>
                    <div class="col-xs-12 info-padding">
                      <span class="h2 location-title">{{$entity->merchant_name}}</span>
                      <span class="h1 coupon-title">{{$entity->name}}</span>
                      <div class="coupon-description">{{$offer->description}}</div>
                      <p><em><strong>Expires:</strong> <span class="coupon-expire">9h : 32m : 17s</span></em></p>
                      <p class="offer-code" style="display:none;"><strong>Offer Code: <span>1211da6f</span></strong></p>
                      <p class="coupon-code"><strong>Code: <span class="">1211da6f</span></strong></p>
                      <img class="img-responsive pull-left company-logo" src="/img/logo-small.png" alt="Save On">
                    </div>
                  </div>
                  <div class="row default-disclaimer">
                    <div class="col-xs-12">
                      <p class="small"><small><strong>RETAILER:</strong> Void if altered, copied, sold, purchased, transfered, exchanged or where prohibited or restricted by law. Do not accept after the expiration.<br>
                        <strong>CONSUMER:</strong> No other coupon may be used with this coupon. Consumer pays any sales tax.</small></p>
                    </div>
                  </div>
                </div>
            </div>
            <div class="row options">
              <div class="col-xs-6">
                <button type="button" class="btn btn-block btn-default btn-coupon-clip"><img src="http://s3.amazonaws.com/saveoneverything_assets/images/masonry-icons/save_it_save_today.png" alt="Save It" class="img-circle">Save It</button>
              </div>
              <div class="col-xs-6">
                <button type="button" class="btn btn-block btn-default btn-coupon-share"><img src="http://s3.amazonaws.com/saveoneverything_assets/images/masonry-icons/share_it_save_today.png" alt="Share It" class="img-circle">Share It</button>
              </div>
            </div>
            <div class="row options-gray">
              
                <div class="col-xs-4">
                  <button type="button" class="btn btn-link btn-block btn-lg thumb-down btn-coupon-dislike"><img alt="Dislike" class="margin-bottom-10" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/icons/thumb-down.png"><br>Dislike <span class="red">(<span class="dislikes-count">4</span>)</span></button>
                </div>
                <div class="col-xs-4">
                  <button type="button" class="btn btn-link btn-block btn-lg thumb-up btn-coupon-like"><img alt="Like" class="margin-bottom-10" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/icons/thumb-up.png"><br>Like <span class="green">(<span class="likes-count">23</span>)</span></button>
                </div>
                <div class="col-xs-4">
                  <button type="button" class="btn btn-darkgrey btn-block btn-lg thumb-up btn-coupon-print" data-loading-text="Printing"><img alt="Print" class="margin-bottom-10" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/icons/print-white.png"><br><span class="print-text">Print</span></button>
                  <button type="button" class="btn btn-darkgrey btn-block btn-lg btn-mobile-only disabled" style="display:none;"><span class="glyphicon glyphicon-ban-circle"></span><br>Mobile Only<br>Offer</button>
                </div>
                <div class="clearfix">
                </div>

                <div class="col-xs-12 footer">
                  <div class="row">
                    <div class="col-xs-12 truncated-about">
                      <span class="h3 spaced">About This Merchant</span>
                      <p><span class="daily-merchant-about">{{$entity->merchant_about_truncated}} </span><a class="daily-merchant-more" href="#">Read More</a></p>
                    </div>
                    <div class="col-xs-12 full-about">
                      <span class="h3 spaced">About This Merchant</span>
                      <p><span class="full-about-text">{{$entity->merchant_about}} </span></p>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-xs-6">
                      <a href="{{URL::abs('/')}}/directions/{{$merchant->slug}}" type="button" class="btn btn-block btn-blue btn-view-locations"><span style="margin-right: 7px;" class="glyphicon glyphicon-map-marker"></span>View Other Locations</a>
                    </div>
                    <div class="col-xs-6">
                      <a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/{{$entity->category_slug}}/{{($entity->category_slug == $entity->subcategory_slug)?'':$entity->subcategory_slug.'/'}}{{$entity->merchant_slug.'/'.$entity->location_id}}" type="button" class="btn btn-block btn-darkgrey btn-view-business"><span style="margin-right: 7px;" class="glyphicon glyphicon-info-sign"></span>View Business</a>
                    </div>
                  </div>
                </div>
                
            </div>
          </div>
        </div>
      </div>
    </div>
    @else
    <div class="modal fade offer in" id="couponModal" tabindex="-1" role="dialog" aria-labelledby="couponModalLabel" aria-hidden="true" style="display:block">
      <div class="modal-dialog">
        <div class="modal-content" id="printThis">
          <div class="modal-header">
            <a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/{{$entity->category_slug}}/{{($entity->category_slug == $entity->subcategory_slug)?'':$entity->subcategory_slug.'/'}}{{$entity->merchant_slug.'/'.$entity->location_id}}" type="button" class="close">Close &times;</a>
            <span class="h1 modal-title fancy"id="couponModalLabel">Coupon</span>
            <p class="visible-xs mobile-code"><strong>Code: <span>1211da6f</span></strong></p>
            <div id="expired-alert" class="alert alert-danger margin-top-20 hidden">
              <p><strong>Fiddle Sticks! This coupon is expired...</strong><br>Don't worry though, we have many more amazing offers.</p>
              <br>
              <a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/all" class="btn btn-black">See More Offers</a>
            </div>
          </div>
          <div class="modal-body">
            <div class="row visible-xs margin-bottom-20">
                
              <div class="col-xs-12">
                  <span class="coupon-redemption-message"></span>
              </div>
                
              <div class="clearfix"></div>
                
                  <div class="col-xs-4">
                    <button type="button" class="btn btn-link btn-block btn-lg thumb-down btn-coupon-dislike"><img alt="Dislike" class="margin-bottom-10" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/icons/thumb-down.png"><br>Dislike <span class="red">(<span class="dislikes-count">4</span>)</span></button>
                  </div>
                  <div class="col-xs-4">
                    <button type="button" class="btn btn-link btn-block btn-lg thumb-up btn-coupon-like"><img alt="Like" class="margin-bottom-10" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/icons/thumb-up.png"><br>Like <span class="green">(<span class="likes-count">23</span>)</span></button>
                  </div>
                  <div class="col-xs-4">
                    <button type="button" class="btn btn-darkgrey btn-block btn-lg thumb-up btn-coupon-redeem"><img alt="Print" class="margin-bottom-10" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/icons/print-white.png"><br>Redeem</button>
                  </div>

            </div>
            <div class="printable">
                <div class="offer-info">
                  <div id="printed-alert" class="alert alert-success margin-top-20 hidden">
                    <p><strong>You have already printed this coupon</strong><br>You can only print a coupon once.</p>
                  </div>
                  <div class="member-print-alert alert alert-success margin-top-20 hidden">
                    <p><strong>You must be signed in to print or redeem this coupon</strong><br>Please <a data-dismiss="modal" data-toggle="modal" data-target="#signUpModal">sign up</a> or <a data-dismiss="modal" data-toggle="modal" data-target="#signInModal">sign in</a>.</p>
                  </div>
                  <div class="row" id="">
                  <div class="col-xs-12 col-sm-6">
                    <img class="logo img-responsive coupon-path" src="{{$entity->path != $entity->logo ? $entity->path : ($entity->about_img ? $entity->about_img : $entity->path)}}" alt="{{$entity->merchant_name}}" class="img-responsive">
                    <img class="img-responsive company-logo" src="/img/logo-small.png" alt="Save On">
                  </div>
                    <div class="col-xs-12 col-sm-6 border-left" id="">
                      <span class="h2 location-title hblock">{{$entity->merchant_name}}</span>
                      <span class="h1 coupon-title">{{$entity->name}}</span>
                      <div class="coupon-description">{{$offer->description}}</div>
                      <p><em><strong>Expires:</strong> <span class="coupon-expire">12-05-2013</span></em></p>
                      <p class="offer-code" style="display:none;"><strong>Offer Code: <span>1211da6f</span></strong></p>
                      <p class="coupon-code"><strong>Code: <span class="">1211da6f</span></strong></p>
                    </div>
                  </div>
                  <div class="row default-disclaimer" id="">
                    <div class="col-xs-12" id="">
                      <p class="small"><small><strong>RETAILER:</strong> Void if altered, copied, sold, purchased, transfered, exchanged or where prohibited or restricted by law. Do not accept after the expiration.<br>
                        <strong>CONSUMER:</strong> No other coupon may be used with this coupon. Consumer pays any sales tax.</small></p>
                    </div>
                  </div>
                </div>
            </div>
            <div class="row options">
              <div class="col-xs-6">
                <button type="button" class="btn btn-block btn-default btn-coupon-clip"><img src="http://s3.amazonaws.com/saveoneverything_assets/images/masonry-icons/save_it_coupon.png" alt="Save It" class="img-circle"><span class="coupon-save-text">Save It</span></button>
              </div>
              <div class="col-xs-6">
                <button type="button" class="btn btn-block btn-default btn-coupon-share"><img src="http://s3.amazonaws.com/saveoneverything_assets/images/masonry-icons/share_it_coupon.png" alt="Share It" class="img-circle">Share It</button>
              </div>
            </div>
            <div class="row options-gray">
              <div class="hidden-xs">
                <div class="col-xs-4">
                  <button type="button" class="btn btn-link btn-block btn-lg thumb-down btn-coupon-dislike"><img class="margin-bottom-10" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/icons/thumb-down.png"><br>Dislike <span class="red">(<span class="dislikes-count">4</span>)</span></button>
                </div>
                <div class="col-xs-4">
                  <button type="button" class="btn btn-link btn-block btn-lg thumb-up btn-coupon-like"><img class="margin-bottom-10" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/icons/thumb-up.png"><br>Like <span class="green">(<span class="likes-count">23</span>)</span></button>
                </div>
                <div class="col-xs-4">
                  <button type="button" class="btn btn-darkgrey btn-block btn-lg thumb-up btn-coupon-print" data-loading-text="Printing"><img class="margin-bottom-10" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/icons/print-white.png"><br><span class="print-text">Print</span></button>
                  <button type="button" class="btn btn-darkgrey btn-block btn-lg btn-mobile-only disabled" style="display:none;"><span class="glyphicon glyphicon-ban-circle"></span><br>Mobile Only<br>Offer</button>
                </div>
              </div>
                <div class="col-xs-12 footer clearfix margin-top-20 default-row">
                  <div class="row">
                    <div class="col-sm-6">
                      <a href="{{URL::abs('/')}}/directions/{{$merchant->slug}}" type="button" class="btn btn-block btn-green btn-view-locations margin-bottom-20"><span style="margin-right: 7px;" class="glyphicon glyphicon-map-marker"></span>Other Locations</a>
                    </div>
                    <div class="clearfix visible-xs"></div>
                    <div class="col-sm-6">
                      <a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/{{$entity->category_slug}}/{{($entity->category_slug == $entity->subcategory_slug)?'':$entity->subcategory_slug.'/'}}{{$entity->merchant_slug.'/'.$entity->location_id}}" type="button" class="btn btn-block btn-darkgrey btn-view-business"><span style="margin-right: 7px;" class="glyphicon glyphicon-info-sign"></span>View Business</a>
                    </div>
                  </div>
                </div>
                <div class="col-xs-12 footer clearfix margin-top-20 save-certified-row">
                  <div class="row">
                    <div class="col-sm-4">
                      <a href="{{URL::abs('/')}}/directions/{{$merchant->slug}}" type="button" class="btn btn-block btn-green btn-view-locations margin-bottom-20"><span style="margin-right: 7px;" class="glyphicon glyphicon-map-marker"></span>Other Locations</a>
                    </div>
                    <div class="clearfix visible-xs"></div>
                    <div class="col-sm-4">
                      <a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/{{$entity->category_slug}}/{{($entity->category_slug == $entity->subcategory_slug)?'':$entity->subcategory_slug.'/'}}{{$entity->merchant_slug.'/'.$entity->location_id}}" type="button" class="btn btn-block btn-darkgrey btn-view-business"><span style="margin-right: 7px;" class="glyphicon glyphicon-info-sign"></span>View Business</a>
                    </div>

                    <div class=" margin-top-20 clearfix visible-xs"></div>
                    @if($quote_control)
                    <div class="col-sm-4">
                      <a href="{{URL::abs('/')}}{{'/homeimprovement/quote?offer_id='.$entity->entitiable_id}}" type="button" class="btn btn-block btn-red btn-get-quote"><span style="margin-right: 7px;" class="glyphicon glyphicon-ok-circle"></span>Get a Quote</a>
                    </div>
                    @endif
                  </div>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    @endif
@elseif($entity->entitiable_type == 'Contest')
<div class="modal fade contest in" id="contestModal" tabindex="-1" role="dialog" aria-labelledby="contestModalLabel" aria-hidden="true" style="display:block">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <span class="h1 modal-title contest fancy" id="contestModalLabel">Contest</span>
        <div id="expired-alert" class="alert alert-danger margin-top-20 hidden">
          <p><strong>Fiddle Sticks! This contest is expired...</strong><br>Don't worry though, we have many more amazing contests.</p>
          <br>
          <a href="{{URL::abs('/')}}/contests/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/all" class="btn btn-black">See More Contests</a>
        </div>
      </div>
      <div class="modal-body contest">
          <div class="top-pic margin-bottom-20">
            <img class="img-responsive contest-banner" src="" alt="Contest Name">
          </div>
          <span class="h1 contest-title"></span>
          <div class="contest-description"></div>
          <form role="form">
            <div class="row">
              <div class="col-xs-12">
                <span class="h3 spaced text-center">Confirm Your Details</span>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <div class="form-group">
                  <span class="h3">Email</span>
                  <input type="text" class="form-control" id="contestEntryEmail" name="contestEntryEmail" placeholder="Email *" value="{{(Auth::check()?Auth::user()->email:'')}}">
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <span class="h3">Zip Code</span>
                  <input type="text" class="form-control" id="contestEntryZip" name="contestEntryZip" placeholder="Zip Code *">
                </div>
              </div>
            </div>
            <p class="required-disclaimer">* These fields are required</p>
            <p>We hate spam as much as you do. We promise to never sell your information or use it inappropriately. For more info see our <a data-toggle="modal" data-target="#termsModal">terms</a> and <a data-toggle="modal" data-target="#privacyModal">privacy policy</a>.</p>
            <hr>
            <div class="form-group">
              <div class="checkbox">
                <label>
                  <input type="checkbox" value="" id="contestEntryRules" name="contestEntryRules">
                  <strong class="warning">Please check the checkbox<br></strong>
                  I have read and agree to the <a data-toggle="modal" data-target="#contestRulesModal">contest rules</a>
                </label>
              </div>
            </div>
            <button type="button" class="btn btn-black btn-block btn-lg center-block btn-enter-contest">ENTER CONTEST</button>
            <div class="clearfix"></div>
            <p class="text-center margin-top-20"><span class="contestMerchantLink" style="display:none;">View <a href="#">Merchant</a> | </span>Read the <a data-toggle="modal" data-target="#contestRulesModal">contest rules</a></p>
          </form>

        </div>
    </div>
  </div>
</div>

@endif

</div>

@stop
