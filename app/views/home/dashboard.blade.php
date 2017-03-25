@extends('master.templates.master', array('hideSearchMobile'=>'true'))

@section('city-banner')

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
<h1>Dashboard <br class="visible-xs"><small><span class="current-location-city">{{ucwords(strtolower($geoip->city_name))}}</span>, <span class="current-location-state">{{$geoip->region_name}}</span></small></h1>
<button class="btn btn-green btn-sm search-nearby-modal" data-toggle="modal" data-target="#changeLocationModal"><span class="glyphicon glyphicon-map-marker"></span> <img alt="Edit Location" src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/edit-location-text.png"> <span class="glyphicon glyphicon-chevron-right"></span></button>
@stop


@section('sidebar')
	<div class="panel panel-default explore-sidebar hidden-xs">
	    <div class="panel-heading">
	      <span class="panel-title h4 hblock">
	        <a data-toggle="collapse" href="#collapseOne" class="">Explore Coupons <span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span></a>
	        <div class="clearfix"></div>
	      </span>
	    </div>
	    <div id="collapseOne" class="panel-collapse collapse in">
		    <div class="panel-body">
		      	<div class="grid">
                    <div class="row margin-bottom-20">
                        <div class="col-xs-4"><a href="{{URL::abs('/')}}/coupons/{{$geoip->region_name}}/{{SoeHelper::getSlug($geoip->city_name)}}/all"><img src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/icons/all_icon.png" class="img-responsive" alt="All"> All</a></div>
                        <div class="col-xs-4"><a href="{{URL::abs('/')}}/coupons/{{$geoip->region_name}}/{{SoeHelper::getSlug($geoip->city_name)}}/food-dining"><img src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/icons/food_dining_icon.png" class="img-responsive" alt="Food &amp; Dining">Food &amp; Dining</a></div>
                        <div class="col-xs-4"><a href="{{URL::abs('/')}}/coupons/{{$geoip->region_name}}/{{SoeHelper::getSlug($geoip->city_name)}}/home-improvement"><img src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/icons/home_services_icon.png" class="img-responsive" alt="Home Improvement"> Home<br>Improvement</a></div>
                    </div>
                    <div class="row margin-bottom-20">
                        <div class="col-xs-4"><a href="{{URL::abs('/')}}/coupons/{{$geoip->region_name}}/{{SoeHelper::getSlug($geoip->city_name)}}/health-beauty"><img src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/icons/health_beauty_icon.png" class="img-responsive" alt="Health &amp; Beauty"> Health &amp; Beauty</a></div>
                        <div class="col-xs-4"><a href="{{URL::abs('/')}}/coupons/{{$geoip->region_name}}/{{SoeHelper::getSlug($geoip->city_name)}}/auto-transportation"><img src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/icons/automotive_icon.png" class="img-responsive" alt="Automotive"> Automotive</a></div>
                        <div class="col-xs-4"><a href="{{URL::abs('/')}}/coupons/{{$geoip->region_name}}/{{SoeHelper::getSlug($geoip->city_name)}}/travel-entertainment"><img src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/icons/travel_fun_icon.png" class="img-responsive" alt="Travel &amp; Fun"> Travel &amp; Fun</a></div>
                    </div>
                    <div class="row margin-bottom-20">
                        <div class="col-xs-4"><a href="{{URL::abs('/')}}/coupons/{{$geoip->region_name}}/{{SoeHelper::getSlug($geoip->city_name)}}/retail-fashion"><img src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/icons/retail_fashion_icon.png" class="img-responsive" alt="Retail &amp; Fashion"> Retail &amp; Fashion</a></div>
                        <div class="col-xs-4"><a href="{{URL::abs('/')}}/coupons/{{$geoip->region_name}}/{{SoeHelper::getSlug($geoip->city_name)}}/special-services"><img src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/icons/everything_else_icon.png" class="img-responsive" alt="Everything Else"> Everything Else</a></div>
                        <div class="col-xs-4"><a href="{{URL::abs('/')}}/groceries"><img src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/icons/groceries_icon.png" class="img-responsive" alt="Groceries"> Groceries</a></div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4 col-xs-offset-4"><a href="{{URL::abs('/')}}/coupons/{{$geoip->region_name}}/{{SoeHelper::getSlug($geoip->city_name)}}/community"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/community_icon.png" class="img-responsive" alt="Community"> Community</a></div>
                    </div>
                </div>
		    </div>
	    </div>
	</div>
	<div id="tour-mystuff" class="panel panel-default mystuff-sidebar hidden-xs">
	    <div class="panel-heading">
	      <span class="panel-title h4 hblock">
	        <a data-toggle="collapse" href="#collapseTwo">My Stuff <span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span></a>
	        <div class="clearfix"></div>
	      </span>
	    </div>
	    <div id="collapseTwo" class="panel-collapse collapse in">
		    <div class="panel-body explore-links">
		      	<ul>
					<li>Dashboard</li>
					<li><a href="{{URL::abs('/')}}/members/mycoupons">My Coupons</a></li>
					<!--<li><a href="{{URL::abs('/')}}/members/mysavetodays">My Save Todays</a></li>-->
					<li><a href="{{URL::abs('/')}}/members/mycontestentries">My Contest Entries</a></li>
					<li><a href="{{URL::abs('/')}}/members/myfavoritemerchants">My Favorite Merchants</a></li>
                    <?php $soct_redirect = Feature::findByName('soct_redirect'); ?>
                    @if((!empty($soct_redirect)) && (Feature::findByName('soct_redirect')->value == 1))
                    <li><a href="{{URL::abs('/')}}/members/mycars">My Cars</a></li>
                    @endif
					<li><a href="{{URL::abs('/')}}/members/mysettings">My Account Settings</a></li>
				</ul>
		    </div>
	    </div>
	</div>
	@include('master.templates.sidebar-offers')

	@if(Feature::findByName('show_ads')->value == 1)
    <!-- <a href="{{URL::abs('/')}}/thirtyyears">
        <img alt="Win This Car!" class="img-responsive" src="/img/WinThisCar_ElderFord_WebTile.png">
    </a> -->
    <div class="clearfix"></div>
    <a href="{{URL::abs('/')}}/mobile" class="visible-xs">
        <img alt="Access SaveOn<sup>&reg;</sup> from Anywhere" class="img-responsive" src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/mobile-banner.jpg">
    </a>
    <a href="{{URL::abs('/')}}/take-your-coupons-with-you" class="hidden-xs">
        <img alt="Take Your Coupons with You" class="img-responsive" src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/desktop-banner.jpg">
    </a>
    @endif
@stop

 
@section('body')
<script>
    type = 'all';
</script>
<div id="tour-shortcuts" class="row row-left row-dashboard-buttons hidden">
	<div class= "col-xs-6 col-sm-3 dashboard_buttons"><button id="tour-mycoupons" type="button" class="btn btn-block dashboard scissors" onclick="window.location.href='{{URL::abs('/')}}/members/mycoupons'"><p class="spaced">My Saved<br>Coupons</p></button></div>
	<!--<div class= "col-xs-6 col-sm-3 dashboard_buttons"><button id="tour-mysavetodays" type="button" class="btn btn-block dashboard clock" onclick="window.location.href='{{URL::abs('/')}}/members/mysavetodays'"><p class="spaced">My Save<br>Todays</p></button></div>-->
	<div class= "col-xs-6 col-sm-3 dashboard_buttons"><button id="tour-mycontests" type="button" class="btn btn-block dashboard trophy" onclick="window.location.href='{{URL::abs('/')}}/members/mycontestentries'"><p class="spaced">My Contest<br>Entries</p></button></div>
	<div class= "col-xs-6 col-sm-3 dashboard_buttons"><button id="tour-myfavoritemerchants" type="button" class="btn btn-block dashboard heart" onclick="window.location.href='{{URL::abs('/')}}/members/myfavoritemerchants'"><p class="spaced">My Favorite<br>Merchants</p></button></div>
</div>

<div class="row margin-bottom-20">
	<div class="col-md-6">
		<h2 class="spaced inline">RECOMMENDED FOR YOU</h2>
	</div>
	<div class="col-md-6">
		<p class="pull-right-lg inline gray">Want to personalize this list more? <a href = "/members/myinterests"> <strong>Click here.</strong> </a></p>
	</div>
	<br>
</div>

<div class="view-change content-bg margin-bottom-20">
    <a type="button" class="btn btn-large spaced btn-green-border tab-toggle list-view" href="#listView" data-toggle="tab"><span class="glyphicon glyphicon-th-list"></span> List</a>
        <a type="button" class="btn btn-large spaced btn-green tab-toggle grid-view" href="#gridView" data-toggle="tab"><span class="glyphicon glyphicon-th"></span> Grid</a>
        <a type="button" class="btn btn-large spaced btn-green-border tab-toggle map-view" href="#mapView" data-toggle="tab"><span class="glyphicon glyphicon-globe"></span> Map</a>
</div>
<div class="clearfix"></div>


<script type='text/ejs' id='template_banner'> 
<% list(banner, function(banner)
{ %>
	<a merchant="<%= banner.merchant_name %>" data-bannerid="<%= banner.id %>" href="#" nav="<%= banner.banner_link != '' ? banner.banner_link : '/coupons/'+banner.merchant_slug+'/'+banner.merchant_id+'/coupon' %>">
		<img alt="<%= banner.merchant_name %>" class="img-responsive center-block" src="<%= banner.path %>">
	</a>
<% }); %>
</script>

		<div id="banner">
            <p class="ajax-loader"><img alt="Loading..." src="/img/loader-transparent.gif"></p>
		</div>

<div class="tab-content">
    <div id="listView" class="tab-pane content-bg">
        <div class="offer-results-list">
            <p class="ajax-loader text-center"><img alt="Loading..." src="/img/loader-transparent.gif" alt=""></p>
        </div>

        <div class="margin-top-20">
            <button class="btn btn-block btn-lg btn-grey view-more center-block" data-loading-text="Loading...">View More</button>
        </div>
    </div>
    <div id="gridView" class="tab-pane active">
		<div id="container" class="js-masonry offer-results-grid">
			<p class="ajax-loader text-center"><img alt="Loading..." src="/img/loader-transparent.gif"></p>
		</div>
		<div class="clearfix"></div>
		<div class="">
			<button class="btn btn-block btn-lg btn-grey view-more center-block" data-loading-text="Loading...">View More</button>
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
                        <p class="ajax-loader text-center"><img alt="Loading..." src="/img/loader-transparent.gif" alt=""></p>
                    </div>
                    
                </div>
            </div>
        </div>

        <div class="clearfix"></div>

        <div>
            <button class="btn btn-lg btn-block btn-grey view-more center-block" data-loading-text="Loading...">View More</button>
        </div>
    </div>
</div>

<div class="content-bg default-no-results">
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
            <a href="{{$sohi ? URL::abs('/homeimprovement') : 'http://saveonhomeimprovement.com'}}" target="_blank">
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

@stop