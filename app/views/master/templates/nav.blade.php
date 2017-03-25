<script type="text/ejs" id="template_search_location">
	<% list(searchLocations.data, function(searchLocation)
	{ %>
		<li class="other-city" data-latitude="<%= searchLocation.latitude %>" data-longitude="<%= searchLocation.longitude %>"  data-city="<%= searchLocation.city %>"  data-state="<%= searchLocation.state %>"><a><%= searchLocation.city.toLowerCase() %>, <%= searchLocation.state %></a></li>
	<% }); %>
</script>

<script type="text/ejs" id="template_search_suggestion">
    <% list(searchSuggestions.data, function(searchSuggestion)
    { %>
        <li class="suggestion" data-suggestion="<%= searchSuggestion.search %>"><a><%= searchSuggestion.search %></a></li>
    <% }); %>
</script>

<script type="text/ejs" id="template_nearby_dropdown">
	<% list(nearbyDropdown.data, function(nearbyDropdown)
	{ %>
		<li class="other-city" data-latitude="<%= nearbyDropdown.latitude %>" data-longitude="<%= nearbyDropdown.longitude %>"  data-city="<%= nearbyDropdown.city %>"  data-state="<%= nearbyDropdown.state %>"><a><%= nearbyDropdown.city.toLowerCase() %>, <%= nearbyDropdown.state %></a></li>
	<% }); %>
</script>

<?php
	if (Auth::check())
	{
		$username = explode(' ', Auth::user()->name);
		if (strlen($username[0]) <= 1)
		{
			$username = $username[0].(isset($username[1]))?" ".$username[1]:"";
		} else {
			$username = $username[0];
		}
	}
	$url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
	if (false !== strpos($url,'/cars'))
	{
	    $altLogo = '/img/logo-soct.png';
	}
	if (false !== strpos($url,'/homeimprovement'))
	{
	    $altLogo = '/img/logo-sohi.png';
	}
?>


<div class="browser-warning text-center">
	SaveOn<sup>&reg;</sup> works best on modern browsers. We recommomend <a href="https://www.mozilla.org/en-US/firefox/new/" target="_blank">Mozilla Firefox</a> or <a href="http://www.google.com/chrome/" target="_blank">Google Chrome</a>.
</div>
<header class="header">
	<div class="row row1">
		<div class="col-md-5 col-sm-4 user-menu user-searchbar">
			@if(Auth::check())
			<div>
				<ul class="list-inline" id="tour-signout">
					<li><div class="btn-group">
							<a class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span> {{$username}} <span class="caret"></span></a>
						<ul class="dropdown-menu text-left">
							<li><a href="{{URL::abs('/')}}/members/dashboard">Dashboard</a></li>
							<li><a href="{{URL::abs('/')}}/members/mycoupons">My Saved Coupons</a></li>
							<!--<li><a href="{{URL::abs('/')}}/members/mysavetodays">My Save Todays</a></li>-->
							<li><a href="{{URL::abs('/')}}/members/mycontestentries">My Contest Entries</a></li>
							<li><a href="{{URL::abs('/')}}/members/myfavoritemerchants">My Favorite Merchants</a></li>
                            <li><a href="{{URL::abs('/')}}/members/mycars">My Cars</a></li>
							<li><a href="{{URL::abs('/')}}/members/mysettings">My Account Settings</a></li>
							<!--<li><a href="{{URL::abs('/')}}/members/myinterests">My Interests</a></li>
							<li><a href="{{URL::abs('/')}}/members/mylocations">My Favorite Locations</a></li>
							<li><a href="{{URL::abs('/')}}/members/mynotifications">My Notifications</a></li>-->
						</ul>
						</div> <button type="button" data-trigger="focus" data-content="Quickly access all of your stuff by clicking your name or the arrow." data-placement="bottom" class="popover-info btn-link"><span class="glyphicon glyphicon-question-sign"></span></button></li>
				  	<li>|</li>
				  	<li><a href="{{URL::abs('/')}}/logout" type="button">Sign Out</a></li>
				</ul>
			</div>
			
			@else
			<div>
				<ul id="tour-signin" class="list-inline">
					<li class="sign-in"><a href="#" class="" data-toggle="modal" data-target="#signInModal">Sign In</a></li>
					<li>|</li>
					<li class="sign-up"><a href="#" class="tour-signup" data-toggle="modal" data-target="#signUpModal">Sign Up</a></li>
				</ul>
			</div>
			@endif
			<div id="tour-search"  class="input-group searchbar">
		      	<input type="text" class="form-control inptSearch" placeholder="Search for Coupons &amp; Merchants">
			    <div class="input-group-btn">
			        <button class="btn btn-green search" type="button">Go</button>
			    </div>
		    </div>
            <div class="search-suggestions">
            </div>
		</div>
		<div class="col-md-2 col-sm-4 logo">
			<a id="logo" href="{{URL::abs('/')}}"><img src="{{(isset($altLogo))?$altLogo:'/img/logo.png'}}" alt="SaveOn &reg;" class="img-responsive center-block" width="178" height="59"></a>
		</div>
		<div class="col-md-5 col-sm-4 location">
			<div class="dropdown">
				<span>Set Location</span><br>
			  	<button class="btn dropdown-toggle btn-green" type="button" id="locationMenu" data-toggle="dropdown">
			    	<span class="glyphicon glyphicon-map-marker"></span> <span class="current-location-city">{{ucwords(strtolower($geoip->city_name))}}</span>, <span class="current-location-state">{{$geoip->region_name}}</span> <span class="glyphicon glyphicon-chevron-down pull-right"></span>
			  	</button>
				<ul class="dropdown-menu pull-right" role="menu" aria-labelledby="locationMenu">
					<li>
						<form class="navbar-form" role="search">
							<div class="form-group">
								<!--<input type="text" class="form-control" style="display:none">-->
	                        	<input type="text" class="form-control navlocation" placeholder="Search for a new city or zipcode..." title="" data-toggle="tooltip" data-original-title="Search for a City or Zipcode" autocomplete="off">
	                      	</div>
                      	</form>
                  	</li>
                  	<div class="search-locations">
				    </div>
				    <div class="static-locations">
                    <li class="current-city"><a>{{ucwords(strtolower($geoip->city_name))}}, {{$geoip->region_name}} @if(Auth::check())<span href="" class="glyphicon glyphicon-heart favorite pull-right"></span>@endif</a></li>
				    <li class="divider"></li>
				    @if(Auth::check())
				    <li class="dropdown-header">Favorite Locations</li>
                    <div class="saved-location-area">
                        <li><a><img alt="Loading..." src="/img/loader-transparent.gif" alt=""></a></li>
                    </div>
				    <li><a href="{{URL::abs('/')}}/members/mylocations" class="btn btn-default">View All</a></li>
				    <li class="divider"></li>
				    <li class="dropdown-header">Want to find locations nearby?</li>
				    <li><a id="btnChangeLocation" data-toggle="modal" data-target="#changeLocationModal">See our suggested locations &gt;</a></li>
				    @else
				    <li class="dropdown-header">Suggested Locations</li>
				    <div class="nearbyLocations">
					    <li><a><img alt="Loading..." src="/img/loader-transparent.gif" alt=""></a></li>
					</div>
				    <li><a id="btnChangeLocation" data-toggle="modal" data-target="#changeLocationModal"><strong>View More &gt;</strong></a></li>
				    <li class="divider"></li>
				    <li class="dropdown-header">Favorite Locations</li>
				    <li class="dropdown-disclaimer">You can easily save your favorite locations by becoming a member. <a class="inline" href="#" data-toggle="modal" data-target="#signUpBenefitsModal"><strong>Find out more.</strong></a></li>
				    @endif
					</div>
				</ul>
                <form id="formChangeLocation" action="/change-location" method="POST">
                    <input id="locLat" type="hidden" name="latitude">
                    <input id="locLng" type="hidden" name="longitude">
                    <input id="locCity" type="hidden" name="city">
                    <input id="locState" type="hidden" name="state">
                    <input id="locUrl" type="hidden" name="url">
                </form>
			</div>
		</div>
	</div>

    <script type="text/ejs" id="template_saved_location">
    <% list(locations, function(location)
    { %>
        <li class="saved-city">
            <a class="change-city" data-latitude="<%= location.latitude %>" data-longitude="<%= location.longitude %>" data-city="<%= location.city %>" data-state="<%= location.state %>"><%= location.city.toLowerCase()+', '+location.state %></a>
            <a class="pull-right remove-saved-city" data-location_id="<%= location.id %>"><span href="" class="glyphicon glyphicon-heart favorite"></span><span class="glyphicon glyphicon-remove"></span></a>
        </li>
    <% }); %>
    </script>

	<div class="clearfix"></div>
	<nav id="cbp-hrmenu" class="cbp-hrmenu hidden-xs">
		<ul>
			<li class= "inner">
				<a class="drop-link" href="#">Coupons <span class="caret"></span></a>
				<div class="cbp-hrsub">
					<div class="cbp-hrsub-inner"> 
						<div class="col-xs-3 coupon-categories">
							<p class="category-title">Categories</p>
							<ul>
                                @include('master.templates.explore', array('active' => 'all', 'type' => 'coupon'))
							</ul>
						</div>
						<div class="col-xs-3 coupon-text">
							<span class="h2 fancy">Print, save, and&nbsp;share.</span>
							<p>Save On everything you could ever need or want with our frequently updated coupons.</p>
							<p><a class="btn btn-green" href="{{URL::abs('/')}}/coupons/all">Explore All Coupons</a></p>
						</div>
						<div class="col-xs-3 featured-coupon1">
						</div>
						<div class="col-xs-3 featured-coupon2">
						</div>
					</div>
				</div>
			</li>
			<!--<li>
				<a class="drop-link" href="#">Daily Deals <span class="caret"></span></a>
				<div class="cbp-hrsub">
					<div class="cbp-hrsub-inner"> 
						<div class="col-xs-6 dailydeal-text">
							<span class="h2 fancy">The hottest daily deals you'll ever find.</span>
							<p>Seriously, our daily deals are on fire! They show up quick, and leave even faster. Catch them while you can!</p>
							<p><a class="btn btn-blue" href="{{URL::abs('/')}}/dailydeals/all">Explore All Daily Deals</a></p>
						</div>
						<div class="col-xs-3 featured-daily-deal1"></div>
						<div class="col-xs-3 featured-daily-deal2"></div>
					</div>
				</div>
			</li>-->
			<li>
				<a href="{{URL::abs('/')}}/contests/all">Contests</a>
				<!--<a class="drop-link" href="#">Contests <span class="caret"></span></a>
				<div class="cbp-hrsub">
					<div class="cbp-hrsub-inner"> 
						<div class="col-xs-6 contest-text contest-categories">
							<span class="h2 fancy">Enter to win some great prizes.</span>
							<p>Whether you want free tickets to see some of the biggest bands in the music biz or just free food and gas, our contests will keep you coming back for more. </p>
							<p><a class="btn btn-red" href="{{URL::abs('/')}}/contests/all">Explore All Contests</a></p>
							<p><a href="{{URL::abs('/')}}/winnerscircle" class="">See All Contest Winners</a></p>
						</div>
						<div class="col-xs-3 featured-contest1"></div>
						<div class="col-xs-3 featured-contest2"></div>
					</div>
				</div>-->
			</li>
			<li>
            	<a href="http://www.saveoncarsandtrucks.com" target="new">Cars &amp; Trucks</a>
				<!--<a class="drop-link" href="#">Cars &amp; Trucks <span class="caret"></span></a>
				<div class="cbp-hrsub">
					<div class="cbp-hrsub-inner"> 
						<div class="col-xs-2 coupon-categories">
							<p class="category-title">Categories</p>
							<ul>
								<li><a href="{{URL::abs('/')}}/cars">All</a></li>
                                <li><a href="{{URL::abs('/')}}/cars/new/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}">New Cars</a></li>
                                @if(isset($full_detroit_only) && !$full_detroit_only)
                                <li><a href="{{URL::abs('/')}}/cars/used">Used Cars</a></li>
                                <li><a href="{{URL::abs('/')}}/cars/auto-services">Service & Lease Specials</a></li>
                                <li><a href="{{URL::abs('/')}}/cars/featured-dealers">Featured Dealers</a></li>
                                @endif
							</ul>
						</div>
						<div class="col-xs-4 soct-text">
							<span class="h2 fancy">Quotes, Incentives, and Deals</span>
							<p>Fill out quotes for new and used cars, find incentives for your next purchase, and get all of the deals you need at SaveOn Cars &amp; Trucks<sup>&reg;</sup>.</p>
                            <p><a class="btn btn-burgundy" href="{{URL::abs('/')}}/cars">Find Your Ride</a></p>
						</div>
						<div class="col-xs-3 featured-newcar0"></div>
						<div class="col-xs-3 featured-newcar1"></div>
					</div>
				</div> -->
			</li>
			<li>
				<a href="{{URL::abs('/homeimprovement')}}">Home Improvement</a>
			</li>
			<li>
				<a href="{{URL::abs('/')}}/groceries">Groceries</a>
			</li>
			<li>
				<a href="{{(Auth::check())?URL::abs('/').'/members/mystuff':URL::abs('/').'/member-benefits'}}">Members</a>
			</li>
		</ul>
	</nav>

</header>
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<div class="navbar-header">
	    <button type="button" class="navbar-toggle menu-toggle main-menu-toggle">
	      	<span class="menu-button-text">Menu </span>
	      	<span class="menu-button-icon pull-right">
		      	<span class="sr-only">Toggle navigation</span>
		      	<span class="icon-bar"></span>
		      	<span class="icon-bar"></span>
		      	<span class="icon-bar"></span>
	      	</span>
	    </button>

	    <ul class="list-inline navbar-right user">
	    	@if(Auth::check())
			<!--<li><a class="dropdown-toggle user-sidebar-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-user"></span></a></li>-->
			@else
			<li class="sign-in-button"><a href="#" class="" data-toggle="modal" data-target="#signInModal">Sign In</a></li>
			<li class="sign-up-button"><a href="#" class="tablet-signup" data-toggle="modal" data-target="#signUpModal">Sign Up</a></li>
			@endif
	        <!--<li class="dropdown">
		  	<a href="#" class="dropdown-toggle location-sidebar-toggle" type="button" id="locationMenu" data-toggle="dropdown">
		    	<span class="glyphicon glyphicon-map-marker"></span>
		  	</a>
			</li>-->
			<li><a href="#" class="mobile-search-button"><span class="glyphicon glyphicon-search"></span></a></li>
	    </ul>
	    <a class="navbar-brand" href="{{URL::abs('/')}}"><img src="{{(isset($altLogo))?$altLogo:'/img/logo.png'}}" alt="Save On" class="img-responsive" width="84" height="30"></a>
	</div>

  	<div class="collapse navbar-collapse hidden-xs" id="bs-example-navbar-collapse-1">
	    <ul class="nav navbar-nav">
	      	<li><a href="{{URL::abs('/')}}/category/all/coupon">Coupons</a></li>
	      	<!--<li><a href="{{URL::abs('/')}}/category/all/dailydeal">Daily Deals</a></li>-->
	      	<li><a href="{{URL::abs('/')}}/category/all/contest">Contests</a></li>
	      	<li><a href="{{URL::abs('/cars')}}">Cars &amp; Trucks</a></li>
	      	<li><a href="{{URL::abs('/homeimprovement')}}">Home Improvement</a></li>
	      	<li><a href="{{URL::abs('/')}}/groceries">Groceries</a></li>
	    </ul>
	    
	</div>
</nav>
@if(!Auth::check())
<nav class="navbar navbar-inverse sign-in-up-bar" role="navigation">
	<div class="navbar-header">
		<ul class="list-inline user center-block text-center">
			<li class="sign-in-button"><a href="#" class="" data-toggle="modal" data-target="#signInModal">Sign In</a></li>
			<li class="sign-up-button"><a href="#" class="mobile-signup" data-toggle="modal" data-target="#signUpModal">Sign Up</a></li>
		</ul>
	</div>
</nav>
@endif
<!-- Search Bar -->
<nav class="navbar navbar-inverse navbar-fixed-top searchbar-mobile-top" role="navigation">
	<div class="navbar-header">
		<div class="input-group searchbar">
	      	<input type="text" class="form-control inptSearch" placeholder="Search by Keyword">
		    <div class="input-group-btn">
		        <button class="btn btn-green search" type="button">Go</button>
		    </div>
	    </div>
        <div class="search-suggestions">
        </div>
	</div>
</nav>
<!-- End Search Bar -->

@yield('city-banner')

<script type='text/ejs' id='template_subheader_dropdown'> 
<% list(subheaderDrop, function(subheaderDrop)
{ %>
	<li><a href="{{URL::abs('/')}}<%== (subheaderDrop.slug == 'groceries')?'/groceries':'/category/'+subheaderDrop.slug %>"><%= subheaderDrop.name %> <%== (subheaderDrop.count > 0)?'('+subheaderDrop.count+')':'' %></a></li>
<% }); %>
</script>

<div class="subheader">
	<div class="row">
		<div class="col-md-5 col-md-push-7">
			<ol class="breadcrumb">
				@yield('breadcrumbs')
			</ol>
		</div>
		<div class="clearfix visible-xs visible-sm"></div>
		<div class="col-md-7 col-md-pull-5 page-title">
			@yield('page-title')
		</div>
	</div>

	<div class="clearfix"></div>
	<div class="row">
		@if(isset($search_page))
		<div class="col-sm-8">
			<p>Merchant Search Results For &quot;<span class="query">{{$query}}</span>&quot;</p>
		</div>
		@else
		<div class="col-sm-8 hidden-xs subheader-content">
			@yield('subheader-content', (isset($seoContent['Sub-Header']) && $seoContent['Sub-Header'] != '') ? '<p>'.SoeHelper::cityStateReplace($seoContent['Sub-Header'], $geoip).'</p>' : (isset($subheader) ? '<p>'.$subheader.'</p>' : ''))
		</div>
		@endif
		
	</div>
	<div class="clearfix"></div>
</div>
