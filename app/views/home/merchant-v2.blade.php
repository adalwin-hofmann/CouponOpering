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
	@include('soct.templates.become-dealer')
	@endif
	</div>
	@if($franchise->is_certified==1)
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
	@endif
@stop

@section('body')
<script type="text/ejs" id="template_entity_v2">
<% list(entities, function(entities)
{ %>
	 <%
      var c = entities.expires_at.split(/[- :]/);
      var expires = new Date(c[0], c[1]-1, c[2], c[3], c[4], c[5]);
      var company_slug = '';
      if(entities.company_id > 1)
      {
        company_slug = entities.company_name.toLowerCase().replace(/ /g,'-').replace(/[^\w-]+/g,'');
      }
    %>
    <% if (entities.entitiable_type == 'Offer') { %>
    	<% if ((entities.secondary_type == 'lease') || (entities.secondary_type == 'purchase')) { %>
    		<div class="item <%= entities.secondary_type %> invisible version2" itemscope itemtype="http://schema.org/Organization">
    			<div class="item-type <%= entities.secondary_type %>"></div>
    			<div class="top-pic <%== (entities.company_id == 2)?'yipit':''%>" style="background-image: url('<%== entities.path != entities.logo ? entities.path : (entities.about_img ? entities.about_img : entities.path)%>');">
					<a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%== (entities.category_slug == entities.subcategory_slug)?'':entities.subcategory_slug+'/'%><%= entities.merchant_slug+'/'+entities.location_id %>">
	                    <div class="expired-banner"><span class="h3 spaced">Expired</span></div>
	                    <img alt="<%= entities.merchant_name %> Coupons" class="img-responsive" src="<%== entities.path != entities.logo ? entities.path : (entities.about_img ? entities.about_img : entities.path)%>" itemprop="image">
	                </a>
	            </div>
	            <div itemscope itemtype="http://schema.org/Product">
              		<span class="hidden" itemprop="name"><%= entities.merchant_name %> - <%= entities.name %></span>
              		<div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
	              		<a class="item-info" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%== (entities.category_slug == entities.subcategory_slug)?'':entities.subcategory_slug+'/'%><%= entities.merchant_slug+'/'+entities.location_id %>?showeid=<%= entities.id %>" itemscope itemtype="http://schema.org/Product">
	              			<div class="item-name" itemprop="name">
	              				<% if (entities.short_name_line1 != '') { %>
	                        	<div class="line1">
	                          	<%= entities.short_name_line1 %>
	                        	</div>
	                        	<%= entities.short_name_line2 %>
	                      		<% } else { %>
	                      		<%= entities.name %>
	                      		<% } %>
	                      	</div>
	                    </a>
	                    <p class="category-links"><a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>" title="<%= unSlugCategory(entities.category_slug) %> Coupons"><%= unSlugCategory(entities.category_slug) %></a> > <a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%== (entities.category_slug == entities.subcategory_slug)?'':entities.subcategory_slug%>" title="<%= unSlugCategory(entities.subcategory_slug) %> Coupons"><%= unSlugCategory(entities.subcategory_slug) %></a></p>
	                    <% if(entities.hide_expiration == '0'){ %>
	                    <p class="expires_at" itemprop="availabilityEnds">Expires <%== entities.is_reoccurring == "1" ? "{{date('m/t/Y')}}" : expires.getMonth() + 1 +"/"+ expires.getDate() +"/"+ expires.getFullYear() %></p>
	                    <% } %>
                    </div>
                </div>
                <% if (entities.company_id > 1) { %>
                <div class="white-label">
                	<p>Provided by <% if (company_slug != 'yipit'){ %><a href="{{URL::abs('/')}}/<%= company_slug %>"><%= entities.company_name %></a><% }else{ %><%= entities.company_name %><% } %></p>
                </div>
                <% } %>
                <% if (entities.is_certified == '1') { %>
                <div class="certified_section">
                  	<div class="row">
                    	<div class="col-xs-4">
                      		<img alt="Save Certified" class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sohi/save_certified.png">
                    	</div>
                    	<div class="col-xs-6">
                      		<span class="h2 spaced">Save Certified</span>
                    	</div>
                  	</div>
                </div>
                <% } %>
                <div class="btn-group">
                    <button type="button" class="btn btn-default btn-get-coupon" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" data-toggle="tooltip" data-placement="top" title="Get Info on <%= entities.name %> from <%= entities.merchant_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-burgundy-more-info.png" alt="Get Info on <%= entities.name %> from <%= entities.merchant_name %>"></button>
                    <button type="button" class="btn btn-default btn-lease-contact" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" data-toggle="tooltip" data-placement="top" title="Contact <%= entities.merchant_name %> about <%= entities.name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-burgundy-contact.png" alt="Contact <%= entities.merchant_name %> about <%= entities.name %>"></button>
                    <button type="button" class="btn btn-default btn-coupon-share" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" data-toggle="tooltip" data-placement="top" title="Share This Lease Special for <%= entities.name %> from <%= entities.merchant_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-burgundy-share-it.png" alt="Share This Lease Special for <%= entities.name %> from <%= entities.merchant_name %>"></button>
                </div>
    		</div>
    	<% } else { %>
            <div class="item coupon invisible version2 <%= entities.is_certified == 1 ? 'save_certified_offer' : '' %>" itemscope itemtype="http://schema.org/Organization">
                <div class="item-type coupon"></div>
                <div class="top-pic <%== (entities.company_id == 2)?'yipit':''%>" style="background-image: url('<%== entities.path != entities.logo ? entities.path : (entities.about_img ? entities.about_img : entities.path)%>');">
					<a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%== (entities.category_slug == entities.subcategory_slug)?'':entities.subcategory_slug+'/'%><%= entities.merchant_slug+'/'+entities.location_id %>">
	                    <div class="expired-banner"><span class="h3 spaced">Expired</span></div>
	                    <img alt="<%= entities.merchant_name %> Coupons" class="img-responsive" src="<%== entities.path != entities.logo ? entities.path : (entities.about_img ? entities.about_img : entities.path)%>" itemprop="image">
	                </a>
	            </div>
	            <div itemscope itemtype="http://schema.org/Product">
              		<span class="hidden" itemprop="name"><%= entities.merchant_name %> - <%= entities.name %></span>
              		<div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
	              		<a class="item-info" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%== (entities.category_slug == entities.subcategory_slug)?'':entities.subcategory_slug+'/'%><%= entities.merchant_slug+'/'+entities.location_id %>?showeid=<%= entities.id %>" itemscope itemtype="http://schema.org/Product">
	              			<div class="item-name" itemprop="name">
	              				<% if (entities.short_name_line1 != '') { %>
	                        	<div class="line1">
	                          	<%= entities.short_name_line1 %>
	                        	</div>
	                        	<%= entities.short_name_line2 %>
	                      		<% } else { %>
	                      		<%= entities.name %>
	                      		<% } %>
	                      	</div>
	                    </a>
	                    <p class="category-links"><a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>" title="<%= unSlugCategory(entities.category_slug) %> Coupons"><%= unSlugCategory(entities.category_slug) %></a> > <a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= entities.category_slug %>/<%== (entities.category_slug == entities.subcategory_slug)?'':entities.subcategory_slug%>" title="<%= unSlugCategory(entities.subcategory_slug) %> Coupons"><%= unSlugCategory(entities.subcategory_slug) %></a></p>
	                    <% if(entities.hide_expiration == '0'){ %>
	                    <p class="expires_at" itemprop="availabilityEnds">Expires <%== entities.is_reoccurring == "1" ? "{{date('m/t/Y')}}" : expires.getMonth() + 1 +"/"+ expires.getDate() +"/"+ expires.getFullYear() %></p>
	                    <% } %>
                    </div>
                </div>
                <% if (entities.company_id > 1) { %>
                <div class="white-label">
                	<p>Provided by <% if (company_slug != 'yipit'){ %><a href="{{URL::abs('/')}}/<%= company_slug %>"><%= entities.company_name %></a><% }else{ %><%= entities.company_name %><% } %></p>
                </div>
                <% } %>
                <% if (entities.is_certified == '1') { %>
                <div class="certified_section">
                  	<div class="row">
                    	<div class="col-xs-4">
                      		<img alt="Save Certified" class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sohi/save_certified.png">
                    	</div>
                    	<div class="col-xs-6">
                      		<span class="h2 spaced">Save Certified</span>
                    	</div>
                  	</div>
                </div>
                <% } %>
                <div class="btn-group">
                    <button type="button" class="btn btn-default btn-get-coupon" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" data-toggle="tooltip" data-placement="top" title="Get Info on <%= entities.name %> from <%= entities.merchant_name %>"><span class="visible-xs-inline"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-green-get-it.png" alt="Get Info on <%= entities.name %> from <%= entities.merchant_name %>"></span><span class="hidden-xs"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-green-print-it.png" alt="Get Info on <%= entities.name %> from <%= entities.merchant_name %>"></span></button>
                    <button type="button" class="btn btn-default btn-save-coupon <%= entities.is_clipped == true ? 'disabled' : '' %>" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" data-toggle="tooltip" data-placement="top" title="Save This Coupon for <%= entities.name %> from <%= entities.merchant_name %>"><img src="<%= entities.is_clipped == true ? 'http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-green-saved.png' : 'http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-green-save-it.png' %>" alt="Save This Coupon for <%= entities.name %> from <%= entities.merchant_name %>"></button>
                    <% if (entities.is_certified == '0' && entities.is_sohi_trial == '0') { %>
                    <button type="button" class="btn btn-default btn-coupon-share" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" data-toggle="tooltip" data-placement="top" title="Share This Coupon for <%= entities.name %> from <%= entities.merchant_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-green-share-it.png" alt="Share This Coupon for <%= entities.name %> from <%= entities.merchant_name %>"></button>
                    <% } %>
                    @if($quote_control)
                    <% if (entities.is_certified == '1' || entities.is_sohi_trial == '1') { %>
                    <a href="{{URL::abs('/')}}<%= '/homeimprovement/quote?offer_id='+entities.entitiable_id %>" type="button" class="btn btn-default btn-coupon-quote" data-offer_id="<%= entities.entitiable_id %>" data-entity_id="<%= entities.id %>" data-toggle="tooltip" data-placement="top" title="Get a Quote for <%= entities.name %> from <%= entities.merchant_name %>"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-green-quote-it.png" alt="Get a Quote for <%= entities.name %> from <%= entities.merchant_name %>"></a>
                    <% } %>
                    @endif
                </div>
            </div>
        <% } %>
    <% } else if (entities.entitiable_type == 'Contest') { %>
    <% } %>
<% }); %>
</script>

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
	<div itemscope itemtype="http://schema.org/Organization" class="merchant-version{{$page_version}}-body">
		@if(!empty($banner))
		<div class="merchant-header banner">
			<img alt="{{$merchant->display}}" src="{{$banner->path}}" class="img-responsive hidden-xs" width="988" height="350">
			<img class="img-responsive center-block visible-xs" src="{{(!empty($logo))?$logo->path:''}}" alt="{{$merchant->display}}" itemprop="logo">
			@if($franchise->is_certified)
			<img alt="Save Certified" class="save-certified-logo" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sohi/save_certified.png">
			@endif
		</div>
		@endif
		<div class="content-bg margin-bottom-15 address-bar">
			<div class="row">
				<div class="col-sm-6 col-md-5 col-lg-4">
					<div class="pull-left">
						<span class="glyphicon glyphicon-map-marker margin-right-10"></span>
					</div>
					<address itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
						@if($location->is_address_hidden)
							{{$location->custom_address_text}}
						@else
						<span itemprop="streetAddress">{{$location->address}}<br>
						<?php ($location->address2)?$location->address2.'<br>':'' ?></span>
						<span itemprop="addressLocality">{{$location->city}}</span>, <span itemprop="addressRegion">{{$location->state}}</span> <span span itemprop="postalCode">{{$location->zip}}</span><br>
                        @endif
                        @if($location->redirect_number != '')
                            <a class="btn-click-to-call" href="#" data-merchant_name="{{$merchant->display}}" data-location_id="{{$location->id}}">{{$location->redirect_text != '' ? $location->redirect_text : 'Click To Call'}}</a><br>
                        @else
						  @if($location->phone != '')<a href="tel:+1{{preg_replace("/[^0-9]/","",$location->phone)}}" class="phone"><span itemprop="telephone">{{$location->phone}}</span></a>@endif
                        @endif
                    </address>
                    @if($reviews['stats']['total']!=0)
                    <div class="margin-top-10" style="{{($reviews['stats']['total']!=0)?'display:none':''}}">
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
					@endif
				</div>
				<div class="col-sm-6 col-md-5 col-lg-3 buttons">
					@if ($locationCount > 1)
						<a href="{{URL::abs('/')}}/directions/{{$merchant->slug}}" class="btn btn-block btn-default btn-green-hover"><span class="glyphicon glyphicon-map-marker"></span> View Other Locations</a>
					@elseif ($locationCount == 1)
						<a target="_blank" href="http://maps.google.com/?q={{$location->latitude.','.$location->longitude}}" class="btn btn-block btn-default btn-green-hover"><span class="glyphicon glyphicon-map-marker"></span> View On Map</a>
					@endif
					@if($is_reviewed != 1)
						<button type="button" class="btn btn-block btn-default btn-green-hover btn-open-review"><span class="glyphicon glyphicon-star-empty"></span> Write a Review</button>
					@endif
					@if ($catSlug == 'food-dining')
						@if ($pdfs['stats']['total']==1)
						<a class="btn btn-default btn-green-hover btn-block btn-download-menu" href="{{$pdfs['objects'][0]->path}}" target="_blank"><span class="glyphicon glyphicon-file"></span> View Menu</a>
						@elseif ($pdfs['stats']['total']>1)
		            	<button class="btn btn-default btn-green-hover btn-block btn-download-menu btn-downloads" href="#about" data-target="#aboutTab" data-toggle="tab"><span class="glyphicon glyphicon-file"></span> View Menu</button>
		            	@endif
				    @elseif ($franchise->is_dealer == 1)
				    	@if ($pdfs['stats']['total']==1)
						<a class="btn btn-default btn-green-hover btn-block btn-download-menu" href="{{$pdfs['objects'][0]->path}}" target="_blank"><span class="glyphicon glyphicon-file"></span> View Ad</a>
						@elseif ($pdfs['stats']['total']>1)
		            	<button class="btn btn-default btn-green-hover btn-block btn-download-info btn-downloads" href="#about" data-target="#aboutTab" data-toggle="tab"><span class="glyphicon glyphicon-file"></span> View Ad</button>
		            	@endif
				    @else
				    	@if ($pdfs['stats']['total']==1)
						<a class="btn btn-default btn-green-hover btn-block btn-download-menu" href="{{$pdfs['objects'][0]->path}}" target="_blank"><span class="glyphicon glyphicon-file"></span> Get Info</a>
						@elseif ($pdfs['stats']['total']>1)
		            	<button class="btn btn-default btn-green-hover btn-block btn-download-info btn-downloads" href="#about" data-target="#aboutTab" data-toggle="tab"><span class="glyphicon glyphicon-file"></span> Get Info</button>
		            	@endif
		           		
				    @endif
				    @if($location->custom_website != '')
                    	<a class="btn btn-default btn-green-hover btn-block" href="{{(strpos($location->custom_website,'mailto:') !== false)?$location->custom_website:((strpos($location->custom_website, 'http') === false)?'http://'.$location->custom_website:$location->custom_website)}}" {{(strpos($location->custom_website,'mailto:') === false)?'onclick="master_control.TrackCustomClick('.$location->id.');return false':''}}">{{$location->custom_website_text == '' ? $location->custom_website : $location->custom_website_text}}</a>
                    @endif
				</div>
				<div class="margin-bottom-10 clearfix visible-sm visible-xs"></div>
				<div class="col-sm-12 col-md-7 col-md-offset-5 col-lg-5 col-lg-offset-0">
					<div class="btn-group">
						<button type="button" class="btn btn-favorite-merchant{{$is_favorited ? ' disabled' : ''}}" "{{$is_favorited ? 'disabled="disabled"': ''}}">
						<img src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/merchant-btn-favorite.png" alt="Save to Favorites" class="img-responsive center-block">
						@if (!$is_favorited) 
							Save to Favorites
						@else
							My Favorite! 
						@endif
						</button>
						@if ($merchant->facebook!="")
						<a class="btn" href="<?php if(strpos($merchant->facebook, 'http') === false){echo "http://".$merchant->facebook;}else{echo $merchant->facebook;}?>" target="_blank"><img src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/merchant-btn-facebook.png" alt="Facebook" class="img-responsive center-block">Like On Facebook</a>
						@endif
						@if ($merchant->twitter!="")
						<a class="btn" href="<?php if(strpos($merchant->twitter, 'http') === false){echo "http://".$merchant->twitter;}else{echo $merchant->twitter;}?>" target="_blank"><img src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/merchant-btn-twitter.png" alt="Twitter" class="img-responsive center-block">Follow on Twitter</a> 
						@endif
						@if ($location->website!="")
						<a class="btn" href="{{(strpos($location->website, 'http') === false)?'http://'.$location->website:$location->website}}" onclick="master_control.TrackClick({{$location->id}});return false" target="_blank"><img src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/merchant-btn-website.png" alt="Website" class="img-responsive center-block">View Website</a>
						@endif
                        
					</div>
				</div>
			</div>
		</div>

		<!-- Nav tabs -->
		<div class="merchant-nav">
			<div class="merchant-menu row">
				<div class="col-xs-4">
					<a class="btn btn-block spaced btn-white {{ $default_tab == 'offers' ? 'active' : '' }}" href="#offers" data-target="#offersTab" data-toggle="tab">{{(($merchant->coupon_tab_type == '')||($merchant->coupon_tab_type == 'Coupons'))?'Offers':$merchant->coupon_tab_type}} @if(($expired_default && $entities['stats']['total'] != 0) || !$expired_default)<span class="is_dealer_hide">(<span class="offer-count">{{$entities['stats']['total']}}</span>)</span>@endif</a>
				</div>
				<div class="col-xs-4">
					<a class="btn btn-block spaced btn-white {{ $default_tab == 'about' ? 'active' : '' }}" href="#about" data-target="#aboutTab" data-toggle="tab">About</a>
				</div>
				<div class="col-xs-4">
					<a class="btn btn-white btn-block spaced" href="#reviews" data-target="#reviewsTab" data-toggle="tab">Reviews (<span id="reviewCount">{{count($reviews['objects'])}}</span>)</a>
				</div>
			</div>
			<!--<div class="dropdown visible-xs mobile-menu">
				<button class="btn btn-default btn-block dropdown-toggle" type="button" data-toggle="dropdown">
			    	Menu
			  	</button>
			  	<ul class="dropdown-menu merchant-menu">
			    	<li class="{{ $default_tab == 'offers' ? 'active' : '' }}"><a href="#offers" data-target="#offersTab" data-toggle="tab">Offers @if(($expired_default && $entities['stats']['total'] != 0) || !$expired_default)<span class="is_dealer_hide">(<span class="offer-count">{{$entities['stats']['total']}}</span>)</span>@endif</a></li>
				  	<li class="{{ $default_tab == 'about' ? 'active' : '' }}"><a href="#about" data-target="#aboutTab" data-toggle="tab">About</a></li>
				  	<li><a href="#reviews" data-target="#reviewsTab" data-toggle="tab">Reviews ({{count($reviews['objects'])}})</a></li>
			  	</ul>
			</div>-->
		</div>

		<div class="tab-content">

		  	<div class="tab-pane {{ $default_tab == 'offers' ? 'active' : '' }}" id="offersTab">

		  		<div class="active-pane margin-bottom-20">
				  	<div class="is_dealer dealer-menu">
				  		<a type="button" class="btn btn-large spaced {{ $dealer_tab == 'new' ? 'btn-green' : 'btn-white' }}{{($franchise->is_new_car_leads)?'':' hidden'}}" href="#newCars" data-target="#newCarsTab" data-toggle="tab">New Cars</a>
				  		<a type="button" class="btn btn-large spaced {{ $dealer_tab == 'used' ? 'btn-green' : 'btn-white' }}{{($franchise->is_used_car_leads)?'':' hidden'}}" href="#usedCars" data-target="#usedCarsTab" data-toggle="tab">Used Cars</a>
				  		@if($merchant->vendor == 'soct')<a type="button" class="btn btn-large spaced {{ $dealer_tab == 'lease_specials' ? 'btn-green' : 'btn-white' }}" href="#autoServices" data-target="#autoServicesTab" data-toggle="tab"><span class="hidden-xs">Service &amp; Lease </span>Specials</a>@endif
				  	</div>
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
                        <div id="container" class="js-masonry {{$entities['stats']['total'] == 0 ? (($expired_default)?'':'expired') : ''}} {{$franchise->is_dealer == 1 ? 'soct-masonry': ''}} offer-results-grid merchant-page">
                            @foreach($entities['objects'] as $entity)
                                @include('master.templates.entity-v2', array('entity' => $entity))
                            @endforeach
                            @foreach($expired['objects'] as $entity)
                                @include('master.templates.entity-v2', array('entity' => $entity))
                            @endforeach
        				</div>

        				<div class="clearfix"></div>
        				@if($entities['stats']['total'] == 0)
        				<div class="no-offers" style="{{($entities['stats']['total'] == 0)?'display:block':''}}">
        			  		<div class="content-bg margin-bottom-20">
        				  		<span class="h1">Oh Fiddle Sticks...</span>
        				  		<p>It looks like this merchant does not currently have any offers. Don't worry though, we've got lots offers we know you'll love. Check out these offers from similar merchants.</p>
        				  	</div>
        			  	</div>
        			  	@endif
                        @if($entities['stats']['total'] > $entities['stats']['page']*$entities['stats']['take'] && $entities['stats']['returned'] == $entities['stats']['take'])
        				<div class="">
        					<button class="btn btn-block btn-lg btn-green view-more center-block" data-loading-text="Loading...">View More</button>
        				</div>
                        @endif
        				<div id="containerRelated" class="js-masonry offer-results-grid">
                            @foreach($related['objects'] as $entity)
                                @include('master.templates.entity-v2', array('entity' => $entity))
                            @endforeach
        				</div>
                    </div>
                </div>
			</div>
			
		  	<div class="tab-pane {{ $default_tab == 'about' ? 'active' : '' }}" id="aboutTab">
		  		<div class="active-pane margin-bottom-20"></div>
		  		<div class="content-bg about about_content">
			  		<div class="row">
			  			<div class="col-sm-5">
			  				<div class="merchant-about-text" itemprop="description">
			  					{{$about_text}}
			  				</div>
			  			</div>
			  			<div class="col-sm-7">
			  				<div id="sync1" class="owl-carousel margin-bottom-10">
			  					<?php foreach($videos['objects'] as $video) { ?>
			  					<div class="about-item">{{$video->path}}</div>
			  					<?php } ?>
			  					<?php $imageBig = 0; ?>
			  					<?php foreach($images['objects'] as $image) { ?>
			  					<?php $imageBig++; ?>
			  					<div class="about-item"><img alt="About Us Image {{$imageBig}} Large" class="img-responsive center-block" src="{{$image->path}}"></div>
			  					<?php } ?>
							</div>
			  				<div class="about about_content">
				  				
				  				<div id="sync2" class="owl-carousel margin-bottom-15">
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
								@if((count($hours) != 0) || ($location->is_24_hours == 1))
								<?php  ?>
								<div class="hours text-center">
								<div class="h4">Hours</div>
								@if($location->is_24_hours == 1)
									<strong>Open 24 Hours</strong>
								@endif
								@for($i=0; $i <= count($hours) - 1; $i++)
									<span>
									{{date('D',strtotime($hours[$i]->weekday))}} {{(substr($hours[$i]->start_time, -2) == '00')?date('g',strtotime($hours[$i]->start_time)):date('g:i',strtotime($hours[$i]->start_time))}}{{strtolower($hours[$i]->start_ampm)}}-{{(substr($hours[$i]->end_time, -2) == '00')?date('g',strtotime($hours[$i]->end_time)):date('g:i',strtotime($hours[$i]->end_time))}}{{strtolower($hours[$i]->end_ampm)}}
									@if($i != count($hours) - 1)
									<strong>&middot;</strong>
									@endif
									</span>
								@endfor
								</div>
								@endif
								<div class="merchant-img-text">
									<div id="photolargetextdiv" class="image-title-div">{{(count($images['objects'])>0)?$images['objects'][0]->short_description:""}}</div>
	                				<div id="imagetextdisplay" class="imagetextdiv">{{(count($images['objects'])>0)?$images['objects'][0]->long_description:""}}</div>
	                				<hr>
								</div>
								
			  				</div>
			  			</div>
			  		</div>
			  	</div>
			  	@if($pdfs['stats']['total']!=0)
			  	<div class="content-bg downloads" id= "merchantDownloads">
			  		<h3 class="h4 hblock">Downloads</h3>
			  		<ul>
			  		<?php foreach($pdfs['objects'] as $pdf) { ?>
			  			<li><a href="{{$pdf->path}}" target="_blank">{{$pdf->long_description}}</a> <a href="{{$pdf->path}}" target="_blank"><img alt="Download" class="pull-right" src="/img/download-icon.png" /></a></li>
			  		<?php } ?>
			  		</ul>
			  	</div>
			  	@endif

		  	</div>
		  	<div class="tab-pane" id="reviewsTab">
		  		<div class="active-pane margin-bottom-20"></div>
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
		  		@if($reviews['stats']['returned'] == 0)
				<div class="no-reviews">
				<div class="content-bg">
					<p class="open-review-area">Be the first to tell your fellow Savers about this merchant! Write a review <a type="button" class="btn-open-review">here</a>!</p>
				</div>
				</div>
				@endif
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