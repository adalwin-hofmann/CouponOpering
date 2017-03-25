@extends('master.templates.master')
<?php
$geoip = json_decode(GeoIp::getGeoIp('json'));
?>
@section('page-title')
<h1>{{ucwords(strtolower($geoip->city_name))}} Coupons</h1>
@stop


@section('sidebar')
<div class="panel panel-default explore-sidebar">
	    <div class="panel-heading">
	      <span class="panel-title h4 hblock">
	        <a data-toggle="collapse" href="#collapseOne" class="">Explore Coupons <span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span></a>
	        <div class="clearfix"></div>
	      </span>
	    </div>
	    <div id="collapseOne" class="panel-collapse collapse in">
		    <div class="panel-body explore-links">
		      	<ul>
                    @include('master.templates.explore', array('active' => 'all', 'type' => 'coupon'))
				</ul>
		    </div>
	    </div>
	</div>
@if(isset($city_sidebar_desc))
<div class="panel panel-default">
    <div class="panel-heading">
      <span class="panel-title h4 hblock">
        <a data-toggle="collapse" href="#collapseTwo" class="">About {{ucwords(strtolower($geoip->city_name))}} Coupons <span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span></a>
        <div class="clearfix"></div>
      </span>
    </div>
    <div id="collapseTwo" class="panel-collapse collapse in" style="height: auto;">
	    <div class="panel-body explore-links">
		    <div class="category-header">
		    	{{$city_sidebar_desc}}
		    </div> 
	    </div>
    </div>
</div>
@endif
@stop

@section('city-banner')
<div class="city-banner anniversary">
	<div class="city-banner-img" style="background-image: url('{{$city_image->path}}')">
		<div class="fade-left"></div>
		<div class="fade-right"></div>
		<span class="fancy h1 hblock">{{ucwords(strtolower($city_image->display))}}</span>
		<span class="spaced h2 hblock">Featured Near You</span>
	</div>
</div>
@stop

@section('body')
<div class="content-bg">
	<div class="row">
		<div class="col-xs-12">
			<p class="spaced">Best Pizza in Detroit</p>
			<hr style="margin-top:0;" class="green">
		</div>
	</div>
	<div class="row">
		<div class="col-sm-4">
            <a>
                <img alt="contest name" class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/images/1374678538-logo_paparomanos.jpg">
	        	<p class="merchant-name">Papa Romano's - 5399 Crooks Road, Troy</p>
	        	<p class="offer-count"><strong>14</strong> more offers</p>
	        </a>
	    </div>
	    <div class="col-sm-4">
	        <a>
                <img alt="contest name" class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/uploads/logos/13687519a5bf03d670.jpg">
	        	<p class="merchant-name">Perry's Pizza in Clawson, MI</p>
	        	<p class="offer-count"><strong>7</strong> more offers</p>
	        </a>
	    </div>
	    <div class="col-sm-4">
	        <a>
                <img alt="contest name" class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/uploads/logos/195514b4dda290eb.jpg">
	        	<p class="merchant-name">Hungry Howie's Pizza in Michigan</p>
	        	<p class="offer-count"><strong>5</strong> more offers</p>
	        </a>
	    </div>
	</div>
</div>

<div class="content-bg margin-top-20">
	<div class="row">
		<div class="col-xs-12">
			<p class="spaced">Top Coffee Places</p>
			<hr style="margin-top:0;" class="green">
		</div>
	</div>
	<div class="row">
		<div class="col-sm-4">
            <a>
                <img alt="contest name" class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/images/1376401764-logo-biggby-coffee.jpg">
	        	<p class="merchant-name">Biggby Coffee</p>
	        	<p class="offer-count"><strong>4</strong> more offers</p>
	        </a>
	    </div>
	    <div class="col-sm-4">
	        <a>
                <img alt="contest name" class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/images/1376664847-logo_starbucks.jpg">
	        	<p class="merchant-name">Starbucks</p>
	        	<p class="offer-count"><strong>6</strong> more offers</p>
	        </a>
	    </div>
	    <div class="col-sm-4">
	        <a>
                <img alt="contest name" class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/images/1376923034-logo_caribou.jpg">
	        	<p class="merchant-name">Caribou Coffee</p>
	        	<p class="offer-count"><strong>2</strong> more offers</p>
	        </a>
	    </div>
	</div>
</div>

<div class="landing-banner margin-top-20">
    <div class="row">
    	<div class="col-sm-6">
    		<div class="banner-image" style="background:url('http://s3.amazonaws.com/saveoneverything_assets/assets/images/featured_detroit.jpg')center no-repeat;">

    		</div>
    	</div>
    	<hr class="visible-xs">
    	<div class="col-sm-6 banner-offers">
    		<div class="row">
    			<div class="col-xs-4">
    				<img src="http://s3.amazonaws.com/saveoneverything_assets/images/1376585160-logo-north-bros-ford.jpg" class="img-responsive">
    			</div>
    			<div class="col-xs-8">
    				<a href="#" class="">North Brothers Ford</a>
    				<h3>Low Price Tire Guarantee</h3>
    			</div>
    		</div>
    		<hr class="margin-top-10 margin-bottom-10">
    		<div class="row">
    			<div class="col-xs-4">
    				<img src="http://s3.amazonaws.com/saveoneverything_assets/images/1387223872-logo-mr-rooter.jpg" class="img-responsive">
    			</div>
    			<div class="col-xs-8">
    				<a href="#" class="">Mr. Rooter Plumbing &amp; Sewer</a>
    				<h3>FREE Camera Inspection With Sewer Cleaning</h3>
    			</div>
    		</div>
    		<hr class="margin-top-10 margin-bottom-10">
    		<div class="row">
    			<div class="col-xs-4">
    				<img src="http://s3.amazonaws.com/saveoneverything_assets/images/1389201398-logo-medical-weight-loss-clinic.jpg" class="img-responsive">
    			</div>
    			<div class="col-xs-8">
    				<a href="#" class="">Medical Weight Loss Clinic</a>
    				<h3>20% OFF Full Programs</h3>
    			</div>
    		</div>
    	</div>
    </div>
</div>
<div class="content-bg margin-top-20">
	<div class="row">
		<div class="col-xs-12">
			<p class="spaced">Featured Contests</p>
			<hr style="margin-top:0;" class="red">
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12">
			<div class="item contest btn-get-contest">
		        <div class="top-pic">
		            <a>
		                <div class="item-type contest"></div>
		                <img alt="contest name" class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/images/1397158546-Contest">
		            </a>
		            <button class="btn btn-default btn-block btn-get-contest">CLICK HERE TO ENTER</button>
		        </div>
		        
		        <a class="item-info hidden">
		            <h3>Display Name</h3>
		        </a>
		    </div>
		    <div class="item contest btn-get-contest">
		        <div class="top-pic">
		            <a>
		                <div class="item-type contest"></div>
		                <img alt="contest name" class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/images/1397493517-Contest">
		            </a>
		            <button class="btn btn-default btn-block btn-get-contest">CLICK HERE TO ENTER</button>
		        </div>
		        
		        <a class="item-info hidden">
		            <h3>Display Name</h3>
		        </a>
		    </div>
		    <div class="item contest btn-get-contest">
		        <div class="top-pic">
		            <a>
		                <div class="item-type contest"></div>
		                <img alt="contest name" class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/images/1398432908-Contest">
		            </a>
		            <button class="btn btn-default btn-block btn-get-contest">CLICK HERE TO ENTER</button>
		        </div>
		        
		        <a class="item-info hidden">
		            <h3>Display Name</h3>
		        </a>
		    </div>
		</div>
	</div>
</div>

@stop
