@extends('master.templates.master', array('width'=>'full'))

@section('breadcrumbs')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}" itemprop="url"><span itemprop="title">Home</span></a>
    </li>
    <li class="active">Sitemap</li>
@stop

@section('body')

<div class = "content-bg">
<h1 class="fancy">SaveOn.com<sup>&reg;</sup> Sitemap</h1>
<br>
<table class="table">
	<tr>
		<th><h2>Home</h2></th>
		<th><h2>Merchants</h2></th>
		<th><h2>My Stuff</h2></th>
		<th><h2>Who We Are</h2></th>
	</tr>
	<tr>
		<td><a href = "{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/all">Coupons</a></td>
		<td><a href = "{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/all">All Merchants</a></td>
		<td><a href = "{{URL::abs('/')}}/members/dashboard">Dashboard</a></td>
		<td><a href = "{{URL::abs('/')}}/faqs">FAQs</a></td>
	</tr>
	<tr>
		<!--<td><a href = "{{URL::abs('/')}}/dailydeals/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/all">Daily Deals</a></td>-->
		<td><a href = "{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/food-dining">Food &amp; Dining</a></td>
		<td><a href = "{{URL::abs('/')}}/members/mycoupons">Clipped Coupons</a></td>
		<td><a href = "{{URL::abs('/')}}/heritage">Our Heritage</a></td>
	</tr>
	<tr>
		<td><a href = "{{URL::abs('/')}}/contests/all">Contests</a></td>
		<td><a href = "{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/home-improvement">Home Improvement</a></td>
		<!--<td><a href = "{{URL::abs('/')}}/members/mysavetodays">Save Todays</a></td>-->
		<td><a href = "{{URL::abs('/')}}/terms">Terms &amp; Conditions</a></td>
	</tr>
	<tr>
		<td><a href = "{{URL::abs('/')}}/cars">Cars &amp; Trucks</a></td>
		<td><a href = "{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/health-beauty">Health &amp; Beauty</a></td>
		<td><a href = "{{URL::abs('/')}}/members/mycontestentries">Contest Entries</a></td>
		<td><a href = "{{URL::abs('/')}}/privacy">Privacy</a></td>
	</tr>
	<tr>
		<td><a href = "{{$sohi ? URL::abs('/homeimprovement') : 'http://saveonhomeimprovement.com'}}">Home Improvement</a></td>
		<td><a href = "{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/auto-transportation">Automotive</a></td>
		<td><a href = "{{URL::abs('/')}}/members/myfavoritemerchants">Favorite Merchants</a></td>
		<td><a href="{{URL::abs('/')}}/whyadvertise">Advertising</a></td>
	</tr>
	<tr>
		<td><a href="{{URL::abs('/groceries')}}">Groceries</a></td>
		<td><a href = "{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/travel-entertainment">Travel &amp; Fun</a></td>
		<td><a href = "{{URL::abs('/')}}/members/mysettings">Account Settings</a></td>
		<td><a href="{{URL::abs('/')}}/careers">Careers</a></td>
	</tr>
	<tr>
		<td><a href="{{URL::abs('/')}}/cities">Cities</a></td>
		<td><a href = "{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/retail-fashion">Retail &amp; Fashion</a></td>
		<td><a href = "{{URL::abs('/')}}/members/interests">Interests</a></td>
		<td><a href="{{URL::abs('/')}}/headquarters">Headquarters</a></td>
	</tr>
	<tr>
		<td></td>
		<td><a href = "{{URL::abs('/groceries')}}">Groceries</a></td>
		<td><a href = "{{URL::abs('/')}}/members/mylocations">My Favorite Locations</a></td>
		<td><a href="{{URL::abs('/')}}/contact">Contact</a></td>
	</tr>
	<tr>
		<td></td>
		<td><a href = "{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/special-services">Everything Else</a></td>
		<td><a href = "{{URL::abs('/')}}/members/notifications">Notifications</a></td>
		<td><a href="{{URL::abs('/')}}/news">News &amp; Views</a></td>
	</tr>
    <tr>
        <td></td>
        <td><a href = "{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/community">Community</a></td>
        <td></td>
        <td></td>
    </tr>
	<tr>
		<td></td>
		<td><a href = "{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/all">Popular Merchants</a></td>
		<td></td>
		<td></td>
	</tr>

</table>
</div>
@stop