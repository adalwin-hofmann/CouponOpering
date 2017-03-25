@extends('master.templates.master')

@section('city-banner')
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
                <button type="button" class="btn btn-md btn-block btn-cyan">Find Deals <span class="glyphicon glyphicon-chevron-right pull-right"></span></button>
            </div>
        </div>
    </form>
</div>
@stop

@section('page-title')
<h1>Save On Travel <small>to Las Vegas</small></h1>
@stop

@section('subheader-content')
    <!-- Insert New Subheader Content Block Here -->
    <p>Set subheader text here.</p>
@stop

@section('breadcrumbs')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}" itemprop="url"><span itemprop="title">Home</span></a>
    </li>
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/travel')}}" itemprop="url"><span itemprop="title">Travel</span></a>
    </li>
    <li class="active">Las Vegas</li>
@stop

@section('sidebar')

<div class="panel panel-default">
    <div class="panel-heading">
      <span class="h4 panel-title hblock">
        <a data-toggle="collapse" href="#collapseTwo">About Last Vegas<span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span></a>
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

    <div id="calendar_container" class="calendar-container checkin margin-bottom-20 content-bg">
        <div id="" class="margin-bottom-10">
            <button class="calendar_checkin_date calendar_date_button btn btn-cyan btn_so btn_ds btn_ckin selected">
                <span class="date_label">Check-in</span>
                <span class="date_string">Friday, 08/29/14</span>
            </button>
            <button class="calendar_checkout_date calendar_date_button btn btn-white btn_so btn_ds btn_ckout">
                <span class="date_label">Check-out</span>
                <span class="date_string">Monday, 09/01/14</span>
            </button>
            <span class="img_sprite_moon close_calendar close_icon_black hidden_phone trv_close img_sprite_alignment"><!-- --></span>
        </div>
        <div id="js_dealform_calendar" class="calendar cf disabled checkin">
            <div class="month_nav prev disabled">
                <span class="img_sprite_moon prev img_sprite_alignment calendar_prev"></span>
            </div>
            <div id="js_calendar_inner_wrapper" class="calendar_inner_wrapper">
                <div id="js_calendar_inner_slide_wrapper" class="calendar_inner_slide_wrapper row">
                    <div class="calendar_month first col-xs-6">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th colspan="7" class="month_name">August 2014</th>
                                </tr>
                                <tr>
                                    <th><span class="week_day" title="Sunday">Sun</span></th>
                                    <th><span class="week_day" title="Monday">Mon</span></th>
                                    <th><span class="week_day" title="Tuesday">Tue</span></th>
                                    <th><span class="week_day" title="Wednesday">Wed</span></th>
                                    <th><span class="week_day" title="Thursday">Thu</span></th>
                                    <th><span class="week_day" title="Friday">Fri</span></th>
                                    <th><span class="week_day" title="Saturday">Sat</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="calendar_day unselectable other_month" data-date="2014-07-27"><span>27</span></td>
                                    <td class="calendar_day unselectable other_month" data-date="2014-07-28"><span>28</span></td>
                                    <td class="calendar_day unselectable other_month" data-date="2014-07-29"><span>29</span></td>
                                    <td class="calendar_day unselectable other_month" data-date="2014-07-30"><span>30</span></td>
                                    <td class="calendar_day unselectable other_month" data-date="2014-07-31"><span>31</span></td>
                                    <td class="calendar_day unselectable" data-date="2014-08-01"><span>1</span></td>
                                    <td class="calendar_day unselectable weekend" data-date="2014-08-02"><span>2</span></td>
                                </tr><tr>
                                    <td class="calendar_day unselectable weekend" data-date="2014-08-03"><span>3</span></td>
                                    <td class="calendar_day unselectable" data-date="2014-08-04"><span>4</span></td>
                                    <td class="calendar_day unselectable" data-date="2014-08-05"><span>5</span></td>
                                    <td class="calendar_day unselectable" data-date="2014-08-06"><span>6</span></td>
                                    <td class="calendar_day unselectable" data-date="2014-08-07"><span>7</span></td>
                                    <td class="calendar_day unselectable" data-date="2014-08-08"><span>8</span></td>
                                    <td class="calendar_day unselectable weekend" data-date="2014-08-09"><span>9</span></td>
                                </tr><tr>
                                    <td class="calendar_day unselectable weekend" data-date="2014-08-10"><span>10</span></td>
                                    <td class="calendar_day unselectable" data-date="2014-08-11"><span>11</span></td>
                                    <td class="calendar_day unselectable" data-date="2014-08-12"><span>12</span></td>
                                    <td class="calendar_day unselectable" data-date="2014-08-13"><span>13</span></td>
                                    <td class="calendar_day unselectable" data-date="2014-08-14"><span>14</span></td>
                                    <td class="calendar_day unselectable" data-date="2014-08-15"><span>15</span></td>
                                    <td class="calendar_day unselectable weekend" data-date="2014-08-16"><span>16</span></td>
                                </tr><tr>
                                    <td class="calendar_day unselectable weekend" data-date="2014-08-17"><span>17</span></td>
                                    <td class="calendar_day unselectable" data-date="2014-08-18"><span>18</span></td>
                                    <td class="calendar_day " data-date="2014-08-19"><a>19</a></td>
                                    <td class="calendar_day " data-date="2014-08-20"><a>20</a></td>
                                    <td class="calendar_day " data-date="2014-08-21"><a>21</a></td>
                                    <td class="calendar_day " data-date="2014-08-22"><a>22</a></td>
                                    <td class="calendar_day weekend" data-date="2014-08-23"><a>23</a></td>
                                </tr><tr>
                                    <td class="calendar_day weekend" data-date="2014-08-24"><a>24</a></td>
                                    <td class="calendar_day " data-date="2014-08-25"><a>25</a></td>
                                    <td class="calendar_day " data-date="2014-08-26"><a>26</a></td>
                                    <td class="calendar_day " data-date="2014-08-27"><a>27</a></td>
                                    <td class="calendar_day " data-date="2014-08-28"><a>28</a></td>
                                    <td class="calendar_day range start" data-date="2014-08-29"><a>29</a></td>
                                    <td class="calendar_day range weekend" data-date="2014-08-30"><a>30</a></td>
                                </tr><tr>
                                    <td class="calendar_day range weekend" data-date="2014-08-31"><a>31</a></td>
                                    <td class="calendar_day unselectable other_month" data-date="2014-09-01"><span>1</span></td>
                                    <td class="calendar_day unselectable other_month" data-date="2014-09-02"><span>2</span></td>
                                    <td class="calendar_day unselectable other_month" data-date="2014-09-03"><span>3</span></td>
                                    <td class="calendar_day unselectable other_month" data-date="2014-09-04"><span>4</span></td>
                                    <td class="calendar_day unselectable other_month" data-date="2014-09-05"><span>5</span></td>
                                    <td class="calendar_day unselectable other_month" data-date="2014-09-06"><span>6</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="calendar_month last col-xs-6">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th colspan="7" class="month_name">September 2014</th>
                                </tr><tr>
                                    <th><span class="week_day" title="Sunday">Sun</span></th>
                                    <th><span class="week_day" title="Monday">Mon</span></th>
                                    <th><span class="week_day" title="Tuesday">Tue</span></th>
                                    <th><span class="week_day" title="Wednesday">Wed</span></th>
                                    <th><span class="week_day" title="Thursday">Thu</span></th>
                                    <th><span class="week_day" title="Friday">Fri</span></th>
                                    <th><span class="week_day" title="Saturday">Sat</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="calendar_day unselectable other_month" data-date="2014-08-31"><span>31</span></td>
                                    <td class="calendar_day range end" data-date="2014-09-01"><a>1</a></td>
                                    <td class="calendar_day " data-date="2014-09-02"><a>2</a></td>
                                    <td class="calendar_day " data-date="2014-09-03"><a>3</a></td>
                                    <td class="calendar_day " data-date="2014-09-04"><a>4</a></td>
                                    <td class="calendar_day " data-date="2014-09-05"><a>5</a></td>
                                    <td class="calendar_day weekend" data-date="2014-09-06"><a>6</a></td>
                                </tr><tr>
                                    <td class="calendar_day weekend" data-date="2014-09-07"><a>7</a></td>
                                    <td class="calendar_day " data-date="2014-09-08"><a>8</a></td>
                                    <td class="calendar_day " data-date="2014-09-09"><a>9</a></td>
                                    <td class="calendar_day " data-date="2014-09-10"><a>10</a></td>
                                    <td class="calendar_day " data-date="2014-09-11"><a>11</a></td>
                                    <td class="calendar_day " data-date="2014-09-12"><a>12</a></td>
                                    <td class="calendar_day weekend" data-date="2014-09-13"><a>13</a></td>
                                </tr><tr>
                                    <td class="calendar_day weekend" data-date="2014-09-14"><a>14</a></td>
                                    <td class="calendar_day " data-date="2014-09-15"><a>15</a></td>
                                    <td class="calendar_day " data-date="2014-09-16"><a>16</a></td>
                                    <td class="calendar_day " data-date="2014-09-17"><a>17</a></td>
                                    <td class="calendar_day " data-date="2014-09-18"><a>18</a></td>
                                    <td class="calendar_day " data-date="2014-09-19"><a>19</a></td>
                                    <td class="calendar_day weekend" data-date="2014-09-20"><a>20</a></td>
                                </tr><tr>
                                    <td class="calendar_day weekend" data-date="2014-09-21"><a>21</a></td>
                                    <td class="calendar_day " data-date="2014-09-22"><a>22</a></td>
                                    <td class="calendar_day " data-date="2014-09-23"><a>23</a></td>
                                    <td class="calendar_day " data-date="2014-09-24"><a>24</a></td>
                                    <td class="calendar_day " data-date="2014-09-25"><a>25</a></td>
                                    <td class="calendar_day " data-date="2014-09-26"><a>26</a></td>
                                    <td class="calendar_day weekend" data-date="2014-09-27"><a>27</a></td>
                                </tr><tr>
                                    <td class="calendar_day weekend" data-date="2014-09-28"><a>28</a></td>
                                    <td class="calendar_day " data-date="2014-09-29"><a>29</a></td>
                                    <td class="calendar_day " data-date="2014-09-30"><a>30</a></td>
                                    <td class="calendar_day unselectable other_month" data-date="2014-10-01"><span>1</span></td>
                                    <td class="calendar_day unselectable other_month" data-date="2014-10-02"><span>2</span></td>
                                    <td class="calendar_day unselectable other_month" data-date="2014-10-03"><span>3</span></td>
                                    <td class="calendar_day unselectable other_month" data-date="2014-10-04"><span>4</span></td>
                                </tr><tr>
                                    <td class="calendar_day unselectable other_month" data-date="2014-10-05"><span>5</span></td>
                                    <td class="calendar_day unselectable other_month" data-date="2014-10-06"><span>6</span></td>
                                    <td class="calendar_day unselectable other_month" data-date="2014-10-07"><span>7</span></td>
                                    <td class="calendar_day unselectable other_month" data-date="2014-10-08"><span>8</span></td>
                                    <td class="calendar_day unselectable other_month" data-date="2014-10-09"><span>9</span></td>
                                    <td class="calendar_day unselectable other_month" data-date="2014-10-10"><span>10</span></td>
                                    <td class="calendar_day unselectable other_month" data-date="2014-10-11"><span>11</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="month_nav next">
                <span class="img_sprite_moon next img_sprite_alignment calendar_next"></span>
            </div>
        </div>
        <div id="calendar_roomtype_container" class="roomtypes cf btn-group">
            <div class="calendar_room_type_button roomtype_single btn btn_so btn_rt btn-cyan" data-roomtype="1">
                <span class="active"><!-- --></span>
                <div class="img_sprite_moon calendar_single_button img_sprite_alignment"></div>
                <span class="room_type_name">Single Room</span>
            </div>
            <div class="calendar_room_type_button roomtype_double btn btn_so btn_rt selected btn-white" data-roomtype="7">
                <span class="active"><!-- --></span>
                <div class="img_sprite_moon calendar_double_button img_sprite_alignment"></div>
                <span class="room_type_name">Double Room</span>
            </div>
            <div class="calendar_room_type_button roomtype_kids hidden_phone btn btn_so btn_rt btn-cyan" data-roomtype="9">
                <span class="active"><!-- --></span>
                <div class="img_sprite_moon calendar_kids_button img_sprite_alignment"></div>
                <span class="room_type_name">Children/ Groups</span>
            </div>
        </div>
    </div>

<div class="row">
    <div class="col-sm-5 col-md-4 margin-bottom-10 view-change">
        <a type="button" class="btn btn-large spaced btn-white" href="#listView" data-toggle="tab"><span class="glyphicon glyphicon-th-list"></span> List</a>
        <a type="button" class="btn btn-large spaced btn-green" href="#container" data-toggle="tab"><span class="glyphicon glyphicon-th"></span> Grid</a>
    </div>
</div>

<div class="tab-content">
    <div id="listView" class="tab-pane">
        <div class="item list travel">
            <div class="row">
                <div class="col-xs-5 col-md-4 col-lg-3">
                    <img alt="" class="main-img img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sot/sample1.png">
                </div>
                <div class="item-info col-xs-7 col-md-8 col-lg-9">
                    <span class="h3">Grand Palladium Bavaro Suites</span>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="destination-name"><span class="glyphicon glyphicon-map-marker"></span> <span class="destination">Sandusky, OH</span></p>
                        </div>
                        <div class="col-md-6">
                            <span class="h4 price"><span class="old-price">$288</span> From $749</span>
                        </div>
                    </div>
                </div>
                <div class="col-xs-7 col-md-8 col-lg-9 hidden-xs margin-bottom-10">
                    <a type="button" href="#" class="btn btn-default btn-get-coupon" data-offer_id="197558" data-entity_id="3323367"><img src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sot/masonry-icons/view_it_travel.png" alt="Get It" class="img-circle"><br>Get It</a>
                    <button type="button" class="btn btn-default btn-save-coupon " data-offer_id="197558" data-entity_id="3323367"><img src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sot/masonry-icons/save_it_travel.png" alt="Save It" class="img-circle"><br><span class="save-coupon-text">Save It</span></button>
                    <button type="button" class="btn btn-default btn-coupon-share" data-offer_id="197558" data-entity_id="3323367" style=""><img src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sot/masonry-icons/share_it_travel.png" alt="Share It" class="img-circle"><br>Share It</button>
                </div>
                <div class="col-xs-12 visible-xs">
                    <div class="btn-group">
                        <a type="button" href="#" class="btn btn-default btn-get-coupon" data-offer_id="197558" data-entity_id="3323367"><img src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sot/masonry-icons/view_it_travel.png" alt="Get It" class="img-circle"><br>Get It</a>
                        <button type="button" class="btn btn-default btn-save-coupon " data-offer_id="197558" data-entity_id="3323367"><img src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sot/masonry-icons/save_it_travel.png" alt="Save It" class="img-circle"><br><span class="save-coupon-text">Save It</span></button>
                        <button type="button" class="btn btn-default btn-coupon-share" data-offer_id="197558" data-entity_id="3323367" style=""><img src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sot/masonry-icons/share_it_travel.png" alt="Share It" class="img-circle"><br>Share It</button>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <div id="container" class="tab-pane active js-masonry soct-masonry" style="position: relative;">
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
                <!--<span class="h3" itemprop="name">All-Inclusive Punta Cana Vacation with Airfare</span>-->
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
                <!--<span class="h3" itemprop="name">Kid-Friendly Ohio Resort with Indoor Water Park</span>-->
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
                <!--<span class="h3" itemprop="name">Charming Northern Michigan Inn near Lakes</span>-->
                <span class="h4 price"><span class="old-price">$169</span> From $99</span>
                <!--<p class="expires_at" itemprop="availabilityEnds"><strong>Expires</strong> 08/31/2014</p>
                <span itemprop="price" class="hidden">0</span>-->
              </div>
            </a>
            <div class="btn-group">
                <button type="button" class="btn btn-default btn-get-coupon" data-offer_id="197558" data-entity_id="3323367"><img src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sot/masonry-icons/view_it_travel.png" alt="Get It" class="img-circle"><br>Get It</button>
                <button type="button" class="btn btn-default btn-save-coupon " data-offer_id="197558" data-entity_id="3323367"><img src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sot/masonry-icons/save_it_travel.png" alt="Save It" class="img-circle"><br><span class="save-coupon-text">Save It</span></button>
                <button type="button" class="btn btn-default btn-coupon-share" data-offer_id="197558" data-entity_id="3323367" style=""><img src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sot/masonry-icons/share_it_travel.png" alt="Share It" class="img-circle"><br>Share It</button>
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