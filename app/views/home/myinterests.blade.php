@extends('master.templates.master')
@section('page-title')
<h1>My Interests</h1>
@stop

@section('breadcrumbs')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}" itemprop="url"><span itemprop="title">Home</span></a>
    </li>
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}/members/mysettings" itemprop="url"><span itemprop="title">Account Settings</span></a>
    </li>
    <li class="active">My Interests</li>
@stop

@section('sidebar')
<div class="panel panel-default explore-sidebar">
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
    <form role="form" action="save-preferences" method="post">
    	<div class="row">
    		<div class = "col-md-6">
    			<h2>Select the categories that interest you.</h2>
    			<div class="checkbox"><label><input name="food_dining" type = "checkbox" {{$user->food_dining_preference ? 'checked': ''}}/>  Food and Dining</label></div>
    			<div class="checkbox"><label><input name="home_services" type = "checkbox" {{$user->home_services_preference ? 'checked': ''}}/>  Home Improvement</label></div>
    			<div class="checkbox"><label><input name="health_beauty" type = "checkbox" {{$user->health_beauty_preference ? 'checked': ''}}/>  Health &amp; Beauty</label></div>
    			<div class="checkbox"><label><input name="auto_transportation" type = "checkbox" {{$user->auto_transportation_preference ? 'checked': ''}}/>  Automotive</label></div>
    			<div class="checkbox"><label><input name="travel_entertainment" type = "checkbox" {{$user->travel_entertainment_preference ? 'checked': ''}}/>  Travel &amp; Fun</label></div>
    			<div class="checkbox"><label><input name="retail_fashion" type = "checkbox" {{$user->retail_fashion_preference ? 'checked': ''}}/>  Retail</label></div>
    			<div class="checkbox"><label><input name="special_services" type = "checkbox" {{$user->special_services_preference ? 'checked': ''}}/>  Everything Else</label></div>
    			<a id="check-all" class="btn btn-link">Check All  </a><a class="btn btn-link">|</a><a id="uncheck-all" class="btn btn-link">  Uncheck All</a>
    			<br>
                <span style="color:green;">{{Session::has('settings_saved') ? 'Preferences saved. <a href="'.URL::abs('/').'">See your updated recommendations.</a>' : ''}}</span>
    		</div>
    		<div class="col-xs-12 visible-sm visible-xs"><hr></div>
    		<div class = "col-md-6">

    		</div>
    	</div>
    	<hr>
    	<div class="row">
    		<div class="col-md-6">
    			
    		</div>
    		<div class="col-md-6">
    			<button type="submit" class="btn btn-black pull-right" align = "left">Save Changes</button>
    		</div>
    	</div>
    </form>
</div>
@stop


