@extends('master.templates.master', array('width'=>'full', 'hideSubmenu'=>'true'))
<?php
$geoip = json_decode(GeoIp::getGeoIp('json'));
?>
@section('city-banner')
@if(!empty($city_image))
<div class="city-banner hidden-xs banner-menu">
	<div class="city-banner-img" style="background-image: url('{{$city_image->path}}')">
		<div class="fade-left"></div>
		<div class="fade-right"></div>
		<span class="fancy h1">{{(isset($company))?'<img src="'.$company->landing_image.'" class="white-label-logo img-responsive center-block" alt="'.$company->name.'">':ucwords(strtolower($city_image->display))}}</span>
		<div class="btn-row text-center">
        <button class="btn btn-sign-up btn-red" data-toggle="modal" data-target="#signUpModal">Become a Member <span class="glyphicon glyphicon-chevron-right"></span></button>
        <button class="btn btn-green" data-dismiss="modal" onclick="tourFirst();tour.restart()">Take the Tour</button>
        </div>
	</div>
</div>
@endif
<div class="subheader visible-xs">
	<div class="input-group searchbar margin-top-20">
	  	<input type="text" class="form-control inptSearch" placeholder="Search by Keyword">
		<div class="input-group-btn search-type" style="display:{{(Feature::findByName('entity_search')->value == 0)?'none':''}}">
			  	<button class="btn dropdown-toggle btn-default" type="button" id="searchDrop" data-toggle="dropdown" data-value="<?php if(isset($searchType)) { echo $searchType; } else { echo 'merchant'; }?>">
			    	<?php if(isset($searchType)) { echo ucfirst($searchType); } else { echo 'Merchant'; }?> <span class="caret"></span>
			  	</button>
				<ul class="dropdown-menu search-dropdown-menu pull-right" role="menu" aria-labelledby="searchDrop">
					<li><a data-value="merchant">Merchant</a></li>
					<li><a data-value="offer">Offer</a></li>
				</ul>
		</div>
	    <div class="input-group-btn">
	        <button class="btn btn-green search" type="button">Go</button>
	    </div>
	</div>

	<div class="content-bg grid margin-top-20">
		<div class="row margin-bottom-20">
			<div class="col-xs-4"><a href="{{URL::abs('/')}}/coupons/{{$geoip->region_name}}/{{SoeHelper::getSlug($geoip->city_name)}}/all"><img src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/icons/all_icon.png" class="img-responsive" alt="All"> All</a></div>
			<div class="col-xs-4"><a href="{{URL::abs('/')}}/coupons/{{$geoip->region_name}}/{{SoeHelper::getSlug($geoip->city_name)}}/food-dining"><img src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/icons/food_dining_icon.png" class="img-responsive" alt="Food &amp; Dining"> Food &amp; Dining</a></div>
			<div class="col-xs-4"><a href="{{URL::abs('/')}}/coupons/{{$geoip->region_name}}/{{SoeHelper::getSlug($geoip->city_name)}}/home-improvement"><img src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/icons/home_services_icon.png" class="img-responsive" alt="Home Improvement"> Home Improvement</a></div>
		</div>
		<div class="row margin-bottom-20">
			<div class="col-xs-4"><a href="{{URL::abs('/')}}/coupons/{{$geoip->region_name}}/{{SoeHelper::getSlug($geoip->city_name)}}/health-beauty"><img src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/icons/health_beauty_icon.png" class="img-responsive" alt="Health &amp; Beauty"> Health &amp; Beauty</a></div>
			<div class="col-xs-4"><a href="{{URL::abs('/')}}/coupons/{{$geoip->region_name}}/{{SoeHelper::getSlug($geoip->city_name)}}/auto-transportation"><img src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/icons/automotive_icon.png" class="img-responsive" alt="Automotive"> Automotive</a></div>
			<div class="col-xs-4"><a href="{{URL::abs('/')}}/coupons/{{$geoip->region_name}}/{{SoeHelper::getSlug($geoip->city_name)}}/travel-entertainment"><img src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/icons/travel_fun_icon.png" class="img-responsive" alt="Travel &amp; Fun"> Travel &amp; Fun</a></div>
		</div>
		<div class="row margin-bottom-20">
			<div class="col-xs-4"><a href="{{URL::abs('/')}}/coupons/{{$geoip->region_name}}/{{SoeHelper::getSlug($geoip->city_name)}}/retail-fashion"><img src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/icons/retail_fashion_icon.png" class="img-responsive" alt="Retail &amp; Fashion"> Retail &amp; Fashion</a></div>
			<div class="col-xs-4"><a href="{{URL::abs('/')}}/coupons/{{$geoip->region_name}}/{{SoeHelper::getSlug($geoip->city_name)}}/special-services"><img src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/icons/everything_else_icon.png" class="img-responsive" alt="Everything Else"> Everything Else</a></div>
			<!--<div class="col-xs-4"><a href="{{URL::abs('/')}}/groceries"><img src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/icons/groceries_icon.png" class="img-responsive" alt="Groceries"> Groceries</a></div>-->
            <div class="col-xs-4"><a href="{{URL::abs('/')}}/coupons/{{$geoip->region_name}}/{{SoeHelper::getSlug($geoip->city_name)}}/community"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/community_icon.png" class="img-responsive" alt="Community"> Community</a></div>
		</div>
        <!--<div class="row margin-bottom-20">
            <div class="col-xs-4 col-xs-offset-4"><a href="{{URL::abs('/')}}/coupons/{{$geoip->region_name}}/{{SoeHelper::getSlug($geoip->city_name)}}/community"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/community_icon.png" class="img-responsive" alt="Community"> Community</a></div>
        </div>-->
        <div class="soct-mobile-btn">
            <a class="btn btn-lg btn-block btn-blue" href="{{URL::abs('/')}}/cars">Looking to Save On a Vehicle? <span class="glyphicon glyphicon-chevron-right"></span></a>
        </div>
	</div>

</div>
@stop
@section('page-title')
<h1 id="recommendedOffers">Recommended Offers <br class="visible-xs"><small>in <span class="current-location-city">{{ucwords(strtolower($geoip->city_name))}}</span>, <span class="current-location-state">{{$geoip->region_name}}</span></small></h1>
<button class="btn btn-green btn-sm search-nearby-modal" data-toggle="modal" data-target="#changeLocationModal"><span class="glyphicon glyphicon-map-marker"></span> <img alt="Edit Location" src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/edit-location-text.png"> <span class="glyphicon glyphicon-chevron-right"></span></button>
<?php $subheadDropdown = 'true' ?>
@stop
@section('body')
<script>
    type = 'all';
</script>
<script type='text/ejs' id='template_banner'> 
<% list(banner, function(banner)
{ %>
	<a merchant="<%= banner.merchant_name %>" data-bannerid="<%= banner.id %>" href="#" nav="<%= banner.banner_link != '' ? banner.banner_link : '/coupons/'+banner.merchant_slug+'/'+banner.merchant_id+'/coupon' %>">
		<img alt="<%= banner.merchant_name %>" class="img-responsive center-block" src="<%= banner.path %>">
	</a>
<% }); %>
</script>

<script type="text/ejs" id="template_homepage_banner">
<a class="merchant-banner" data-merchant_id="<%= banner.merchant_id %>" data-banner_entity_id="<%= banner.banner_entity_id %>" href="#" data-nav="<%= banner.custom_url != '' ? banner.custom_url : '/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/'+banner.category_slug+'/'+banner.subcategory_slug+'/'+banner.merchant_slug+'/'+banner.location_id %>">
    <img alt="<%= banner.merchant_name %>" class="img-responsive center-block" src="<%= banner.path %>">
</a>
</script>

@if(!empty($win5k))
<!-- <div class="banner-offer 5k">
    <div class="item contest featured btn-get-contest" data-entity_id="{{empty($win5k) ? 0 : $win5k->id}}">
        <div class="row">
            <div class="item-type contest"></div>
            <div class="col-md-4 hidden-xs hidden-sm">
                <a href="{{URL::abs('/')}}/contests/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/all?showeid={{empty($win5k) ? 0 : $win5k->id}}"><img alt="Win $5k" class="img-responsive" src="/img/5k_img.jpg"></a>
            </div>
            <div class="col-md-8">
                <div class="text-center">
                    <span class="h3 hblock spaced">Featured Contest</span>
                    <span class="fancy text-center h2 hblock">You Could Win &#36;5,000</span>   
                </div>
                <button type="button" class="margin-top-20 margin-bottom-15 btn btn-default btn-black btn-get-contest" data-entity_id="{{empty($win5k) ? 0 : $win5k->id}}">Check The Winning Numbers</button>
            </div>
        </div>
    </div>
</div> -->
@endif

<div id="banner">
     <p class="ajax-loader"><img src="/img/loader-transparent.gif" alt="Loading..." width="32" height="32"></p>
</div>

<div class="view-change content-bg margin-bottom-20">
    <div class="row">
        <div class="col-sm-6 col-sm-push-6 newsletter">
            <div class="pull-left margin-right-10">
                <img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/newsletter-icon-white.png" alt="Newsletter Icon" class="newsletter-icon hidden-xs">
                <img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/newsletter-icon.png" alt="Newsletter Icon" class="newsletter-icon visible-xs">
            </div>
            <p class="margin-bottom-0">We'll send you unbeatable coupons, deals and contests in {{ucwords(strtolower($geoip->city_name))}}</p>
            <div class="input-group">
                <input type="text" class="form-control newsletter-input" placeholder="Email">
                <div class="input-group-btn">
                    <button class="btn btn-green newsletter-btn" type="button">Start Saving</button>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-sm-pull-6 margin-top-15">
            <a type="button" class="btn btn-large spaced btn-green-border tab-toggle list-view" href="#listView" data-toggle="tab"><span class="glyphicon glyphicon-th-list"></span> List</a>
            <a type="button" class="btn btn-large spaced btn-green tab-toggle grid-view" href="#gridView" data-toggle="tab"><span class="glyphicon glyphicon-th"></span> Grid</a>
            <a type="button" class="btn btn-large spaced btn-green-border tab-toggle map-view" href="#mapView" data-toggle="tab"><span class="glyphicon glyphicon-globe"></span> Map</a>
        </div>
    </div>
</div>


<div class="clearfix"></div>

@if($entities)

<div class="tab-content">
    <div id="listView" class="tab-pane content-bg">
        <div class="offer-results-list">
            <p class="ajax-loader"><img alt="Loading..." src="/img/loader-transparent.gif" alt=""></p>
        </div>

        <div class="margin-top-20">
            <a href="{{URL::abs('/coupons/'.strtolower($geoip->region_name).'/'.SoeHelper::getSlug($geoip->city_name).'/all')}}" class="btn btn-block btn-lg btn-grey view-more center-block">See More Coupons</a>
        </div>
    </div>
    <div id="gridView" class="tab-pane active">
        <div id="container" class="js-masonry offer-results-grid">
            @foreach($entities as $entity)
                @include('master.templates.entity', array('entity'=>$entity))
            @endforeach
        	<!-- <p class="ajax-loader"><img src="/img/loader-transparent.gif" alt="Loading..."></p> -->
        </div>
        <div class="clearfix"></div>
        <div class="">
        	<a href="{{URL::abs('/coupons/'.strtolower($geoip->region_name).'/'.SoeHelper::getSlug($geoip->city_name).'/all')}}" class="btn btn-block btn-lg btn-grey view-more center-block">See More Coupons</a>
        </div>
    </div>
    <div id="mapView" class="tab-pane content-bg merchant-search">
        <div class="row margin-bottom-20">
            <div class="col-sm-9">
                <div id="map"></div>
            </div>
            <div class="col-sm-3 map-list">
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
            <a href="{{URL::abs('/coupons/'.strtolower($geoip->region_name).'/'.SoeHelper::getSlug($geoip->city_name).'/all')}}" class="btn btn-lg btn-block btn-grey view-more center-block">See More Coupons</a>
        </div>
    </div>
</div>

@else

<div class="content-bg default-no-results" style="{{(!$entities)?'display:block':''}}">
    <p class="spaced"><strong>Sorry, We Have No Recommended Offers In Your Area!</strong></p>
    <p>Try one of our affiliated sites for deals near you.</p>
    <div class="row">
        <div class="col-sm-4">
            <a href="{{URL::abs('/')}}/groceries" target="_blank">
                <img alt="Print Free Grocery Coupons" class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/images/groceries_ad.jpg">
            </a>
            <br>
        </div>
        <div class="col-sm-4">
            <a href="{{ $sohi ? URL::abs('/homeimprovement') : 'http://saveonhomeimprovement.com'}}" target="_blank">
                <img alt="Save On Home Improvement" class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/images/sohi_ad.jpg">
            </a>
            <br>
        </div>
        <div class="col-sm-4">
            <a href="{{URL::abs('/cars')}}" target="_blank">
                <img alt="Save On Cars and Trucks" class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/images/soct_ad.jpg">
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
@endif

@if(isset($company))
<div class="modal fadeSo whats crackin" id="firstTimeCompanyModal" tabindex="-1" role="dialog" aria-labelledby="firstTimeCompanyModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="text-center modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <div class="h1 modal-title fancy" id="firstTimeCompanyModalLabel">
            <img src="{{$company->landing_image}}" class="img-responsive center-block main-logo" alt="{{$company->name}}">
        </div>
        <div>
            <small class="text-center">Powered By:</small>
            <img src="{{(isset($altLogo))?$altLogo:'/img/logo.png'}}" alt="Save On" class="img-responsive center-block powered-by-logo">
        </div>
      </div>
      <div class=" modal-body">
        <p>SaveOn<sup>&reg;</sup> is the best place to find <a href="{{URL::abs('/')}}/coupons/all">coupons</a><!--, <a href="{{URL::abs('/')}}/dailydeals/all">daily deals</a>,--> and <a href="{{URL::abs('/')}}/contests/all">contests</a>!</p> 
        <!--<div class="h1">Do you want to save on deals in {{$geoip->city_name}},&nbsp;{{$geoip->region_name}}? <button class="btn btn-green btn-sm search-nearby-modal" data-dismiss="modal" data-toggle="modal" data-target="#changeLocationModal"><span class="glyphicon glyphicon-map-marker"></span> Edit Location <span class="glyphicon glyphicon-chevron-right"></span></button></div>
        <p>Your location will help us customize your SaveOn<sup>&reg;</sup> experience to you! For even better, more personalized recommendations, <a class="first-time-user" data-dismiss="modal" data-toggle="modal" data-target="#signUpModal">become a member</a>.</p>-->
        <div class="row">
          <div class="col-sm-3 tour-hide">
          </div>
          <div class="col-sm-6 margin-bottom-15">
            <button class="btn btn-block btn-blue first-time-user" data-dismiss="modal" data-toggle="modal" data-target="#signUpModal">Become a Member</button>
          </div>
          <div class="col-sm-6 margin-bottom-15 tour-show hidden hidden-xs">
            <button class="btn btn-block btn-green-border" data-dismiss="modal" onclick="tour.restart()">Take the Tour</button>
          </div>
        </div>
        <p>Becoming a member is quick and easy! Not convinced?<br>
        <a class="inline" href="#" data-toggle="modal" data-target="#signUpBenefitsModal"><strong>Why become a member?</strong></a></p>
        <div class="row links-line">
            <!--<div class="col-sm-6">
                <p>Already a member? <a href="#" data-dismiss="modal" data-toggle="modal" data-target="#signInModal">Sign In</a></p>
            </div>-->
            <div class="hidden-xs col-sm-6 tour-column hidden">
              <!--<p>New to SaveOn?<br><a href="#" data-dismiss="modal" onclick="tour.restart()">Take the tour</a></p>-->
              <p class="margin-bottom-5"><strong>Popular Offer Categories:</strong></p>
              <p><a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/food-dining" title="Food &amp; Dining Coupons in {{$geoip->city_name}}, {{$geoip->region_name}}">Food &amp; Dining Coupons</a><br>
                <a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/auto-transportation" title="Automotive Coupons in {{$geoip->city_name}}, {{$geoip->region_name}}">Automotive Coupons</a><br>
                <a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/health-beauty" title="Health &amp; Beauty Coupons in {{$geoip->city_name}}, {{$geoip->region_name}}">Health &amp; Beauty Coupons</a></p>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
    @if(isset($fireFirstTimeCompanyModal))
    <script>
        fireFirstTimeCompanyModal = 1;
    </script>
    @endif
@endif

@stop
