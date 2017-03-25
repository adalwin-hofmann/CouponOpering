@extends('master.templates.master')
@section('page-title')
<h1>SaveOn<sup>&reg;</sup> Gives Back</h1>
@stop
@section('breadcrumbs')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}" itemprop="url"><span itemprop="title">Home</span></a>
    </li>
    <li class="active">Give Back</li>
@stop
@section('sidebar')
<div class="panel panel-default">
    <div class="panel-heading">
      <span class="h4 hblock panel-title">
        <a data-toggle="collapse" href="#collapseOne">Explore {{!isset($type) || $type == 'coupon' ? 'Coupons' : ($type == 'dailydeal' ? 'Daily Deals' : 'Contests')}} <span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span></a>
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
<div class="content-bg relative">
  <div class="row">
    <div class="col-md-3 margin-bottom-10">
      <img src="/img/logo-gives-back.png" alt="SaveOn<sup>&reg;</sup> Gives Back" class="img-responsive">
    </div>
    <div class="col-md-9">
      <h1>Help SaveOn<sup>&reg;</sup> Give Back to the Community</h1>
      <p>Join SaveOn<sup>&reg;</sup> in giving back to the community in 2014. When you buy any of our SaveOn<sup>&reg;</sup> apparel, all proceeds go to charity! Click on any of clothing below or visit the store at <a href="http://www.cafepress.com/saveon" target="_blank">http://www.cafepress.com/saveon</a> so you can give back.</p>
    </div>
  </div>
  
	<div id="container">
    <a href="http://www.cafepress.com/saveon.1363584668" target="_blank" class="item">
      <p><img src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/community1.jpg" class="img-responsive"></p>
      <p class="text-center"><button class="btn btn-green">Purchase &gt;</button></p>
    </a>
    <a href="http://www.cafepress.com/saveon.1363584665" target="_blank" class="item">
      <p><img src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/community2.jpg" class="img-responsive"></p>
      <p class="text-center"><button class="btn btn-green">Purchase &gt;</button></p>
    </a>
    <a href="http://www.cafepress.com/saveon.1363584662" target="_blank" class="item">
      <p><img src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/community3.jpg" class="img-responsive"></p>
      <p class="text-center"><button class="btn btn-green">Purchase &gt;</button></p>
    </a>
    <a href="http://www.cafepress.com/saveon.1363584644" target="_blank" class="item">
      <p><img src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/community4.jpg" class="img-responsive"></p>
      <p class="text-center"><button class="btn btn-green">Purchase &gt;</button></p>
    </a>
    <a href="http://www.cafepress.com/saveon.1363584648" target="_blank" class="item">
      <p><img src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/community5.jpg" class="img-responsive"></p>
      <p class="text-center"><button class="btn btn-green">Purchase &gt;</button></p>
    </a>
    <a href="http://www.cafepress.com/saveon.1363584658" target="_blank" class="item">
      <p><img src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/community6.jpg" class="img-responsive"></p>
      <p class="text-center"><button class="btn btn-green">Purchase &gt;</button></p>
    </a>
  </div>
  <div class="clearfix"></div>
</div>
@stop