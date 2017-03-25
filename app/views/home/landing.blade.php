@extends('master.templates.master')
<?php
$geoip = json_decode(GeoIp::getGeoIp('json'));
?>
@section('page-title')
<h1>Recommended Offers <small>in {{ucwords(strtolower($geoip->city_name))}}, {{$geoip->region_name}}</small></h1>
@stop


@section('sidebar')
	<div class="panel panel-default">
	    <div class="panel-heading">
	      <h4 class="panel-title">
	        <a data-toggle="collapse" href="#collapseCities">Featured Cities <span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span></a>
	        <div class="clearfix"></div>
	      </h4>
	    </div>
	    <div id="collapseCities" class="panel-collapse collapse in">
		    <div class="panel-body explore-links">
		      	<ul>
		      		<li><a href="{{URL::abs('/')}}/featured/in/detroit/mi">Detroit, MI</a></li>
		      		<li><a href="{{URL::abs('/')}}/featured/in/chicago/il">Chicago, IL</a></li>
		      		<li><a href="{{URL::abs('/')}}/featured/in/minneapolis/mn">Minneapolis, MN</a></li>
		      		<li><a href="{{URL::abs('/')}}/featured/in/ann-arbor/mi">Ann Arbor, MI</a></li>
		      	</ul>
		    </div>
		</div>
	</div>
	<div class="panel panel-default">
	    <div class="panel-heading">
	      <h4 class="panel-title">
	        <a data-toggle="collapse" href="#collapseOne">Explore {{$type == 'coupon' ? 'Coupons' : ($type == 'dailydeal' ? 'Daily Deals' : 'Contests')}} <span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span></a>
	        <div class="clearfix"></div>
	      </h4>
	    </div>
	    <div id="collapseOne" class="panel-collapse collapse in">
		    <div class="panel-body explore-links">
		      	<ul>
                    @include('master.templates.explore', array('active' => 'all', 'type' => 'coupon'))
				</ul>
		    </div>
	    </div>
	</div>
@stop

@section('body')
<div class="landing-banner">
    <div class="row">
    	<div class="col-sm-6">
    		<div class="banner-image">

    		</div>
    	</div>
    	<hr class="visible-xs">
    	<div class="col-sm-6 banner-offers">
    		<div class="row">
    			<div class="col-xs-4">
    				<img src="http://s3.amazonaws.com/saveoneverything_assets/images/1396531363-gallery" class="img-responsive">
    			</div>
    			<div class="col-xs-8">
    				<a href="#" class="blue">Salon Lily in Troy, MI</a>
    				<h3>$15 Blow-Out</h3>
    			</div>
    		</div>
    		<hr class="margin-top-10 margin-bottom-10">
    		<div class="row">
    			<div class="col-xs-4">
    				<img src="http://s3.amazonaws.com/saveoneverything_assets/images/1392301267-imgST-papa-romanos-bread.jpg" class="img-responsive">
    			</div>
    			<div class="col-xs-8">
    				<a href="#" class="blue">Papa Romano's - 5399 Crooks Road, Troy</a>
    				<h3>FREE Bambino Bread</h3>
    			</div>
    		</div>
    		<hr class="margin-top-10 margin-bottom-10">
    		<div class="row">
    			<div class="col-xs-4">
    				<img src="http://s3.amazonaws.com/saveoneverything_gallery/Category_Images/Everything Else/Party Rental Supplies/PartyRentalSupplies3_72dpi.jpg" class="img-responsive">
    			</div>
    			<div class="col-xs-8">
    				<a href="#" class="blue">Bob B's Party Rentals</a>
    				<h3>FREE Snow Cone or Popcorn Machine</h3>
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
		                <img alt="contest name" class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/images/1398458487-Contest">
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
		                <img alt="contest name" class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/images/1397156521-Contest">
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
		                <img alt="contest name" class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/images/1397156445-Contest">
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

<script type="text/ejs" id="template_soct_deals">
<% list(entities, function(entities){ %>
    <hr class="margin-top-10 margin-bottom-10">
	<div class="row">
		<div class="col-md-4 col-sm-12 col-xs-4">
			<img src="http://placehold.it/500x300" class="img-responsive">
		</div>
		<div class="col-md-8 col-sm-12 col-xs-8">
			<a href="#">Lorem Ipsum Biz</a>
			<p>Quisque tincidunt lectus ut lectus congue venenatis.</p>
		</div>
	</div>
<% }); %>
</script>

<div class="content-bg margin-top-20">
	<div class="section-type featured"></div>
	<div class="row">
		<div class="col-sm-4">
			<p class="spaced">Cars &amp; Trucks</p>
			<hr class="margin-top-0 margin-bottom-10 green">
			<img src="/img/landing-soct.jpg" class="img-responsive">
			<div id="soct_deals">
				<hr class="margin-top-10 margin-bottom-10">
				<div class="row">
					<div class="col-md-4 col-sm-12 col-xs-4">
						<img src="http://s3.amazonaws.com/saveoneverything_assets/images/1374086465-logo-elder-ford.jpg" class="img-responsive">
					</div>
					<div class="col-md-8 col-sm-12 col-xs-8">
						<a href="#">Elder Ford</a>
						<p>10% OFF Any Repair or Parts Purchase</p>
					</div>
				</div>
				<hr class="margin-top-10 margin-bottom-10">
				<div class="row">
					<div class="col-md-4 col-sm-12 col-xs-4">
						<img src="http://s3.amazonaws.com/saveoneverything_assets/images/1389287775-logo-buff-whelan-chevy.jpg" class="img-responsive">
					</div>
					<div class="col-md-8 col-sm-12 col-xs-8">
						<a href="#">Buff Whelan Chevy</a>
						<p>$19.95 Lube, Oil &amp; Filter</p>
					</div>
				</div>
				<hr class="margin-top-10 margin-bottom-10">
				<div class="row">
					<div class="col-md-4 col-sm-12 col-xs-4">
						<img src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/uploads/logos/1470651953c1e07333.jpg" class="img-responsive">
					</div>
					<div class="col-md-8 col-sm-12 col-xs-8">
						<a href="#">Golling Buick GMC</a>
						<p>$12.95 Four Tire Rotation</p>
					</div>
				</div>
			</div>
		</div>
		<div class="col-sm-4">
			<p class="spaced">Home Improvement</p>
			<hr class="margin-top-0 margin-bottom-10 green">
			<img src="/img/landing-sohi.jpg" class="img-responsive">
			<hr class="margin-top-10 margin-bottom-10">

			<div class="row">
				<div class="col-md-4 col-sm-12 col-xs-4">
					<img src="http://s3.amazonaws.com/saveoneverything_assets/images/1374597356-logo-americas-best-bath.jpg" class="img-responsive">
				</div>
				<div class="col-md-8 col-sm-12 col-xs-8">
					<a href="#">America's Best Bath</a>
					<p>$500 OFF The Installation of a New Bathtub or Shower</p>
				</div>
			</div>
			<hr class="margin-top-10 margin-bottom-10">
			<div class="row">
				<div class="col-md-4 col-sm-12 col-xs-4">
					<img src="http://s3.amazonaws.com/saveoneverything_assets/images/1387223872-logo-mr-rooter.jpg" class="img-responsive">
				</div>
				<div class="col-md-8 col-sm-12 col-xs-8">
					<a href="#">Mr. Rooter Plumbing &amp; Sewer</a>
					<p>FREE Camera Inspection With Sewer Cleaning</p>
				</div>
			</div>
			<hr class="margin-top-10 margin-bottom-10">
			<div class="row">
				<div class="col-md-4 col-sm-12 col-xs-4">
					<img src="http://s3.amazonaws.com/saveoneverything_assets/images/1398690973-logo" class="img-responsive">
				</div>
				<div class="col-md-8 col-sm-12 col-xs-8">
					<a href="#">Mr. Furnace</a>
					<p>$69.95 Furnace or A/C Tune-Up</p>
				</div>
			</div>
		</div>
		<div class="col-sm-4">
			<p class="spaced">Food &amp; Dining</p>
			<hr class="margin-top-0 margin-bottom-10 green">
			<img src="/img/landing-food.jpg" class="img-responsive">
			<hr class="margin-top-10 margin-bottom-10">

			<div class="row">
				<div class="col-md-4 col-sm-12 col-xs-4">
					<img src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/uploads/logos/806510a996cb5b0c.jpg" class="img-responsive">
				</div>
				<div class="col-md-8 col-sm-12 col-xs-8">
					<a href="#">Leo's Coney Island</a>
					<p>$1 OFF</p>
				</div>
			</div>
			<hr class="margin-top-10 margin-bottom-10">
			<div class="row">
				<div class="col-md-4 col-sm-12 col-xs-4">
					<img src="http://s3.amazonaws.com/saveoneverything_assets/images/1392130925-logo-tom-sushi.jpg" class="img-responsive">
				</div>
				<div class="col-md-8 col-sm-12 col-xs-8">
					<a href="#">Tom Sushi in Troy, MI</a>
					<p>15% OFF Catering</p>
				</div>
			</div>
			<hr class="margin-top-10 margin-bottom-10">
			<div class="row">
				<div class="col-md-4 col-sm-12 col-xs-4">
					<img src="http://s3.amazonaws.com/saveoneverything_assets/images/1374678538-logo_paparomanos.jpg" class="img-responsive">
				</div>
				<div class="col-md-8 col-sm-12 col-xs-8">
					<a href="#">Papa Romano's - 5399 Crooks Road, Troy</a>
					<p>$8.88 Large 1 Topping All Corner Pizza</p>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
    category_id = '2';
    type = 'all';
    page = 0;
</script>

@stop
