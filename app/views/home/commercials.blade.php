@extends('master.templates.master')
@section('page-title')
<h1>SaveOn<sup>&reg;</sup> TV Commercials</h1>
@stop
@section('breadcrumbs')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}" itemprop="url"><span itemprop="title">Home</span></a>
    </li>
    <li class="active">SaveOn<sup>&reg;</sup> TV Commercials</li>
@stop
@section('sidebar')
<div class="panel panel-default">
    <div class="panel-heading">
      <span class="h4 hblock panel-title">
        <a data-toggle="collapse" href="#collapseOne">Explore Coupons <span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span></a>
        <div class="clearfix"></div>
      </span>
    </div>
    <div id="collapseOne" class="panel-collapse collapse in">
	    <div class="panel-body explore-links">
	      	<ul>
                 @include('master.templates.explore', array('active' => 'all', 'type' => 'coupon'))
			</ul>
	    </div>
    </div>
</div>
@stop
@section('body')

<div class="content-bg">

	<div class="h1">Look For SaveOn<sup>&reg;</sup> on TV</div>
	<p>Here are some of our commercials that air across the country. Check them all out below. We love them because they help to educate our loyal users on how to save time and money on whatever they are looking for. From local coupons to amazing sweepstakes and contests. SaveOn.com has the offers &amp; deals you have been looking for near you.</p>

	<h2 class="margin-bottom-10">Featured</h2>
	<div class="row">
		<div class="col-sm-6 col-lg-4 margin-bottom-10">
			<p><strong>SaveOn<sup>&reg;</sup> Magazine</strong></p>
			<iframe width="560" height="315" src="https://www.youtube.com/embed/LPYGy5lSUNY" frameborder="0" allowfullscreen style="width:100%"></iframe>
		</div>
		<div class="col-sm-6 col-lg-4 margin-bottom-10">
			<p><strong>SaveOn<sup>&reg;</sup> Cars and Trucks<sup>&reg;</sup> Magazine</strong></p>
			<iframe width="560" height="315" src="https://www.youtube.com/embed/mrzwqvkImnI" frameborder="0" allowfullscreen style="width:100%"></iframe>
		</div>
		<div class="col-sm-6 col-lg-4 margin-bottom-10">
			<p><strong>SaveOn<sup>&reg;</sup> Home Improvement<sup>sm</sup></strong></p>
			<iframe width="560" height="315" src="https://www.youtube.com/embed/SeQVIeU7C1I" frameborder="0" allowfullscreen style="width:100%"></iframe>
		</div>
	</div>

	<div class="margin-10 text-center view-more-container"><button class="btn btn-grey btn-lg">View More</button></div>

	<div class="hidden-content hidden">
	<h2 class="margin-bottom-10">Chicago</h2>
	<div class="row">
		<div class="col-sm-6 col-lg-4 margin-bottom-10">
			<p><strong>SaveOn<sup>&reg;</sup> Home Improvement<sup>sm</sup> - Chicago #1</strong></p>
			<iframe width="560" height="315" src="https://www.youtube.com/embed/fH7GCrE4mgQ" frameborder="0" allowfullscreen style="width:100%"></iframe>
		</div>
		<div class="col-sm-6 col-lg-4 margin-bottom-10">
			<p><strong>SaveOn<sup>&reg;</sup> Home Improvement<sup>sm</sup> - Chicago #2</strong></p>
			<iframe width="560" height="315" src="https://www.youtube.com/embed/vmIrLpM67-A" frameborder="0" allowfullscreen style="width:100%"></iframe>
		</div>
		<div class="col-sm-6 col-lg-4 margin-bottom-10">
			<p><strong>SaveOn<sup>&reg;</sup> Home Improvement<sup>sm</sup> - Chicago #3</strong></p>
			<iframe width="560" height="315" src="https://www.youtube.com/embed/banzzpsYKMg" frameborder="0" allowfullscreen style="width:100%"></iframe>
		</div>
		<div class="col-sm-6 col-lg-4 margin-bottom-10">
			<p><strong>SaveOn<sup>&reg;</sup> Home Improvement<sup>sm</sup> - Chicago #4</strong></p>
			<iframe width="560" height="315" src="https://www.youtube.com/embed/Sf7lUPvhZR8" frameborder="0" allowfullscreen style="width:100%"></iframe>
		</div>
		<div class="col-sm-6 col-lg-4 margin-bottom-10">
			<p><strong>SaveOn<sup>&reg;</sup> Home Improvement<sup>sm</sup> - Chicago #5</strong></p>
			<iframe width="560" height="315" src="https://www.youtube.com/embed/tvlMXUhrIDs" frameborder="0" allowfullscreen style="width:100%"></iframe>
		</div>
		<div class="col-sm-6 col-lg-4 margin-bottom-10">
			<p><strong>SSaveOn<sup>&reg;</sup> Home Improvement<sup>sm</sup> - Chicago #6</strong></p>
			<iframe width="560" height="315" src="https://www.youtube.com/embed/3XCZRATh2Y0" frameborder="0" allowfullscreen style="width:100%"></iframe>
		</div>
		<div class="col-sm-6 col-lg-4 margin-bottom-10">
			<p><strong>SaveOn<sup>&reg;</sup> Home Improvement<sup>sm</sup> - Chicago #7</strong></p>
			<iframe width="560" height="315" src="https://www.youtube.com/embed/gHbd6IfE62o" frameborder="0" allowfullscreen style="width:100%"></iframe>
		</div>
		<div class="col-sm-6 col-lg-4 margin-bottom-10">
			<p><strong>SaveOn<sup>&reg;</sup> Home Improvement<sup>sm</sup> - Chicago #8</strong></p>
			<iframe width="560" height="315" src="https://www.youtube.com/embed/5942C2xmHOQ" frameborder="0" allowfullscreen style="width:100%"></iframe>
		</div>
		<div class="col-sm-6 col-lg-4 margin-bottom-10">
			<p><strong>SaveOn<sup>&reg;</sup> Home Improvement<sup>sm</sup> - Chicago #9</strong></p>
			<iframe width="560" height="315" src="https://www.youtube.com/embed/BOWug0dS010" frameborder="0" allowfullscreen style="width:100%"></iframe>
		</div>
	</div>

	<h2 class="margin-bottom-10">Detroit</h2>
	<div class="row">
		<div class="col-sm-6 col-lg-4 margin-bottom-10">
			<p><strong>SaveOn<sup>&reg;</sup> Home Improvement<sup>sm</sup> - Detroit #1</strong></p>
			<iframe width="560" height="315" src="https://www.youtube.com/embed/SeQVIeU7C1I" frameborder="0" allowfullscreen style="width:100%"></iframe>
		</div>
	</div>

	<h2 class="margin-bottom-10">Grand Rapids</h2>
	<div class="row">
		<div class="col-sm-6 col-lg-4 margin-bottom-10">
			<p><strong>SaveOn<sup>&reg;</sup> Home Improvement<sup>sm</sup> - Grand Rapids #1</strong></p>
			<iframe width="560" height="315" src="https://www.youtube.com/embed/ucqTO4svI2w" frameborder="0" allowfullscreen style="width:100%"></iframe>
		</div>
		<div class="col-sm-6 col-lg-4 margin-bottom-10">
			<p><strong>SaveOn<sup>&reg;</sup> / Wave of Savings - Grand Rapids #1</strong></p>
			<iframe width="560" height="315" src="https://www.youtube.com/embed/9zjYP8WCzy8" frameborder="0" allowfullscreen style="width:100%"></iframe>
		</div>
	</div>

	<h2 class="margin-bottom-10">Lansing</h2>
	<div class="row">
		<div class="col-sm-6 col-lg-4 margin-bottom-10">
			<p><strong>SaveOn<sup>&reg;</sup> Home Improvement<sup>sm</sup> - Lansing #1</strong></p>
			<iframe width="560" height="315" src="https://www.youtube.com/embed/FFyWkMQvpUc" frameborder="0" allowfullscreen style="width:100%"></iframe>
		</div>
		<div class="col-sm-6 col-lg-4 margin-bottom-10">
			<p><strong>SaveOn<sup>&reg;</sup> Cars and Trucks<sup>&reg;</sup> - Lansing #1</strong></p>
			<iframe width="560" height="315" src="https://www.youtube.com/embed/IMSdJnwgtss" frameborder="0" allowfullscreen style="width:100%"></iframe>
		</div>
	</div>

	<h2 class="margin-bottom-10">All Markets</h2>
	<div class="row">
		<div class="col-sm-6 col-lg-4 margin-bottom-10">
			<p><strong>SaveOn<sup>&reg;</sup> Magazine #1</strong></p>
			<iframe width="560" height="315" src="https://www.youtube.com/embed/LPYGy5lSUNY" frameborder="0" allowfullscreen style="width:100%"></iframe>
		</div>
		<div class="col-sm-6 col-lg-4 margin-bottom-10">
			<p><strong>SaveOn<sup>&reg;</sup> Cars and Trucks<sup>&reg;</sup> Magazine #1</strong></p>
			<iframe width="560" height="315" src="https://www.youtube.com/embed/mrzwqvkImnI" frameborder="0" allowfullscreen style="width:100%"></iframe>
		</div>
	</div>

	</div>

</div>

@stop