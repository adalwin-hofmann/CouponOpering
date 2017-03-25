@extends('master.templates.master')
@section('page-title')
<h1>Groceries</h1>
@stop

@section('breadcrumbs')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}" itemprop="url"><span itemprop="title">Home</span></a>
    </li>
    <li class="active">Groceries</li>
@stop

@section('sidebar')
@if(count($category_ads['objects']))
    @include('master.templates.advertisement', array('advertisement' => $category_ads['objects'][0]))
@endif
<div class="panel panel-default">
    <div class="panel-heading">
      <span class="panel-title h4 hblock">
        <a data-toggle="collapse" href="#collapseOne">Local Coupons <span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span></a>
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
	<div class="how_it_works_label">

	</div>
	<div class="row margin-bottom-20">
        <div class="col-sm-4">
            <div class="row">
                <div class="col-md-3">
                </div>
                <div class="col-md-9">
                    <h2>Clip</h2>
                    <br>
                    <p>Sort by category or brand to find all the money-saving coupons that you want. Simply click on the coupon to 'clip' it.</p>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="row">
                <div class="col-md-12">
                    <h2>Print</h2>
                    <br>
                    <p>After you have clipped all the coupons you want, click the red "Print Coupons" button. If you haven't already, you will be prompted to install our coupon printer app.</p>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="row">
                
                <div class="col-md-9">
                    <h2>Save</h2>
                    <br>
                    <p>Grab your shopping list, and don't forget your coupons! SaveOn.com can help you save on your next shopping trip.</p>
                </div>
                <div class="col-md-3">
                </div>
            </div>
        </div>
    </div>
    <hr>
    <br>
	<iframe class="ci_ccss" id="ci_CouponsClickParentIframe" frameborder="0" height="1440px" width="750px" name="ci_CouponsClickParentIframe" style="overflow: hidden; margin: 0px auto; padding: 0px; border:none; scroll:no; display:block;" src="//bc.coupons.com/loadcoupons?scriptid=13143&amp;bid=1236190001&amp;format=718x940&amp;scrh=900&amp;scrw=1600&amp;bannertype=3&amp;parenturl=http%3A%2F%2Fwww.saveon.com%2F&amp;parent=http%3A%2F%2Fwww.saveon.com&amp;iheight=1440&amp;iwidth=750"></iframe>
</div>
@stop