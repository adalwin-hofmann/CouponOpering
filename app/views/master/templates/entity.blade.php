<?php
    $company_slug = '';
    if($entity->company_id > 1)
    {
        $company_slug = SoeHelper::getSlug($entity->company_name);
    }

    $features = App::make('FeatureRepositoryInterface');
    $quote_control = $features->findByName('master_quotes_control');
    $quote_control = empty($quote_control) ? 0 : $quote_control->value;
    $detroit_quote_control = $features->findByName('detroit_quotes_only');
    $detroit_quote_control = empty($detroit_quote_control) ? 120 : $detroit_quote_control->value;
    if($detroit_quote_control)
    {
        $distance = GeometryHelper::getDistance($geoip->latitude, $geoip->longitude, 42.38, -83.10);
        $detroit_quote_control = ($distance < $detroit_quote_control && $geoip->region_name == 'MI') ? 1 : 0;
        $quote_control = $quote_control && $detroit_quote_control;
    }
?>
@if($entity->entitiable_type == 'Offer')
    @if(($entity->secondary_type == 'lease') || ($entity->secondary_type == 'purchase'))
        <div class="item {{$entity->secondary_type}}" itemscope itemtype="http://schema.org/Organization">
            <div class="item-type {{$entity->secondary_type}}"></div>
            <div class="top-pic">
                <a title="{{$entity->merchant_name}} Purchases and Leases in {{$geoip->city_name}}, {{$geoip->region_name}}" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/{{$entity->category_slug}}/{{($entity->category_slug == $entity->subcategory_slug)?'':$entity->subcategory_slug.'/'}}{{$entity->merchant_slug.'/'.$entity->location_id}}">
                    <div class="expired-banner"><span class="h3 spaced">Expired</span></div>
                    <img alt="{{$entity->merchant_name}} Purchases and Leases in {{$geoip->city_name}}, {{$geoip->region_name}}" class="img-responsive" src="{{$entity->path != $entity->logo ? $entity->path : ($entity->about_img ? $entity->about_img : $entity->path)}}">
                </a>
            </div>
            <a class="merchant-link" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/{{$entity->category_slug}}/{{($entity->category_slug == $entity->subcategory_slug)?'':$entity->subcategory_slug.'/'}}{{$entity->merchant_slug.'/'.$entity->location_id}}">
                <div class="item-info">
                    <p class="merchant-name" itemprop="name">{{$entity->merchant_name}}</p>
                </div>
            </a>
            <div itemscope itemtype="http://schema.org/Product">
                <span class="hidden" itemprop="name">{{$entity->merchant_name}} - {{$entity->name}}</span>
                <div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                    <a class="item-info" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/{{$entity->category_slug}}/{{($entity->category_slug == $entity->subcategory_slug)?'':$entity->subcategory_slug.'/'}}{{$entity->merchant_slug.'/'.$entity->location_id . '?showeid=' . $entity->id}}"itemscope itemtype="http://schema.org/Product">
                        <span class="h3" itemprop="name">{{$entity->name}}</span>
                    </a>
                    <p class="category-links"><a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/{{$entity->category_slug}}" title="{{SoeHelper::unSlugCategory($entity->category_slug)}} Coupons">{{SoeHelper::unSlugCategory($entity->category_slug)}}</a> > <a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/{{$entity->category_slug}}/{{($entity->category_slug == $entity->subcategory_slug)?'':$entity->subcategory_slug}}" title="{{SoeHelper::unSlugCategory($entity->subcategory_slug)}} Coupons">{{SoeHelper::unSlugCategory($entity->subcategory_slug)}}</a></p>
                    <div class="margin-10 expires_at">
                    @if(!$entity->hide_expiration)
                        <span itemprop="availabilityEnds"><strong>Expires</strong> {{$entity->is_reoccurring ? date('m/t/Y') : date('m/d/Y', strtotime($entity->expires_at))}}</span>
                    @endif
                    &nbsp;
                    </div>
                    <span itemprop="price" class="hidden">{{$entity->special_price}}</span>
                </div>
            </div>
            @if($entity->is_certified)
            <div class="certified_section">
              <div class="row">
                <div class="col-xs-4">
                  <img alt="Save Certified" class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sohi/save_certified.png" width="68" height="57">
                </div>
                <div class="col-xs-6">
                  <div class="h2 spaced">Save Certified</div>
                </div>
              </div>
            </div>
            @elseif($entity->company_id > 1)
            <div class="white-label">
                <p>Provided by @if($company_slug != 'yipit')<a href="{{URL::abs('/')}}/{{$company_slug}}">{{$entity->company_name}}</a>@else{{$entity->company_name}}@endif</p>
            </div>
            @else
            <div class="see-more">
                @if(isset($merchant_page))
                    <button class="btn btn-block btn-burgundy-border btn-lg btn-get-coupon" data-offer_id="{{$entity->entitiable_id}}" data-entity_id="{{$entity->id}}">View Vehicle</button>
                @else
                    <a class="btn btn-block btn-burgundy-border btn-lg" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/{{$entity->category_slug}}/{{($entity->category_slug == $entity->subcategory_slug)?'':$entity->subcategory_slug.'/'}}{{$entity->merchant_slug.'/'.$entity->location_id}}">
                    @if($entity->total_entities > 1)
                        View <strong>{{$entity->total_entities - 1}}</strong> more offer{{($entity->total_entities > 2)?"s":""}}
                    @else
                        View all offers
                    @endif
                    </a>
                @endif
            </div>
            @endif
            <div class="btn-group">
                <button type="button" class="btn btn-default btn-get-coupon" data-offer_id="{{$entity->entitiable_id}}" data-entity_id="{{$entity->id}}" data-toggle="tooltip" data-placement="top" title="Get Info on {{$entity->name}} from {{$entity->merchant_name}}"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-burgundy-more-info.png" alt="Get Info on {{$entity->name}} from {{$entity->merchant_name}}"></button>
                <button type="button" class="btn btn-default btn-lease-contact" data-offer_id="{{$entity->entitiable_id}}" data-entity_id="{{$entity->id}}" data-toggle="tooltip" data-placement="top" title="Contact {{$entity->merchant_name}} about {{$entity->name}}"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-burgundy-contact.png" alt="Contact {{$entity->merchant_name}} about {{$entity->name}}"></button>
                <button type="button" class="btn btn-default btn-coupon-share" data-offer_id="{{$entity->entitiable_id}}" data-entity_id="{{$entity->id}}" data-toggle="tooltip" data-placement="top" title="Share This Lease Special for {{$entity->name}} from {{$entity->merchant_name}}"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-burgundy-share-it.png" alt="Share This Lease Special for {{$entity->name}} from {{$entity->merchant_name}}"></button>
            </div>
            @if(isset($entity->city))
            <div class="city-state text-center">
                <span>{{ ucwords(strtolower($entity->city)).', '.strtoupper($entity->state)}}</span>
            </div>
            @endif
        </div>
    @elseif($entity->is_dailydeal == '1')
        <div class="item save-today {{ $entity->is_certified ? 'save_certified_offer' : ''}}" itemscope itemtype="http://schema.org/Organization">
            <div class="item-type save-today"></div>
            <div class="top-pic">
                <a title="{{$entity->merchant_name}} Coupons in {{$geoip->city_name}}, {{$geoip->region_name}}" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/{{$entity->category_slug}}/{{($entity->category_slug == $entity->subcategory_slug)?'':$entity->subcategory_slug.'/'}}{{$entity->merchant_slug.'/'.$entity->location_id}}">
                    <div class="expired-banner"><span class="h3 spaced">Expired</span></div>
                    <img alt="{{$entity->merchant_name}} Coupons in {{$geoip->city_name}}, {{$geoip->region_name}}" class="img-responsive" src="{{$entity->path != $entity->logo ? $entity->path : ($entity->about_img ? $entity->about_img : $entity->path)}}">
                </a>
            </div>
            <div class="row">
                <div class="merchant-offer-count col-xs-12">
                    <a class="item-info" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/{{$entity->category_slug}}/{{($entity->category_slug == $entity->subcategory_slug)?'':$entity->subcategory_slug.'/'}}{{$entity->merchant_slug.'/'.$entity->location_id}}">
                        <p class="merchant-name" itemprop="name">{{$entity->merchant_name}}</p>
                    </a>
                </div>
            </div>
            <div itemscope itemtype="http://schema.org/Product">
                <span class="hidden" itemprop="name">{{$entity->merchant_name}} - {{$entity->name}}</span>
                <div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                    <a class="item-info" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/{{$entity->category_slug}}/{{($entity->category_slug == $entity->subcategory_slug)?'':$entity->subcategory_slug.'/'}}{{$entity->merchant_slug.'/'.$entity->location_id . '?showeid=' . $entity->id}}"itemscope itemtype="http://schema.org/Product">
                        <span class="h3" itemprop="name">{{$entity->name}}</span>
                    </a>
                    <p class="category-links"><a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/{{$entity->category_slug}}" title="{{SoeHelper::unSlugCategory($entity->category_slug)}} Coupons">{{SoeHelper::unSlugCategory($entity->category_slug)}}</a> > <a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/{{$entity->category_slug}}/{{($entity->category_slug == $entity->subcategory_slug)?'':$entity->subcategory_slug}}" title="{{SoeHelper::unSlugCategory($entity->subcategory_slug)}} Coupons">{{SoeHelper::unSlugCategory($entity->subcategory_slug)}}</a></p>
                    <div class="margin-10 expires_at">
                    @if(!$entity->hide_expiration)
                        <span itemprop="availabilityEnds"><strong>Expires</strong> {{$entity->is_reoccurring ? date('m/t/Y') : date('m/d/Y', strtotime($entity->expires_at))}}</span>
                    @endif
                    &nbsp;
                    </div>
                    <span itemprop="price" class="hidden">{{$entity->special_price}}</span>
                </div>
            </div>
            @if($entity->is_certified)
            <div class="certified_section">
              <div class="row">
                <div class="col-xs-4">
                  <img alt="Save Certified" class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sohi/save_certified.png" width="68" height="57">
                </div>
                <div class="col-xs-6">
                  <div class="h2 spaced">Save Certified</div>
                </div>
              </div>
            </div>
            @elseif($entity->company_id > 1)
            <div class="white-label">
              <p>Provided by @if($company_slug != 'yipit')<a href="{{URL::abs('/')}}/{{$company_slug}}">{{$entity->company_name}}</a>@else{{$entity->company_name}}@endif</p>
            </div>
            @else
            <div class="see-more">
                @if(isset($merchant_page))
                    <button class="btn btn-block btn-blue-border btn-lg btn-get-coupon" data-offer_id="{{$entity->entitiable_id}}" data-entity_id="{{$entity->id}}">View Save Today</button>
                @else
                    <a class="btn btn-block btn-blue-border btn-lg" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/{{$entity->category_slug}}/{{($entity->category_slug == $entity->subcategory_slug)?'':$entity->subcategory_slug.'/'}}{{$entity->merchant_slug.'/'.$entity->location_id}}">
                    @if($entity->total_entities > 1)
                        View <strong>{{$entity->total_entities - 1}}</strong> more offer{{($entity->total_entities > 2)?"s":""}}
                    @else
                        View all offers
                    @endif
                    </a>
                @endif
            </div>
            @endif
            <div class="btn-group">
                <button type="button" class="btn btn-default btn-get-coupon" data-offer_id="{{$entity->entitiable_id}}" data-entity_id="{{$entity->id}}" data-toggle="tooltip" data-placement="top" title="Get Info on {{$entity->name}} from {{$entity->merchant_name}}"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-get-it.png" alt="Get Info on {{$entity->name}} from {{$entity->merchant_name}}" class=""></button>
                <button type="button" class="btn btn-default btn-save-coupon {{$entity->is_clipped == true ? 'disabled' : ''}}" data-offer_id="{{$entity->entitiable_id}}" data-entity_id="{{$entity->id}}" data-toggle="tooltip" data-placement="top" title="Save This Save Today for {{$entity->name}} from {{$entity->merchant_name}}"><img src="{{$entity->is_clipped == true ? 'http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-saved.png':'http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-save-it.png'}}" alt="Save This Save Today for {{$entity->name}} from {{$entity->merchant_name}}" class=""></button>
                @if(!$quote_control || (!$entity->is_certified && !$entity->is_sohi_trial))
                <button type="button" class="btn btn-default btn-coupon-share" data-offer_id="{{$entity->entitiable_id}}" data-entity_id="{{$entity->id}}" data-toggle="tooltip" data-placement="top" title="Share This Save Today for {{$entity->name}} from {{$entity->merchant_name}}"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-share-it.png" alt="Share This Save Today for {{$entity->name}} from {{$entity->merchant_name}}" class=""></button>
                @else
                <a href="{{URL::abs('/')}}{{'/homeimprovement/quote?offer_id='.$entity->entitiable_id}}" type="button" class="btn btn-default btn-coupon-quote" data-offer_id="{{$entity->entitiable_id}}" data-entity_id="{{$entity->id}}" data-toggle="tooltip" data-placement="top" title="Get a Quote for {{$entity->name}} from {{$entity->merchant_name}}"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-blue-quote-it.png" alt="Get a Quote for {{$entity->name}} from {{$entity->merchant_name}}"></a>
                @endif
            </div>
            @if(isset($entity->city))
            <div class="city-state text-center">
                <span>{{ ucwords(strtolower($entity->city)).', '.strtoupper($entity->state)}}</span>
            </div>
            @endif
        </div>
    @else
        <div class="item coupon {{$entity->is_certified == 1 ? 'save_certified_offer' : ''}}" itemscope itemtype="http://schema.org/Organization">
            <div class="item-type coupon"></div>
            <div class="top-pic {{($entity->company_id == 2)?'yipit':''}}">
                <a title="{{$entity->merchant_name}} Coupons in {{$geoip->city_name}}, {{$geoip->region_name}}" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/{{$entity->category_slug}}/{{($entity->category_slug == $entity->subcategory_slug)?'':$entity->subcategory_slug.'/'}}{{$entity->merchant_slug.'/'.$entity->location_id}}">
                    <div class="expired-banner"><span class="h3 spaced">Expired</span></div>
                    <img alt="{{$entity->merchant_name}} Coupons in {{$geoip->city_name}}, {{$geoip->region_name}}" class="img-responsive" src="{{$entity->merchant_logo ? $entity->merchant_logo : ($entity->path != $entity->logo ? $entity->path : ($entity->about_img ? $entity->about_img : $entity->path))}}" itemprop="image">
                </a>
            </div>
            <div class="row">
                <div class="merchant-offer-count col-xs-12">
                    <a class="item-info" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/{{$entity->category_slug}}/{{($entity->category_slug == $entity->subcategory_slug)?'':$entity->subcategory_slug.'/'}}{{$entity->merchant_slug.'/'.$entity->location_id}}">
                        <p class="merchant-name" itemprop="name">{{$entity->merchant_name}}</p>
                    </a>
                </div>
            </div>
            <div itemscope itemtype="http://schema.org/Product">
                <span class="hidden" itemprop="name">{{$entity->merchant_name}} - {{$entity->name}}</span>
                <div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                    <a class="item-info {{($entity->company_id > 1)?'third-party':''}}" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/{{$entity->category_slug}}/{{($entity->category_slug == $entity->subcategory_slug)?'':$entity->subcategory_slug.'/'}}{{$entity->merchant_slug.'/'.$entity->location_id . '?showeid=' . $entity->id}}" itemscope itemtype="http://schema.org/Product">
                        <div class="h3" itemprop="name">{{$entity->name}}</div>
                    </a>
                    <p class="category-links"><a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/{{$entity->category_slug}}" title="{{SoeHelper::unSlugCategory($entity->category_slug)}} Coupons">{{SoeHelper::unSlugCategory($entity->category_slug)}}</a> > <a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/{{$entity->category_slug}}/{{($entity->category_slug == $entity->subcategory_slug)?'':$entity->subcategory_slug}}" title="{{SoeHelper::unSlugCategory($entity->subcategory_slug)}} Coupons">{{SoeHelper::unSlugCategory($entity->subcategory_slug)}}</a></p>
                    <div class="margin-10 expires_at">
                    @if(!$entity->hide_expiration)
                        <span itemprop="availabilityEnds"><strong>Expires</strong> {{$entity->is_reoccurring ? date('m/t/Y') : date('m/d/Y', strtotime($entity->expires_at))}}</span>
                    @endif
                    &nbsp;
                    </div>
                    <span itemprop="price" class="hidden">{{$entity->special_price}}</span>
                </div>
            </div>
            @if($entity->is_certified)
            <div class="certified_section">
              <div class="row">
                <div class="col-xs-4">
                  <img alt="Save Certified" class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sohi/save_certified.png" width="68" height="57">
                </div>
                <div class="col-xs-6">
                  <div class="h2 spaced">Save Certified</div>
                </div>
              </div>
            </div>
            @elseif($entity->company_id > 1)
            <div class="white-label">
              <p>Provided by @if($company_slug != 'yipit')<a href="{{URL::abs('/')}}/{{$company_slug}}">{{$entity->company_name}}</a>@else{{$entity->company_name}}@endif</p>
            </div>
            @else
            <div class="see-more">
                @if(isset($merchant_page))
                    <button class="btn btn-block btn-green-border btn-lg btn-get-coupon" data-offer_id="{{$entity->entitiable_id}}" data-entity_id="{{$entity->id}}">View Coupon</button>
                @else
                    <a class="btn btn-block btn-green-border btn-lg" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/{{$entity->category_slug}}/{{($entity->category_slug == $entity->subcategory_slug)?'':$entity->subcategory_slug.'/'}}{{$entity->merchant_slug.'/'.$entity->location_id}}">
                    @if($entity->total_entities > 1)
                        View <strong>{{$entity->total_entities - 1}}</strong> more offer{{($entity->total_entities > 2)?"s":""}}
                    @else
                        View all offers
                    @endif
                    </a>
                @endif
            </div>
            @endif
            <div class="btn-group">
                <button type="button" class="btn btn-default btn-get-coupon" data-offer_id="{{$entity->entitiable_id}}" data-entity_id="{{$entity->id}}" data-toggle="tooltip" data-placement="top" title="Get Info on {{$entity->name}} from {{$entity->merchant_name}}"><span class="visible-xs-inline"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-green-get-it.png" alt="Get Info on {{$entity->name}} from {{$entity->merchant_name}}"></span><span class="hidden-xs"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-green-print-it.png" alt="Get Info on {{$entity->name}} from {{$entity->merchant_name}}"></span></button>
                <button type="button" class="btn btn-default btn-save-coupon {{$entity->is_clipped == true ? 'disabled' : ''}}" data-offer_id="{{$entity->entitiable_id}}" data-entity_id="{{$entity->id}}" data-toggle="tooltip" data-placement="top" title="Save This Coupon for {{$entity->name}} from {{$entity->merchant_name}}"><img src="{{$entity->is_clipped == true ? 'http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-green-saved.png' : 'http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-green-save-it.png'}}" alt="Save This Coupon for {{$entity->name}} from {{$entity->merchant_name}}"></button>
                @if(!$quote_control || (!$entity->is_certified && !$entity->is_sohi_trial))
                <button type="button" class="btn btn-default btn-coupon-share" data-offer_id="{{$entity->entitiable_id}}" data-entity_id="{{$entity->id}}" data-toggle="tooltip" data-placement="top" title="Share This Coupon for {{$entity->name}} from {{$entity->merchant_name}}"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-green-share-it.png" alt="Share This Coupon for {{$entity->name}} from {{$entity->merchant_name}}"></button>
                @else
                <a href="{{URL::abs('/')}}{{'/homeimprovement/quote?offer_id='.$entity->entitiable_id}}" type="button" class="btn btn-default btn-coupon-quote" data-offer_id="{{$entity->entitiable_id}}" data-entity_id="{{$entity->id}}" data-toggle="tooltip" data-placement="top" title="Get a Quote for {{$entity->name}} from {{$entity->merchant_name}}"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-green-quote-it.png" alt="Get a Quote for {{$entity->name}} from {{$entity->merchant_name}}"></a>
                @endif
            </div>
            @if(isset($entity->city))
            <div class="city-state text-center">
                <span>{{ ucwords(strtolower($entity->city)).', '.strtoupper($entity->state)}}</span>
            </div>
            @endif
        </div>
    @endif
@elseif($entity->entitiable_type == 'Contest')
    <div class="item contest btn-get-contest" data-offer_id="{{$entity->entitiable_id}}" data-entity_id="{{$entity->id}}" itemscope itemtype="http://schema.org/Organization">
      <div itemscope itemtype="http://schema.org/Product">
        <span class="hidden" itemprop="name">{{$entity->merchant_name}} - {{$entity->display_name}}</span>
        <div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
          <div class="item-type contest"></div>
          <div class="top-pic" style="background-image: url('{{$entity->path}}')">
              <img alt="{{$entity->name}}" class="img-responsive" src="{{$entity->path}}">
          </div>
          <div class="item-info">
            <div class="item-name">
              <span itemprop="name">{{isset($entity->display_name) ? $entity->display_name : $entity->name}}</span>
            </div>
            <button class="btn btn-red btn-lg btn-block btn-get-contest">CLICK HERE TO ENTER</button>
          </div>
          @if(isset($entity->winner_first_name))
          <div class="contest-winner">
            <img alt="Winner: {{$entity->winner_first_name}} {{$entity->winner_last_name.charAt(0)}}.@if($entity->winner_city != '') from {{$entity->winner_city}}, {{$entity->winner_state}}@endif" class="pull-left img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sohi/trophy.jpg">
            <p class="spaced">Winner</p>
            <p>{{$entity->winner_first_name}} {{$entity->winner_last_name.charAt(0)}}.@if($entity->winner_city != '') from {{$entity->winner_city}}, {{$entity->winner_state}}@endif</p>
          </div>
          @endif
          @if(isset($entity->city))
            <div class="city-state text-center">
                <span class="">{{ ucwords(strtolower($entity->city)).', '.strtoupper($entity->state)}}</span>
            </div>
          @endif
        </div>
      </div>
    </div>

@else
    <a class="item" href="{{$entity->url}}" target="_blank">
        <img alt="Banner" class="img-responsive" src="{{$entity->path}}">
    </a>
@endif