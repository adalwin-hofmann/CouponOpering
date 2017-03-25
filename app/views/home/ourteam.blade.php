@extends('master.templates.master')
@section('page-title')
<h1>Our Team</h1>
@stop

@section('breadcrumbs')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}" itemprop="url"><span itemprop="title">Home</span></a>
    </li>
    <li class="active">Our Team</li>
@stop

@section('sidebar')
	@include('master.templates.corporatesidebar')
@stop

@section('body')

<div id="container" class="js-masonry team-photos">
	<div class="item pointer" data-toggle="modal" data-target="#mikeGModal">
		<div class="top-pic">
			<img class="img img-responsive" alt="" style="" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/corporate/team/mike-gauthier.jpg">
		</div>
		<div class="item-info">
			<p><strong>Mike Gauthier</strong><br>Founder</p>
		</div>
	</div>
	<div class="item pointer" data-toggle="modal" data-target="#heatherUModal">
		<div class="top-pic">
			<img class="img img-responsive" alt="" style="" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/corporate/team/heather-uballe.jpg">
		</div>
		<div class="item-info">
			<p><strong>Heather Uballe</strong><br>President</p>
		</div>
	</div>
	<div class="item pointer" data-toggle="modal" data-target="#billDModal">
		<div class="top-pic">
			<img class="img img-responsive" alt="" style="" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/corporate/team/bill-davis.jpg">
		</div>
		<div class="item-info">
			<p><strong>Bill Davis</strong><br>Vice President, Sales</p>
		</div>
	</div>
	<div class="item pointer" data-toggle="modal" data-target="#joeKModal">
		<div class="top-pic">
			<img class="img img-responsive" alt="" style="" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/corporate/team/joe-kudron.jpg">
		</div>
		<div class="item-info">
			<p><strong>Joe Kudron</strong><br>Vice President, Sales, Chicago</p>
		</div>
	</div>
	<!--<div class="item pointer" data-toggle="modal" data-target="#willFModal">
		<div class="top-pic">
			<img class="img img-responsive" alt="" style="" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/corporate/team/will-fobbs.jpg">
		</div>
		<div class="item-info">
			<p><strong>Will Fobbs</strong><br>Director of IT</p>
		</div>
	</div>-->
	<div class="item pointer" data-toggle="modal" data-target="#emileaWModal">
		<div class="top-pic">
			<img class="img img-responsive" alt="" style="" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/corporate/team/emilea-weaver.jpg">
		</div>
		<div class="item-info">
			<p><strong>Emilea Weaver</strong><br>Corporate Controller</p>
		</div>
	</div>
	<div class="item pointer" data-toggle="modal" data-target="#jimTModal">
		<div class="top-pic">
			<img class="img img-responsive" alt="" style="" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/corporate/team/jim-traeger.jpg">
		</div>
		<div class="item-info">
			<p><strong>Jim Traeger</strong><br>Regional Sales Director</p>
		</div>
	</div>
	<!--<div class="item pointer" data-toggle="modal" data-target="#laurieBModal">
		<div class="top-pic">
			<img class="img img-responsive" alt="" style="" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/corporate/team/laurie-buck.jpg">
		</div>
		<div class="item-info">
			<p><strong>Laurie Buck</strong><br>Production Manager</p>
		</div>
	</div>-->
	<div class="item pointer" data-toggle="modal" data-target="#leannGModal">
		<div class="top-pic">
			<img class="img img-responsive" alt="" style="" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/corporate/team/leann-gutzke.jpg">
		</div>
		<div class="item-info">
			<p><strong>LeAnn Gutzke</strong><br>City Manager, Minneapolis</p>
		</div>
	</div>
	<!--<div class="item pointer" data-toggle="modal" data-target="#corporateModal">
		<div class="top-pic">
			<img class="img img-responsive" alt="" style="" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/corporate/team/corporate-team.jpg">
		</div>
		<div class="item-info">
			<p><strong>Corporate</strong></p>
		</div>
	</div>
	<div class="item pointer" data-toggle="modal" data-target="#productionModal">
		<div class="top-pic">
			<img class="img img-responsive" alt="" style="" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/corporate/team/production-team.jpg">
		</div>
		<div class="item-info">
			<p><strong>Production</strong></p>
		</div>
	</div> -->
</div>

<div class="modal fade review" id="mikeGModal" tabindex="-1" role="dialog" aria-labelledby="mikeGModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <h1 class="h1 modal-title fancy" id="mikeGModalLabel">Mike Gauthier</h1>
        <h2 class="h2">Founder</h2>
      </div>
      <div class="modal-body">
      	<img class="img img-responsive" alt="" style="" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/corporate/team/mike-gauthier.jpg">
      	<!--<hr>
  		<p>Our mission at SaveOn<sup>&reg;</sup> is to improve the quality of life for every home we mail to. Our new digital platform will allow those same great savings even on the go! We accomplish this by providing great offers from only reputable businesses. We are leaders in marketing innovation and are committed to the growth of our business partners. We will be the #1 used savings product in the U.S., reaching 18 million households monthly to save them time and money.</p>-->
      </div>
    </div>
  </div>
</div>

<div class="modal fade review" id="heatherUModal" tabindex="-1" role="dialog" aria-labelledby="heatherUModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <h1 class="h1 modal-title fancy" id="heatherUModalLabel">Heather Uballe</h1>
        <h2 class="h2">President</h2>
      </div>
      <div class="modal-body">
      	<img class="img img-responsive" alt="" style="" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/corporate/team/heather-uballe.jpg">
      </div>
    </div>
  </div>
</div>

<div class="modal fade review" id="billDModal" tabindex="-1" role="dialog" aria-labelledby="billDModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <h1 class="h1 modal-title fancy" id="billDModalLabel">Bill Davis</h1>
        <h2 class="h2">Vice President, Sales</h2>
      </div>
      <div class="modal-body">
      	<img class="img img-responsive" alt="" style="" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/corporate/team/bill-davis.jpg">
      </div>
    </div>
  </div>
</div>

<div class="modal fade review" id="joeKModal" tabindex="-1" role="dialog" aria-labelledby="joeKModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <h1 class="h1 modal-title fancy" id="joeKModalLabel">Joe Kudron</h1>
        <h2 class="h2">Vice President, Sales, Chicago</h2>
      </div>
      <div class="modal-body">
      	<img class="img img-responsive" alt="" style="" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/corporate/team/joe-kudron.jpg">
      </div>
    </div>
  </div>
</div>

<div class="modal fade review" id="andyMModal" tabindex="-1" role="dialog" aria-labelledby="andyMModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <h1 class="h1 modal-title fancy" id="andyMModalLabel">Andy Massingill</h1>
        <h2 class="h2">Vice President, Digital Sales</h2>
      </div>
      <div class="modal-body">
      	<img class="img img-responsive" alt="" style="" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/corporate/team/andy-massingill.jpg">
      </div>
    </div>
  </div>
</div>

<div class="modal fade review" id="willFModal" tabindex="-1" role="dialog" aria-labelledby="willFModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <h1 class="h1 modal-title fancy" id="willFModalLabel">Will Fobbs</h1>
        <h2 class="h2">Director of IT</h2>
      </div>
      <div class="modal-body">
      	<img class="img img-responsive" alt="" style="" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/corporate/team/will-fobbs.jpg">
      </div>
    </div>
  </div>
</div>

<div class="modal fade review" id="emileaWModal" tabindex="-1" role="dialog" aria-labelledby="emileaWModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <h1 class="h1 modal-title fancy" id="emileaWModalLabel">Emilea Weaver</h1>
        <h2 class="h2">Corporate Controller</h2>
      </div>
      <div class="modal-body">
      	<img class="img img-responsive" alt="" style="" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/corporate/team/emilea-weaver.jpg">
      </div>
    </div>
  </div>
</div>

<div class="modal fade review" id="jimTModal" tabindex="-1" role="dialog" aria-labelledby="jimTModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <h1 class="h1 modal-title fancy" id="jimTModalLabel">Jim Traeger</h1>
        <h2 class="h2">Regional Sales Director</h2>
      </div>
      <div class="modal-body">
      	<img class="img img-responsive" alt="" style="" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/corporate/team/jim-traeger.jpg">
      </div>
    </div>
  </div>
</div>

<div class="modal fade review" id="laurieBModal" tabindex="-1" role="dialog" aria-labelledby="laurieBModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <h1 class="h1 modal-title fancy" id="laurieBModalLabel">Laurie Buck</h1>
        <h2 class="h2">Production Manager</h2>
      </div>
      <div class="modal-body">
      	<img class="img img-responsive" alt="" style="" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/corporate/team/laurie-buck.jpg">
      </div>
    </div>
  </div>
</div>

<div class="modal fade review" id="leannGModal" tabindex="-1" role="dialog" aria-labelledby="leannGModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <h1 class="h1 modal-title fancy" id="leannGModalLabel">LeAnn Gutzke</h1>
        <h2 class="h2">City Manager, Minneapolis</h2>
      </div>
      <div class="modal-body">
      	<img class="img img-responsive" alt="" style="" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/corporate/team/leann-gutzke.jpg">
      </div>
    </div>
  </div>
</div>

<div class="modal fade review" id="corporateModal" tabindex="-1" role="dialog" aria-labelledby="corporateModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <h1 class="h1 modal-title fancy" id="corporateModalLabel">Corporate</h1>
        <h2 class="h2">Bill Davis, Andy Massingill, Will Fobbs, Joe Kudron, Jim Traeger, Kelly Schuck, Emilea Weaver, LeAnn Gutzke, Heather Uballe, Laurie Buck, Mike Gauthier</h2>
      </div>
      <div class="modal-body">
      	<img class="img img-responsive" alt="" style="" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/corporate/team/corporate-team.jpg">
      </div>
    </div>
  </div>
</div>

<div class="modal fade review" id="productionModal" tabindex="-1" role="dialog" aria-labelledby="productionModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <h1 class="h1 modal-title fancy" id="productionModalLabel">Production</h1>
        <h2 class="h2">Kristen Carpenter, Sue Pickett, Vince Mansfield, Susan Eatmon, Bill Gerstenlauer, Brittany April, Kadie Vinson, David Herbst, Laurie Buck, Ashley MacKinnon</h2>
      </div>
      <div class="modal-body">
      	<img class="img img-responsive" alt="" style="" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/corporate/team/production-team.jpg">
      </div>
    </div>
  </div>
</div>

@stop

