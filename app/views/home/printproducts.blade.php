@extends('master.templates.master')
@section('page-title')
<h1>Print Products</h1>
@stop
@section('breadcrumbs')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}" itemprop="url"><span itemprop="title">Home</span></a>
    </li>
    <li class="active">Print Products</li>
@stop
@section('sidebar')
@include('master.templates.corporatesidebar')
@stop
@section('body')

<div class="span9 main-column two-columns-left">
<div class="content-bg">
    <div class="row ">
        <div class="col-sm-6 col-xs-12">
            <h2>SaveOn<sup>&reg;</sup> Magazine</h2>
            <br>
             <img class = "img-responsive img-thumbnail" src="http://s3.amazonaws.com/saveoneverything_assets/images/corporate/print-products/magazine.jpg"/>
            <div>
                <p>The SaveOn<sup>&reg;</sup> Magazine provides superior readership.</p>

                <p>How? We have a full color magazine format for easier and repeated viewing. Because of this, our magazine remains in homes longer. 54% of our readers keep their current issue of SaveOn<sup>&reg;</sup> until a new issue is published. Another great part of our magazine is that, unlike an envelope format, there are no coupons to sort. This has led to some consistently great response rates on our cover promotions and giveaways. </p>
            </div>
        </div>
        <div class="col-sm-6 col-xs-12">
            <h2>Insert Media</h2>
            <br>
             <img class = "img-responsive img-thumbnail" src="http://s3.amazonaws.com/saveoneverything_assets/images/corporate/print-products/inserts.jpg"/>
            <div>
                <p>With SaveOn<sup>&reg;</sup> Insert Media, you can add a powerful and valuable marketing tool to your advertising arsenal! Reach and capture the attention of your target audience with booklets, blow-in cards, post cards, flyers, and 4-page circulars. </p>

                <p>Our inserts really “stand-out” from the competition. They are nestled into the SaveOn<sup>&reg;</sup> Magazine and “hang out” there for all to see. SaveOn<sup>&reg;</sup> acts as mailing carrier to save you up to 70% off the cost of mailing it yourself.</p>
            </div>
        </div>
    </div>
    <hr>
    <div class="row ">
        <div class="col-sm-6 col-xs-12">
            <h2>D.A.L. Postcards</h2>
            <br>
             <img class = "img-responsive img-thumbnail" src="http://s3.amazonaws.com/saveoneverything_assets/images/corporate/print-products/dal.jpg"/>
            <div>
                <p>Detached Address Label (DAL) postcards are delivered with our magazine and act as the address label for our distribution. Because of the new automation of the post office, all letter-size products get processed into one bundle. So our DAL cards deliver with the 1st class mail.</p>

                <p>Unlike your own direct mail standard class postcard, SAVE DAL's can guarantee your in-home delivery date. You get 1st class fulfillment at 1/3 the cost. That's over 60% savings on standard class with a guaranteed delivery date.</p>
            </div>
        </div>
        <div class="col-sm-6 col-xs-12">
            <h2>SaveOn Cars &amp; Trucks<sup>&reg;</sup></h2>
            <br>
             <img class = "img-responsive img-thumbnail" src="http://s3.amazonaws.com/saveoneverything_assets/images/corporate/print-products/soct_magazine.jpg"/>
            <div>
                <p>SaveOn Cars &amp; Trucks<sup>&reg;</sup> is the leading monthly publication for consumers to find the best deals on wheels delivered right to their mailbox. As the premier direct mail piece in the marketplace, SaveOn Cars &amp; Trucks<sup>&reg;</sup> provides consumers with the information they need to make cost effective car buying decisions. Additionally, our website www.saveoncarsandtrucks.com provides online car shoppers can find information including pricing, reviews, photo galleries, and a large selection of new and used car inventory. </p>
            </div>
        </div>
    </div>
</div>
@stop