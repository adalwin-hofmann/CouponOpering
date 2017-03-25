@extends('master.templates.master')
@section('page-title')
<h1>Chamber of Commerce Promotion</h1>
@stop
@section('breadcrumbs')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}" itemprop="url"><span itemprop="title">Home</span></a>
    </li>
    <li class="active">Chamber of Commerce Promotion</li>
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

<h2>Hello Chamber Member!</h2>

<div class="row">
	<div class="col-sm-6">

	<p>Thank you for your interest in the Chamber of Commerce Presentation. We are offering a limited time promotion to advertise with us for six months, at no cost to you!</p>

	<p>Digital Marketing Package:</p>

		<ul class="check_list">
			<li>6 Months of Free Advertising</li>
			<li>Merchant Page/Microsite on SaveOn.com<sup>&reg;</sup></li>
			<li>Free Contests (with follow-up offer email)</li>
			<li>Reporting</li>
		</ul>
	</div>
	<div class="col-sm-6 margin-bottom-10">
		<img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/chamber/chamber-landing.jpg" alt="Chamber Presentation" class="img-responsive" style="max-width: 400px">
	</div>
</div>

<p>Please fill out the following information, and one of our Customer Service Representatives will contact you to get your program started!</p>

<p style="color:green;">{{Session::has('contact_send') ? 'Information Sent.' : ''}}</p>

<form method="post" action="{{URL::abs('/')}}/send-chamber-info">
	<div class="row">
		<div class="col-sm-6">
			<div class="form-group">
				<input type="text" class="form-control" name="firstname" placeholder="Enter First Name">
			</div>
		</div>
		<div class="col-sm-6">
			<div class="form-group">
				<input type="text" class="form-control" name="lastname" placeholder="Enter Last Name">
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<div class="form-group">
				<input type="text" class="form-control" name="company" placeholder="Enter Your Company">
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6">
			<div class="form-group">
				<input type="text" class="form-control" name="phone" placeholder="Enter Your Phone Number">
			</div>
		</div>
		<div class="col-sm-6">
			<div class="form-group">
				<input type="email" class="form-control" name="email" placeholder="Enter Email">
			</div>
		</div>
	</div>
	<div class="form-group text-center">
		<button type="submit" class="btn btn-lg btn-black">Submit</button>
	</div>
</form>

<p>Again, we thank you! If you would like to download this presentation, click <a href="http://saveoneverything_assets.s3.amazonaws.com/assets/pdfs/chamber-of-commerce-presentation.pdf" target="_blank">HERE</a>.</p>


</div>

@stop