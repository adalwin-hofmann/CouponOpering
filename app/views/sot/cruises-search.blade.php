@extends('master.templates.master')

@section('city-banner')
<div class="city-banner sot banner-menu">
    <div class="city-banner-img" style="background-image: url(http://s3.amazonaws.com/saveoneverything_assets/assets/images/sot/sot_banner.jpg)">
        <div class="fade-left"></div>
        <div class="fade-right"></div>
        <div class="row">
            <div class="col-xs-12 col-md-10 col-md-offset-1">
                <div class="content-bg">
                    <form>
                        <div class="row">
                            <div class="col-sm-4">
                                <label>Going to</label>
                                <select class="form-control" id="cruise-destination" name="cruise-destination">
                                    <option value="">Show all</option>
                                    <optgroup label="Most Popular">
                                        <option value="Caribbean"> Caribbean </option>
                                        <option value="Bahamas"> Bahamas </option>
                                        <option value="Mexico"> Mexico </option>
                                        <option value="Alaska"> Alaska </option>
                                        <option value="Europe"> Europe </option>
                                        <option value="Bermuda"> Bermuda </option>
                                        <option value="Hawaii"> Hawaii </option>
                                        <option value="Canada / New England"> Canada / New England </option>
                                    </optgroup>
                                    <optgroup label="Other Destinations">
                                        <option value="Africa"> Africa </option>
                                        <option value="Arctic / Antarctic"> Arctic / Antarctic </option>
                                        <option value="Asia"> Asia </option>
                                        <option value="Australia / New Zealand"> Australia / New Zealand </option>
                                        <option value="Central America"> Central America </option>
                                        <option value="Cruise To Nowhere"> Cruise To Nowhere </option>
                                        <option value="Galapagos"> Galapagos </option>
                                        <option value="Greenland/Iceland"> Greenland/Iceland </option>
                                        <option value="Middle East"> Middle East </option>
                                        <option value="Pacific Coastal"> Pacific Coastal </option>
                                        <option value="Panama Canal"> Panama Canal </option>
                                        <option value="South America"> South America </option>
                                        <option value="South Pacific"> South Pacific </option>
                                        <option value="Tahiti"> Tahiti </option>
                                        <option value="Transatlantic"> Transatlantic </option>
                                        <option value="World Cruises"> World Cruises </option>
                                    </optgroup>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label>Departure month</label>
                                <select class="form-control" id="cruise-departure-month" name="cruise-departure-month">
                                    <option value="">Show all</option>
                                    @for($i=0;$i<=17;$i++)
                                        <option value="{{date('FY', strtotime('+'.$i.' month'))}}">{{date('F Y', strtotime('+'.$i.' month'))}}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <label>&nbsp;</label>
                                <a href="{{URL::abs('/')}}/travel/cruises/search" type="submit" class="btn btn-green btn-block">Search</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('page-title')
<h1>SaveOn Cruises in the Caribbean during May 2015</h1>
@stop

@section('breadcrumbs')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}" itemprop="url"><span itemprop="title">Home</span></a>
    </li>
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}/travel" itemprop="url"><span itemprop="title">Travel</span></a>
    </li>
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}/travel/cruises" itemprop="url"><span itemprop="title">Cruises</span></a>
    </li>
    <li class="active">Search</li>
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
                <p>SaveOn Cruises is a great way to find the cruise of your dreams for the right price. After booking a cruise, get $100 back for every person you refer. <a href="{{URL::abs('/')}}/travel/cruises/howitworks">Find out how it works.</a></p>
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

<div class="content-bg">

    <div class="offer-results-list">
        <div class="item list coupon " itemscope="" itemtype="http://schema.org/Organization">
            <div class="row margin-bottom-10 margin-top-10">
                <a class="col-xs-5 col-sm-3" href="{{URL::abs('/')}}/travel/cruises/referral">
                    <div class="list-img">
                        <div class="expired-banner"><span class="h3 spaced">Expired</span></div>
                        <img alt="" class="img-responsive center-block" src="http://cruise.expedia.com/pictures/db/Ship/2452.jpg" itemprop="image">
                    </div>
                </a>
                <div class="col-xs-7 col-sm-5 col-lg-4 item-info">
                    <a href="{{URL::abs('/')}}/travel/cruises/referral">
                      <p class="merchant-name" itemprop="name">
                        2015 Departures: May 2
                      </p>
                    </a>
                                
                    <a href="{{URL::abs('/')}}/travel/cruises/referral">
                      <div class="h3" itemprop="name">
                        7-night Western Caribbean Cruise from Miami (Roundtrip)
                      </div>
                    </a>
                    <p>Ports of Call: Miami, Florida - At Sea - Cozumel, Mexico - Belize City, Belize - Mahogany Bay, Honduras - Georgetown, Grand Cayman- At Sea - Miami, Florida</p>
                </div>
                <div class="col-md-4 visible-lg btn-col">
                    <button onclick="location.href='{{URL::abs('/')}}/travel/cruises/referral'" class="btn btn-default">Book for $800 <span class="glyphicon glyphicon-chevron-right"></span></button>
                </div>
            </div>
            <div class="hidden-lg">
                <button onclick="location.href='{{URL::abs('/')}}/travel/cruises/referral'" class="btn btn-default">Book for $800 <span class="glyphicon glyphicon-chevron-right"></span></button>
            </div>
        </div>
        <div class="item list coupon " itemscope="" itemtype="http://schema.org/Organization">
            <div class="row margin-bottom-10 margin-top-10">
                <a class="col-xs-5 col-sm-3" href="{{URL::abs('/')}}/travel/cruises/referral">
                    <div class="list-img">
                        <div class="expired-banner"><span class="h3 spaced">Expired</span></div>
                        <img alt="" class="img-responsive center-block" src="http://cruise.expedia.com/pictures/db/Ship/1423.jpg" itemprop="image">
                    </div>
                </a>
                <div class="col-xs-7 col-sm-5 col-lg-4 item-info">
                    <a href="{{URL::abs('/')}}/travel/cruises/referral">
                      <p class="merchant-name" itemprop="name">
                        2015 Departures: Jan: 31, Feb: 28, Apr: 11, 25, May: 9, 23, Jun: 6 
                      </p>
                    </a>
                                
                    <a href="{{URL::abs('/')}}/travel/cruises/referral">
                      <div class="h3" itemprop="name">
                        7-night Western Caribbean Cruise from Miami (Roundtrip)
                      </div>
                    </a>
                    <p>Ports of Call: Miami, Florida - At Sea - Cozumel, Mexico - Belize City, Belize - Mahogany Bay, Honduras - Georgetown, Grand Cayman- At Sea - Miami, Florida</p>
                </div>
                <div class="col-md-4 visible-lg btn-col">
                    <button class="btn btn-default">Book for $600 <span class="glyphicon glyphicon-chevron-right"></span></button>
                </div>
            </div>
            <div class="hidden-lg">
                <button class="btn btn-default">Book for $600 <span class="glyphicon glyphicon-chevron-right"></span></button>
            </div>
        </div>
        <div class="item list coupon " itemscope="" itemtype="http://schema.org/Organization">
            <div class="row margin-bottom-10 margin-top-10">
                <a class="col-xs-5 col-sm-3" href="{{URL::abs('/')}}/travel/cruises/referral">
                    <div class="list-img">
                        <div class="expired-banner"><span class="h3 spaced">Expired</span></div>
                        <img alt="" class="img-responsive center-block" src="http://cruise.expedia.com/pictures/db/Ship/1416.jpg" itemprop="image">
                    </div>
                </a>
                <div class="col-xs-7 col-sm-5 col-lg-4 item-info">
                    <a href="{{URL::abs('/')}}/travel/cruises/referral">
                      <p class="merchant-name" itemprop="name">
                        2015 Departures: Mar: 22
                      </p>
                    </a>
                                
                    <a href="{{URL::abs('/')}}/travel/cruises/referral">
                      <div class="h3" itemprop="name">
                        7-night Eastern Caribbean Cruise from Port Canaveral to San Juan
                      </div>
                    </a>
                    <p>Ports of Call: Port Canaveral, Florida - At Sea - At Sea - St. Thomas, Virgin Islands - Basseterre, St. Kitts- St. Maarten, Netherlands Antilles - Tortola, British Virgin Islands - San Juan, Puerto Rico</p>
                </div>
                <div class="col-md-4 visible-lg btn-col">
                    <button class="btn btn-default">Book for $700 <span class="glyphicon glyphicon-chevron-right"></span></button>
                </div>
            </div>
            <div class="hidden-lg">
                <button class="btn btn-default">Book for $700 <span class="glyphicon glyphicon-chevron-right"></span></button>
            </div>
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