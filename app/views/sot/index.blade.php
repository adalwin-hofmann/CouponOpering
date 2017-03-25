@extends('master.templates.master')

@section('city-banner')
<div class="city-banner sot banner-menu">
    <div class="city-banner-img" style="background-image: url(http://s3.amazonaws.com/saveoneverything_assets/assets/images/sot/sot_banner.jpg)">
        <div class="fade-left"></div>
        <div class="fade-right"></div>
        <div class="row">
            <div class="col-xs-12 col-md-9">
                <div class="search-box content-bg row">
                    <div class="col-xs-3 col-sm-4 hidden-xs">
                        <div class="panel-group" id="accordion">
                            <div class="panel">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#panelFlights" class="collapsed">
                                            <span class="panel-icon flights"></span><span class="hidden-xs">Flights</span>
                                        </a>
                                    </h4>
                                </div>
                                <div id="panelFlights" class="panel-collapse collapse" style="height: 0px;">
                                    <div class="panel-body">
                                    </div>
                                </div>
                            </div>
                            <div class="panel">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#panelHotels" class="collapsed">
                                            <span class="panel-icon hotels"></span><span class="hidden-xs">Hotels</span>
                                        </a>
                                    </h4>
                                </div>
                                <div id="panelHotels" class="panel-collapse collapse" style="height: 0px;">
                                    <div class="panel-body">
                                    </div>
                                </div>
                            </div>
                            <div class="panel open">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#panelPackages">
                                            <span class="panel-icon packages"></span><span class="hidden-xs">Packages</span>
                                        </a>
                                    </h4>
                                </div>
                                <div id="panelPackages" class="panel-collapse collapse in">
                                    <div class="panel-body hidden-xs">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" class="travel-radio" name="packagesRadios" id="packagesRadios1" value="flight-hotel" data-parent_type="packages">
                                                Flight + Hotel
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" class="travel-radio" name="packagesRadios" id="packagesRadios2" value="flight-hotel-car" data-parent_type="packages" checked>
                                                Flight + Hotel + Car
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" class="travel-radio" name="packagesRadios" id="packagesRadios3" value="flight-car" data-parent_type="packages">
                                                Flight + Car
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" class="travel-radio" name="packagesRadios" id="packagesRadios4" value="hotel-car" data-parent_type="packages">
                                                Hotel + Car
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#panelCars" class="collapsed">
                                            <span class="panel-icon cars"></span><span class="hidden-xs">Cars</span>
                                        </a>
                                    </h4>
                                </div>
                                <div id="panelCars" class="panel-collapse collapse">
                                    <div class="panel-body">
                                    </div>
                                </div>
                            </div>
                            <div class="panel">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#panelCruises" class="collapsed">
                                            <span class="panel-icon cruises"></span><span class="hidden-xs">Cruises</span>
                                        </a>
                                    </h4>
                                </div>
                                <div id="panelCruises" class="panel-collapse collapse">
                                    <div class="panel-body">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-8 margin-top-20 margin-bottom-20">
                        <div class="visible-xs margin-bottom-10">
                            <div class="btn-group">
                                <a href="#panelFlights" class="btn btn-cyan btn-travel-tab btn-icon-flights"><span class="btn-icon flights"></span><br>Flights</a>
                                <a href="#panelHotels" class="btn btn-cyan btn-travel-tab btn-icon-hotels"><span class="btn-icon hotels"></span><br>Hotels</a>
                                <a href="#panelPackages" class="btn btn-white btn-travel-tab btn-icon-packages"><span class="btn-icon packages"></span><br>Packages</a>
                                <a href="#panelCars" class="btn btn-cyan btn-travel-tab btn-icon-cars"><span class="btn-icon cars"></span><br>Cars</a>
                                <a href="#panelCruises" class="btn btn-cyan btn-travel-tab btn-icon-cruises"><span class="btn-icon cruises"></span><br>Cruises</a>
                            </div>
                        </div>
                        <h4><em>SaveOn.com</em></h4>
                        <h1 class="fancy cyan">Vacation Planner</h1>
                        <hr style="margin-left:-30px;margin-right:-15px;border-color:#D6D6D6">
                        <div class="tab-content main-tabs">
                            <div class="tab-pane" id="flights">
                                <p>Flights</p>
                            </div>
                            <div class="tab-pane" id="hotels">
                                <p>Hotels</p>
                            </div>
                            <div class="tab-pane active" id="packages">
                                <div class="visible-xs margin-bottom-15">
                                    <label class="radio-inline">
                                        <input type="radio" class="travel-radio" name="mobilePackagesRadios" id="mobilePackagesRadios1" value="flight-hotel" data-parent_type="packages">
                                        Flight + Hotel
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" class="travel-radio" name="mobilePackagesRadios" id="mobilePackagesRadios2" value="flight-hotel-car" data-parent_type="packages" checked>
                                        Flight + Hotel + Car
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" class="travel-radio" name="mobilePackagesRadios" id="mobilePackagesRadios3" value="flight-car" data-parent_type="packages">
                                        Flight + Car
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" class="travel-radio" name="mobilePackagesRadios" id="mobilePackagesRadios4" value="hotel-car" data-parent_type="packages">
                                        Hotel + Car
                                    </label>
                                </div>
                                <div class="tab-content packages-tabs">
                                    <div class="tab-pane" id="flight-hotel">
                                        
                                    </div>
                                    <div class="tab-pane active" id="flight-hotel-car">
                                        <form role="form">
                                            <div class="row">
                                                <div class="form-group col-xs-6">
                                                    <label for="flyingFrom">Flying From</label>
                                                    <div class="left-inner-addon">
                                                        <span class="glyphicon glyphicon-map-marker"></span>
                                                        <input type="text" class="form-control" id="flyingFrom" name="flyingFrom" placeholder="City or airport">
                                                    </div>
                                                </div>
                                                <div class="form-group col-xs-6">
                                                    <label for="flyingTo">Flying To</label>
                                                    <div class="left-inner-addon">
                                                        <span class="glyphicon glyphicon-map-marker"></span>
                                                        <input type="text" class="form-control" id="flyingTo" name="flyingTo" placeholder="City or airport">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="form-group col-xs-6 col-sm-5">
                                                    <label for="departing">Departing</label>
                                                    <div class="left-inner-addon">
                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                        <input type="text" class="form-control" id="departing" name="departing" placeholder="mm/dd/yyyy">
                                                    </div>
                                                </div>
                                                <div class="form-group col-xs-6 col-sm-5">
                                                    <label for="returning">Returning</label>
                                                    <div class="left-inner-addon">
                                                        <span class="glyphicon glyphicon-calendar"></span>
                                                        <input type="text" class="form-control" id="returning" name="returning" placeholder="mm/dd/yyyy">
                                                    </div>
                                                </div>
                                                <div class="form-group col-xs-3 col-sm-2">
                                                    <label for="roomCount">Rooms</label>
                                                    <select class="form-control">
                                                        <option>1</option>
                                                        <option>2</option>
                                                        <option>3</option>
                                                        <option>4</option>
                                                        <option>5</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <hr class="margin-bottom-5">
                                            <p>Room 1</p>
                                            <div class="row">
                                                <div class="form-group col-xs-6 col-sm-3">
                                                    <label for="roomCount">Adults (18+)</label>
                                                    <select class="form-control">
                                                        <option>1</option>
                                                        <option selected="selected">2</option>
                                                        <option>3</option>
                                                        <option>4</option>
                                                        <option>5</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-xs-6 col-sm-3">
                                                    <label for="roomCount">Children (0 - 17)</label>
                                                    <select class="form-control">
                                                        <option>0</option>
                                                        <option>1</option>
                                                        <option>2</option>
                                                        <option>3</option>
                                                        <option>4</option>
                                                        <option>5</option>
                                                    </select>
                                                </div>
                                                <div class="col-xs-12 col-sm-6">
                                                    <p>An economy car will be added to your search.  (You may change your car options later.)</p>
                                                </div>
                                            </div>
                                            <div class="clearfix margin-top-10"></div>
                                            <button type="button" class="btn btn-lg btn-cyan">Search Packages <span class="glyphicon glyphicon-chevron-right"></span></button>
                                        </form>
                                    </div>
                                    <div class="tab-pane" id="flight-car">
                                        <p>Flight + Car</p>
                                    </div>
                                    <div class="tab-pane" id="hotel-car">
                                        <p>Hotel + Car</p>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="cars">Cars</div>
                            <div class="tab-pane" id="cruises">Cruises</div>
                        </div>
                       
                    </div>
                     <div class="best-price"><img src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sot/best-price-guarantee.png" alt="Best Price Guarentee"></div>
                </div>
            </div>
            <div class="col-md-3 hidden-xs hidden-sm">
                <div class="content-bg callout-box callout-box-cyan">
                    <h1 class="fancy text-center">SaveOn.com</h1>
                    <h2>Vacation Planner</h2>
                    <hr>
                    <p>Welcome to SaveOn Travel! We're excited to help you plan your perfect vacation. Want to learn more? Take our tour to learn how it all works!</p>
                    <div class="row">
                        <a href="#">
                            Take the Tour <span class="glyphicon glyphicon-chevron-right pull-right"></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="banner-search-bar content-bg">
    <form>
        <div class="row">
            <div class="form-group col-md-4">
                <div class="left-inner-addon">
                    <span class="glyphicon glyphicon-map-marker"></span>
                    <input type="text" class="form-control" id="flyingTo" name="flyingTo" placeholder="Your Destination">
                </div>
            </div>
            <div class="form-group col-md-3 col-xs-6">
                <div class="left-inner-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                    <input type="text" class="form-control" id="departing" name="departing" placeholder="Departure Date">
                </div>
            </div>
            <div class="form-group col-md-3 col-xs-6">
                <div class="left-inner-addon">
                    <span class="glyphicon glyphicon-calendar"></span>
                    <input type="text" class="form-control" id="returning" name="returning" placeholder="Return Date">
                </div>
            </div>
            <div class="form-group col-md-2">
                <a href="/travel/city" class="btn btn-md btn-block btn-cyan">Find Deals <span class="glyphicon glyphicon-chevron-right pull-right"></span></a>
            </div>
        </div>
    </form>
</div>
@stop

@section('page-title')
<h1>Save On Travel</h1>
@stop

@section('breadcrumbs')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}" itemprop="url"><span itemprop="title">Home</span></a>
    </li>
    <li class="active">Travel</li>
@stop

@section('subheader-content')
    <!-- Insert New Subheader Content Block Here -->
    <p>Set subheader text here.</p>
@stop

@section('sidebar')

<div class="panel panel-default">
    <div class="panel-heading">
      <span class="h4 panel-title hblock">
        <a data-toggle="collapse" href="#collapseTwo">About SaveOn Travel<span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span></a>
        <div class="clearfix"></div>
      </span>
    </div>
    <div id="collapseTwo" class="panel-collapse collapse in">
        <div class="panel-body explore-links">
            <div class="category-header">
                <p>SaveOn Travel is a great way to find the vacation of your dreams for the right price.</p>
            </div> 
        </div>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
      <span class="h4 hblock panel-title">
        <a data-toggle="collapse" class="" href="#questionsCollaspe">Got Questions?<span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span></a>
        <div class="clearfix"></div>
      </span>
    </div>
    <div id="questionsCollaspe" class="panel-collapse collapse in" style="height: auto;">
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
                    <a onclick="showClassicWidget()">Send Feedback</a>
                </div>
            </div>
        </div>
    </div>
</div>

@stop
@section('body')

<script>
    
</script>

    <div class="banner-offer">

    </div>

    <div class="clearfix"></div>

    <div id="container" class="js-masonry soct-masonry" style="position: relative;">
        <div class="item travel" itemscope="" itemtype="http://schema.org/Organization">
            <div class="item-type travel"></div>
            <div class="top-pic ">
                <a href="http://www.saveon.com/coupons/mi/milford/travel-entertainment/dance-studios/shanon-s-dance-academy-in-keego-harbor-mi/280302">
                    <div class="expired-banner"><span class="h3 spaced">Expired</span></div>
                    <img alt="Grand Palladium Bavaro Suites" class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sot/sample1.png" itemprop="image">
                </a>
            </div>
            <div class="row">
              <div class="col-xs-12">
                <a class="item-info" href="http://www.saveon.com/coupons/mi/milford/travel-entertainment/dance-studios/shanon-s-dance-academy-in-keego-harbor-mi/280302">
                    <p class="merchant-name" itemprop="name">Grand Palladium Bavaro Suites</p>
                    <p class="destination-name"><span class="glyphicon glyphicon-map-marker"></span> <span class="destination">Punta Cana, Dominican Republic</span></p>
                </a>
              </div>
            </div>
            <a class="item-info" href="http://www.saveon.com/coupons/mi/milford/travel-entertainment/dance-studios/shanon-s-dance-academy-in-keego-harbor-mi/280302?showeid=3323367" itemscope="" itemtype="http://schema.org/Product">
              <span class="hidden" itemprop="name"></span>
              <div itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
                <span class="h3" itemprop="name">All-Inclusive Punta Cana Vacation with Airfare</span>
                <span class="h4 price">From $749</span>
                <!--<p class="expires_at" itemprop="availabilityEnds"><strong>Expires</strong> 08/31/2014</p>
                <span itemprop="price" class="hidden">0</span>-->
              </div>
            </a>
            <div class="btn-group">
                <button type="button" class="btn btn-default btn-get-coupon" data-offer_id="197558" data-entity_id="3323367"><img src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sot/masonry-icons/view_it_travel.png" alt="Get Info on 10% OFF Fall Session from Shanon's Dance Academy in Keego Harbor, MI" class="img-circle"><br>Get It</button>
                <button type="button" class="btn btn-default btn-save-coupon " data-offer_id="197558" data-entity_id="3323367"><img src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sot/masonry-icons/save_it_travel.png" alt="Save This Coupon for 10% OFF Fall Session from Shanon's Dance Academy in Keego Harbor, MI" class="img-circle"><br><span class="save-coupon-text">Save It</span></button>
                <button type="button" class="btn btn-default btn-coupon-share" data-offer_id="197558" data-entity_id="3323367" style=""><img src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sot/masonry-icons/share_it_travel.png" alt="Share This Coupon for 10% OFF Fall Session from Shanon's Dance Academy in Keego Harbor, MI" class="img-circle"><br>Share It</button>
            </div>
        </div>
        <div class="item travel" itemscope="" itemtype="http://schema.org/Organization">
            <div class="item-type travel"></div>
            <div class="top-pic ">
                <a href="http://www.saveon.com/coupons/mi/milford/travel-entertainment/dance-studios/shanon-s-dance-academy-in-keego-harbor-mi/280302">
                    <div class="expired-banner"><span class="h3 spaced">Expired</span></div>
                    <img alt="Maui Sands Resort &amp; Indoor Waterpark" class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sot/sample2.png" itemprop="image">
                </a>
            </div>
            <div class="row">
              <div class="col-xs-12">
                <a class="item-info" href="http://www.saveon.com/coupons/mi/milford/travel-entertainment/dance-studios/shanon-s-dance-academy-in-keego-harbor-mi/280302">
                    <p class="merchant-name" itemprop="name">Maui Sands Resort &amp; Indoor Waterpark</p>
                    <p class="destination-name"><span class="glyphicon glyphicon-map-marker"></span> <span class="destination">Sandusky, OH</span></p>
                </a>
              </div>
            </div>
            <a class="item-info" href="http://www.saveon.com/coupons/mi/milford/travel-entertainment/dance-studios/shanon-s-dance-academy-in-keego-harbor-mi/280302?showeid=3323367" itemscope="" itemtype="http://schema.org/Product">
              <span class="hidden" itemprop="name"></span>
              <div itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
                <span class="h3" itemprop="name">Kid-Friendly Ohio Resort with Indoor Water Park</span>
                <span class="h4 price"><span class="old-price">$288</span> From $749</span>
                <!--<p class="expires_at" itemprop="availabilityEnds"><strong>Expires</strong> 08/31/2014</p>
                <span itemprop="price" class="hidden">0</span>-->
              </div>
            </a>
            <div class="btn-group">
                <button type="button" class="btn btn-default btn-get-coupon" data-offer_id="197558" data-entity_id="3323367"><img src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sot/masonry-icons/view_it_travel.png" alt="Get Info on 10% OFF Fall Session from Shanon's Dance Academy in Keego Harbor, MI" class="img-circle"><br>Get It</button>
                <button type="button" class="btn btn-default btn-save-coupon " data-offer_id="197558" data-entity_id="3323367"><img src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sot/masonry-icons/save_it_travel.png" alt="Save This Coupon for 10% OFF Fall Session from Shanon's Dance Academy in Keego Harbor, MI" class="img-circle"><br><span class="save-coupon-text">Save It</span></button>
                <button type="button" class="btn btn-default btn-coupon-share" data-offer_id="197558" data-entity_id="3323367" style=""><img src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sot/masonry-icons/share_it_travel.png" alt="Share This Coupon for 10% OFF Fall Session from Shanon's Dance Academy in Keego Harbor, MI" class="img-circle"><br>Share It</button>
            </div>
        </div>
        <div class="item travel" itemscope="" itemtype="http://schema.org/Organization">
            <div class="item-type travel"></div>
            <div class="top-pic ">
                <a href="http://www.saveon.com/coupons/mi/milford/travel-entertainment/dance-studios/shanon-s-dance-academy-in-keego-harbor-mi/280302">
                    <div class="expired-banner"><span class="h3 spaced">Expired</span></div>
                    <img alt="Springbrook Inn" class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sot/sample3.png" itemprop="image">
                </a>
            </div>
            <div class="row">
              <div class="col-xs-12">
                <a class="item-info" href="http://www.saveon.com/coupons/mi/milford/travel-entertainment/dance-studios/shanon-s-dance-academy-in-keego-harbor-mi/280302">
                    <p class="merchant-name" itemprop="name">Springbrook Inn</p>
                    <p class="destination-name"><span class="glyphicon glyphicon-map-marker"></span> <span class="destination">Prudenville, MI</span></p>
                </a>
              </div>
            </div>
            <a class="item-info" href="http://www.saveon.com/coupons/mi/milford/travel-entertainment/dance-studios/shanon-s-dance-academy-in-keego-harbor-mi/280302?showeid=3323367" itemscope="" itemtype="http://schema.org/Product">
              <span class="hidden" itemprop="name"></span>
              <div itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
                <span class="h3" itemprop="name">Charming Northern Michigan Inn near Lakes</span>
                <span class="h4 price"><span class="old-price">$169</span> From $99</span>
                <!--<p class="expires_at" itemprop="availabilityEnds"><strong>Expires</strong> 08/31/2014</p>
                <span itemprop="price" class="hidden">0</span>-->
              </div>
            </a>
            <div class="btn-group">
                <button type="button" class="btn btn-default btn-get-coupon" data-offer_id="197558" data-entity_id="3323367"><img src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sot/masonry-icons/view_it_travel.png" alt="Get Info on 10% OFF Fall Session from Shanon's Dance Academy in Keego Harbor, MI" class="img-circle"><br>Get It</button>
                <button type="button" class="btn btn-default btn-save-coupon " data-offer_id="197558" data-entity_id="3323367"><img src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sot/masonry-icons/save_it_travel.png" alt="Save This Coupon for 10% OFF Fall Session from Shanon's Dance Academy in Keego Harbor, MI" class="img-circle"><br><span class="save-coupon-text">Save It</span></button>
                <button type="button" class="btn btn-default btn-coupon-share" data-offer_id="197558" data-entity_id="3323367" style=""><img src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sot/masonry-icons/share_it_travel.png" alt="Share This Coupon for 10% OFF Fall Session from Shanon's Dance Academy in Keego Harbor, MI" class="img-circle"><br>Share It</button>
            </div>
        </div>
    </div>

    <div class="clearfix"></div>

    <!--<div class="">
        <a href="{{ URL::abs('/cars/used/') }}" class="btn btn-block btn-lg btn-grey view-more center-block" data-loading-text="Loading...">View More</a>
    </div>-->
    
<div class="content-bg default-no-results">
        <p class="spaced"><strong>Sorry, We Have No Cars &amp; Trucks Offers In Your Area!</strong></p>
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