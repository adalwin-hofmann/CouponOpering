@extends('master.templates.master')

@section('page-title')
<h1>Hotel FIVE</h1>
@stop

@section('breadcrumbs')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}" itemprop="url"><span itemprop="title">Home</span></a>
    </li>
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/travel')}}" itemprop="url"><span itemprop="title">Travel</span></a>
    </li>
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
    	<a href="{{URL::abs('/travel/city')}}" itemprop="url"><span itemprop="title">Las Vegas</span></a>
    </li>
  	<li class="active">Hotel FIVE</li>
@stop

@section('subheader-content')
	<!-- Insert New Subheader Content Block Here -->
@stop

@section('sidebar')

	<div class="content-bg margin-bottom-15 hidden-xs">
		<img class="img-responsive center-block" src="http://images.travelnow.com/hotels/1000000/10000/5900/5900/5900_47_b.jpg" style="width:100%" alt="" itemprop="logo">
	</div>

	<div class="panel panel-default">
    <div class="panel-heading">
      <span class="h4 panel-title hblock">
        <a data-toggle="collapse" href="#collapseTwo">About Hotel FIVE<span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span></a>
        <div class="clearfix"></div>
      </span>
    </div>
    <div id="collapseTwo" class="panel-collapse collapse in">
        <div class="panel-body explore-links">
            <div class="category-header">
                <p><b>Location. </b> <br />Located in Dallas, MCM Elegante Hotel & Suites is near the airport and close to Bachman Lake Park, University of Dallas, and L.B Houston Municipal Golf Course. Other area points of interest include Frontiers of Flight Museum and Four Seasons Resort and Club Dallas at Las Colinas. </p><p><b>Hotel Features. </b><br />MCM Elegante Hotel & Suites features a restaurant and a bar/lounge. Room service is available during limited hours. The hotel serves a complimentary hot and cold buffet breakfast. Recreational amenities include an outdoor pool, a spa tub, and a fitness facility. This 3-star property has a business center and offers small meeting rooms, audio-visual equipment, and business services. High-speed Internet access is available in public areas. This Dallas property has event space consisting of banquet facilities, conference/meeting rooms, and a ballroom. Complimentary shuttle services include a roundtrip airport shuttle and an area shuttle.  Guest parking is complimentary. Additional property amenities include a concierge desk, gift shops/newsstands, and laundry facilities. </p><p><b>Guestrooms. </b> <br /> 199 air-conditioned guestrooms at MCM Elegante Hotel & Suites feature coffee/tea makers and complimentary weekday newspapers. Beds come with premium bedding. Bathrooms feature shower/tub combinations, complimentary toiletries, and hair dryers. Wireless Internet access is available. In addition to desks, guestrooms offer multi-line phones with voice mail, as well as free local calls (restrictions may apply). 32-inch flat-panel televisions have satellite channels, video-game consoles, and pay movies. Also included are ceiling fans and blackout drapes/curtains. Housekeeping is available daily. </p> <br /><br /> <p><strong>Notifications and Fees:</strong><br /></p><p><ul><li>There are no room charges for children 18 years old and younger who occupy the same room as their parents or guardians, using existing bedding. </li> </ul></p><p></p><p></p> <p>The following fees and deposits are charged by the property at time of service, check-in, or check-out.  <ul><li>Pet fee: USD 50 per stay</li><li>Buffet breakfast fee: USD 7.95 per person (approximate amount)</li> </ul></p><p>The above list may not be comprehensive. Fees and deposits may not include tax and are subject to change. </p>
            </div> 
        </div>
    </div>
</div>

@stop

@section('body')

	<div class="content-bg margin-bottom-15 visible-xs">
		<img class="img-responsive center-block" src="http://images.travelnow.com/hotels/1000000/10000/5900/5900/5900_47_b.jpg" style="width:100%" alt="" itemprop="logo">
	</div>

	<div class="content-bg margin-bottom-15">
		<div class="row">
			<div class="col-sm-3">
				<h2 itemprop="name">Hotel FIVE</h2>
				<address itemprop="address" itemscope="" itemtype="http://schema.org/PostalAddress">
					<span itemprop="streetAddress">Pier 67, 2411 Alaskan Way<br></span>
					<span itemprop="addressLocality">Las Vegas</span>, <span itemprop="addressRegion">NV</span> <span span="" itemprop="postalCode">48084</span><br>
				</address>
			</div>
			<div class="col-sm-3 hidden-xs">
				<img class="img-responsive" src="https://www.google.com/maps/vt/data=VLHX1wd2Cgu8wR6jwyh-km8JBWAkEzU4,ypdZz7yJOJJYtBG3cwB07OgnGhycWv7boYwYSYFXNxibWDRWzqw51_bPXv4jRuL58O2vren3Q336ReM0tapFZrZOkDogGb_Dy5pQFJAbBLxjGhcR66oizxqXWRhtgVGp0GR7sSS9ZmKu4FanH_O9jBMPKxEBBLA0ZyC8gVj1l1roatso9lj3p_zfwjIn2W9McVL3JH_OYlpUy0_yV7dYZfYx7kaVe15Ii54X17oTEqRxzaT0bZdQ6qkc-qVmWbshlonHuxn2uX2txCEVk2orvFb-K-glNA" title="Map of 2294-2504 Alaskan Way, Seattle, WA 98121" alt="Map of 2294-2504 Alaskan Way, Seattle, WA 98121">
			</div>
			<div class="col-sm-6">
				<h3>Amenities</h3>
				<p>24-hour front desk, ATM/banking, Accessibility equipment for the deaf, Accessible bathroom, Internet access - wireless, Pets accepted, Restaurant(s) in hotel, Room service (limited hours), Swimming pool - outdoor, Wedding services</p>
			</div>
		</div>
	</div>
	<div class="js-masonry">
		<div class="item list travel">
            <div class="row">
                <div class="col-xs-5 col-md-4 col-lg-3">
                    <img alt="" class="main-img img-responsive" src="http://media.expedia.com/hotels/1000000/20000/12000/11969/11969_64_s.jpg">
                </div>
                <div class="item-info col-xs-7 col-md-8 col-lg-9">
                    <span class="h3">Deluxe 2 Queens - Non Refundable</span>
                    <span class="h4 price">From $275.90</span>
                    <p><a href="http://travel.ian.com/hotels/priceCheck.jsp?cid=55505&locale=en_US&currencyCode=USD&numberOfRooms=1&room-0-adult-total=2&room-0-child-total=2&room-0-child-0-age=5&room-0-child-1-age=7&rateChange=false&rateCode=200820962&roomTypeCodeDescription=Deluxe+2+Queens+-+Non+Refundable&roomTypeCode=200069341&cityLink=Seattle&stateProvinceLink=WA&countryLink=US&resType=hotel&propertyID=11969&hotelID=125719&supplierType=E&propertyType=H&requestKey=b68add38-5f2d-40fb-ad27-fd67f9551043&rateKey=b68add38-5f2d-40fb-ad27-fd67f9551043&arrivalMonth=10&arrivalDay=11&arrivalYear=2012&departureMonth=10&departureDay=13&departureYear=2012&nativeNightlyRates=145.0,145.0&nativeCurrencyCode=USD&nativeRoomRate=333.9&displayNightlyRates=145.0,145.0&displayCurrencyCode=USD&displayRoomRate=333.9&chargeableRoomRateTotal=333.9&chargeableRoomRateTaxes=43.9&guarantee=C&directConnect=false&rateFrequency=B&rateIsBase=true" class="btn btn-cyan">Get Deal</a></p>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="item list travel">
            <div class="row">
                <div class="col-xs-5 col-md-4 col-lg-3">
                    <img alt="" class="main-img img-responsive" src="http://media.expedia.com/hotels/1000000/20000/12000/11969/11969_64_s.jpg">
                </div>
                <div class="item-info col-xs-7 col-md-8 col-lg-9">
                    <span class="h3">Deluxe 2 Queens</span>
                    <span class="h4 price">From $343.90</span>
                    <p><a href="http://travel.ian.com/hotels/priceCheck.jsp?cid=55505&locale=en_US&currencyCode=USD&numberOfRooms=1&room-0-adult-total=2&room-0-child-total=2&room-0-child-0-age=5&room-0-child-1-age=7&rateChange=false&rateCode=200820962&roomTypeCodeDescription=Deluxe+2+Queens+-+Non+Refundable&roomTypeCode=200069341&cityLink=Seattle&stateProvinceLink=WA&countryLink=US&resType=hotel&propertyID=11969&hotelID=125719&supplierType=E&propertyType=H&requestKey=b68add38-5f2d-40fb-ad27-fd67f9551043&rateKey=b68add38-5f2d-40fb-ad27-fd67f9551043&arrivalMonth=10&arrivalDay=11&arrivalYear=2012&departureMonth=10&departureDay=13&departureYear=2012&nativeNightlyRates=145.0,145.0&nativeCurrencyCode=USD&nativeRoomRate=333.9&displayNightlyRates=145.0,145.0&displayCurrencyCode=USD&displayRoomRate=333.9&chargeableRoomRateTotal=333.9&chargeableRoomRateTaxes=43.9&guarantee=C&directConnect=false&rateFrequency=B&rateIsBase=true" class="btn btn-cyan">Get Deal</a></p>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
	</div>

<script>

</script>

@stop









