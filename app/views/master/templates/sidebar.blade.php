<div class="visible-xs-block margin-bottom-15">

	<div id="navCurrentCityMobile" class="sidebar-title h4 hblock">
		<span class="glyphicon glyphicon-map-marker"></span> <span class="current-location-city">{{ucwords(strtolower($geoip->city_name))}}</span>, <span class="current-location-state">{{$geoip->region_name}}</span>
	</div>
	<div id="locationMenu" class="btn btn-green btn-block btn-left margin-bottom-10 menu-toggle location-sidebar-toggle">Set Location <span class="glyphicon glyphicon-chevron-right pull-right"></span></div>
	@if(Auth::check())
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
	?>
	<div class="btn btn-darkgrey btn-block btn-left margin-bottom-10 menu-toggle user-sidebar-toggle"><span class="glyphicon glyphicon-user"></span> {{$username}} <span class="glyphicon glyphicon-chevron-right pull-right"></span></div>
	@endif
	<a class="btn btn-darkgrey btn-block btn-left" href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/all">Coupons <span class="glyphicon glyphicon-chevron-right pull-right"></span></a>
	<!--<a class="btn btn-darkgrey btn-block btn-left" href="{{URL::abs('/')}}/dailydeals/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/all">Daily Deals <span class="glyphicon glyphicon-chevron-right pull-right"></span></a>-->
	<a class="btn btn-darkgrey btn-block btn-left" href="{{URL::abs('/')}}/contests/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/all">Contests <span class="glyphicon glyphicon-chevron-right pull-right"></span></a>
	<a class="btn btn-darkgrey btn-block btn-left" href="{{(Feature::findByName('soct_redirect')->value == 0)?'http://www.saveoncarsandtrucks.com':URL::abs('/cars')}}">Cars &amp; Trucks <span class="glyphicon glyphicon-chevron-right pull-right"></span></a>
	<a class="btn btn-darkgrey btn-block btn-left" href="{{(Feature::findByName('home_improvement')->value == 0)?'http://www.saveonhomeimprovement.com':URL::abs('/homeimprovement')}}">Home Improvement <span class="glyphicon glyphicon-chevron-right pull-right"></span></a>
	<!--<a class="btn btn-darkgrey btn-block btn-left" href="{{URL::abs('/')}}/groceries">Groceries <span class="glyphicon glyphicon-chevron-right pull-right"></span></a>-->
	<div class="panel panel-default hidden">
	    <div class="panel-heading">
	      <span class="h4 hblock panel-title">Menu</span>
	    </div>
	    <div class="panel-collapse collapse in">
		    <div class="panel-body explore-links">
		      	<ul>
					<li class="<?php echo (isset($navItem) && $navItem == 'coupons' ? 'active' : '') ?>"><a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/all">Coupons</a></li>
				    <!--<li class="<?php echo (isset($navItem) && $navItem == 'daily-deals' ? 'active' : '') ?>"><a href="{{URL::abs('/')}}/dailydeals/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/all">Daily Deals</a></li>-->
				    <li class="<?php echo (isset($navItem) && $navItem == 'contests' ? 'active' : '') ?>"><a href="{{URL::abs('/')}}/contests/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/all">Contests</a></li>
				    <li class="<?php echo (isset($navItem) && $navItem == 'cars-trucks' ? 'active' : '') ?>"><a href="{{(Feature::findByName('soct_redirect')->value == 0)?'http://www.saveoncarsandtrucks.com':URL::abs('/cars')}}">Cars &amp; Trucks</a></li>
				    <li class="<?php echo (isset($navItem) && $navItem == 'home-improvement' ? 'active' : '') ?>"><a href="{{(Feature::findByName('home_improvement')->value == 0)?'http://www.saveonhomeimprovement.com':URL::abs('/homeimprovement')}}">Home Improvement</a></li>
				    <!--<li class="<?php echo (isset($navItem) && $navItem == 'groceries' ? 'active' : '') ?>"><a href="{{URL::abs('/')}}/groceries">Groceries</a></li>-->
				</ul>
		    </div>
	    </div>
	</div>
</div>
