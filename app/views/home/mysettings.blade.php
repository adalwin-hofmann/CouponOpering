@extends('master.templates.master')
@section('page-title')
<h1>My Settings</h1>
@stop

@section('breadcrumbs')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}" itemprop="url"><span itemprop="title">Home</span></a>
    </li>
    <li class="active">My Account Settings</li>
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
@include('master.templates.member-sidebar')
@include('master.templates.sidebar-offers')


<div class="ad"><-- Insert Image Here --></div>
<div class="ad"><div class="" style="background-color:#CCCCCC; width: 100%; min-height: 100px"><p>Advertising</p></div></div>

@stop

@section('body')
<div class="content-bg">
	<div class="row">
		<div class = "col-md-6 ">
			<form action="/members/change-user-info" method="post" role = "form">
				<div class = "form-group">
					<span class="h3 inline">Name</span>
					<div class="relative">
						<input type ="text" name="name" class = "form-control" placeholder = "{{$user->name}}" value="{{$user->name}}"/>
					</div>
					<br>
					<span class="h3 inline">E-Mail</strong></span><button type="button" data-trigger="focus" data-content="Your email address is your username. To change your email settings, go to the My Notifications page." data-placement="right" class="popover-info btn-link"><span class="glyphicon glyphicon-question-sign"></span></button>
					<div class="relative">
						<input type = "email" name="email" class = "form-control" size = "30" placeholder = "{{$user->email}}" value= "{{$user->email}}"/>
						
					</div>
                    <span style="red">{{$errors->first('email')}}</span>
					<br>

					<span class="h3 inline">Birthday</span><button type="button" href="#" data-trigger="focus" data-content="Please enter your birthday to verify you are at least 13 years of age." data-placement="right" class="popover-info btn-link"><span class="glyphicon glyphicon-question-sign"></span></button>
					<div class = "row">
						<div class = "col-xs-4">
							<select name = "month" class = "form-control">
								<option value = "01" {{date('m',strtotime($user->birthday)) == 01 ? 'selected' : ''}}>01</option>
								<option value = "02" {{date('m',strtotime($user->birthday)) == 02 ? 'selected' : ''}}>02</option>
								<option value = "03" {{date('m',strtotime($user->birthday)) == 03 ? 'selected' : ''}}>03</option>
								<option value = "04" {{date('m',strtotime($user->birthday)) == 04 ? 'selected' : ''}}>04</option>
								<option value = "05" {{date('m',strtotime($user->birthday)) == 05 ? 'selected' : ''}}>05</option>
								<option value = "06" {{date('m',strtotime($user->birthday)) == 06 ? 'selected' : ''}}>06</option>
								<option value = "07" {{date('m',strtotime($user->birthday)) == 07 ? 'selected' : ''}}>07</option>
								<option value = "08" {{date('m',strtotime($user->birthday)) == 08 ? 'selected' : ''}}>08</option>
								<option value = "09" {{date('m',strtotime($user->birthday)) == 09 ? 'selected' : ''}}>09</option>
								<option value = "10" {{date('m',strtotime($user->birthday)) == 10 ? 'selected' : ''}}>10</option>
								<option value = "11" {{date('m',strtotime($user->birthday)) == 11 ? 'selected' : ''}}>11</option>
								<option value = "12" {{date('m',strtotime($user->birthday)) == 12 ? 'selected' : ''}}>12</option>
							</select>
						</div>
						<div class = "col-xs-4">
							<select name = "day" class = "form-control">
                                @for($i=1; $i<32; $i++)
								<option value = "{{str_pad($i, 2, '0', STR_PAD_LEFT)}}" {{date('j',strtotime($user->birthday)) == $i ? 'selected' : ''}}>{{$i}}</option>
                                @endfor
							</select>
						</div>
						<div class = "col-xs-4">
							<select name = "year" class = "form-control">
                                @for($i=90; $i>0; $i--)
								<option value = "{{date('Y') - $i}}" {{(date('Y') - $i) == date('Y', strtotime($user->birthday)) ? 'selected' : ''}}>{{date('Y') - $i}}</option>
                                @endfor
							</select>
						</div>
					</div>
					<br>
                    <span class="h3 inline">Gender</span><button type="button" href="#" data-trigger="focus" data-content="Providing your gender helps us find coupons, deals, and contests that are customized and catered to your interests." data-placement="right" class="popover-info btn-link"><span class="glyphicon glyphicon-question-sign"></span></button>
                    <select name = "gender" class = "form-control">
                        <option selected value = "Choose Gender">&#10004; Choose Gender</option>
                        <option value = "M" {{$user->sex == 'M' ? 'selected' : ''}}>Male</option>
                        <option value = "F" {{$user->sex == 'F' ? 'selected' : ''}}>Female</option>
                        <option value = "NoResponse" {{$user->sex == '' ? 'selected' : ''}}>I prefer not to respond.</option>
                    </select>

                    <span style="color:green;">{{Session::has('settings_saved') ? 'Settings saved.' : ''}}</span>
                    <br>

                    <button type="submit" class="btn btn-lg btn-green pull-right">Save Changes</button>
				</div>
			</form>
			<div class="clearfix"></div>
		</div>
		<div class = "col-md-6">
			<form action="/members/change-password" method="post" role = "form">
				<div class = "form-group">
					<span class="h3">Password</span>
                    <input type = "password" class = "form-control" name="current_password" placeholder = "Current Password"/>
                    @if(Session::has('invalid_password')) <span style="color:red;">Incorrect password entered.</span> @endif
                    <br>
					<input type = "password" class = "form-control" name="password" placeholder = "Change Password"/>
                    <span style="color:red;">{{$errors->first('password')}}</span>
					<br>
					<input type = "password" class = "form-control" name="password_confirmation" placeholder = "Confirm Password"/>
                    <span style="color:green;">{{Session::has('password_changed') ? 'Password changed successfully.' : ''}}</span>
					<br>
                    <button type="submit" class="btn btn-lg btn-blue pull-right">Change Password</button>
					<br>
					<br>
					<br><br>
					<br><br>
				</div>
			</form>
		</div>
	</div>
	<hr>
	<div class="row">
		<div class="col-xs-6">
			<button type="button" data-toggle="modal" data-target="#deleteAccountModal" class="btn btn-grey pull-left" align = "left">Delete Account</button>
		</div>
		<div class="col-xs-6">
		
		</div>
	</div>
</div>
@stop