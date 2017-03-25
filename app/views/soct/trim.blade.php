@extends('master.templates.master')

@section('city-banner')
    @include('soct.templates.soct-banner')
@stop

@section('page-title')
<h1>SaveOn {{$style->year}} {{$style->make_name}} {{$style->model_name}}</h1>
@stop

@section('breadcrumbs')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}" itemprop="url"><span itemprop="title">Home</span></a>
    </li>
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}/cars" itemprop="url"><span itemprop="title">Cars &amp; Trucks</span></a>
    </li>
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}/cars/research/{{($style->make_slug)?$style->make_slug:$style->make_id}}" itemprop="url"><span itemprop="title">{{$style->make_name}}</span></a>
    </li>
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}/cars/research/{{($style->make_slug)?$style->make_slug:$style->make_id}}/{{($style->model_slug)?$style->model_slug:$style->model_id}}" itemprop="url"><span itemprop="title">{{$style->model_name}}</span></a>
    </li>
    <li class="active">{{$style->year}} {{$style->name}}</li>
@stop

@section('sidebar')

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
    currentPage = 0;
    style_id = '{{$style->id}}';
    make_id = '{{$style->make_id}}';
    model_id = '{{$style->model_id}}';
</script>

<div class="visible-xs">
    @include('soct.templates.search-vehicles-mobile')
</div>

<script type="text/ejs" id="template_incentive">
<% list(incentives, function(incentive){ %>
    <hr>
    <p><a class="burgundy show-incentive" data-incentive_id="<%= incentive.id %>"><%= incentive.name %></a> <%= incentive.description %> <a class="burgundy show-incentive" data-incentive_id="<%= incentive.id %>">Learn More </a></p>
<% }); %>
</script>
<div class="content-bg">
	<div class="row">
		<div class="col-sm-5">
			<div id="sync1" class="owl-carousel">
				<?php foreach($assets as $image) { ?>
					<div class="about-item"><img class="img-responsive center-block" src="{{$image->path}}"></div>
				<?php } ?>
			</div>
			@if (count($assets) != 0)
			<p class="margin-top-20">Click a thumbnail to see larger {{$style->make_name}} {{$style->model_name}} pictures</p>
			<div id="sync2" class="owl-carousel">
				<?php foreach($assets as $image) { ?>
					<div class="about-item"><img class="img-responsive" src="{{$image->path}}"></div>
				<?php } ?>
			</div>
			@endif
		</div>
		<div class="col-sm-7">
			<h2 class="margin-bottom-10">{{$style->year}} {{$style->make_name}} {{$style->model_name}} {{$style->name}}</h2>
			<div class="row margin-bottom-10">
				<div class="margin-top-10 hidden-xs clearfix"></div>
				<div class="col-sm-12">

					<div class="pull-left margin-right-20" style="{{($reviews['stats']['total']==0)?'display:none':''}}">
						<div class="progress-popular avg" style="" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
					        <meta itemprop="worstRating" content = "0">
					        <meta itemprop="ratingValue" content = "{{$style->rating}}">
					        <meta itemprop="bestRating" content = "5">
					        <meta itemprop="reviewCount" content = "{{$style->rating_count}}">
					        <div class="bar-popular-container" style="width: {{$style->rating / 5 * 100}}%;">
					            <div class="bar-popular stars" style="width: 150px;"></div>
					        </div>
					    </div>
					</div>
			        <p><a style="" class="pull-left margin-top-10 btn-open-review">Write a Cars &amp; Truck Review</a></p>
			    </div>
			    <div class="margin-bottom-10 hidden-xs clearfix"></div>
			</div>
			<div class="clearfix"></div>
			<h2>Features, Specs &amp; {{$style->make_name}} {{$style->model_name}} Pricing</h2>
			<div class="row margin-bottom-10">
				<div class="col-sm-6">
					<p>Engine: <strong>{{($style->engine_name)?$style->engine_name:'N/A'}}</strong></p>
					<p>Body Type: <strong>{{$style->primary_body_type}}</strong></p>
					<p>Transmission: <strong>{{($style->transmission)?ucwords(strtolower($style->transmission)):'N/A'}}</strong></p>
				</div>
				<div class="col-sm-6">
					<p>Fuel Economy: <strong>{{($style->city_epa != 0)?$style->city_epa.'/'.$style->highway_epa.' mpg':'N/A'}}</strong></p>
					<p>Base MSRP: <strong>{{($style->price != 0)?'$'.number_format($style->price,2):'N/A'}}</strong></p>
				</div>
			</div>
            <div>
				<button id="btnFavorite" class="btn btn-grey btn-block {{$favorited ? 'hidden' : ''}}" data-style_id="{{$style->id}}" data-loading-text="Loading...">
					ADD TO FAVORITES
				</button>
            </div>
            <div>
                <button id="btnUnFavorite" class="btn btn-grey btn-block {{$favorited ? '' : 'hidden'}}" data-style_id="{{$style->id}}" data-loading-text="Loading...">
                    REMOVE FAVORITE
                </button>
            </div>
            @if($new_year_start <= $year && $new_year_end >= $year)
			<button class="btn btn-burgundy btn-block btn-lg btn-new-car-quote margin-top-10" data-vehicle_id="{{$style->id}}"><strong>GET A QUOTE</strong> <em class="hidden-xs hidden-sm"><small>for the {{$style->make_name}} {{$style->model_name}}</small></em></button>
            @endif
		</div>
	</div>
</div>
<div class="content-bg margin-top-20 incentives">
	<h2><span class="glyphicon glyphicon-ok"></span> Incentives</h2>
    <div id="divIncentives">
    	
    </div>
    <div class="clearfix"></div>
    <div>
        <ul class="pager">
            <li class="previous"><a class="btn-link">Prev</a></li>
            <li class="next"><a class="btn-link">Next</a></li>
        </ul>
    </div>
</div>

@if ($model->about)
<div class="content-bg margin-top-20">
	<h2>About the {{$style->year}} {{$style->make_name}} {{$style->model_name}} {{$style->name}}</h2>
	<hr>
	{{$model->about}}
</div>
@endif

<div class="content-bg margin-top-20">
	<div class="row">
		<div class="col-sm-6">
			<h2>What Others Are Saying About the {{$style->make_name}} {{$style->model_name}}</h2>
			<p>Customer {{$style->make_name}} Reviews</p>
		</div>
		<div class="col-sm-6">
			<button type="button" class="btn btn-black btn-lg btn-open-review pull-right">
				<span class="glyphicon glyphicon-pencil"></span>
				<span>Write a Review</span>
			</button>
		</div>
	</div>
</div>
<?php foreach($reviews['objects'] as $review) { ?>
		  		<div class="content-bg margin-top-20 review" data-review_id="{{$review->id}}">
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
<div class="clearfix"></div>
<div class="no-reviews" style="{{$reviews['stats']['returned'] >0 ? 'display: none;' : ''}}">
<div class="content-bg margin-top-20">
	<p class="open-review-area" style="">Be the first to tell your fellow Savers about this vehicle! Write a review <a type="button" class="btn-open-review">here</a>!</p>
</div>
</div>
<div class="clearfix"></div>

<div class="modal fade offer incentive" id="IncentiveModal" tabindex="-1" role="dialog" aria-labelledby="couponModalLabel" aria-hidden="true" >
  <div class="modal-dialog">
    <div class="modal-content">
      	<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <span class="h1 modal-title fancy"id="IncentiveModalLabel">New Vehicle Incentive</span>
        <div id="expired-alert" class="alert alert-danger margin-top-20 hidden">
          <p><strong>Fiddle Sticks! This coupon is expired...</strong><br>Don't worry though, we have many more amazing offers.</p>
          <br>
          <a href="{{URL::abs('/')}}/coupons/all" class="btn btn-black">See More Offers</a>
        </div>
      	</div>
      	<div class="modal-body">
        	<div class="offer-info">
	            <div class="row" id="">
	              	<div class="col-xs-12 col-sm-6"><img class="logo img-responsive coupon-path" src="{{(count($assets) != 0)?$assets[0]->path:''}}" alt="Save On" class="img-responsive" id="">
	              	</div>
	                <div class="col-xs-12 col-sm-6 border-left" id="">
	                  	<span class="h2 location-title">{{$style->year}} {{$style->make_name}} {{$style->model_name}}</span>
	                  	<span class="h1 coupon-title">Buy One Lunch, Get 2nd Free</span>
	                  	<p class="coupon-description">Max $5 discount. Must purchase 2 beverages. Dine-in only. Valid 11 am - 3 pm. Excludes holidays. Not valid with any other offers.</p>
	                  	<p><em><strong>Expires:</strong> <span class="coupon-expire">12-05-2013</span></em></p>
	                </div>
	            </div>
	            <div class="row default-disclaimer" id="">
	                <div class="col-xs-12" id="">
	                  <p class="small"><small><span class="restrictions"></span></small></p>
	                </div>
	            </div>
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
  		<span class="h1">{{$style->year}} {{$style->make_name}} {{$style->model_name}}</span>
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
@stop

