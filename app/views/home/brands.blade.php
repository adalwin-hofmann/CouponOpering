@extends('master.templates.master')
@section('page-title')
<h1>Our Brands</h1>
@stop

@section('breadcrumbs')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}" itemprop="url"><span itemprop="title">Home</span></a>
    </li>
    <li class="active">Our Brands</li>
@stop

@section('sidebar')
 @include('master.templates.corporatesidebar')
@stop
@section('body')
<div class="content-bg">
    <div class="row">
        <div class="col-sm-4 col-md-3">
            <img class = "img-responsive" alt= "" src="http://s3.amazonaws.com/saveoneverything_assets/images/corporate/logo/save-on-logo.jpg"/>
        </div>
        <div class="col-sm-8 col-md-9">
            <h2>SaveOn<sup>&reg;</sup></h2>
            <span class="h5 spaced hblock">Helping People Save For Over 30 Years</span>
            <p>We are a comprehensive digital marketing and direct mail company specializing in providing solutions through a variety of products including: Web &amp; mobile phone applications, direct mail, inserts, and DAL cards.
            Our direct mail magazine, SaveOn, is distributed monthly to over 3million homes in Detroit, Chicago, and Minneapolis/St. Paul.
            As a trusted resource, we put your business in the hands of customers looking to SaveOn<sup>&reg;</sup> what they value most: Time and money. </p>
        </div> 
    </div>
    <hr>
    <div class="row">
        <div class="col-sm-4 col-md-3">
            <img class = "img-responsive" alt= "" src="http://s3.amazonaws.com/saveoneverything_assets/images/corporate/logo/cars-and-trucks.jpg"/>
        </div>
        <div class="col-sm-8 col-md-9">
            <h2>SaveOn Cars &amp; Trucks<sup>&reg;</sup></h2>
                <span class="h5 spaced hblock">Hundreds Of New &amp; Used Cars &amp; Trucks</span>
                <p>We currently mail to over 1 million homes each month in multiple markets, SaveOn Cars &amp; Trucks<sup>&reg;</sup> is the leading monthly publication for consumers to find the best deals on wheels delivered right to their mailbox. As the premier direct mail piece in the marketplace, SaveOn Cars &amp; Trucks<sup>&reg;</sup> provides consumers with the information they need to make cost effective car buying decisions. Additionally, our website www.saveoncarsandtrucks.com provides online car shoppers can find information including pricing, reviews, photo galleries, and a large selection of new and used car inventory. </p>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-sm-4 col-md-3">
            <img class = "img-responsive" alt= "" src="http://s3.amazonaws.com/saveoneverything_assets/images/corporate/logo/home-improvement.jpg"/>
        </div>
        <div class="col-sm-8 col-md-9">
            <h2>SaveOn<sup>&reg;</sup> Home Improvement<sup>sm</sup></h2>
            <span class="h5 spaced hblock">Fall In Love With Your Contractor</span>
            <p>Let us "fix you up" with one of our Save Certified&#8482; contractors for your next home improvement project.</p>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-sm-4 col-md-3">
            <img class = "img-responsive" alt= "" src="http://s3.amazonaws.com/saveoneverything_assets/images/corporate/logo/groceries.jpg"/>
        </div>
        <div class="col-sm-8 col-md-9">
            <h2>SaveOn<sup>&reg;</sup> Groceries<sup>sm</sup></h2>
                <span class="h5 spaced hblock">Great Grocery Coupons</span>
                <p>Couponing is much easier when you use SaveOnGroceries.com because we help you find deals on the brands you actually want. Our savings for groceries are every couponer's dream. We supply you with deals on national brands that change as quickly as most peoples' appetites. Saving money is so much easier with your pick of the best brands. Be sure to use SaveOn<sup>&reg;</sup> Groceries coupons to be able to do more with your money. We can't wait to offer you even more deals! </p>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-sm-4 col-md-3">
            <img class = "img-responsive" alt= "" src="http://s3.amazonaws.com/saveoneverything_assets/images/corporate/logo/travel.jpg"/>
        </div>
        <div class="col-sm-8 col-md-9">
            <h2>SaveOn<sup>&reg;</sup> Travel<sup>sm</sup></h2>
            <span class="h5 spaced hblock">Your Journey Begins Here</span>
            <p>You’ll find travel sites, charter companies &amp; cruise lines to book your vacation.</p>
        </div>
        </div>
    </div>

@stop

