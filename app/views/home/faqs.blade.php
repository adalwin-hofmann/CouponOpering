@extends('master.templates.master')
@section('page-title')
<h1>Frequently Asked Questions</h1>
@stop

@section('breadcrumbs')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}" itemprop="url"><span itemprop="title">Home</span></a>
    </li>
    <li class="active">Frequently Asked Questions</li>
@stop

@section('sidebar')
@include('master.templates.corporatesidebar')
@stop

@section('body')
<div class = "content-bg">
<div class="panel-group" id="accordion">
  <div class="panel panel-default">
    <div class="panel-heading">
      <span class="panel-title h4 hblock">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne"><span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span>
          I am having trouble viewing offers or printing my coupons.
        </a>
      </span>
    </div>
    <div id="collapseOne" class="panel-collapse collapse collapse">
      <div class="panel-body">
        This happens from time to time. This can most commonly be resolved by either updating your browser to the most recent version or by trying a different browser. SaveOn.com works best with Chrome, Firefox, and Safari. You can download any one of these browsers for free <a href="http://browsehappy.com/" target="_blank">here</a>.
      </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading">
      <span class="panel-title h4 hblock">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo"><span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span>
          Can I use coupons on my mobile phone? Can I save them for later use if I am on-the-go?
        </a>
      </span>
    </div>
    <div id="collapseTwo" class="panel-collapse collapse">
      <div class="panel-body">
        Absolutely! We have a feature on every coupon that allows you to save your offer and recall it later when you are in a position to use it. Once you save an offer, you can access all of your saved offers from any device or computer that you are logged into. You can see how this feature works <a href="{{URL::abs('/')}}/take-your-coupons-with-you">here</a>. Be sure not to click &quot;redeem&quot;, the Merchant will do that when you get there!
      </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading">
      <span class="panel-title h4 hblock">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseFAQThree"><span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span>
          Can I use coupons without becoming a Member? Why should I become a Member? 
        </a>
      </span>
    </div>
    <div id="collapseFAQThree" class="panel-collapse collapse">
      <div class="panel-body">
        Each SaveOn Member can enjoy savings from their favorite Merchants, enter Contests, set your preferences so that only relevant Deals are coming to you, share Deals with friends and family, and many other features. Plus, becoming a Member on SaveOn.com is free! Lastly, by becoming a Member, we will regularly send you an exclusive newsletter that contains new Contests and Deals especially for you! 
      </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading">
      <span class="panel-title h4 hblock">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour"><span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span>
          There is a Merchant that I love to frequent and they are not currently on SaveOn.com, do you take suggestions? 
        </a>
      </span>
    </div>
    <div id="collapseFour" class="panel-collapse collapse collapse">
      <div class="panel-body">
        Yes! If you have a Merchant that you think would be a great fit for SaveOn, let us know <a href="{{URL::abs('/')}}/suggestmerchant">here</a> and we will reach out to them to see if they want to get involved. 
      </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading">
      <span class="panel-title h4 hblock">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseFive"><span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span>
          How do Contests work?
        </a>
      </span>
    </div>
    <div id="collapseFive" class="panel-collapse collapse collapse">
      <div class="panel-body">
        Each SaveOn.com Member has the opportunity to enter our <a href="{{URL::abs('/')}}/contests">Contests</a>. When you see a Contest you are interested in entering, all you have to do is click the &quot;Click Here To Enter&quot; button and you will be prompted to enter your email address. At each award date, a winner is selected and notified by email (keep an eye on your email!). You will then be prompted to fill out a disclaimer. Once the disclaimer is filled out, we send you your prize! 
      </div>
    </div>
  </div>
</div>
</div>

@stop