@extends('master.templates.master', array('special_merchant'=>$special_merchant,'width'=>'full'))

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
			<img alt="{{$merchant->display}}" src="{{$banner->path}}" class="img-responsive hidden-xs" width="1300" height="200">
			<img class="img-responsive center-block visible-xs" src="{{(!empty($logo))?$logo->path:''}}" alt="{{$merchant->display}}" itemprop="logo">
			@if($franchise->is_certified)
			<img alt="Save Certified" class="save-certified-logo" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sohi/save_certified.png">
			@endif
		</div>
		@endif

		<div id="container" class="js-masonry {{$entities['stats']['total'] == 0 ? (($expired_default)?'':'expired') : ''}} {{$franchise->is_dealer == 1 ? 'soct-masonry': ''}} offer-results-grid merchant-page">
            @foreach($entities['objects'] as $entity)
                @include('master.templates.entity-v2', array('entity' => $entity))
            @endforeach
            @foreach($expired['objects'] as $entity)
                @include('master.templates.entity-v2', array('entity' => $entity))
            @endforeach
		</div>

		<div class="clearfix"></div>

		<div class="content-bg margin-bottom-15 address-bar">
			<div class="row">
				<div class="col-sm-6 col-md-5 col-lg-4">
					<div class="pull-left">
						<span class="glyphicon glyphicon-map-marker margin-right-10"></span>
					</div>
					<address itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
						<span itemprop="streetAddress">{{$location->address}}<br>
						<?php ($location->address2)?$location->address2.'<br>':'' ?></span>
						<span itemprop="addressLocality">{{$location->city}}</span>, <span itemprop="addressRegion">{{$location->state}}</span> <span span itemprop="postalCode">{{$location->zip}}</span><br>
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
						<a target="_blank" href="http://maps.google.com/?q={{ $location->address.' '.$location->address2.' '.SoeHelper::getSlug($location->city).', '.strtolower($location->state).' '.$location->zip }}" class="btn btn-block btn-default btn-green-hover"><span class="glyphicon glyphicon-map-marker"></span> View On Map</a>
					@endif
					@if($is_reviewed != 1)
						<button type="button" class="btn btn-block btn-default btn-green-hover btn-open-review"><span class="glyphicon glyphicon-star-empty"></span> Write a Review</button>
					@endif
					@if ($catSlug == 'food-dining')
		            	<button class="btn btn-default btn-green-hover btn-block btn-download-menu btn-downloads" style="{{($pdfs['stats']['total']==0)?'display:none':''}}" href="#about" data-target="#aboutTab" data-toggle="tab"><span class="glyphicon glyphicon-file"></span> View Menu</button>
				    @elseif ($franchise->is_dealer == 1)
		           		<button class="btn btn-default btn-green-hover btn-block btn-download-info btn-downloads" style="{{($pdfs['stats']['total']==0)?'display:none':''}}" href="#about" data-target="#aboutTab" data-toggle="tab"><span class="glyphicon glyphicon-file"></span> View Ad</button>
				    @else
		           		<button class="btn btn-default btn-green-hover btn-block btn-download-info btn-downloads" style="{{($pdfs['stats']['total']==0)?'display:none':''}}" href="#about" data-target="#aboutTab" data-toggle="tab"><span class="glyphicon glyphicon-file"></span> Get Info</button>
				    @endif
				    @if($location->custom_website != '')
                    	<a class="btn btn-default btn-green-hover btn-block" href="{{(strpos($location->custom_website, 'http') === false)?'http://'.$location->custom_website:$location->custom_website}}" onclick="master_control.TrackCustomClick({{$location->id}});return false">{{$location->custom_website_text == '' ? $location->custom_website : $location->custom_website_text}}</a>
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