@extends('master.templates.master')

@section('city-banner')
    @include('soct.templates.soct-banner')
@stop

@section('page-title')
<h1>SaveOn Featured Dealers</h1>
@stop

@section('breadcrumbs')
    @include('soct.templates.breadcrumbs')
@stop

@section('sidebar')

@if(count($premium['objects']))
@include('master.templates.advertisement', array('advertisement' => $premium['objects'][0]))
@endif
@include('soct.templates.quote')
<div class="hidden-xs">
    @include('soct.templates.search-vehicles')
</div>
@include('soct.templates.makes')
@include('soct.templates.explore')
@include('soct.templates.questions')
@include('soct.templates.become-dealer')

@stop
@section('body')
<script>
    currentPage = 0;
</script>

<div class="visible-xs">
    @include('soct.templates.search-vehicles-mobile')
</div>

<script type="text/ejs" id="template_featured_dealers">
<% list(featured, function(featured){ %>
<div class="item list">
    <div class="margin-20">
        <div class="row">
            <div class="col-sm-5 col-md-4 col-lg-3" data-id="<%= featured.id %>">
                <a href="{{URL::abs('/')}}/coupons/auto-transportation/auto-dealers/<%= featured.merchant.slug %>"><img class="main-img img-responsive relative" src="<%= featured.display_image.path %>">
            </div>
            <div class="col-sm-7 col-md-5 col-md-5">
                <a class="item-info" href="{{URL::abs('/')}}/coupons/auto-transportation/auto-dealers/<%= featured.merchant.slug %>">
                    <h2><%= featured.merchant.name %></h2>
                    <p><%= featured.address %> <%= featured.address2 %><br>
                        <%= featured.city %>, <%= featured.state %> <%= featured.zip %></p>
                </a>
                <p><a href="{{URL::abs('/')}}/coupons/auto-transportation/auto-dealers/<%= featured.merchant.slug %>?writereview" class=""><span class="glyphicon glyphicon-pencil"></span> Write a Review</a></p>
            </div>
            <div class="col-sm-12 col-md-3 hidden-xs margin-bottom-10 dealer-buttons">
                <a type="button" class="btn btn-sm spaced btn-grey" href="{{URL::abs('/')}}/coupons/auto-transportation/auto-dealers/<%= featured.merchant.slug %>#newCars">New Cars</a>
                <a type="button" class="btn btn-sm spaced btn-grey" href="{{URL::abs('/')}}/coupons/auto-transportation/auto-dealers/<%= featured.merchant.slug %>#usedCars">Used Cars</a>
                <a type="button" class="btn btn-sm spaced btn-grey" href="{{URL::abs('/')}}/coupons/auto-transportation/auto-dealers/<%= featured.merchant.slug %>#autoServices">Specials</a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 visible-xs">
            <div class="btn-group">
                <a type="button" class="btn spaced btn-default" href="{{URL::abs('/')}}/coupons/auto-transportation/auto-dealers/<%= featured.merchant.slug %>#newCars">New Cars</a>
                <a type="button" class="btn spaced btn-default" href="{{URL::abs('/')}}/coupons/auto-transportation/auto-dealers/<%= featured.merchant.slug %>#usedCars">Used Cars</a>
                <a type="button" class="btn spaced btn-default" href="{{URL::abs('/')}}/coupons/auto-transportation/auto-dealers/<%= featured.merchant.slug %>#autoServices">Specials</a>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
<% }); %>
</script>

<div id="listView" class="featured-dealer list">
    @foreach($dealers as $dealer)
        @include('soct.templates.featured-dealers', array('featured'=>$dealer))
    @endforeach
</div>
@if(count($dealers))
<div class="">
    <button class="btn btn-block btn-lg btn-grey view-more center-block" data-loading-text="Loading...">View More</button>
</div>
@endif
@if(!count($dealers))
<div class="content-bg hidden no-results">
    <p class="spaced"><strong>Oh Fiddle Sticks, We Have Couldn't Find Any Featured Dealers...</strong></p>
    <p>Please check out some of our other <a href="{{URL::abs('/')}}/cars/auto-services">Service & Lease Specials</a>.</p>
</div>
@endif
@stop

