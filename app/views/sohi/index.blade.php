@extends('master.templates.master')
@section('city-banner')
 
<div class="hidden-xs city-banner sohi banner-menu sofhbg">
    <div class="city-banner-img banner-shadow" style="background-image: url(http://s3.amazonaws.com/saveoneverything_assets/assets/images/sohi/sofh_banner_{{rand(1,5)}}.jpg)">
        <!-- <div class="fade-left"></div>
        <div class="fade-right"></div>
        <div id="certified_logo">
            <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sohi/save_certified.png">
        </div> -->
        <div id="sofh_banner_title">
           <h1 class="sofhtitle dropshadow"><span style="font-size: 50px;">SaveOn</span> <br>
           <strong>For The Home</strong></h1>
            <p class="h2 dropshadow">&nbsp;</p>
        </div>
        <!-- <div class="btn-row text-center">
          <a href="{{URL::abs('/')}}/homeimprovement/quote" class="btn btn-red {{$generic_quote ? '' : 'hidden'}}">Get a Quote</a>
        </div> -->
    </div>
</div>
 
@stop
 
@section('page-title')
<h1 style="margin-left: 20px;">Find Everything For Your Home <br class="visible-xs"><small>in {{ucwords(strtolower($geoip->city_name))}}, {{$geoip->region_name}}</small></h1>
<button class="btn btn-green btn-sm search-nearby-modal" data-toggle="modal" data-target="#changeLocationModal"><span class="glyphicon glyphicon-map-marker"></span> <img alt="Edit Location" src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/edit-location-text.png"> <span class="glyphicon glyphicon-chevron-right"></span></button>
@stop
 
@section('breadcrumbs')
    <li><a href="{{URL::abs('/')}}/homeimprovement">Home</a></li>
    <li class="active">Home Improvement</li>
@stop
 
@section('full-section')
@if($quote_control)
<div class="panel panel-default margin-top-20 how-it-works">
    <div class="panel-heading"><span class="h4 hblock panel-title">
      <!-- <a data-toggle="collapse" href="#collapseHowItWorks">How It Works<span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span></a> -->
    <div class="clearfix"></div>
      </span>
    </div>
    <div id="collapseHowItWorks" class="panel-collapse collapse in">
        <div class="panel-body explore-links">    
          <div class="row">
            <div class="col-sm-1">
            </div>
                <div class="col-sm-4">
<div class="row" style="padding-top: 35px;">
    <p class="h1 text-center"><span class="h1 sofh text-center">What Are You Looking For?</span></p>
    <p>&nbsp;</p>
    <select class="form-control" id="category" onchange="Category();">
        <option value="">Select a Category</option>
        <!--<option value="{{URL::abs('/')}}/homeimprovement/coupons">All</option>-->
        <?php $parents = $tagRepo->getParentTags(); ?>
        @foreach($parents['objects'] as $parent)
            <?php $children = $tagRepo->getChildren($parent); ?>
                @foreach($children['objects'] as $child)
                <option value="{{URL::abs('/')}}/homeimprovement/coupons/{{$parent->slug}}/{{$child->slug}}">
                    {{$child->name}}
                </option>
                @endforeach
        @endforeach
    </select>
    <!-- <strong><a href="{{URL::abs('/')}}/homeimprovement/get-certified">Become a Merchant</a></strong> -->
</div>
                </div>
                <div class="col-sm-1">
                </div>
            <div class="col-sm-6">
                    <span class="h2">SaveOn Guarantee!</span>
                    <p>We guarantee that any "Save Certified" contractor has gone through our 5 point inspection.</p><br>
                    <div class="row">
                        <div class="col-sm-8">
                            <ul class="check_list">
                                All companies must be:
                                <li><strong>Licensed</strong> - Not every industry requires a license</li>
                                <li><strong>Bonded</strong> - When needed</li>
                                <li><strong>Insured</strong> - Workman's Comp / Company Liability</li>
                                <li><strong>Background checks of workers, by the company</strong></li>
                                <li><strong>Better Business Bureau</strong></li>
                            </ul>
                        </div>
                        <div class="col-sm-4">
                            <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sohi/save_certified.png">
                        </div>
                        <div class="col-sm-12 center-text">
                            <a href="{{URL::abs('/')}}/homeimprovement/get-certified" class="btn btn-green">BECOME A MERCHANT - GET SAVE CERTIFIED! <span class="glyphicon glyphicon-chevron-right"></span></a>
                        </div>
                    </div>
                    <!-- <p>Also: Every job will be reviewed when completed for job satisfaction. Companies that don't live up to our Save Satisfaction will be removed from our referral program.</p> -->
                </div>
            </div> 
            <hr style="margin-left:-15px; margin-right:-15px;">
            <div class="row">
                <div class="col-sm-1"></div>
                <div class="col-sm-5" style="padding: 15px;">
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/IvF-dbWU3rs?rel=0" frameborder="0" allowfullscreen></iframe>
                    </div>
                </div>
        
                <div class="col-sm-5" style="padding: 15px;">
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/cM_V2jCnoCQ?rel=0" frameborder="0" allowfullscreen></iframe>
                    </div>
                </div>
                <div class="col-sm-1">
                </div>
            </div>
            <div class="row">
            <div class="col-sm-1">
                </div>
            <div class="col-sm-7" style="padding: 25px 25px 0px 0px;">
            <a href="http://www.saveon.com/coupons/mi/detroit/home-improvement/air-conditioning-heating/air-conditioning-engineers/3979" ><img class="img-responsive img-rounded" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sohi/SOFH_weboffer_left.png"></a>
            </div>
             <div class="col-sm-3" style="padding: 35px 0px 25px 0px;">
            <a href="http://www.saveon.com/coupons/mi/detroit/home-improvement/waterproofing/ayers-basement-systems-in-west-michigan/112264" ><img class="img-responsive img-rounded" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sohi/SOFH_weboffer_right.png"></a>
            </div>
            <div class="col-sm-1">
                </div>
          </div>    
        </div>
     </div>
</div>
@endif
@stop
 
<!-- @if($quote_control)<a id="tour-quote" href="{{URL::abs('/')}}/homeimprovement/quote" class="tour-quote sidebar-panel {{$generic_quote ? '' : 'hidden'}}"><img class="img-responsive margin-bottom-20" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sohi/get_a_quote.jpg"></a>@endif -->

<!-- <a id="tour-quote" href="{{URL::abs('/')}}/homeimprovement/get-certified" class="sidebar-panel"><img class="img-responsive margin-bottom-20" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sohi/get_save_certified.jpg"></a> -->
 
<!-- <div class="content-bg margin-bottom-20 callout-box callout-box-green sidebar-panel">
    <span class="h1 fancy"><a data-toggle="modal" data-target="#youtubeModal"><img class="img-responsive center-block" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sohi/save_certified.png"></a></span>
    <div class="row">
        <a data-toggle="modal" data-target="#youtubeModal">
            As Seen on TV <span class="glyphicon glyphicon-chevron-right pull-right"></span>
        </a>
    </div>
</div> -->
 
<!-- <div class="clearfix"></div>
<a class="item contest" href="{{URL::abs('/')}}/contests">
    <div class="top-pic">
        <div class="item-type contest"></div>
        <img alt="Car Give Away" class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/images/free_car_contest_tile2.jpg">
        <button class="btn btn-default btn-block btn-get-contest">CLICK HERE TO ENTER</button>
    </div>
</a> -->
<div class="clearfix"></div>
<!-- <div class="panel panel-default">
    <div class="panel-heading">
      <span class="h4 hblock panel-title">
        <a data-toggle="collapse" href="#collapseThree">Got Questions?<span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span></a>
        <div class="clearfix"></div>
      </span>
    </div>
    <div id="collapseThree" class="panel-collapse collapse in">
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
                    <a href="#">Send Feedback</a>
                </div>
            </div>
        </div>
    </div>
</div> -->
@section('body')
<script>
    category_id = '231';
    type = 'coupon';
    page = 0;
    <?php
      if (!Session::has('newHomeUser') && !stristr(Request::url(), 'printable')) {
        Session::put('newHomeUser', 1);
    ?>
        newHomeUser = {{Session::get('newHomeUser')}};
    <?php } else { ?>
        newHomeUser = 0;
    <?php } ?>
</script>
 
<script type="text/ejs" id="template_banner_offer">
<% if (bannerOffer.entitiable_type == 'Offer') { %>
    <% if (bannerOffer.is_dailydeal == '1') { %>
    <% } else { %>
        <div class="item coupon <%= bannerOffer.is_certified == 1 ? 'save_certified_offer' : '' %>">
            <div class="item-type featured"></div>
            <div class="row">
                <div class="col-md-4 col-sm-6 logo">
                    <a href="{{URL::abs('/coupons')}}/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= bannerOffer.category_slug %>/<%= bannerOffer.subcategory_slug %>/<%= bannerOffer.merchant_slug+'/'+bannerOffer.location_id %>">
                        <img alt="<%= bannerOffer.name %>" class="img-responsive center-block" src="<%= bannerOffer.logo %>">
                    </a>
                </div>
                <div class="col-md-8 col-sm-6">
                    <a class="item-info" href="{{URL::abs('/coupons')}}/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= bannerOffer.category_slug %>/<%= bannerOffer.subcategory_slug %>/<%= bannerOffer.merchant_slug+'/'+bannerOffer.location_id %>">
                        <p class="merchant-name"><%= bannerOffer.merchant_name %></p>
                        <% if (bannerOffer.offer_count > 0) { %>
                        <p class="offer-count"><strong><%= bannerOffer.offer_count %></strong> more offer<%== (bannerOffer.offer_count > 1)?"s":""%>&nbsp;</p>
                        <% } %>
                        <div class="h2 hblock"><%= bannerOffer.name %></div>
                    </a>
                    <div class="btn-group">
                        <button type="button" class="btn btn-default btn-get-coupon" data-offer_id="<%= bannerOffer.entitiable_id %>" data-entity_id="<%= bannerOffer.id %>"><img src="http://s3.amazonaws.com/saveoneverything_assets/images/masonry-icons/get_it_coupon.png" alt="Get It" class="img-circle"><br>Get It</button>
                        <button type="button" class="btn btn-default btn-save-coupon" data-offer_id="<%= bannerOffer.entitiable_id %>" data-entity_id="<%= bannerOffer.id %>"><img src="http://s3.amazonaws.com/saveoneverything_assets/images/masonry-icons/save_it_coupon.png" alt="Save It" class="img-circle"><br><span class="save-coupon-text"><%= bannerOffer.is_clipped == true ? 'Saved!' : 'Save It' %></span></button>
                        <button type="button" class="btn btn-default btn-coupon-share" data-offer_id="<%= bannerOffer.entitiable_id %>" data-entity_id="<%= bannerOffer.id %>" @if($quote_control)style="<%= (bannerOffer.is_certified || bannerOffer.is_sohi_trial) ? 'display:none;' : '' %>"@endif><img src="http://s3.amazonaws.com/saveoneverything_assets/images/masonry-icons/share_it_coupon.png" alt="Share It" class="img-circle"><br>Share It</button>
                        @if($quote_control)<a href="{{URL::abs('/homeimprovement/quote')}}?offer_id=<%= bannerOffer.entitiable_id %>" type="button" class="btn btn-default btn-coupon-quote" data-offer_id="<%= bannerOffer.entitiable_id %>" data-entity_id="<%= bannerOffer.id %>" style="<%= (bannerOffer.is_certified || bannerOffer.is_sohi_trial) ? '' : 'display:none;' %>"><img src="http://s3.amazonaws.com/saveoneverything_assets/images/masonry-icons/quote_it_coupon.png" alt="Quote It" class="img-circle"><br>Quote It</a>@endif
                    </div>
                </div>
            </div>
        </div>
    <% } %>
<% } else if (bannerOffer.entitiable_type == 'Contest') { %>
    <div class="item contest featured">
        <a class="btn-get-contest" data-entity_id="{{empty($win5k) ? 0 : $win5k->id}}">
            <div class="row">
                <div class="item-type contest"></div>
                <div class="col-md-4 hidden-xs hidden-sm">
                    <a href="{{URL::abs('/')}}/contests/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/all?showeid={{empty($win5k) ? 0 : $win5k->id}}"><img alt="Win $5k" class="img-responsive" src="/img/5k_img.jpg"></a>
                </div>
                <div class="col-md-8">
                    <div class="col-xs-12 text-center">
                        <span class="h3 hblock spaced">Featured Contest</span>
                        <span class="fancy text-center h2 hblock">You Could Win &#36;5,000</span>   
                    </div>
                    <div class="clearfix "></div>
                        <button type="button" class="margin-top-20 btn btn-default btn-black btn-get-contest" data-entity_id="{{empty($win5k) ? 0 : $win5k->id}}">Check The Winning Numbers</button>
                    </div>
                </div>
                 
            </div>
        </a>
    </div>
<% } %>
</script>
<!--<div class="banner-offer">
 
    </div>-->
 
    <div class="clearfix"></div>
 
    @if($sohi_markets)
    <div id="container" class="js-masonry offer-results-grid">
        @foreach($entities as $entity)
            @include('master.templates.entity', array('entity'=>$entity))
        @endforeach
    </div>
    @endif
 
    <div class="clearfix"></div>
    @if(!empty($category) && $type == 'coupon')
    <div class="category-footer"><p>{{$category->footer_heading}}</p></div>
    @endif
    @if(count($entities))
    <div class="">
        <a href="{{URL::abs('/coupons/'.strtolower($geoip->region_name).'/'.SoeHelper::getSlug($geoip->city_name).'/home-improvement')}}" class="btn btn-block btn-lg btn-grey view-more center-block" data-loading-text="Loading...">View More</a>
    </div>
    @endif
 
@if($sohi_markets)
<div class="content-bg default-no-results" style="{{(!$entities)?'display:block':''}}">
        <p class="spaced"><strong>Sorry, We Have No Home Improvement Offers In Your Area!</strong></p>
        <p>Try one of our affiliated sites for deals near you.</p>
        <div class="row">
            <div class="col-sm-4">
                <a href="{{URL::abs('/')}}/groceries" target="_blank">
                    <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/images/groceries_ad.jpg">
                </a>
                <br>
            </div>
            <div class="col-sm-4">
                <a href="{{URL::abs('/')}}/homeimprovement" target="_blank">
                    <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/images/sohi_ad.jpg">
                </a>
                <br>
            </div>
            <div class="col-sm-4">
                <a href="http://saveoncarsandtrucks.com" target="_blank">
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
                        <label>City (required)</label>
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
</div>
@else
<div class="content-bg margin-bottom-20">
    <p class="spaced"><strong>Sorry, We Have No Home Improvement Offers In Your Area!</strong></p>
    <p>Sadly, SaveOn Home Improvement isn't in your area yet. We'll let you know once it does.</p>
    @if(!Auth::check())
    <div class="input-group">
        <input type="text" class="form-control newsletter-input" placeholder="Email">
        <div class="input-group-btn">
            <button class="btn btn-green newsletter-btn" type="button">Sign Up</button>
        </div>
    </div>
    @endif
    @if(count($relatedEntities))
    <p class="margin-top-10">Check out some of these other great offers.</p>
    @endif
</div>
    <div id="related-container" class="js-masonry offer-results-grid">
        @foreach($relatedEntities as $entity)
            @include('master.templates.entity', array('entity'=>$entity))
        @endforeach
    </div>   
@endif
 
<!-- <div class="modal fade" id="youtubeModal" tabindex="-1" role="dialog" aria-labelledby="youtubeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <span class="h4 hblock modal-title" id="youtubeModalLabel">SaveOn<sup>&reg;</sup> Home Improvement<sup>sm</sup></span>
      </div>
      <div class="modal-body">
       <iframe width="560" height="315" src="//www.youtube.com/embed/j-jTT3NdeYQ" frameborder="0" allowfullscreen></iframe>
      </div>
    </div>
  </div>
</div> -->
<div class="row">
      <div class="col-sm-6">
        <img class="center-block img-responsive bottomimage" src="https://s3.amazonaws.com/saveoneverything_assets/assets/images/sohi/SOFH_Ad_Left.jpg" />    
      </div>
  <div class="col-sm-6">
        <img class="center-block img-responsive bottomimage" src="https://s3.amazonaws.com/saveoneverything_assets/assets/images/sohi/SOFH_Ad_Right.jpg" />
      </div>
</div>
 
@stop