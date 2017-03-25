@extends('master.templates.master')
@section('page-title')
<h1>Digital Products</h1>
@stop

@section('breadcrumbs')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}" itemprop="url"><span itemprop="title">Home</span></a>
    </li>
    <li class="active">Digital Products</li>
@stop

@section('sidebar')
@include('master.templates.corporatesidebar')
@stop
@section('body')
<div class="content-bg">
    <div class="row">
        <div class="col-sm-6">
            <h2 class="margin-bottom-10">Merchant Page</h2>
            <div class="margin-bottom-10">
                <img class = "img-thumbnail img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/images/corporate/digital-products/merchant_page.jpg"/>
            </div>
            <div>
                <p>When you join the SaveOn<sup>&reg;</sup> family as one of our valued merchants, you receive an online site tailored to your industry and brand. This personal page can feature any combination of photos, streaming videos, product and service information, menus, brochures, special offers, reviews, and even more.</p>

                <p>This page also serves as a spot where users can locate your unique SaveOn<sup>&reg;</sup> offers. Whether it's through a coupon, daily deal, or contest, these merchant pages serve as the perfect tool to expand your visibility in the digital arena. </p>
                <p>Our Merchant pages offer optional <a href="http://www.callsource.com/home/reporting-login">Call Capture Tracking Software</a>.</p>
            </div>
        </div>
        <div class="visible-xs col-xs-12">
            <hr>
        </div>
        <div class="col-sm-6">
            <h2 class="margin-bottom-10">Coupons</h2>
            <div class="margin-bottom-10">
                <img class="img-thumbnail img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/images/corporate/digital-products/coupons.jpg"/>
            </div>
            <div>
                <p>Looking for a way to reach out to new customers? Coupons will increase traffic to your digital or physical locations and also help to generate leads. This is a great resource for all types of businesses, which is why we have such a wide array of categories and accompanying sub-categories. Regardless of your industry, we have a coupon strategy for you. Let's grow your business together!</p>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-sm-6">
            <h2 class="margin-bottom-10">Contests - Win &amp; Save</h2>
            <div class="margin-bottom-10">
                <img class="img-thumbnail img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/images/corporate/digital-products/win_and_save.jpg"/>
            </div>
            <div>
                <p>This is a Win-Win opportunity for our members to enter into contests to win great prizes! After the contest has ended, applicants who did not win a prize are sent a special follow-up deal by email, so they still have a chance to save!</p>
                <p>How will this help you?</p>
                <ul class="check_list">
                    <li>Our Win &amp; Save has proven to increase traffic and awareness for our merchant Cites and businesses. </li>
                    <li>In addition to offers, you get increased visibility from our contest page.</li>
                    <li>The follow-up offer can bring new customers to your business.</li>
                    <li>It is a great platform to get people excited about what you have to offer!</li>
                </ul>
            </div>
        </div>
        <!--<div class="col-sm-6">
            <h2>Contests &amp; Email Strategy</h2>
            <br>
            <img class = "img-thumbnail img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/images/corporate/digital-products/win_and_save.jpg"/>
            <div>
                <p>SaveOn's e-mail strategy will grow your email list. How? Through a comprehensive contest platform not only grows your email list, but also pushes offers to customers who like your business. At the end of your contest, we send an email announcing the winner and sending a coupon to all who didn't win.</p>
            </div>
        </div> -->
    </div>
</div>
@stop