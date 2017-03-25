@extends('master.templates.master', array('special_merchant'=>$special_merchant))

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

@section('sidebar')
	<div class="panel panel-default explore-sidebar is_dealer_hide">
	    <div class="panel-heading">
	      <span class="h4 hblock panel-title">
	        <a data-toggle="collapse" href="#collapseOne" class="collapsed">Explore Coupons <span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span></a>
	        <div class="clearfix"></div>
	      </span>
	    </div>
	    <div id="collapseOne" class="panel-collapse collapse">
		    <div class="panel-body explore-links">
		      	<ul>
                    @include('master.templates.explore', array('active' => 'all', 'type' => 'coupon'))
				</ul>
		    </div>
	    </div>
	</div>

	<?php /*
    @if($similar['stats']['returned'])
	<div class="panel panel-default">
	    <div class="panel-heading">
	      <span class="h4 hblock panel-title">
	        <a data-toggle="collapse" href="#collapseTwo">Similar Merchants <span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span></a>
	        <div class="clearfix"></div>
	      </span>
	    </div>
	    <div id="collapseTwo" class="panel-collapse collapse in">
		    <div class="panel-body">
		      	<ul>
                    @foreach($similar['objects'] as $sim)
					<li><a href="/coupons/{{$sim->merchant_slug.'/'.$sim->location_id}}">{{$sim->merchant_name}}</a>
					<ul>
						<li>({{$sim->total_entities}} Offers)</li>
					</ul></li>
                    @endforeach
				</ul>
		    </div>
		    <div class="panel-footer"><a href="/category/{{$catSlug}}">View All <span class="glyphicon glyphicon-chevron-right pull-right"></span></a></div>
	    </div>
	</div>
    @endif
    */?>
    <div class="is_dealer_hide">
	@include('master.templates.sidebar-offers')
	</div>
	<div class="is_dealer">
    @if($franchise->is_dealer)
	@include('soct.templates.search-vehicles')
    @endif
	@include('soct.templates.become-dealer')
	</div>
    <div class="panel panel-default explore-sidebar save_certified" style="{{($franchise->is_certified==1)?'display:block':''}}">
	    <div class="panel-heading">
	      <span class="h4 hblock panel-title">
	        <a data-toggle="collapse" href="#collapseOne" class="collapsed">What Is Save Certified? <span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span></a>
	        <div class="clearfix"></div>
	      </span>
	    </div>
	    <div id="collapseOne" class="panel-collapse collapse in">
		    <div class="panel-body explore-links">
		      	<p>All our Save Certified Contractors are pre-screened, and verified to meet the following criteria (where applicable):  <strong>Licensed, Bonded, Insured for Injury and Workmans Comp, Performs Background Checks.</strong></p>
		    </div>
	    </div>
	</div>	
@stop

@section('body')
<script type="text/ejs" id="template_review">
<% list(reviews, function(review)
{ %>
    <div class="content-bg review">
        <div class="progress-popular pull-left" style="" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
            <meta itemprop="worstRating" content = "0">
            <meta itemprop="ratingValue" content = "<%= review.rating %>">
            <meta itemprop="bestRating" content = "5">
            <div class="bar-popular-container" style="width: <%= review.rating / 5 * 100 %>%;">
                <div class="bar-popular stars" style="width: 150px;"></div>
            </div>
        </div>
        <div class="pull-right">
            <% if(review.user_id == user_id){ %><button class="btn btn-link btn-delete-review" data-review_id="<%= review.id %>">Delete</button><% } %>
        </div>
        <div class="clearfix"></div>
        <p><%= review.content %></p>
        <p><%= review.created_at %></p>
    </div>
<% }); %>
</script>

<script>
    merchant_name = "{{$merchant->display}}";
	merchant_id = '{{$merchant->id}}';
	location_id = '{{$location->id}}';
    franchise_id = '{{$location->franchise_id}}';
	user_id = '{{$user->id}}';
	user_type = '{{$user->getType()}}';
	is_reviewed = '{{$is_reviewed}}';
    is_dealer = '{{$franchise->is_dealer}}';
    make_ids = '{{$make_ids}}';
	special_merchant = '{{$special_merchant}}';
	category_id = '{{$catId}}';
	subcategory_id = '{{$subcatId}}';
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
	<div itemscope itemtype="http://schema.org/Organization">
		<div class="merchant-header">
			<img alt="Save Certified" class="save_certified pull-right" style="{{($franchise->is_certified==1)?'display:block':''}}" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sohi/save_certified.png">
			<div class="row merchant-info">
				<div class="col-xs-12 col-sm-6 text-center">
					<div class="logo">
						@if(($expired_default && $entities['stats']['total'] != 0) || !$expired_default)
						<div class="offer-count img-circle is_dealer_hide"><span class="offer-count">{{$entities['stats']['total']}}</span><br>offers</div>
						@endif
						<img class="img-responsive center-block" src="{{(!empty($logo))?$logo->path:''}}" alt="{{$merchant->display}}" itemprop="logo">
					</div>
					@if (isset($company) && $reviews['stats']['total']!=0)
					<p class="margin-top-10"><a href="{{URL::abs('/')}}/partner/{{$company->id}}"><img src="{{$company->logo_image}}" class="company-logo img-responsive center-block" alt="{{$company->name}}"></a></p>
					@endif
				</div>
				<div class="col-xs-12 col-sm-6">
					<h2 itemprop="name">{{$location->display_name == '' ? $merchant->display : $location->display_name}}</h2>
					<div class="catchphrase">{{$merchant->catchphrase}}</div>
					<div class="row">
						<div class="col-xs-12 col-md-6 margin-bottom-15">
							@if($location->is_address_hidden)
							{{$location->custom_address_text}}
							@endif
							@if(!$location->is_address_hidden)
							<h3>Address</h3>
							@endif
							<address itemprop="address" itemscope itemtype="http://schema.org/PostalAddress"><span itemprop="streetAddress">
								@if(!$location->is_address_hidden)
								{{$location->address}}<br>
								<?php ($location->address2)?$location->address2.'<br>':'' ?></span>
								<span itemprop="addressLocality">{{$location->city}}</span>, <span itemprop="addressRegion">{{$location->state}}</span> <span span itemprop="postalCode">{{$location->zip}}</span><br>
								@endif
                                @if($location->redirect_number != '')
                                    <a class="btn-click-to-call" href="#" data-merchant_name="{{$merchant->display}}" data-location_id="{{$location->id}}">{{$location->redirect_text != '' ? $location->redirect_text : 'Click To Call'}}</a><br>
                                @else
								    @if($location->phone != '')P: <span itemprop="telephone">{{$location->phone}}</span><br>@endif
                                @endif
                                @if($location->website!="")
								<a style="{{($location->website!="")?'':'display:none'}}" href="{{(strpos($location->website, 'http') === false)?'http://'.$location->website:$location->website}}" onclick="master_control.TrackClick({{$location->id}});return false">{{(strlen($location->website) > 30)?'View Website':$location->website}}</a><br>
                                @endif
                                @if($location->custom_website != '')
                                <a href="{{(strpos($location->custom_website, 'http') === false)?'http://'.$location->custom_website:$location->custom_website}}" onclick="master_control.TrackCustomClick({{$location->id}});return false">{{$location->custom_website_text == '' ? $location->custom_website : $location->custom_website_text}}</a><br>
                                @endif
								<?php if ($locationCount > 1) { ?><a href="{{URL::abs('/')}}/directions/{{$merchant->slug}}">View all locations</a><?php } ?>
                            </address>
						</div>
						@if (($merchant->facebook!='') || ($merchant->twitter!='') || ($location->website!='') || ($pdfs['stats']['total']!=0))
						<div class="col-xs-12 col-md-6 margin-bottom-15 socialize">
							@if (($merchant->facebook!='') || ($merchant->twitter!='') || ($location->website!=''))
							<div class="social-icons">
								<h3>Socialize</h3>
								<p><a style="{{($merchant->facebook!="")?'':'display:none'}}" href="{{(strpos($merchant->facebook, 'http') === false)?'http://'.$merchant->facebook:$merchant->facebook}}" target="_blank"><img src="http://s3.amazonaws.com/saveoneverything_assets/images/social-icon-facebook.png" alt="Facebook" class="img-circle"></a>
        						<a style="{{($merchant->twitter!="")?'':'display:none'}}" href="{{(strpos($merchant->twitter, 'http') === false)?'http://'.$merchant->twitter:$merchant->twitter}}" target="_blank"><img src="http://s3.amazonaws.com/saveoneverything_assets/images/social-icon-twitter.png" alt="Twitter" class="img-circle"></a> 
        						<a style="{{($location->website!="")?'':'display:none'}}" href="{{(strpos($location->website, 'http') === false)?'http://'.$location->website:$location->website}}" target="_blank" onclick="master_control.TrackClick({{$location->id}});return false"><img src="http://s3.amazonaws.com/saveoneverything_assets/images/social-icon-website.png" alt="Website" class="img-circle"></a></p>
        					</div>
        					@endif
							@if ($catSlug == 'food-dining')
								@if ($pdfs['stats']['total']==1)
								<a class="btn btn-black btn-block btn-download-menu" href="{{$pdfs['objects'][0]->path}}" target="_blank"><span class="glyphicon glyphicon-cutlery"></span> Get Menu</a>
								@elseif ($pdfs['stats']['total']>1)
				            	<button class="btn btn-black btn-block btn-download-menu btn-downloads" href="#about" data-target="#aboutTab" data-toggle="tab"><span class="glyphicon glyphicon-cutlery"></span> Get Menu</button>
				            	@endif
				           	@elseif ($franchise->is_dealer == 1)
				           		@if ($pdfs['stats']['total']==1)
				           		<a class="btn btn-black btn-block btn-download-info" href="{{$pdfs['objects'][0]->path}}" target="_blank"><span class="glyphicon glyphicon-download-alt"></span> View Ad</a>
				           		@elseif ($pdfs['stats']['total']>1)
				           		<button class="btn btn-black btn-block btn-download-info btn-downloads" href="#about" data-target="#aboutTab" data-toggle="tab"><span class="glyphicon glyphicon-download-alt"></span> View Ad</button>
				           		@endif
				           	@else
				           		@if ($pdfs['stats']['total']==1)
				           		<a class="btn btn-black btn-block btn-download-info" href="{{$pdfs['objects'][0]->path}}" target="_blank"><span class="glyphicon glyphicon-download-alt"></span> Get Info</a>
				           		@elseif ($pdfs['stats']['total']>1)
				           		<button class="btn btn-black btn-block btn-download-info btn-downloads" href="#about" data-target="#aboutTab" data-toggle="tab"><span class="glyphicon glyphicon-download-alt"></span> Get Info</button>
				           		@endif
				           	@endif			            
						</div>
						@endif
  					</div>
  					<div class="row" style="display:none">
						<div class="col-xs-12 col-md-6">
							<h3>Tags</h3>
							<p><a href="#">subs</a>, <a href="#">sandwiches</a>, <a href="#">breakfast</a>, <a href="#">lunch</a>, <a href="#">dinner</a></p>
						</div>
					</div>
					<div class="row margin-top-20 save_certified" style="{{($franchise->is_certified==1)?'display:block':''}}"> <!-- Call Tracking Number for SOHI merchants -->
						<!--<div class="col-xs-12">
							<h3>Give us a call</h3>
							<p>1-234-5678</p>
						</div>-->
					</div>
					
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12 col-sm-6">
					@if($reviews['stats']['total']!=0)
					<div class="row" style="{{($reviews['stats']['total']==0)?'display:none':''}}">
						<div class="progress-popular avg" style="" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
					        <meta itemprop="worstRating" content = "0">
					        <meta itemprop="ratingValue" content = "{{$location->rating}}">
					        <meta itemprop="bestRating" content = "5">
					        <meta itemprop="reviewCount" content = "{{$location->rating_count}}">
					        <div class="bar-popular-container" style="width: {{$location->rating / 5 * 100}}%;">
					            <div class="bar-popular stars" style="width: 150px;"></div>
					        </div>
					    </div>
					</div>
					@elseif (isset($company))
						<a href="{{URL::abs('/')}}/partner/{{$company->id}}"><img src="{{$company->logo_image}}" class="company-logo img-responsive center-block" alt="{{$company->name}}"></a>
					@endif
				</div>
			
				<div class="col-xs-12 col-sm-6 merchant-buttons">
					<div class="row">
						<div class="col-sm-12 col-md-4" style="{{($locationCount > 1) ? '' : 'display: none'}}">
							<a href="{{URL::abs('/')}}/directions/{{$merchant->slug}}" type="button" class="btn btn-block btn-green">
								<span class="glyphicon glyphicon-map-marker"></span>
								<span>View Other <br class="visible-lg visible-md">Locations</span>
							</a>
						</div>
						<div class="col-sm-12 col-md-4" style="{{($locationCount == 1) ? '' : 'display: none'}}">
							<a target="_blank" href="http://maps.google.com/?q={{$location->latitude.','.$location->longitude}}" type="button" class="btn btn-block btn-green">
								<span class="glyphicon glyphicon-map-marker"></span>
								<span>View On <br class="visible-lg visible-md">Map</span>
							</a>
						</div>
						<div class="col-sm-12 col-md-4">
							<button type="button" class="btn btn-block btn-grey btn-favorite-merchant{{$is_favorited ? ' disabled' : ''}}" "{{$is_favorited ? 'disabled="disabled"': ''}}">
								<span class="glyphicon glyphicon-heart favorite"></span>
								<span class="fav-text">@if(!$is_favorited) Add to <br class="visible-lg visible-md">Favorites @else My <br class="visible-lg">Favorite! @endif</span>
							</button>
						</div>
						<div class="col-sm-12 col-md-4" style="{{$is_reviewed == 1 ? 'display: none;' : ''}}">
							<button type="button" class="btn btn-block btn-black btn-open-review ">
								<span class="glyphicon glyphicon-pencil"></span>
								<span>Write a <br class="visible-lg visible-md">Review</span>
							</button>
						</div>
					</div>
				</div>
			</div>
			<div class="row save_certified" style="{{($franchise->is_certified==1 || $franchise->is_sohi_trial == 1) && $quote_control ?'display:block':''}}">
				<div class="col-sm-6 col-xs-12 pull-right">
					<a class="btn btn-green btn-block btn-lg" href="{{URL::abs('/')}}/homeimprovement/quote?franchise_id={{$franchise->id}}"><strong>GET A QUOTE</strong> <em class="hidden-xs hidden-sm"><small>from this merchant</small></em></a>
				</div>
			</div>
			<div class="row is_dealer hidden">
				<div class="col-sm-6 col-xs-12 pull-right">
					<button class="btn btn-green btn-block btn-lg btn-new-car-quote"><strong>GET A QUOTE</strong> <em class="hidden-xs hidden-sm"><small>from this merchant</small></em></button>
				</div>
			</div>
		</div>

		<!-- Nav tabs -->
		<div class="merchant-nav">
			<div class="merchant-menu row"> 
				<div class="col-xs-4">
					<a class="btn btn-block spaced {{ $default_tab == 'offers' ? 'btn-black' : 'btn-white' }}" href="#offers" data-target="#offersTab" data-toggle="tab">{{(($merchant->coupon_tab_type == '')||($merchant->coupon_tab_type == 'Coupons'))?'Offers':$merchant->coupon_tab_type}} @if(($expired_default && $entities['stats']['total'] != 0) || !$expired_default)<span class="is_dealer_hide">(<span class="offer-count">{{$entities['stats']['total']}}</span>)</span>@endif</a>
				</div>
				<div class="col-xs-4">
					<a class="btn btn-block spaced {{ $default_tab == 'about' ? 'btn-black' : 'btn-white' }}" href="#about" data-target="#aboutTab" data-toggle="tab">About</a>
				</div>
				<div class="col-xs-4">
					<a class="btn btn-white btn-block spaced" href="#reviews" data-target="#reviewsTab" data-toggle="tab">Reviews (<span id="reviewCount">{{count($reviews['objects'])}}</span>)</a>
				</div>
			</div>
			<!---<div class="dropdown visible-xs mobile-menu">
				<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown">
			    	Menu
			  	</button>
			  	<ul class="dropdown-menu merchant-menu">
			    	<li class="{{ $default_tab == 'offers' ? 'active' : '' }}"><a href="#offers" data-target="#offersTab" data-toggle="tab">Offers @if(($expired_default && $entities['stats']['total'] != 0) || !$expired_default)<span class="is_dealer_hide">(<span class="offer-count">0</span>)</span>@endif</a></li>
				  	<li class="{{ $default_tab == 'about' ? 'active' : '' }}"><a href="#about" data-target="#aboutTab" data-toggle="tab">About</a></li>
				  	<li><a href="#reviews" data-target="#reviewsTab" data-toggle="tab">Reviews ({{count($reviews['objects'])}})</a></li>
			  	</ul>
			</div>-->
		</div>

		<div class="tab-content">

		  	<div class="tab-pane {{ $default_tab == 'offers' ? 'active' : '' }}" id="offersTab">
			  	<div class="margin-bottom-20 is_dealer dealer-menu">
			  		<a type="button" class="btn btn-large spaced {{ $dealer_tab == 'new' ? 'btn-green' : 'btn-white' }}{{($franchise->is_new_car_leads)?'':' hidden'}}" href="#newCars" data-target="#newCarsTab" data-toggle="tab">New Cars</a>
			  		@if($vehicles['stats']['total'] > 0)
			  		<a type="button" class="btn btn-large spaced {{ $dealer_tab == 'used' ? 'btn-green' : 'btn-white' }}{{($franchise->is_used_car_leads)?'':' hidden'}}" href="#usedCars" data-target="#usedCarsTab" data-toggle="tab">Used Cars</a>
			  		@endif
			  		@if($merchant->vendor == 'soct')<a type="button" class="btn btn-large spaced {{ $dealer_tab == 'lease_specials' ? 'btn-green' : 'btn-white' }}" href="#autoServices" data-target="#autoServicesTab" data-toggle="tab"><span class="hidden-xs">Service &amp; Lease </span>Specials</a>@endif
			  	</div>
	  		   <div class="tab-content">
                    <div class="tab-pane {{ $dealer_tab == 'new' ? 'active' : '' }}" id="newCarsTab">
                        <div id="new-cars" class="js-masonry soct-masonry">
        			  	@foreach($new['objects'] as $vehicle)
                            @include('soct.templates.new-car', array('vehicle' => $vehicle))
                        @endforeach
                        </div>
                        <div class="clearfix"></div>
                        @if($new['stats']['total'] > $new['stats']['page']*$new['stats']['take'] && $new['stats']['returned'] == $new['stats']['take'])
                        <div class="">
                            <button class="btn btn-block btn-lg btn-grey view-more center-block" data-loading-text="Loading...">View More</button>
                        </div>
                        @endif
                    </div>

                    <div class="tab-pane {{ $dealer_tab == 'used' ? 'active' : '' }}" id="usedCarsTab">
                        <div id="used-cars" class="js-masonry soct-masonry">
                        @foreach($vehicles['objects'] as $vehicle)
                            @include('soct.templates.grid-vehicle-entity', array('vehicle' => $vehicle))
                        @endforeach
						</div>
                        <div class="clearfix"></div>
                        @if($vehicles['stats']['total'] > $vehicles['stats']['page']*$vehicles['stats']['take'] && $vehicles['stats']['returned'] == $vehicles['stats']['take'])
                        <div class="">
                            <button class="btn btn-block btn-lg btn-grey view-more center-block" data-loading-text="Loading...">View More</button>
                        </div>
                        @endif
                    </div>

                    <div class="tab-pane {{ $dealer_tab == 'lease_specials' ? 'active' : '' }}" id="autoServicesTab">
                        <div id="container" class="js-masonry {{$entities['stats']['total'] == 0 ? (($expired_default)?'':'expired') : ''}} {{$franchise->is_dealer == 1 ? 'soct-masonry': ''}} offer-results-grid merchant-page" style="position:relative; height: 791px;">
                            @foreach($entities['objects'] as $entity)
                                @include('master.templates.entity', array('entity' => $entity, 'merchant_page' => 'true'))
                            @endforeach
                            @foreach($expired['objects'] as $entity)
                                @include('master.templates.entity', array('entity' => $entity, 'merchant_page' => 'true'))
                            @endforeach
        				</div>

        				<div class="clearfix"></div>

        				<div class="no-offers" @if($entities['stats']['total'] == 0)style="display:block"@endif>
        			  		<div style="" class="content-bg margin-bottom-20">
        				  		<span class="h1">Oh Fiddle Sticks...</span>
        				  		<p>It looks like this merchant does not currently have any offers. Don't worry though, we've got lots offers we know you'll love. Check out these offers from similar merchants.</p>
        				  	</div>
        			  	</div>
                        @if($entities['stats']['total'] > $entities['stats']['page']*$entities['stats']['take'] && $entities['stats']['returned'] == $entities['stats']['take'])
        				<div class="">
        					<button class="btn btn-block btn-lg btn-green view-more center-block" data-loading-text="Loading...">View More</button>
        				</div>
                        @endif
        				<div id="containerRelated" class="js-masonry offer-results-grid" style="">
                            @foreach($related['objects'] as $entity)
                                @include('master.templates.entity', array('entity' => $entity))
                            @endforeach
        				</div>
                    </div>
                </div>
			</div>
			
		  	<div class="tab-pane {{ $default_tab == 'about' ? 'active' : '' }}" id="aboutTab">
		  		<div class="content-bg about">
			  		<div class="row">
			  			<div class="col-sm-6">
			  				<div id="sync1" class="owl-carousel">
			  					<?php foreach($videos['objects'] as $video) { ?>
			  					<div class="about-item">{{$video->path}}</div>
			  					<?php } ?>
			  					<?php $imageBig = 0; ?>
			  					<?php foreach($images['objects'] as $image) { ?>
			  					<?php $imageBig++; ?>
			  					<div class="about-item"><img alt="About Us Image {{$imageBig}} Large" class="img-responsive center-block" src="{{$image->path}}"></div>
			  					<?php } ?>
							</div>
							
			  			</div>
			  			<div class="col-sm-6">
			  				<div class="about about_content">
				  				
				  				<div id="sync2" class="owl-carousel">
				  					<?php $videoSmall = 0; ?>
				  					<?php foreach($videos['objects'] as $video) { ?>
				  					<?php $videoSmall++; ?>
				  					<div class="about-item"><img alt="About Us Video {{$imageBig}} Small" class="img-responsive" src="/img/play-button.png"></div>
				  					<?php } ?>
				  					<?php $imageSmall = 0; ?>
								  	<?php foreach($images['objects'] as $image) { ?>
								  	<?php $imageSmall++; ?>
			  						<div class="about-item">
			  							<img alt="About Us Image {{$imageSmall}} Small" class="img-responsive" src="{{$image->path}}">
			  							<div class="img-text">
				  							<div id="photolargetextdiv" class="image-title-div">{{$image->short_description}}</div>
	                						<div id="imagetextdisplay" class="imagetextdiv" style="">{{$image->long_description}}</div>
	                						<hr>
				  						</div>
			  						</div>
			  					<?php } ?>
								</div>
								<div class="merchant-img-text">
									<div id="photolargetextdiv" class="image-title-div">{{(count($images['objects'])>0)?$images['objects'][0]->short_description:""}}</div>
	                				<div id="imagetextdisplay" class="imagetextdiv" style="">{{(count($images['objects'])>0)?$images['objects'][0]->long_description:""}}</div>
	                				<hr>
								</div>
								<div class="merchant-about-text" itemprop="description">
				  					{{$about_text}}
				  				</div>
			  				</div>
			  			</div>
			  		</div>
			  	</div>
			  	<div class="content-bg downloads" id= "merchantDownloads" style="{{($pdfs['stats']['total']==0)?'display:none':''}}">
			  		<h3 class="h4 hblock">Downloads</h3>
			  		<ul>
			  		<?php foreach($pdfs['objects'] as $pdf) { ?>
			  			<li><a href="{{$pdf->path}}" target="_blank">{{$pdf->long_description}}</a> <a href="{{$pdf->path}}" target="_blank"><img alt="Download" class="pull-right" src="/img/download-icon.png" /></a></li>
			  		<?php } ?>
			  		</ul>
			  	</div>

		  	</div>
		  	<div class="tab-pane" id="reviewsTab">
		  		<?php foreach($reviews['objects'] as $review) { ?>
		  		<div class="content-bg review" data-review_id="{{$review->id}}">
		  			<div class="row">
		  				<div class="col-xs-8">
		  					<p><strong class="total-ups">{{$review->upvotes}}</strong> of <strong class="total-votes">{{$review->votes}}</strong> people found this review helpful</p>
		  				</div>
		  				<div class="col-xs-4">
				  			<div class="pull-right">
		                        @if($review->user_id == $user->id && $user->getType() == 'User')
		                        <button class="btn btn-link btn-delete-review" data-review_id="{{$review->id}}">Delete</button>
		                        @endif
		                    </div>
		                </div>
	                </div>
		  			<div class="row">
		  				<div class="col-sm-4 col-md-3">
							<div class="progress-popular pull-left" style="" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
		    			        <meta itemprop="worstRating" content = "0">
		    			        <meta itemprop="ratingValue" content = "{{$review->rating}}">
		    			        <meta itemprop="bestRating" content = "5">
		    			        <div class="bar-popular-container" style="width: {{$review->rating / 5 * 100}}%;">
		    			            <div class="bar-popular stars" style="width: 150px;"></div>
		    			        </div>
		    			    </div>
		    			</div>
		    			<div class="clearfix visible-xs"></div>
		    			<div class="col-sm-8 col-md-9">
		                    <p>{{$review->content}}</p>
		                </div>
	                </div>
                    <div class="clearfix"></div>
                    <div class="row review-bottom">
    					<div class="col-xs-6">
	    					<p>By <strong>{{$review->user->name == '' ? 'Anonymous' : $review->user->name}}</strong> {{date('m/d/Y',strtotime($review->created_at))}}</p>
	    				</div>
	    				<div class="col-xs-6">
	    					<div class="pull-right">
	    						@if($review->user_id != $user->id && $user->getType() == 'User' || $user->getType() == 'Nonmember')<p>Was this review helpful? <br> <a class="btn btn-link vote-up{{$review->my_vote == 1 ? ' active' : ''}}" data-review_id="{{$review->id}}">Yes</a> <a class="btn btn-link">|</a> <a class="btn btn-link vote-down{{$review->my_vote == -1 ? ' active' : ''}}" data-review_id="{{$review->id}}">No</a></p>@endif
	    					</div>
	    				</div>
                    </div>
				</div>
		  		<?php } ?>
				<div class="no-reviews" style="{{$reviews['stats']['returned'] >0 ? 'display: none;' : ''}}">
				<div class="content-bg">
					<p class="open-review-area" style="">Be the first to tell your fellow Savers about this merchant! Write a review <a type="button" class="btn-open-review">here</a>!</p>
				</div>
				</div>
			</div>
		</div>
	</div>

<div class="modal fade review" id="reviewModal" tabindex="-1" role="dialog" aria-labelledby="reviewModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <span class="h1 modal-title fancy" id="reviewModalLabel">Write a Review</span>
      </div>
      <div class="modal-body">
  		<span class="h1 hblock">{{$merchant->display}} ({{$location->city.', '.$location->state}})</span>
  		<br>
      		<div class="form-group">
			    <label class="sr-only" for="reviewText">Your Review...</label>
			    <textarea class="form-control" rows="5" id="reviewText" name="ReviewText" placeholder="Your review..."></textarea>
			</div>
      		<div>
                <div id="star" data-score="5"></div>
            </div>
      		<div class="form-group">
	      		<div class="checkbox">
				  	<label>
				    	<input type="checkbox" value="" id="rules" name="rules">
				    	I have read and agree to the <a href="{{URL::abs('/')}}/terms">terms of use</a>
				  	</label>
				</div>
			</div>
			<button class="btn btn-primary pull-right btn-review-submit">Submit</button>
			<div class="clearfix"></div>
      	</div>
      <div class="modal-footer">
        <span id="reviewMessages"></span>
      </div>
    </div>
  </div>
</div>

<div class="modal fade review" id="reviewErrorModal" tabindex="-1" role="dialog" aria-labelledby="reviewErrorModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <span class="h1 modal-title fancy" id="reviewModalLabel">Oops</span>
      </div>
      <div class="modal-body">
  		<p>You have already written a review for this merchant.</p>
      	</div>
    </div>
  </div>
</div>

<script>
	@if((Auth::check() && (strtolower(Auth::User()->type) != 'employee' && strtolower(Auth::User()->type) != 'demo')) || !Auth::check())
       /*mixpanel.track('Location View', {
           'Environment': "{{App::environment()}}",
           'LocationId': "{{$location->id}}",
           'MerchantId': "{{$location->merchant_id}}",
           'FranchiseId': "{{$location->franchise_id}}",
           'MerchantName': "{{$location->merchant_name}}",
           'MerchantNameAddress': "{{$location->merchant_name.' - '.$location->address}}"
       }); */
   @endif
</script>

@stop