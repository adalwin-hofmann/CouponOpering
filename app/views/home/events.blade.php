@extends('master.templates.master')
@section('page-title')
<h1>{{$event->name}}</h1>
@stop

@section('breadcrumbs')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}" itemprop="url"><span itemprop="title">Home</span></a>
    </li>
    <li class="active">{{$event->name}}</li>
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
	<h2>Sign Up Below</h2>
	<p>Please enter your information below to attend {{$event->name}}.</p>

	<p><strong>Date:</strong> {{date('n/j/Y', strtotime($event->date))}} - <strong>Time:</strong> {{date('g:i a', strtotime($event->date))}}{{($event->end_datetime != '0000-00-00 00:00:00')?' - '.date('g:i a', strtotime($event->end_datetime)):''}}</p>

	<hr>

	<p>{{$event->description}}</p>

	@if(Session::has('attendee_submit'))
	<p class="alert alert-success">Thank you for signing up!</p>
	@endif

	<form method="post" action="{{URL::abs('/')}}/events-submit/{{$event->id}}">
		<div class="form-group">
			<input type="text" class="form-control" id="attendee_name" name="attendee_name" placeholder="Enter Name" required="required">
		</div>
		<div class="row">
			<div class="col-sm-6 form-group">
				<input type="email" class="form-control" id="attendee_email" name="attendee_email" placeholder="Enter Email" required="required">
			</div>
			<div class="col-sm-6 form-group">
				<input type="text" class="form-control" id="attendee_company" name="attendee_company" placeholder="Enter Company Name" required="required">
			</div>
		</div>
		<div class="form-group text-center">
			<input type="hidden" id="event_id" name="event_id" value="{{$event->id}}">
			<button type="submit" class="btn btn-black btn-lg">Submit</button>
		</div>
	</form>
</div>

<?php
	Session::put('newUser', 1);
?>

@stop