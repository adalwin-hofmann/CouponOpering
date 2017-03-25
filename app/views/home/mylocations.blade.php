@extends('master.templates.master')

@section('page-title')
<h1>My Locations</h1>
@stop

@section('breadcrumbs')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}" itemprop="url"><span itemprop="title">Home</span></a>
    </li>
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}/members/mysettings" itemprop="url"><span itemprop="title">Account Settings</span></a>
    </li>
    <li class="active">My Favorite Locations</li>
@stop

@section ('sidebar')

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
<script type="text/ejs" id="template_saved">
<% list(locations, function(location)
{ %>
    <tr>
        <td class="city-name"><%= location.city.toLowerCase()+', '+location.state %></td>
        <td><button type="button" class="btn btn-green other-city" data-latitude="<%= location.latitude %>" data-longitude="<%= location.longitude %>" data-city="<%= location.city %>" data-state="<%= location.state %>">Use This Location</button></td>
        <td><button type="button" class="btn btn-default remove-saved-location" data-location_id="<%= location.id %>" align = "left">Remove Location</button></td>
    </tr>
<% }); %>
</script>

<div class="content-bg">

	<table class="table">
	<thead>
        <th><h2>My Favorite Locations</h2></th>
        <th></th>
        <th></th>
    </thead>
    <tbody class="saved-location-results">
    
    </tbody>
	</table>
	
	<p>&nbsp;</p>

	<script type="text/ejs" id="template_add_location">
	  <% list(searchLocations.data, function(searchLocation)
	  { %>
	    <li class="add-city" data-latitude="<%= searchLocation.latitude %>" data-longitude="<%= searchLocation.longitude %>"  data-city="<%= searchLocation.city %>"  data-state="<%= searchLocation.state %>"><a href ="#"><%= searchLocation.city.toLowerCase() %>, <%= searchLocation.state %></a></li>
	  <% }); %>
	</script>

	<div class="row">
		<div class="col-md-8 col-xs-12">
			<div class="form-group">
				<div class="add-location">
	        		<input type="text" class="form-control" placeholder="Search for a new city or zipcode..." title="" data-toggle="tooltip" data-original-title="Search for a City or Zipcode" autocomplete="off">
					<ul class="add-location-dropdown dropdown-menu">
                	</ul>
	        	</div>
	      	</div>
  		</div>
  	</div>
</div>
@stop