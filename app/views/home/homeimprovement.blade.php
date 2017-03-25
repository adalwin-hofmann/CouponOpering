@extends('master.templates.master')
@section('city-banner')
<div class="hidden-xs city-banner anniversary">
	<div class="city-banner-img">
		<div class="fade-left"></div>
		<div class="fade-right"></div>
		<h1 class="fancy">Home Improvement</h1>
		<h2 class="spaced">Quality workmanship at a fair price</h2>
		
	</div>
</div>
@stop
@section('page-title')
<h1>Home Improvement <br class="visible-xs"><small>in {{ucwords(strtolower($geoip->city_name))}}, {{$geoip->region_name}}</small></h1>
<button class="btn btn-green btn-sm search-nearby-modal" data-toggle="modal" data-target="#changeLocationModal"><span class="glyphicon glyphicon-map-marker"></span> <img alt="Edit Location" src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/edit-location-text.png"> <span class="glyphicon glyphicon-chevron-right"></span></button>
@stop
@section('sidebar')
<div class="content-bg margin-bottom-20 sohi-quote">
	<h1 class="fancy">Get a quote</h1>
	<hr>
	<p>Three easy steps to complete your home improvement project</p>
	<form>
		<div class="input-group">
			<select class="form-control">
				<option>Choose a project type</option>
			</select>
			<div class="input-group-btn">
				<button class="btn btn-black">Go</button>
			</div>
		</div>
	</form>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" href="#collapseThree">Categories<span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span></a>
        <div class="clearfix"></div>
      </h4>
    </div>
    <div id="collapseThree" class="panel-collapse collapse in">
        <div class="panel-body explore-links inverse">
            <ul>
                <li><a href="#">All</a></li>
                <li><a href="#">_________</a></li>
                <li><a href="#">Home Remodeling</a></li>
                <li><a href="#">Windows &amp; Siding</a></li>
                <li><a href="#">Plumbers</a></li>
                <li><a href="#">_________</a></li>
            </ul>
        </div>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" href="#collapseThree">Got Questions?<span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span></a>
        <div class="clearfix"></div>
      </h4>
    </div>
    <div id="collapseThree" class="panel-collapse collapse in">
        <div class="panel-body explore-links">
            <div>
            	<div class="col-xs-4">
            		<p>Phone</p>
            	</div>
            	<div class="col-xs-8">
            		<p>248.362.9119</p>
            	</div>
            </div>
            <div>
            	<div class="col-xs-4">
            		<p>Fax</p>
            	</div>
            	<div class="col-xs-8">
            		<p>248.362.2177</p>
            	</div>
            </div>
            <div>
            	<div class="col-xs-12">
            		<a href="#">Send Feedback</a>
            	</div>
            </div>
        </div>
    </div>
</div>
@stop
@section('body')
<script>
    category_id = '2';
    type = 'coupon';
    page = 0;
</script>

<div class="visible-xs content-bg margin-bottom-20 sohi-quote">
	<h1 class="fancy">Get a quote</h1>
	<hr>
	<p>Three easy steps to complete your home improvement project</p>
	<form>
		<div class="input-group">
			<select class="form-control">
				<option>Choose a project type</option>
			</select>
			<div class="input-group-btn">
				<button class="btn btn-black">Go</button>
			</div>
		</div>
	</form>
</div>

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
    <div class="banner-offer">

    </div>

    <div class="clearfix"></div>

    <div id="container" class="js-masonry offer-results-grid">
        <p class="ajax-loader"><img src="/img/loader-transparent.gif"></p>
    </div>

    <div class="clearfix"></div>
    @if(!empty($category) && $type == 'coupon')
    <div class="category-footer"><p>{{$category->footer_heading}}</p></div>
    @endif
    <div class="">
        <button class="btn btn-block btn-lg btn-grey view-more center-block" data-loading-text="Loading...">View More</button>
    </div>

    <div>
        <ol class="breadcrumb">
            <li><a href="{{URL::abs('/')}}">Home</a></li>
            <li class="active">Home Improvement</li>
        </ol>
    </div>
    
<div class="content-bg default-no-results">
        <p class="spaced"><strong>Sorry, We Have No Home Improvement Offers In Your Area!</strong></p>
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