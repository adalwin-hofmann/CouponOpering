@extends('master.templates.master', array('width'=>$width, 'search_page'=>$search_page))
@section('page-title')
<h1>Search Results</h1>
@stop

@section('breadcrumbs')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}" itemprop="url"><span itemprop="title">Home</span></a>
    </li>
    <li class="active">Search Results</li>
@stop

@section('body')

<script type="text/ejs" id="template_subcategory">
<li><a class="subcategory-facet" data-value="">All</a></li>
<% for(var i=0;((i<{{(Feature::findByName('facet_limit')->value == 0)?'subcategories.length':Feature::findByName('facet_limit')->value}}) && (i<subcategories.length));i++) { %>
	<li><a class="subcategory-facet" data-value="<%= subcategories[i].value %>"><%= subcategories[i].value %> <span class="hidden">(<%= subcategories[i].count %>)</span></a></li>
<% } %>
</script>

<script type="text/ejs" id="template_city">
<li><a class="city-facet" data-value="">All</a></li>
<% for(var i=0;((i<{{(Feature::findByName('facet_limit')->value == 0)?'cities.length':Feature::findByName('facet_limit')->value}}) && (i<cities.length));i++) { %>
	<li><a class="city-facet" data-value="<%= cities[i].value %>"><%= cities[i].value %> <span class="hidden">(<%= cities[i].count %>)</span></a></li>
<% } %>
</script>

<div class="visible-xs">
	<div class="input-group searchbar margin-bottom-20">
	  	<input type="text" class="form-control inptSearch" placeholder="Search by Keyword">
	    <div class="input-group-btn">
	        <button class="btn btn-green search" type="button">Go</button>
	    </div>
	</div>
</div>

<script type="text/ejs" id="template_locations">
<% list(locations, function(location)
{ %>

	<div class="search-result">
		<div style="display:none" class="featured"></div>
		<div class="row">
			<a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= location.category_slug %>/<%= location.subcategory_slug %>/<%= location.merchant_slug+'/'+location.id %>" class="col-md-7">
				<div class="row">
					<div class="col-sm-9">
						<div class="row">
							<div class="col-xs-4">
								<img alt="<%= location.display_name == '' ? location.merchant_name : location.display_name %>" class="img-responsive" src="<%= location.logo %>">
							</div>
							<div class="col-xs-8">
								<h2><%= location.display_name == '' ? location.merchant_name : location.display_name %></h2>
							</div>

							<div class="clearfix visible-xs"></div>
							<div class="col-xs-12 col-sm-8">
							<% if(location.is_address_hidden == 1) { %>
									<address><%= location.custom_address_text %></address>
							<% } else { %>
								<address><%= location.address %> <%= location.address2 %>, <%= location.city %>, <%= location.state %> <%= location.zip %></address>
							<% } %>
							</div>
						</div>
					</div>
					<div class="col-xs-12 col-sm-3 distance margin-bottom-5">
						<div class="row">
							<div class="col-xs-6 col-md-12">
								<p><strong><%== Math.round( location.distance * 10) / 10 %> miles</strong></p>
							</div>
							<div class="col-xs-6 col-md-12">
								<button class="btn btn-xs btn-green btn-favorite-merchant <%= location.is_favorite ? 'disabled' : '' %>" data-location_id="<%= location.id %>" data-toggle="tooltip" data-placement="left" title="Add to Favorites"><span class="glyphicon glyphicon-heart"></span></button>
                				<button class="btn btn-xs btn-blue btn-has-offers" style="<%= location.offer_count == 0 ? 'display:none;' : 'display:inline-block;' %>" data-toggle="tooltip" data-placement="right" title="Offers Available!"><span class="glyphicon glyphicon-usd"></span></button>
							</div>
						</div>
					</div>
					<div class="clearfix visible-sm"></div>
					
				</div>
			</a>
            <div class="col-md-2">
                <a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= location.category_slug %>/<%= location.subcategory_slug %>/<%= location.merchant_slug+'/'+location.id %>" class="btn <%= location.offer_count == 0 ? 'btn-link' : 'btn-blue' %>"><%= location.offer_count == 0 ? 'View Merchant' : 'View Offers' %> <span class="glyphicon glyphicon-chevron-right"></span></a>
            </div>
			<div class="col-md-3">
				<div class="row">
					<div class="col-xs-6 col-md-12" style="display: <%== (location.rating_count == 0)?'none':'' %>">
						<div class="progress-popular" style="" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
				            <meta itemprop="worstRating" content = "0">
				            <meta itemprop="ratingValue" content = "<%= location.rating %>">
				            <meta itemprop="bestRating" content = "5">
				            <div class="bar-popular-container" style="width: <%= location.rating / 5 * 100 %>%;">
				                <div class="bar-popular stars" style="width: 150px;"></div>
				            </div>
				        </div>
			        </div>
			        <div class="<%== (location.rating_count == 0)?'col-xs-12':'col-xs-6' %> col-md-12 write_review_button" style="display:{{(Feature::findByName('write_review_button')->value == 0)?'none':''}}">
						<a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= location.category_slug %>/<%= location.subcategory_slug %>/<%= location.merchant_slug+'/'+location.id %>?writereview" class="btn btn-block btn-link"><span class="glyphicon glyphicon-pencil"></span> <%== (location.rating_count == 0)?'Write the First Review':'Write a Review' %></a>
			        </div>
		        </div>
			</div>
		</div>
		

	</div>
<% }); %>
</script>

<script type="text/ejs" id="template_locations_grid">
<% list(locations, function(location)
{ %>
	<div class="item search-result-grid <%= locations.is_certified == 1 ? 'save_certified_offer' : '' %>" itemscope itemtype="http://schema.org/Organization">
        <div class="top-pic">
            <a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= location.category_slug %>/<%= location.subcategory_slug %>/<%= location.merchant_slug+'/'+location.id %>">
                <div class="expired-banner"><span class="h3 spaced">Expired</span></div>
                <img alt="<%= location.display_name == '' ? location.merchant_name : location.display_name %>" class="img-responsive" src="<%= location.logo %>" itemprop="image">
            </a>
        </div>
        <a class="item-info" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= location.category_slug %>/<%= location.subcategory_slug %>/<%= location.merchant_slug+'/'+location.id %>" itemscope itemtype="http://schema.org/Product">
          <span class="hidden" itemprop="name"><%= location.display_name == '' ? location.merchant_name : location.display_name %></span>
          <div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
            <span class="h3" itemprop="name"><%= location.display_name == '' ? location.merchant_name : location.display_name %></span>
          </div>
        </a>
        <div class="margin-left-15 margin-right-15 margin-bottom-15">
        <% if(location.is_address_hidden == 1) { %>
			<address><%= location.custom_address_text %></address>
		<% } else { %>
        	<address><%= location.address %> <%= location.address2 %>, <%= location.city %>, <%= location.state %> <%= location.zip %></address>
		<% } %>
        </div>
        <div class="margin-15">
        	<a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= location.category_slug %>/<%= location.subcategory_slug %>/<%= location.merchant_slug+'/'+location.id %>" class="btn btn-block <%= location.offer_count == 0 ? 'btn-link' : 'btn-blue' %>"><%= location.offer_count == 0 ? 'View Merchant' : 'View Offers' %> <span class="glyphicon glyphicon-chevron-right"></span></a>
        </div>
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
    </div>
<% }); %>
</script>

<script type="text/ejs" id="template_locations_map">
<% list(locations, function(location)
{ %>
	<div class="search-result">
		
			<div class="row margin-bottom-5">
				<div class="col-xs-5 hidden">
				<a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= location.category_slug %>/<%= location.subcategory_slug %>/<%= location.merchant_slug+'/'+location.id %>">
					<img alt="<%= location.display_name == '' ? location.merchant_name : location.display_name %>" class="img-responsive" src="<%= location.logo %>">
				</a>
				</div>
				<div class="col-xs-12">
				<a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= location.category_slug %>/<%= location.subcategory_slug %>/<%= location.merchant_slug+'/'+location.id %>">
					<h2><%= location.display_name == '' ? location.merchant_name : location.display_name %></h2>
				</a>
				</div>
			</div>
			<% if(location.is_address_hidden == 1) { %>
				<address><%= location.custom_address_text %></address>
			<% } else { %>
				<address><%= location.address %> <%= location.address2 %>, <%= location.city %>, <%= location.state %> <%= location.zip %></address>
			<% } %>
			<div class="row">
				<div class="col-xs-4 col-sm-12 col-md-4 distance">
					<p><strong><%== Math.round( location.distance * 10) / 10 %> miles</strong></p>
				</div>
				<div class="col-xs-6 col-sm-12 col-md-6 margin-bottom-10">
	                <button class="btn btn-xs btn-green btn-favorite-merchant <%= location.is_favorite ? 'disabled' : '' %>" data-location_id="<%= location.id %>" data-toggle="tooltip" data-placement="top" title="Add to Favorites"><span class="glyphicon glyphicon-heart"></span></button>
	                <a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= location.category_slug %>/<%= location.subcategory_slug %>/<%= location.merchant_slug+'/'+location.id %>" class="btn btn-xs btn-blue btn-has-offers" style="<%= location.offer_count == 0 ? 'display:none;' : 'display:inline-block;' %>" data-toggle="tooltip" data-placement="right" title="Offers Available!"><span class="glyphicon glyphicon-usd"></span></a>
	            </div>
			</div>
			<a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= location.category_slug %>/<%= location.subcategory_slug %>/<%= location.merchant_slug+'/'+location.id %>" class="btn <%= location.offer_count == 0 ? 'btn-link' : 'btn-blue' %>"><%= location.offer_count == 0 ? 'View Merchant' : 'View Offers' %> <span class="glyphicon glyphicon-chevron-right"></span></a>
			<div class="row hidden">
				<div class="col-xs-6 col-md-12" style="display: <%== (location.rating_count == 0)?'none':'' %>">
					<div class="progress-popular" style="" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
			            <meta itemprop="worstRating" content = "0">
			            <meta itemprop="ratingValue" content = "<%= location.rating %>">
			            <meta itemprop="bestRating" content = "5">
			            <div class="bar-popular-container" style="width: <%= location.rating / 5 * 100 %>%;">
			                <div class="bar-popular stars" style="width: 150px;"></div>
			            </div>
			        </div>
		        </div>
		        <div class="col-xs-6 col-md-12 write_review_button" style="display:{{(Feature::findByName('write_review_button')->value == 0)?'none':''}}">
					<a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= location.category_slug %>/<%= location.subcategory_slug %>/<%= location.merchant_slug+'/'+location.id %>?writereview" class="btn btn-block btn-link">
						<span class="glyphicon glyphicon-pencil"></span> <%== (location.rating_count == 0)?'Write the First Review':'Write a Review' %>
					</a>
		        </div>
	        </div>
	</div>
<% }); %>
</script>

<script type="text/ejs" id="template_offers">
<% list(entities, function(entities)
{ %>

	<div class="search-result">
		<div class="row">
			<div class="col-xs-3 col-sm-5 col-md-4">
				<img alt="<%= entities.name %>" class="img-responsive" src="<%= entities.path %>">
			</div>
			<div class="col-xs-9 col-sm-7 col-md-8">
				<a href="#"><%= entities.name %></a>
				<p><%= entities.merchant_name %></p>
			</div>
		</div>
	</div>
<% }); %>
</script>

<script type="text/ejs" id="template_keyword_banner">
<a class="merchant-banner" data-merchant_id="<%= banner.merchant_id %>" data-banner_entity_id="<%= banner.banner_entity_id %>" href="#" data-nav="<%= banner.custom_url != '' ? banner.custom_url : '/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/'+banner.category_slug+'/'+banner.subcategory_slug+'/'+banner.merchant_slug+'/'+banner.location_id %>">
    <img alt="<%= banner.merchant_name %>" class="img-responsive center-block" src="<%= banner.path %>">
</a>
</script>
<div id="banner">

</div>

<div class="content-bg margin-bottom-20 merchant-search merchant-results-holder">
	<div class="pull-left">
		<p>Change your view:</p>
		<div class="view-change-search">
			<a type="button" class="btn btn-large spaced btn-green tab-toggle list-view" href="#listView" data-toggle="tab"><span class="glyphicon glyphicon-th-list"></span> List</a>
	        <a type="button" class="btn btn-large spaced btn-green-border tab-toggle grid-view" href="#gridView" data-toggle="tab"><span class="glyphicon glyphicon-th"></span> Grid</a>
	        <a type="button" class="btn btn-large spaced btn-green-border tab-toggle map-view" href="#mapView" data-toggle="tab"><span class="glyphicon glyphicon-globe"></span> Map</a>
		</div>
	</div>
	<div class="pull-right hidden-xs">
		<p>Filter: &nbsp;&nbsp;
            <!--<strong>List View</strong><br>-->
            <span id="searchHintDistance" class="search-hint spaced" style="display:none;"><strong>Want to sort by <a id="btnDistanceSort">Distance?</a></strong></span>
            <span id="searchHintRelevance" class="search-hint spaced" style="display:none;"><strong>Want to sort by <a id="btnRelevanceSort">Relevance?</a></strong></span>
        </p>
		<div class="filters">
			<div class="dropdown sorting">
				<button id="sort-dropdown" type="button" class="btn btn-large btn-blue" data-toggle="dropdown">Sort&nbsp;&nbsp;<small><span class="glyphicon glyphicon-chevron-down"></span></small></button>
				<ul class="dropdown-menu sort-dropdown" role="menu" aria-labelledby="sort-dropdown">
					<li><a data-value="distance">Sort by Nearest First</a></li>
	                <li><a data-value="relevance">Sort by Relevance</a></li>
					<li><a data-value="popular">Sort by Most Popular</a></li>
					<!--<li><a data-value="az">Sort A &ndash; Z</a></li>
					<li><a data-value="za">Sort Z &ndash; A</a></li>-->
				</ul>
			</div>
			<div class="dropdown active-filter">
				<button type="button" class="btn btn-large btn-green" data-toggle="dropdown">Offers&nbsp;&nbsp;<small><span class="glyphicon glyphicon-chevron-down"></span></small></button>
				<ul class="dropdown-menu" role="menu">
	                <li class="active"><a data-value="active">Show Merchants With Offers</a></li>
	                <li><a data-value="inactive">Show Merchants Without Offers</a></li>
	                <li><a data-value="all">Show All Merchants</a></li>
	            </ul>
	        </div>
	        <div class="dropdown subcategory-links">
				<button type="button" class="btn btn-large btn-green" data-toggle="dropdown">Categories&nbsp;&nbsp;<small><span class="glyphicon glyphicon-chevron-down"></span></small></button>
				<ul class="dropdown-menu" role="menu">
				</ul>
			</div>
			<div class="dropdown city-links">
				<button type="button" class="btn btn-large btn-green" data-toggle="dropdown">Cities&nbsp;&nbsp;<small><span class="glyphicon glyphicon-chevron-down"></span></small></button>
				<ul class="dropdown-menu" role="menu">
				</ul>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="panel panel-default mobile-filter margin-top-15 visible-xs">
    	<div class="panel-heading">
			<span class="h4 hblock panel-title">
				<a data-toggle="collapse" class="collapsed" href="#searchFilter">Filter<span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span></a>
			<div class="clearfix"></div>
			</span>
    	</div>
    	<div id="searchFilter" class="panel-collapse collapse">
			<div class="dropdown sorting">
				<button id="sort-dropdown" type="button" class="btn btn-large btn-block btn-blue" data-toggle="dropdown">Sort&nbsp;&nbsp;<small><span class="glyphicon glyphicon-chevron-down"></span></small></button>
				<ul class="dropdown-menu sort-dropdown" role="menu" aria-labelledby="sort-dropdown">
					<li><a data-value="distance">Sort by Nearest First</a></li>
	                <li><a data-value="relevance">Sort by Relevance</a></li>
					<li><a data-value="popular">Sort by Most Popular</a></li>
					<!--<li><a data-value="az">Sort A &ndash; Z</a></li>
					<li><a data-value="za">Sort Z &ndash; A</a></li>-->
				</ul>
			</div>
			<div class="dropdown active-filter">
				<button type="button" class="btn btn-large btn-block btn-green" data-toggle="dropdown">Offers&nbsp;&nbsp;<small><span class="glyphicon glyphicon-chevron-down"></span></small></button>
				<ul class="dropdown-menu" role="menu">
	                <li class="active"><a data-value="active">Show Merchants With Offers</a></li>
	                <li><a data-value="inactive">Show Merchants Without Offers</a></li>
	                <li><a data-value="all">Show All Merchants</a></li>
	            </ul>
	        </div>
	        <div class="dropdown subcategory-links">
				<button type="button" class="btn btn-large btn-block btn-green" data-toggle="dropdown">Categories&nbsp;&nbsp;<small><span class="glyphicon glyphicon-chevron-down"></span></small></button>
				<ul class="dropdown-menu" role="menu">
				</ul>
			</div>
			<div class="dropdown city-links">
				<button type="button" class="btn btn-large btn-block btn-green" data-toggle="dropdown">Cities&nbsp;&nbsp;<small><span class="glyphicon glyphicon-chevron-down"></span></small></button>
				<ul class="dropdown-menu" role="menu">
				</ul>
			</div>
    	</div>
    </div>
</div>

<div class="tab-content merchant-results-holder">
	<div id="listView" class="tab-pane content-bg active">
		<div class="merchant-result">
			<p class="ajax-loader"><img alt="Loading..." src="/img/loader-transparent.gif" alt=""></p>
		</div>

		<div class="row margin-top-20 button-row">
			<div class="col-sm-6">
				<button class="btn btn-block btn-lg btn-grey view-more center-block" data-loading-text="Loading...">View More</button>
			</div>
			<div class="clearfix visible-xs"></div>
			<div class="col-sm-6">
				<button class="btn btn-block btn-lg btn-black suggest-merchant center-block">Suggest a Merchant</button>
			</div>
		</div>
	</div>
	<div id="gridView" class="tab-pane">
		<div id="container" class="js-masonry merchant-result-grid">
			<p class="ajax-loader"><img alt="Loading..." src="/img/loader-transparent.gif" alt=""></p>
		</div>
		<div class="row margin-top-20 button-row">
			<div class="col-sm-6">
				<button class="btn btn-block btn-lg btn-grey view-more center-block" data-loading-text="Loading...">View More</button>
			</div>
			<div class="clearfix visible-xs"></div>
			<div class="col-sm-6">
				<button class="btn btn-block btn-lg btn-black suggest-merchant center-block">Suggest a Merchant</button>
			</div>
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
					<div class="merchant-result-map-results">
						<p class="ajax-loader"><img alt="Loading..." src="/img/loader-transparent.gif" alt=""></p>
					</div>
					
				</div>
			</div>
		</div>

		<div class="clearfix"></div>

		<div class="button-row row">
			<div class="col-sm-6 col-sm-push-6">
				<button class="btn btn-lg btn-block btn-grey view-more center-block" data-loading-text="Loading...">View More</button>
			</div>
			<div class="col-sm-6 col-sm-pull-6">
				<button class="btn btn-lg btn-block btn-black suggest-merchant center-block">Suggest a Merchant</button>
			</div>
		</div>

	</div>
</div>

	<div class="no-merchant-results {{($search_version == 2)?'content-bg':''}}">
		
        <div id="suggest-merchant">
        	<p class="spaced"><strong>Sorry, We Couldn't Find Any Matches!</strong></p>
			<p>Please try searching again with a different key word or name.<br>
			Can't find the Merchant you're looking for? Please complete the form below.</p>
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
	</div>
	
</div>

<script type="text/javascript">
	query = "{{$query}}";
	searchType = "{{$searchType}}";
    page = 0;
    sorting_type = "{{Input::get('s', 'distance')}}";
    active_filter = "{{Input::get('f', 'active')}}"
    search_version = {{$search_version}};
</script>

@stop