@extends('master.templates.master')
@section('page-title')
<h1>SaveOn<sup>&reg;</sup> Media Kit</h1>
@stop
@section('sidebar')
    @include('master.templates.corporatesidebar')
@stop
@section('body')

<div class="content-bg">
    <div class="row">
        <div class="col-xs-12">
            <p class="spaced green">About SaveOn<sup>&reg;</sup></p>
            <div class="row"><div class="col-sm-3"><hr style="margin-top:0;"></div></div>
            <p>We are a comprehensive digital marketing and direct mail company specializing in providing solutions through a variety of products including: Web &amp; mobile phone applications, direct mail, inserts, and DAL cards. Our direct mail magazine, SaveOn, is distributed monthly to over 3 million homes in Detroit, Chicago, and Minneapolis/St. Paul. As a trusted resource, we put your business in the hands of customers looking to SaveOn<sup>&reg;</sup> what they value most: Time and Money.</p>
        </div>
    </div>
    <div class="row margin-top-20">
        <div class="col-xs-12">
            <p class="spaced green">Our Mission</p>
            <div class="row"><div class="col-sm-3"><hr style="margin-top:0;"></div></div>
            <blockquote><p>Our mission at SaveOn<sup>&reg;</sup> is to improve the quality of life for every home we mail to. Our new digital platform will allow those same great savings even on the go! We accomplish this by providing great offers from only reputable businesses. We are leaders in marketing innovation and are committed to the growth of our business partners. We will be the #1 used savings product in the U.S., reaching 18 million households monthly to save them time and money</p>
            <p class="author">Mike Gauthier</p>
            </blockquote>
        </div>
    </div>
    <div class="row margin-top-20">
        <div class="col-xs-6">
            <p class="spaced green">Logos</p>
            <div class="row"><div class="col-sm-3"><hr style="margin-top:0;"></div></div>
            <ul>
                <li><a href="{{URL::abs('/')}}/img/press_kit/save-on-logo.png">Logo in color</a></li>
                <li><a href="{{URL::abs('/')}}/img/press_kit/save-on-logo-black.png">Logo in black &amp; white</a></li>
                <li><a href="{{URL::abs('/')}}/img/press_kit/save-on-home-improvement-logo.png">SaveOn<sup>&reg;</sup> Home Improvement<sup>sm</sup> logo</a></li>
            </ul>
        </div>
        <div class="col-xs-6">
            <p class="spaced green">Photos &amp; Videos</p>
            <div class="row"><div class="col-sm-3"><hr style="margin-top:0;"></div></div>
            <ul>
                <li><a href="{{URL::abs('/')}}/img/press_kit/save-building.jpg">Photo of building</a></li>
                <li><a href="{{URL::abs('/')}}/img/press_kit/mikeg.png">Mike Gauthier, CEO</a></li>
                <li><a href="https://www.youtube.com/watch?v=oyp8FPPr2vU">SaveOn<sup>&reg;</sup> Home Improvement<sup>sm</sup> - Ted &amp; Jennifer</a></li>
                <li><a href="https://www.youtube.com/watch?v=JEN9bxe9fO4">SaveOn<sup>&reg;</sup> Home Improvement<sup>sm</sup> - Contractor Guarantee</a></li>
                <li><a href="https://www.youtube.com/watch?v=WahcY4pWwcA">SaveOn<sup>&reg;</sup> Home Improvement<sup>sm</sup> - Find Your Match</a></li>
                <li><a href="https://www.youtube.com/watch?v=1veY_tyh0pM">SaveOn<sup>&reg;</sup> Home Improvement<sup>sm</sup> - 15 Sec - Save Big!</a></li>
                <li><a href="https://www.youtube.com/watch?v=jYC9mkKJY8M">SaveOn<sup>&reg;</sup> Home Improvement<sup>sm</sup> - 15 Sec - Fix Up Your Home</a></li>
                <li><a href="https://www.youtube.com/watch?v=EDKDsmaXDlY">SaveOn<sup>&reg;</sup> Home Improvement<sup>sm</sup> - 15 Sec - Save a Fortune</a></li>
            </ul>
        </div>
    </div>
</div>
@stop

