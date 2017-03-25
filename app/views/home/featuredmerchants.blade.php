@extends('master.templates.master')
@section('page-title')
<h1>Testimonials</h1>
@stop

@section('breadcrumbs')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}" itemprop="url"><span itemprop="title">Home</span></a>
    </li>
    <li class="active">Testimonials</li>
@stop

@section('sidebar')
@include('master.templates.corporatesidebar')
@stop
@section('body')
<div class="content-bg">
<div class="row">

        <div class="col-xs-12 col-sm-6 ">
            <h2>Modernistic</h2>
            <img class = "img-responsive img-thumbnail" alt= "" style = "width: 200x; height: 150px; margin:10px auto;" src="http://s3.amazonaws.com/saveoneverything_assets/images/corporate/Screen Shot 2014-01-10 at 8.08.36 AM.jpg"/>
            <div>
                <blockquote>When we started with SAVE we had 4 trucks. After 25 years of running each and every month we have grown to 60+ trucks to better service our customers. We count on SAVE to help keep our staff working and our company growing.
                    <p class="author">– Bob McDonald, President</p>
                </blockquote>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 ">
        <h2>Fan &amp; Clock</h2>
            <img class = "img-responsive img-thumbnail" alt= "" style = "width: 200x; height: 150px; margin:10px auto;" src="http://s3.amazonaws.com/saveoneverything_assets/images/corporate/fan-clock.jpg"/>
            <div>
                <blockquote>When SAVE’s ad drops the first week sales go through the roof! I have tried everything in 27 years. Save On has the highest rate of return by far.</blockquote>
            </div>
        </div>
</div>

        <hr>
    <div class="row">
        <div class="col-xs-12 col-sm-6 ">
        <h2>Leo's Coney Island</h2>
            <img class = "img-responsive img-thumbnail" alt= "" style = "width: 200x; height: 150px; margin:10px auto;" src="http://s3.amazonaws.com/saveoneverything_assets/images/corporate/leos-coney.jpg"/>
            <div>
                <blockquote>I am writing this letter to thank you and let you know how far you have separated your company from your competition. When I run a back cover with SAVE (1 zone – 25,000 homes) I will receive at least 80 coupons the first week it hits homes.</blockquote>
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 ">
        <h2>Mr. Rooter</h2>
            <img class = "img-responsive img-thumbnail" alt= "" style = "width: 200x; height: 150px; margin:10px auto;" src="http://s3.amazonaws.com/saveoneverything_assets/images/corporate/mr-rooter.jpg"/>
            <div>
                <blockquote>I just wanted to write to you and let you know how much we greatly appreciate everything you and your magazine has done for us. It seemed to start a little slow at first but since we are committed to a long term campaign we are seeing outstanding results.</blockquote>
            </div>
        </div>

    </div>
    <hr>
    <div class="row">
        <div class="col-xs-12 ">
            <h2>Mi Zarape</h2>
            <img class = "img-responsive img-thumbnail" alt= "" style = "width: 200x; height: 150px; margin:10px auto;" src="http://s3.amazonaws.com/saveoneverything_assets/images/corporate/mi-zarape.jpg"/>
            <div>
                <blockquote>We had over 30 coupons redeemed in the first day alone, with many more to follow in weeks after. Without a doubt this is the best response out of any publication I have ever advertised in including Clipper, Money Mailers, etc.</blockquote>
            </div>
        </div>
    </div>
</div>
@stop