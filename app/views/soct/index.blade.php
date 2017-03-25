@extends('master.templates.master')

@section('city-banner')
@include('soct.templates.soct-banner')
@stop

@section('page-title')
<h1>Cars &amp; Trucks <br class="visible-xs"><small>in {{ucwords(strtolower($geoip->city_name))}}, {{$geoip->region_name}}</small></h1>
<button class="btn btn-green btn-sm search-nearby-modal" data-toggle="modal" data-target="#changeLocationModal"><span class="glyphicon glyphicon-map-marker"></span> <img alt="Edit Location" src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/edit-location-text.png"> <span class="glyphicon glyphicon-chevron-right"></span></button>
@stop

@section('breadcrumbs')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}" itemprop="url"><span itemprop="title">Home</span></a>
    </li>
    <li class="active">Cars &amp; Trucks</li>
@stop

@section('sidebar')

@if(count($premium['objects']))
@include('master.templates.advertisement', array('advertisement' => $premium['objects'][0]))
@endif
@include('soct.templates.quote')
<div class="hidden-xs">
@include('soct.templates.search-vehicles')
</div>
@include('soct.templates.makes')
@include('soct.templates.explore')
@include('soct.templates.questions')
@include('soct.templates.become-dealer')

@stop
@section('body')

<script>
    category_id = '231';
    type = 'coupon';
    featuredCoupon = false;
    page = 0;
    <?php
      if (!Session::has('newHomeUser') && !stristr(Request::url(), 'printable')) {
        Session::put('newHomeUser', 1);
    ?>
        newHomeUser = {{Session::get('newHomeUser')}};
    <?php } else { ?>
        newHomeUser = 0;
    <?php } ?>
</script>

    <div class="visible-xs">
        @include('soct.templates.search-vehicles-mobile', array('panelOpen' => 1))
    </div>

    <div class="banner-offer">
    </div>

    <div class="banner-vehicle">
        @if(!empty($featuredVehicle) && isset($featuredVehicle))
        @include('soct.templates.featured-vehicle', array('vehicle' => $featuredVehicle))
        @endif
    </div>

    <div class="featured-dealer">
        @if(isset($featuredDealer) && count($featuredDealer['objects']))
        @include('soct.templates.featured-dealers', array('featured' => $featuredDealer['objects'][0]))
        <div class="margin-bottom-15"></div>
        @endif
    </div>

    <div class="view-change content-bg margin-bottom-20">
        <div class="row">
            <div class="col-md-6 col-md-push-6 {{(!Auth::check())?'newsletter':''}}">
                @if(!Auth::check())
                <div class="pull-left margin-right-10">
                    <img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/newsletter-icon-white.png" alt="Newsletter Icon" class="newsletter-icon hidden-sm hidden-xs">
                    <img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/newsletter-icon.png" alt="Newsletter Icon" class="newsletter-icon visible-xs visible-sm">
                </div>
                <p class="margin-bottom-5">We'll send you unbeatable coupons, deals and contests in {{ucwords(strtolower($geoip->city_name))}}</p>
                <div class="input-group">
                    <input type="text" class="form-control newsletter-input" placeholder="Email">
                    <div class="input-group-btn">
                        <button class="btn btn-green newsletter-btn" type="button">Start Saving</button>
                    </div>
                </div>
                <hr class="visible-xs">
                @endif
            </div>
            <div class="col-md-6 col-md-pull-6 {{(!Auth::check())?'margin-top-15':''}}">
                <a type="button" class="btn btn-large spaced btn-green-border tab-toggle list-view" href="#listView" data-toggle="tab"><span class="glyphicon glyphicon-th-list"></span> List</a>
                <a type="button" class="btn btn-large spaced btn-green tab-toggle grid-view" href="#gridView" data-toggle="tab"><span class="glyphicon glyphicon-th"></span> Grid</a>
                <a type="button" class="btn btn-large spaced btn-green-border tab-toggle map-view" href="#mapView" data-toggle="tab"><span class="glyphicon glyphicon-globe"></span> Map</a>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>

<div class="tab-content">
    <div id="listView" class="tab-pane content-bg">
        <div class="offer-results-list">
            <p class="ajax-loader"><img alt="Loading..." src="/img/loader-transparent.gif" alt=""></p>
        </div>

        <div class="margin-top-20">
            <a href="{{ URL::abs('/cars/used/') }}" class="btn btn-block btn-lg btn-grey view-more center-block" data-loading-text="Loading...">See Used Cars</a>
        </div>
    </div>
    <div id="gridView" class="tab-pane active">
        <div id="container" class="js-masonry soct-masonry offer-results-grid" style="position: relative;">
            @include('soct.templates.recommendations', array('entities'=>$entities))
        </div>

        <div class="clearfix"></div>
        @if(!empty($category) && $type == 'coupon')
        <div class="category-footer"><p>{{$category->footer_heading}}</p></div>
        @endif
        <div class="">
            <a href="{{ URL::abs('/cars/used/') }}" class="btn btn-block btn-lg btn-grey view-more center-block" data-loading-text="Loading...">See Used Cars</a>
        </div>
    </div>
    <div id="mapView" class="tab-pane content-bg merchant-search">
        <div class="row margin-bottom-20">
            <div class="col-sm-8">
                <div id="map"></div>
            </div>
            <div class="col-sm-4 map-list">
                <div class="h3 spaced margin-top-0 margin-bottom-10 results-header">Results</div>
                <div class="merchant-result-map">
                    <div class="offer-results-map">
                        <p class="ajax-loader"><img alt="Loading..." src="/img/loader-transparent.gif" alt=""></p>
                    </div>
                    
                </div>
            </div>
        </div>

        <div class="clearfix"></div>

        <div>
            <a href="{{ URL::abs('/cars/used/') }}" class="btn btn-block btn-lg btn-grey view-more center-block" data-loading-text="Loading...">See Used Cars</a>
        </div>
    </div>
</div>
    
<div class="content-bg default-no-results">
        <p class="spaced"><strong>Sorry, We Have No Cars &amp; Trucks Offers In Your Area!</strong></p>
        <p>Try one of our affiliated sites for deals near you.</p>
        <div class="row">
            <div class="col-sm-4">
                <a href="{{URL::abs('/')}}/groceries" target="_blank">
                    <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/images/groceries_ad.jpg">
                </a>
                <br>
            </div>
            <div class="col-sm-4">
                <a href="{{URL::abs('/homeimprovement')}}" target="_blank">
                    <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/images/sohi_ad.jpg">
                </a>
                <br>
            </div>
            <div class="col-sm-4">
                <a href="{{URL::abs('/cars')}}" target="_blank">
                    <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/images/soct_ad.jpg">
                </a>
                <br>
            </div>
        </div>
        <hr>
        <p>Want to see offers in your area? Please complete the form below and we will do our best to get them.</p>
        <hr class="dark">
        <p class="spaced"><strong>Suggest a Merchant</strong></p>
        <div id="suggest-merchant">
            <div class="row suggest-form">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Business Name (required)</label>
                        <input id="suggest_business" type="text" class="form-control" placeholder=""/>
                    </div>
                    <div class="form-group">
                        <label>Address Line 1</label>
                        <input id="suggest_address1" type="text" class="form-control" placeholder =""/>
                    </div>
                    <div class="form-group">
                        <label>Address Line 2</label>
                        <input id="suggest_address2" type="text" class="form-control" size="30" placeholder=""/>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <labeL>City (required)</label>
                        <input id="suggest_city" type = "text" class="form-control" placeholder=""/>
                    </div>
                    <div class="form-group">
                        <label>State (required)</label>
                        <select id="suggest_state" name = "State" class = "form-control">
                            <option selected value = "">Please select one...</option>
                            <option value = "Alabama">Alabama</option>
                            <option value = "Alaska">Alaska</option>
                            <option value = "Arizona">Arizona</option>
                            <option value = "Arkansas">Arkansas</option>
                            <option value = "California">California</option>
                            <option value = "Colorado">Colorado</option>
                            <option value = "Connecticut">Connecticut</option>
                            <option value = "Delaware">Delaware</option>
                            <option value = "Florida">Florida</option>
                            <option value = "Georgia">Georgia</option>
                            <option value = "Hawaii">Hawaii</option>
                            <option value = "Idaho">Idaho</option>
                            <option value = "Illinois">Illinois</option>
                            <option value = "Indiana">Indiana</option>
                            <option value = "Iowa">Iowa</option>
                            <option value = "Kansas">Kansas</option>
                            <option value = "Kentucky">Kentucky</option>
                            <option value = "Louisiana">Louisiana</option>
                            <option value = "Maine">Maine</option>
                            <option value = "Maryland">Maryland</option>
                            <option value = "Massachusetts">Massachusetts</option>
                            <option value = "Michigan">Michigan</option>
                            <option value = "Minnesota">Minnesota</option>
                            <option value = "Mississippi">Mississippi</option>
                            <option value = "Missouri">Missouri</option>
                            <option value = "Montana">Montana</option>
                            <option value = "Nebraska">Nebraska</option>
                            <option value = "Nevada">Nevada</option>
                            <option value = "New Hampshire">New Hampshire</option>
                            <option value = "New Jersey">New Jersey</option>
                            <option value = "New Mexico">New Mexico</option>
                            <option value = "New York">New York</option>
                            <option value = "North Carolina">North Carolina</option>
                            <option value = "North Dakota">North Dakota</option>
                            <option value = "Ohio">Ohio</option>
                            <option value = "Oklahoma">Oklahoma</option>
                            <option value = "Oregon">Oregon</option>
                            <option value = "Pennsylvania">Pennsylvania</option>
                            <option value = "Rhode Island">Rhode Island</option>
                            <option value = "South Carolina">South Carolina</option>
                            <option value = "South Dakota">South Dakota</option>
                            <option value = "Tennessee">Tennessee</option>
                            <option value = "Texas">Texas</option>
                            <option value = "Utah">Utah</option>
                            <option value = "Vermont">Vermont</option>
                            <option value = "Virgina">Virginia</option>
                            <option value = "Washington">Washington</option>
                            <option value = "West Virginia">West Virginia</option>
                            <option value = "Wisconsin">Wisconsin</option>
                            <option value = "Wyoming">Wyoming</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Zip Code</label>
                        <input id="suggest_zipcode" type="text" class="form-control" placeholder=""/>
                    </div>
                    <input type="hidden" id="suggest_category_id" value="{{isset($category) ? $category->id : 0}}"/>
                </div>
            </div>
            <button id="btnSuggestion" type="button" class="btn btn-lg btn-black">Submit</button>
            <span id="suggestMessages"></span>
            <div class="clearfix"></div>
        </div>
@stop