@extends('master.templates.master', array('width'=>$width))

@section('page-title')
<h1>{{ empty($category) ? '' : $category->name.' '}}{{$type == 'coupon' ? 'Coupons<span class="hidden-xs"> &amp; Offers</span> ' : ($type == 'dailydeal' ? 'Daily Deals &amp; Save Todays' : 'Contests')}} <br class="visible-xs"><small>in {{ucwords(strtolower($geoip->city_name))}}, {{studly_case($geoip->region_name)}}</small></h1>
<button class="btn btn-green btn-sm search-nearby-modal" data-toggle="modal" data-target="#changeLocationModal"><span class="glyphicon glyphicon-map-marker"></span> <img alt="Edit Location" src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/edit-location-text.png"> <span class="glyphicon glyphicon-chevron-right"></span></button>
<?php $subheadDropdown = 'sort' ?>
@stop

@section('breadcrumbs')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb" class="">
        <a href="{{URL::abs('/')}}" itemprop="url"><span itemprop="title">Home</span></a>
    </li>
	<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb" class="hidden-xs">
		<a href="{{URL::abs('/coupons/'.strtolower($geoip->region_name))}}" itemprop="url"><span itemprop="title">{{strtoupper($geoip->region_name)}}</span></a>
	</li>
	<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb" class="hidden-xs">
		<a href="{{URL::abs('/coupons/'.strtolower($geoip->region_name).'/'.SoeHelper::getSlug($geoip->city_name))}}" itemprop="url"><span itemprop="title">{{$geoip->city_name}}</span></a>
	</li>
@if($parent_id != 0)
	<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb" class="hidden-xs">
		<a href="{{URL::abs('/coupons/'.strtolower($geoip->region_name).'/'.SoeHelper::getSlug($geoip->city_name).'/'.$parent_slug)}}" itemprop="url"><span itemprop="title">{{$parent_name}}</span></a>
	</li>
@endif
	<li class="active hidden-xs">{{(empty($category))?'All':$category->name}}</li>
	<li class="active visible-xs">{{$type == 'coupon' ? 'Coupons' : ($type == 'dailydeal' ? 'Deals' : 'Contests')}}</li>
	<div class="visible-xs category-select margin-top-5">
		<script type="text/ejs" id="template_select_subcategory">
		<% list(categories, function(category)
		    { %>
		        <option value="<%= category.slug %>"><%= category.name %></option>
		<% }); %>    
		</script>
		<form>
			<div class="row">
				<div class="col-xs-5">
					<select id="parent_category_select" class="form-control">
						<option value="all">Category</option>
						<option value="all">All</option>
                        @foreach($categories['objects'] as $cat)
						<option {{($active == $cat->id)?'selected':''}} value="{{$cat->slug}}">{{$cat->name}}</option>
                        @endforeach
					</select>
				</div>
				<div class="col-xs-5">
					<select id="subcategory_select" class="form-control {{isset($subcategories)?'':'disabled'}}" {{isset($subcategories)?'':'disabled="disabled"'}}>
						<option value="all">{{(isset($subcategories))?'All':'Subcategory'}}</option>
						@if(isset($subcategories))
						@foreach($subcategories['objects'] as $subcategory)
						<option {{($category_id == $subcategory->id)?'selected':''}} value="{{$subcategory->slug}}">{{$subcategory->name}}</option>
						@endforeach
						@endif
					</select>
				</div>
				<div class="col-xs-2">
					<button class="btn btn-green btn-block category-select-btn" type="button">Go</button>
				</div>
			</div>
		</form>
	</div>
@stop

@section('sidebar')
    <script type="text/ejs" id="template_merchant_banner">
    <a class="merchant-banner" data-merchant_id="<%= banner.merchant_id %>" data-banner_entity_id="<%= banner.banner_entity_id %>" href="#" data-nav="<%= banner.custom_url != '' ? banner.custom_url : '/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/'+banner.category_slug+'/'+banner.subcategory_slug+'/'+banner.merchant_slug+'/'+banner.location_id %>">
        <img alt="<%= banner.merchant_name %>" class="img-responsive margin-bottom-15" src="<%= banner.path %>">
    </a>
    </script>

	<div class="panel panel-default hidden-xs">
	    <div class="panel-heading">
	      <span class="h4 panel-title hblock">
	        <a data-toggle="collapse" href="#collapseOne">Explore {{$type == 'coupon' ? 'Coupons' : ($type == 'dailydeal' ? 'Daily Deals' : 'Contests')}} <span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span></a>
	        <div class="clearfix"></div>
	      </span>
	    </div>
	    <div id="collapseOne" class="panel-collapse collapse in">
		    <div class="panel-body explore-links">
		      	<ul>
                    @include('master.templates.explore', array('active' => $active, 'type' => $type, 'parent_id' => $parent_id, 'category' => $category))
				</ul>
		    </div>
	    </div>
	</div>

	@if(!empty($category) && $type == 'coupon')
	<div class="panel panel-default hidden-xs">
	    <div class="panel-heading">
	      <span class="h4 panel-title hblock">
	        <a data-toggle="collapse" href="#collapseTwo">About {{ empty($category) ? '' : $category->name.' '}}<span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span></a>
	        <div class="clearfix"></div>
	      </span>
	    </div>
	    <div id="collapseTwo" class="panel-collapse collapse in">
		    <div class="panel-body explore-links">
			    <div class="category-header">
			    	<p>{{isset($seoContent['Header-About']) && $seoContent['Header-About'] != '' ? SoeHelper::cityStateReplace($seoContent['Header-About'], $geoip) : SoeHelper::cityStateReplace($category->above_heading, $geoip)}}</p>
			    </div> 
		    </div>
	    </div>
	</div>
	@endif
	@if(Feature::findByName('show_ads')->value == 1)
	<!-- <a href="{{URL::abs('/thirtyyears')}}">
		<img alt="Win This Car!" class="img-responsive" src="/img/WinThisCar_ElderFord_WebTile.png">
	</a> -->
    <div id="banner">
        <p class="ajax-loader"><img src="/img/loader-transparent.gif" alt="Loading..." width="32" height="32"></p>
    </div>
	<div class="clearfix"></div>
    <a href="{{URL::abs('/')}}/mobile" class="visible-xs">
        <img alt="Access SaveOn<sup>&reg;</sup> from Anywhere" class="img-responsive margin-bottom-15" src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/mobile-banner.jpg">
    </a>
    <a href="{{URL::abs('/')}}/take-your-coupons-with-you" class="hidden-xs">
        <img alt="Take Your Coupons with You" class="img-responsive margin-bottom-15" src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/desktop-banner.jpg">
    </a>
    <?php $adfeat = Feature::findByName('adlocalize'); $adfeat = $adfeat ? $adfeat->value : 0;?>
    @if($adfeat == 1)
    <div class="margin-bottom-15">
        <div class="ad-localize-root"><script src="http://adlocalize.com/placement/adlocalize.js?zid=12&type=URL&zip={{$userZip}}"></script></div>
    </div>
    @endif
	@endif
    @if(count($category_ads['objects']))
        @include('master.templates.advertisement', array('advertisement' => $category_ads['objects'][0]))
    @endif
@stop

@section('body')
<script>
    parent_category_id = '{{$parent_id}}';
    category_id = '{{$category_id}}';
    category_type = '{{$category ? ($category->parent_id ? "subcategory" : "category") : "all-coupons"}}';
    type = '{{$type}}';
    page = 0;
    initialReturn = '{{count($entities)}}';
<?php 
    $quote_control = Feature::findByName('master_quotes_control');
    $quote_control = empty($quote_control) ? 0 : $quote_control->value;
    $detroit_quote_control = Feature::findByName('detroit_quotes_only');
    $detroit_quote_control = empty($detroit_quote_control) ? 120 : $detroit_quote_control->value;
    if($detroit_quote_control)
    {
        $geoip = json_decode(GeoIp::getGeoIp('json'));
        $distance = GeometryHelper::getDistance($geoip->latitude, $geoip->longitude, 42.38, -83.10);
        $detroit_quote_control = ($distance < $detroit_quote_control && $geoip->region_name == 'MI') ? 1 : 0;
        $quote_control = $quote_control && $detroit_quote_control;
    }
    else
    {
        $quote_control = $quote_control;
    }
	if(isset($entity))
	{
?>
		entity_id = '{{$entity->id}}';
		entitable_type = '{{$entity->entitiable_type}}';
		entitable_id = '{{$entity->entitiable_id}}';
		entity_is_dailydeal = '{{$entity->is_dailydeal}}';
<?php
	}
?>
</script>

<script type='text/ejs' id='template_banner'> 
<% list(banner, function(banner)
{ %>
	<a merchant="<%= banner.merchant_name %>" data-bannerid="<%= banner.id %>" href="#" nav="<%= banner.banner_link != '' ? banner.banner_link : '/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/'+banner.merchant_slug+'/'+banner.merchant_id+'/coupon' %>">
		<img alt="<%= banner.merchant_name %>" class="img-responsive center-block" src="<%= banner.path %>">
	</a>
<% }); %>
</script>

<script type="text/ejs" id="template_banner_offer">
<% if (bannerOffer.entitiable_type == 'Offer') { %>
	<% if (bannerOffer.is_dailydeal == '1') { %>
	<% } else { %>
		<div class="item coupon <%= bannerOffer.is_certified == 1 ? 'save_certified_offer' : '' %>">
			<div class="item-type featured"></div>
			<div class="row">
				<div class="col-md-4 col-sm-6 logo">
					<a href="{{URL::abs('/coupons')}}/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= bannerOffer.category_slug %>/<%= bannerOffer.subcategory_slug %>/<%= bannerOffer.merchant_slug+'/'+bannerOffer.location_id %>">
						<img alt="<%= bannerOffer.name %>" class="img-responsive center-block" src="<%= bannerOffer.logo %>">
					</a>
				</div>
				<div class="col-md-8 col-sm-6">
					<a class="item-info" href="{{URL::abs('/coupons')}}/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= bannerOffer.category_slug %>/<%= bannerOffer.subcategory_slug %>/<%= bannerOffer.merchant_slug+'/'+bannerOffer.location_id %>">
						<p class="merchant-name"><%= bannerOffer.merchant_name %></p>
						<% if (bannerOffer.offer_count > 0) { %>
						<p class="offer-count"><strong><%= bannerOffer.offer_count %></strong> more offer<%== (bannerOffer.offer_count > 1)?"s":""%>&nbsp;</p>
						<% } %>
						<div class="h2 hblock"><%= bannerOffer.name %></div>
					</a>
					<div class="btn-group">
						<button type="button" class="btn btn-default btn-get-coupon" data-offer_id="<%= bannerOffer.entitiable_id %>" data-entity_id="<%= bannerOffer.id %>"><img src="http://s3.amazonaws.com/saveoneverything_assets/images/masonry-icons/get_it_coupon.png" alt="Get It" class="img-circle"><br>Get It</button>
						<button type="button" class="btn btn-default btn-save-coupon" data-offer_id="<%= bannerOffer.entitiable_id %>" data-entity_id="<%= bannerOffer.id %>"><img src="http://s3.amazonaws.com/saveoneverything_assets/images/masonry-icons/save_it_coupon.png" alt="Save It" class="img-circle"><br><span class="save-coupon-text"><%= bannerOffer.is_clipped == true ? 'Saved!' : 'Save It' %></span></button>
						<button type="button" class="btn btn-default btn-coupon-share" data-offer_id="<%= bannerOffer.entitiable_id %>" data-entity_id="<%= bannerOffer.id %>" @if($quote_control)style="<%= (bannerOffer.is_certified || bannerOffer.is_sohi_trial) ? 'display:none;' : '' %>"@endif><img src="http://s3.amazonaws.com/saveoneverything_assets/images/masonry-icons/share_it_coupon.png" alt="Share It" class="img-circle"><br>Share It</button>
                        @if($quote_control)<a href="{{URL::abs('/homeimprovement/quote')}}?offer_id=<%= bannerOffer.entitiable_id %>" type="button" class="btn btn-default btn-coupon-quote" data-offer_id="<%= bannerOffer.entitiable_id %>" data-entity_id="<%= bannerOffer.id %>" style="<%= (bannerOffer.is_certified || bannerOffer.is_sohi_trial) ? '' : 'display:none;' %>"><img src="http://s3.amazonaws.com/saveoneverything_assets/images/masonry-icons/quote_it_coupon.png" alt="Quote It" class="img-circle"><br>Quote It</a>@endif
					</div>
				</div>
			</div>
		</div>
	<% } %>
<% } else if (bannerOffer.entitiable_type == 'Contest') { %>
	<div class="item contest featured">
        <a class="btn-get-contest" data-entity_id="{{empty($win5k) ? 0 : $win5k->id}}">
    		<div class="row">
    			<div class="item-type contest"></div>
    			<div class="col-md-4 hidden-xs hidden-sm">
					<a href="{{URL::abs('/')}}/contests/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/all?showeid={{empty($win5k) ? 0 : $win5k->id}}"><img alt="Win $5k" class="img-responsive" src="/img/5k_img.jpg"></a>
				</div>
				<div class="col-md-8">
					<div class="col-xs-12 text-center">
						<span class="h3 hblock spaced">Featured Contest</span>
						<span class="fancy text-center h2 hblock">You Could Win &#36;5,000</span>	
					</div>
					<div class="clearfix "></div>
						<button type="button" class="margin-top-20 btn btn-default btn-black btn-get-contest" data-entity_id="{{empty($win5k) ? 0 : $win5k->id}}">Check The Winning Numbers</button>
					</div>
				</div>
				
    		</div>
        </a>
	</div>
<% } %>
</script>

<div id="banner">
</div>
	@if($type != 'contest')
	<div class="banner-offer">

	</div>
	@else
	<div class="row margin-bottom-20 contest-banner">
		<div class="col-lg-8">
			<div class="content-bg">
				<div class="row">
					<div class="col-sm-6">
						<img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/images/1413298205-Contest" alt="Win up to $1,000 Getaway!">
					</div>
					<div class="col-sm-6">
						<div class="h2 fancy margin-bottom-20">Win up to $1,000 Getaway!</div>
						<div class="contest-winner">
		                	<img alt="Winner: Erika . from Crystal Lake, IL" class="pull-left img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sohi/trophy.jpg">
		                	<p class="spaced">Winner</p>
		                	@if(strtoupper($geoip->region_name) == 'IL')
		                	<p>Erika B. from Crystal Lake, IL</p>
		                	@elseif(strtoupper($geoip->region_name) == 'MN')
		                	<p>Nicole N. from Eden Prairie, MN</p>
		                	@else
		                	<p>Sandra S. from Clinton Township, MI</p>
		                	@endif
		              </div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-4">
			<div class="content-bg">
			<a href="{{URL::abs('/')}}/winnerscircle" class="btn btn-block btn-red all-winners">
				<div class="h2 fancy">See All of Our Winners!</div>
			</a>
			</div>
		</div>
	</div>
	@endif
	@if(count($entities))
	@if($type != 'contest')
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
	@endif
	<div class="clearfix"></div>
	<div class="tab-content">
		<div id="listView" class="tab-pane content-bg {{($type != 'contest')?'active':''}}">
			<div class="offer-results-list">
				<p class="ajax-loader"><img alt="Loading..." src="/img/loader-transparent.gif" alt=""></p>
			</div>

			<div class="margin-top-20">
				<button class="btn btn-block btn-lg btn-grey view-more center-block" data-loading-text="Loading...">View More</button>
			</div>
		</div>
		<div id="gridView" class="tab-pane {{($type != 'contest')?'':'active'}}">
			<div id="container" class="js-masonry offer-results-grid">
		        @foreach($entities as $entity)
		            @include('master.templates.entity', array('entity'=>$entity))
		        @endforeach
				<!-- <p class="ajax-loader"><img alt="Loading..." src="/img/loader-transparent.gif"></p> -->
			</div>
			@if(count($entities) >= 12)
			<div class="clearfix"></div>
			<div class="">
				<button class="btn btn-block btn-lg btn-grey view-more center-block" data-loading-text="Loading...">View More</button>
			</div>
			@endif
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
				<button class="btn btn-lg btn-block btn-grey view-more center-block" data-loading-text="Loading...">View More</button>
			</div>
		</div>
	</div>
	@endif

@if(count($entities))
	<div class="clearfix"></div>
	@if(!empty($category) && $type == 'coupon')
    <div class="category-footer"><p>{{isset($seoContent['Footer-About']) && $seoContent['Footer-About'] != '' ? SoeHelper::cityStateReplace($seoContent['Footer-About'], $geoip) : SoeHelper::cityStateReplace($category->footer_heading, $geoip)}}</p></div>
    @endif
@endif

@if(!count($entities))
<div class="content-bg margin-bottom-15">
    @if(!empty($category) && $type == 'coupon')
    <p>{{isset($seoContent['Footer-About']) && $seoContent['Footer-About'] != '' ? SoeHelper::cityStateReplace($seoContent['Footer-About'], $geoip) : SoeHelper::cityStateReplace($category->footer_heading, $geoip)}}</p>
    @endif
    <p class="spaced"><strong>Sorry, We Have No {{isset($category) ? ($category->name.' '.$displayType) : ($displayType)}} In {{ucwords(strtolower($geoip->city_name))}}, {{studly_case($geoip->region_name)}}!</strong></p>
    @if(count($relatedEntities))
    <p>Check out some of these similar offers.</p>
    @endif
</div>
    <div id="related-container" class="js-masonry offer-results-grid">
        @foreach($relatedEntities as $entity)
            @include('master.templates.entity', array('entity'=>$entity))
        @endforeach
		<!-- <p class="ajax-loader"><img alt="Loading..." src="/img/loader-transparent.gif"></p> -->
	</div>
    <!--<div class="row">
        <div class="col-sm-4">
            <a href="{{URL::abs('/')}}/groceries" target="_blank">
                <img alt="Print Free Grocery Coupons" class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/images/groceries_ad.jpg">
            </a>
            <br>
        </div>
        <div class="col-sm-4">
            <a href="{{URL::abs('/homeimprovement')}}" target="_blank">
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
    </div>-->

<div class="content-bg">
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
                    <label>City (required)</label>
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
</div>
@endif
@stop