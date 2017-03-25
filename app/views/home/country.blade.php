@extends('master.templates.master')

@section('page-title')
<h1>Offers by Location</h1>
<?php $subheadDropdown = 'sort' ?>
@stop

@section('sidebar')
    <div class="panel panel-default">
        <div class="panel-heading">
          <span class="panel-title h4 hblock">
            <a data-toggle="collapse" href="#collapseOne">Explore {{!isset($type) || $type == 'coupon' ? 'Coupons' : ($type == 'dailydeal' ? 'Daily Deals' : 'Contests')}} <span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span></a>
            <div class="clearfix"></div>
          </span>
        </div>
        <div id="collapseOne" class="panel-collapse">
            <div class="panel-body explore-links">
                <ul>
                    @include('master.templates.explore', array('active' => $active, 'type' => 'coupon'))
                </ul>
            </div>
        </div>
    </div>
    @if(!empty($category) && $type == 'coupon')
    <div class="panel panel-default">
        <div class="panel-heading">
          <span class="panel-title h4 hblock">
            <a data-toggle="collapse" href="#collapseTwo" class="collapsed">About {{ empty($category) ? '' : $category->name.' '}}<span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span></a>
            <div class="clearfix"></div>
          </span>
        </div>
        <div id="collapseTwo" class="panel-collapse collapse">
            <div class="panel-body explore-links">
                <div class="category-header">
                    <p>{{$category->above_heading}}</p>
                </div> 
            </div>
        </div>
    </div>
    @endif
@stop

@section('body')

    <div class="clearfix"></div>

    <div class="merchant-results-holder">
        <img class="logo img-responsive coupon-path" src="http://s3.amazonaws.com/saveoneverything_assets/city_images/world.jpg" alt="Save on Everything" class="img-responsive" id="">
        <hr class="dark">
        <p class="spaced margin-bottom-20"><strong>Choose Your Country</strong></p>
        <div id="country-text"><p>Mike's Marketshare Coupons, the original name of SaveOn, was started by head inventor Michael Gauthier in the Spring of 1984. The publication was deemed Marketplace Magazine, which has a circulation of 80,000 households in '87. Boy, we've come along way since that day in May. In February 2014 Marketplace Magazine evolved into the ever growing brand that we all know and love,  SaveOn. As the name implies we work to help you save money in all arenas of life! We have since expanded with 3 additional brands: Save on Cars &amp; Trucks, Save on Groceries, and Save on Travel SaveOn<sup>&reg;</sup> now services 3 million homes between Detroit Metro, Chicago, and Minneapolis. It is with great joy and pleasure that we diligently work to improve the lives of all those we come into contact with.</p></div>
        <hr class="dark">
        <div class="country-result">
            <ul>
                <li><a href="{{URL::abs('/')}}/country/usa">United States</a></li>
            </ul>
        </div>
        
    </div>
    <div class="clearfix"></div>
@stop