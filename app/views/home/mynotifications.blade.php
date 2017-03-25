@extends('master.templates.master')
@section('page-title')
<h1>My Notifications</h1>
@stop

@section('breadcrumbs')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}" itemprop="url"><span itemprop="title">Home</span></a>
    </li>
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}/mysettings" itemprop="url"><span itemprop="title">Account Settings</span></a>
    </li>
    <li class="active">My Notifications</li>
@stop

@section ('sidebar')

<div class="panel panel-default explore-sidebar">
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
	@include('master.templates.member-sidebar')
	@include('master.templates.sidebar-offers')


<div class="ad"><-- Insert Image Here --></div>
<div class="ad"><div class="" style="background-color:#CCCCCC; width: 100%; min-height: 100px"><p>Advertising</p></div></div>

@stop
@section('body')
<div class = "content-bg">

<?php
	if ((Feature::findByName('notification_password_reset')->value == 0) && (Feature::findByName('notification_contest_expiring')->value == 0) && (Feature::findByName('notification_save_today_expiring')->value == 0) && (Feature::findByName('notification_coupon_expiring')->value == 0) && (Feature::findByName('notification_unredeemed_coupons')->value == 0) && (Feature::findByName('notification_merchant_new_offers')->value == 0) && (Feature::findByName('notification_reccomended_offers')->value == 0))
	{
		$notifications = '0';
	} else {
		$notifications = '1';
	}
?>
	<div style="display:{{($notifications == 0)?'block':'none'}}">
		<span class="h4 hblock fancy">Coming Soon</span>
		<p>Notifications are not yet available. Check back often because this feature is coming soon! </p>
	</div>
    <div style="display:{{($notifications == 1)?'block':'none'}}">
    	<h2>Notify me when...</h2>
		<span class="h3">(Select the type of notifications you wish to receive by e-mail.)</span>
		<br>
		<br>

		<form action="/members/save-notifications" method="POST">
    		<ul>
        		<li style="display:{{(Feature::findByName('notification_password_reset')->value == 0)?'none':''}}"><p><input name="password_reset" type = "checkbox" {{Auth::User()->password_reset_notification ? 'checked': ''}}/>  My password is reset.</p></li>
        		<li style="display:{{(Feature::findByName('notification_contest_expiring')->value == 0)?'none':''}}"><p><input name="contest_end" type = "checkbox" {{Auth::User()->contest_end_notification ? 'checked': ''}}/>  A contest I've entered is about to expire.</p></li>
        		<li style="display:{{(Feature::findByName('notification_save_today_expiring')->value == 0)?'none':''}}"><p><input name="daily_deal_end" type = "checkbox" {{Auth::User()->daily_deal_end_notification ? 'checked': ''}}/>  A Daily Deal I've saved is about to expire.</p></li>
        		<li style="display:{{(Feature::findByName('notification_coupon_expiring')->value == 0)?'none':''}}"><p><input name="coupon_end" type = "checkbox" {{Auth::User()->coupon_end_notification ? 'checked': ''}}/>  A coupon I've saved is about to expire.</p></li>
        		<li style="display:{{(Feature::findByName('notification_unredeemed_coupons')->value == 0)?'none':''}}"><p><input name="unredeemed" type = "checkbox" {{Auth::User()->unredeemed_notification ? 'checked': ''}}/>  I have unredeemed coupons.</p></li>
        		<li style="display:{{(Feature::findByName('notification_merchant_new_offers')->value == 0)?'none':''}}"><p><input name="new_offers" type = "checkbox" {{Auth::User()->new_offers_notification ? 'checked': ''}}/>  A merchant I like has new offers.</p></li>
        		<li style="display:{{(Feature::findByName('notification_reccomended_offers')->value == 0)?'none':''}}"><p><input name="love_offers" type = "checkbox" {{Auth::User()->love_offers_notification ? 'checked': ''}}/>  SaveOn<sup>&reg;</sup> has offers I'll love.</p></li>
    		</ul>
    		<br>
    		<p><a id="check-all" class="btn btn-link">Check All </a><a class="btn btn-link">|</a><a id="uncheck-all" class="btn btn-link">  Uncheck All</a></p>
            <br>
            <span style="color:green;">{{Session::has('settings_saved') ? 'Preferences saved.' : ''}}</span>
    		<br>
    		<button type="submit" class="btn btn-black">Save Changes</button>
        </form>
	</div>
@stop