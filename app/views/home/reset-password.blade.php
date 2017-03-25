@extends('master.templates.master')
@section('page-title')
<h1>Password Reset</h1>
@stop
@section('sidebar')

<div class="panel panel-default">
    <div class="panel-heading">
      <span class="h4 hblock panel-title">
        <a data-toggle="collapse" href="#collapseOne" class="collapsed">Explore Coupons <span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span></a>
        <div class="clearfix"></div>
      </span>
    </div>
    <div id="collapseOne" class="panel-collapse collapse">
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
	<div class="row">
		<div class="col-md-3"></div>
		<div class="col-xs-12 col-md-6">
			<form action="/reset-password" method="post" role = "form">
                <div class="form-group">
                    <label for="password">Email</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="Email Address"/>
                    <span style="color:red;">{{$errors->first('email')}}</span>
                </div>
				<div class="form-group">
					<label for="password">New Password</label>
					<input type="password" class="form-control" name="password" id="password" placeholder="Change Password"/>
		            <span style="color:red;">{{$errors->first('password')}}</span>
		        </div>
		        <div class="form-group">
		        	<label for="password">Confirm Password</label>
					<input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password"/>
		            <span style="color:green;">{{Session::has('password_changed') ? 'Password changed successfully.' : ''}}</span>
				</div>
                <input type="hidden" name="uniq" value="{{$uniq}}"/>
                @if(Session::has('invalid'))<span class="pull-left" style="color:red;">This password reset is invalid, <br/>please request a new one <a href="#" data-toggle="modal" data-target="#forgotPasswordModal">here.</a></span>@endif
		        <button type="submit" class="btn btn-lg btn-blue pull-right">Reset Password</button>
		        <div class="clearfix"></div>
			</form>
		</div>
		<div class="col-md-3"></div>
	</div>
</div>
@stop