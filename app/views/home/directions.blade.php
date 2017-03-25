@extends('master.templates.master')

@section('page-title')
<h1>{{$merchant->display}}</h1>
@stop

@section('breadcrumbs')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}" itemprop="url"><span itemprop="title">Home</span></a>
    </li>
  	<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
    	<a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/{{$catSlug}}" itemprop="url"><span itemprop="title">{{$catName}}</span></a>
    </li>
  	<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
    	<a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/{{$catSlug}}/{{$subcatSlug}}" itemprop="url"><span itemprop="title">{{$subcatName}}</span></a>
    </li>
  	<li class="active">{{$merchant->display}}</li>
@stop

@section('sidebar')

	<div class="content-bg margin-bottom-15 hidden-xs">
		<img class="img-responsive center-block" src="{{(!empty($logo))?$logo->path:''}}" alt="{{$merchant->display}}" itemprop="logo">
	</div>

    @if($updated['stats']['returned'] > 0)
	<div class="panel panel-default">
	    <div class="panel-heading">
	      <span class="h4 panel-title hblock">
	        <a data-toggle="collapse" href="#collapseUpdated">Recently Updated <span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span></a>
	        <div class="clearfix"></div>
	      </span>
	    </div>
	    <div id="collapseUpdated" class="panel-collapse collapse in">
		    <div class="panel-body"><!-- Added class explore-links for bold -->
		      	<ul>
                @foreach($updated['objects'] as $location)
                    <li><a href="{{ URL::abs('/coupons/'.strtolower($location->state).'/'.SoeHelper::getSlug($location->city).'/'.$catSlug.'/'.$subcatSlug.'/'.$merchant->slug.'/'.$location->id) }}">{{ $merchant->display.' in '.ucwords(strtolower($location->city)).', '.strtoupper($location->state) }}</a></li>
                @endforeach
				</ul>
		    </div>
	    </div>
	</div>
    @endif

	@if(count($images['objects']) >= 4)
	<div class="content-bg">
		<div class="row margin-bottom-20">
			<div class="col-xs-6">
				<img alt="{{$merchant->display}} Image 1" class="img-responsive pointer" src="{{$images['objects'][0]->path}}" data-toggle="modal" data-target="#imageModal0">
			</div>
			<div class="col-xs-6">
				<img alt="{{$merchant->display}} Image 2" class="img-responsive pointer" src="{{$images['objects'][1]->path}}" data-toggle="modal" data-target="#imageModal1">
			</div>
		</div>
		<div class="row">
			<div class="col-xs-6">
				<img alt="{{$merchant->display}} Image 3" class="img-responsive pointer" src="{{$images['objects'][2]->path}}" data-toggle="modal" data-target="#imageModal2">
			</div>
			<div class="col-xs-6">
				<img alt="{{$merchant->display}} Image 4" class="img-responsive pointer" src="{{$images['objects'][3]->path}}" data-toggle="modal" data-target="#imageModal3">
			</div>
		</div>
	</div>
	@endif

@stop

@section('body')

<script type='text/ejs' id='template_locations'>
<% 
	startPagination = 0;
	lastPage = Math.floor(locations.stats.total / locations.stats.take);
%>
<% if (locations.stats.total > locations.stats.returned) { %>
	<% if ((locations.stats.page == 0) || (locations.stats.page == 1)) {
		startPagination = 0;
	} else {
		startPagination = locations.stats.page - 1;
	}
	
%>
<ul class="pagination">
  	<li class="<%== (locations.stats.page == 0)? 'disabled': '' %>"><a data-page="<%== (locations.stats.page == 0)? '0': locations.stats.page - 1 %>">&laquo;</a></li>
  	<% for(var i=startPagination;i<((lastPage === 0)? (locations.stats.total/locations.stats.take) - 1 : locations.stats.total/locations.stats.take) && i<(Number(Number(startPagination) + Number(4)));i++) { %>
  	<li class="hidden-sm <%== (locations.stats.page == i)? 'active': '' %>" style=""><a data-page="<%= i %>"><%== i + 1  %></a></li>
  	<% } %>
  	<% if (((locations.stats.total/locations.stats.take) > 4) && (lastPage >= (locations.stats.page + 2))) { %>
	<li class="hidden-sm <%== (locations.stats.page == ((locations.stats.total % locations.stats.take === 0)? (locations.stats.total/locations.stats.take) - 1 : locations.stats.total/locations.stats.take))? 'active': '' %>"><a data-page="<%== lastPage %>"><%== lastPage + 1 %></a></li>
	<% } %>
  	<li class="<%== (lastPage == locations.stats.page)? 'disabled': '' %>"><a data-page="<%== (locations.stats.total <= locations.stats.returned)? locations.stats.page : Number(Number(locations.stats.page) + Number(1)) %>">&raquo;</a></li>
</ul>
<% } %>
<% list(locations, function(locations)
{ %>
	<div class="row location">
		<div class="col-sm-12">
			<div class="row">
				<a class="marker" data-latitude="<%= locations.latitude %>" data-longitude="<%= locations.longitude %>" data-id="<%= locations.id %>"><img alt="Map Marker" class="img-responsive pull-left hidden-xs" src="/img/marker-icon.png"></a>
				<h3><a href="{{URL::abs('/')}}/coupons/<%= String(locations.state).toLowerCase() %>/<%= slugify(locations.city) %>/{{$catSlug}}/{{$subcatSlug}}/{{$merchant->slug}}/<%= locations.id %>"><%= locations.merchant_name %></a></h3>
			</div>
			<div class="row">
				<div class="col-xs-12" style="display: <%== (locations.rating_count == 0)?'none':'' %>">
				<div class="progress-popular avg" style="" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
			        <meta itemprop="worstRating" content = "0">
                    <meta itemprop="ratingValue" content = "<%= locations.rating %>">
                    <meta itemprop="bestRating" content = "5">
			        <div class="bar-popular-container" style="width: <%= locations.rating / 5 * 100 %>%;">
			            <div class="bar-popular stars" style="width: 150px;"></div>
			        </div>
			    </div>
			</div>
		</div>
			<address><%= locations.address %><br>
            <%= locations.address2 %><%==(locations.address2 == '')?'':'<br>' %>
            <%= locations.city %>, <%= locations.state %> <%= locations.zip %><br>
				P: <%= locations.phone %></address>
				
				<div class="row">
					<div class="col-md-6 col-sm-12 col-xs-6">
						<a href="{{URL::abs('/')}}/coupons/<%= String(locations.state).toLowerCase() %>/<%= slugify(locations.city) %>/{{$catSlug}}/{{$subcatSlug}}/{{$merchant->slug}}/<%= locations.id %>" class="btn btn-default btn-block btn-green">View Location</a>
					</div>
					<div class="col-md-6 col-sm-12 col-xs-6">
						<a href="http://maps.google.com/?q=<%= locations.address %> <%= locations.address2 %> <%= slugify(locations.city) %>, <%= locations.state %> <%= locations.zip %>" class="btn btn-block btn-link" target="_blank">Get Directions</a>
					</div>
				</div>
				<p><a style="display:{{(Feature::findByName('info_correct')->value == 0)?'none':''}}" href="#" style="margin-top:10px; display:inline-block" data-toggle="modal" data-target="#accurateInfoModal">Is this information accurate?</a></p>
		</div>
	</div>
<% }); %>
<ul class="pagination">
  	<li class="<%== (locations.stats.page == 0)? 'disabled': '' %>"><a data-page="<%== (locations.stats.page == 0)? '0': locations.stats.page - 1 %>">&laquo;</a></li>
  	<% for(var i=startPagination;i<((lastPage === 0)? (locations.stats.total/locations.stats.take) - 1 : locations.stats.total/locations.stats.take) && i<(Number(Number(startPagination) + Number(4)));i++) { %>
  	<li class="hidden-sm <%== (locations.stats.page == i)? 'active': '' %>" style=""><a data-page="<%= i %>"><%== i + 1  %></a></li>
  	<% } %>
  	<% if (((locations.stats.total/locations.stats.take) > 4) && (lastPage >= (locations.stats.page + 2))) { %>
	<li class="hidden-sm <%== (locations.stats.page == ((locations.stats.total % locations.stats.take === 0)? (locations.stats.total/locations.stats.take) - 1 : locations.stats.total/locations.stats.take))? 'active': '' %>"><a data-page="<%== lastPage %>"><%== lastPage + 1 %></a></li>
	<% } %>
  	<li class="<%== (lastPage == locations.stats.page)? 'disabled': '' %>"><a data-page="<%== (locations.stats.total <= locations.stats.returned)? locations.stats.page : Number(Number(locations.stats.page) + Number(1)) %>">&raquo;</a></li>
</ul>

</script>

<script type="text/ejs" id="template_find_location">
  <% list(searchLocations.data, function(searchLocation)
  { %>
    <li class="find-location-city" data-latitude="<%= searchLocation.latitude %>" data-longitude="<%= searchLocation.longitude %>"  data-city="<%= searchLocation.city %>"  data-state="<%= searchLocation.state %>"><a><%= searchLocation.city.toLowerCase() %>, <%= searchLocation.state %></a></li>
  <% }); %>
</script>

	<div class="content-bg margin-bottom-15 visible-xs">
		<img class="img-responsive center-block" src="{{(!empty($logo))?$logo->path:''}}" alt="{{$merchant->display}}" itemprop="logo">
	</div>

		<div class="content-bg">
			<div class="row">
				<div class="col-sm-5 col-lg-4 col-xs-12">
					<div class="row">
						<div class="col-xs-12">
								<!-- <a href="{{URL::abs('/')}}/coupons/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/{{$catSlug}}/{{$subcatSlug}}/{{$merchant->slug}}">Return to Merchant Page</a> -->
							<h2>{{$merchant->display}} Locations{{ $zipcode ? ' Near '.$zipcode : '' }}</h2>
							<label>Find locations near a zip code:</label>
                            <form action="{{URL::abs('/directions/'.$merchant->slug)}}" method="POST">
							    <div class="input-group search-zipcode">
                                
						            <input name="zipcode" type="text" class="form-control" placeholder="Enter Zip Code">
							        <span class="input-group-btn">
							            <button class="btn btn-default btn-green" type="submit">Go!</button>
							        </span>
						        </div>
                            </form>
						</div>
					</div>

					<div class="location-list">
						<ul class="pagination">
                            <li class="{{ $locations['stats']['page'] == 0 ? 'disabled' : '' }}"><a {{ $locations['stats']['page'] == 0 ? '' : 'rel="prev"' }} {{ $locations['stats']['page'] == 0 ? '' : 'href="'.URL::abs('/directions/'.$merchant->slug.'?page='.($locations['stats']['page'] == 0 ? '0': $locations['stats']['page'] - 1)).'"' }}>&laquo;</a></li>
                            @for($i = $startPagination; $i < ($lastPage === 0 ? ($locations['stats']['total'] / $locations['stats']['take']) - 1 : $locations['stats']['total'] / $locations['stats']['take']) && $i < ($startPagination + 4); $i++)
                            <li class="hidden-sm {{ $locations['stats']['page'] == $i ? 'active' : '' }}" style=""><a {{ $locations['stats']['page'] - 1  == $i ? 'rel="prev"' : '' }} {{ $locations['stats']['page'] + 1 == $i ? 'rel="next"' : '' }} href="{{ URL::abs('/directions/'.$merchant->slug.'?page='.$i) }}">{{ $i + 1 }}</a></li>
                            @endfor
                            @if((($locations['stats']['total'] / $locations['stats']['take']) > 4) && ($lastPage >= ($locations['stats']['page'] + 2)))
                            <li class="hidden-sm {{ $locations['stats']['page'] == ($locations['stats']['total'] % $locations['stats']['take'] === 0 ? ($locations['stats']['total'] / $locations['stats']['take']) - 1 : $locations['stats']['total'] / $locations['stats']['take']) ? 'active': '' }}"><a href=" {{URL::abs('/directions/'.$merchant->slug.'?page='.$lastPage) }}">{{ $lastPage + 1 }}</a></li>
                            @endif
                            <li class="{{ $lastPage == $locations['stats']['page'] ? 'disabled': '' }}"><a {{ $lastPage == $locations['stats']['page'] ? '': 'rel="next"' }} {{ $lastPage == $locations['stats']['page'] ? '' : 'href="'.URL::abs('/directions/'.$merchant->slug.'?page='.($locations['stats']['total'] <= $locations['stats']['returned'] ? $locations['stats']['page'] : $locations['stats']['page'] + 1)).'"' }}>&raquo;</a></li>
                        </ul>
                    	<div class="clearfix"></div>
                    @foreach($locations['objects'] as $location)
                        <div class="row location">
                            <div class="col-sm-12">
                                <div class="row">
                                    <a class="marker" data-latitude="{{ $location->latitude }}" data-longitude="{{ $location->longitude }}" data-id="{{ $location->id }}"><img alt="Map Marker" class="img-responsive pull-left hidden-xs" src="/img/marker-icon.png"></a>
                                    <h3><a href="{{URL::abs('/')}}/coupons/{{ strtolower($location->state) }}/{{ SoeHelper::getSlug($location->city) }}/{{ $catSlug }}/{{ $subcatSlug }}/{{ $merchant->slug }}/{{ $location->id }}">{{ $merchant->display.' in '.ucwords(strtolower($location->city)).', '.strtoupper($location->state) }}</a></h3>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12" style="display: {{ $location->rating_count == 0 ? 'none' : '' }}">
                                    <div class="progress-popular avg" style="" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
                                        <meta itemprop="worstRating" content = "0">
                                        <meta itemprop="ratingValue" content = "{{ $location->rating }}">
                                        <meta itemprop="bestRating" content = "5">
                                        <div class="bar-popular-container" style="width: {{ $location->rating / 5 * 100 }}%;">
                                            <div class="bar-popular stars" style="width: 150px;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                <address>{{ $location->address }}<br>
                                {{ $location->address2 }}{{ $location->address2 == '' ? '' : '<br>' }}
                                {{ $location->city }}, {{ $location->state.' '.$location->zip }}<br>
                                    P: {{ $location->phone }}</address>
                                    
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12 col-xs-6">
                                            <a href="{{URL::abs('/')}}/coupons/{{ strtolower($location->state) }}/{{ SoeHelper::getSlug($location->city) }}/{{ $catSlug }}/{{ $subcatSlug }}/{{ $merchant->slug }}/{{ $location->id }}" class="btn btn-default btn-block btn-green">View Location</a>
                                        </div>
                                        <div class="col-md-6 col-sm-12 col-xs-6">
                                            <a href="http://maps.google.com/?q={{$location->latitude.','.$location->longitude}}" class="btn btn-block btn-link" target="_blank">Get Directions</a>
                                        </div>
                                    </div>
                                    <p><a style="display:{{(Feature::findByName('info_correct')->value == 0)?'none':''}}" href="#" style="margin-top:10px; display:inline-block" data-toggle="modal" data-target="#accurateInfoModal">Is this information accurate?</a></p>
                            </div>
                        </div>
                    @endforeach
                    	<div class="clearfix"></div>
                        <ul class="pagination">
                            <li class="{{ $locations['stats']['page'] == 0 ? 'disabled' : '' }}"><a {{ $locations['stats']['page'] == 0 ? '' : 'rel="prev"' }} {{ $locations['stats']['page'] == 0 ? '' : 'href="'.URL::abs('/directions/'.$merchant->slug.'?page='.($locations['stats']['page'] == 0 ? '0': $locations['stats']['page'] - 1)).'"' }}>&laquo;</a></li>
                            @for($i = $startPagination; $i < ($lastPage === 0 ? ($locations['stats']['total'] / $locations['stats']['take']) - 1 : $locations['stats']['total'] / $locations['stats']['take']) && $i < ($startPagination + 4); $i++)
                            <li class="hidden-sm {{ $locations['stats']['page'] == $i ? 'active' : '' }}" style=""><a {{ $locations['stats']['page'] - 1  == $i ? 'rel="prev"' : '' }} {{ $locations['stats']['page'] + 1 == $i ? 'rel="next"' : '' }} href="{{ URL::abs('/directions/'.$merchant->slug.'?page='.$i) }}">{{ $i + 1 }}</a></li>
                            @endfor
                            @if((($locations['stats']['total'] / $locations['stats']['take']) > 4) && ($lastPage >= ($locations['stats']['page'] + 2)))
                            <li class="hidden-sm {{ $locations['stats']['page'] == ($locations['stats']['total'] % $locations['stats']['take'] === 0 ? ($locations['stats']['total'] / $locations['stats']['take']) - 1 : $locations['stats']['total'] / $locations['stats']['take']) ? 'active': '' }}"><a href=" {{URL::abs('/directions/'.$merchant->slug.'?page='.$lastPage) }}">{{ $lastPage + 1 }}</a></li>
                            @endif
                            <li class="{{ $lastPage == $locations['stats']['page'] ? 'disabled': '' }}"><a {{ $lastPage == $locations['stats']['page'] ? '': 'rel="next"' }} {{ $lastPage == $locations['stats']['page'] ? '' : 'href="'.URL::abs('/directions/'.$merchant->slug.'?page='.($locations['stats']['total'] <= $locations['stats']['returned'] ? $locations['stats']['page'] : $locations['stats']['page'] + 1)).'"' }}>&raquo;</a></li>
                        </ul>
					</div>
		  		</div>
		  		<div class="col-sm-7 col-lg-8 hidden-xs">
                    <div class="row margin-bottom-20">
                        <div class="col-md-12">
		  			        <div id="map"></div>
                        </div>
                    </div>
                    <div class="row offer-results-grid">
                    @foreach($coupons['objects'] as $entity)
                    	<div class="col-md-6 margin-bottom-15 show-city-state">
                        @include('master.templates.entity', array('entity' => $entity))
                        <div class="clearfix"></div>
                        </div>
                    @endforeach
                    </div>
		  		</div>
		  		
		</div>
	</div>

<script>
	merchant_id = '{{$merchant->id}}';
	merchant_slug = '{{$merchant->slug}}';
    category_slug = '{{$catSlug}}';
    subcategory_slug = '{{$subcatSlug}}';
    locationsJson = {{ $locationsJson }};
</script>

@if(count($images['objects']) >= 4)
<?php $imageBig = 0; ?>
<?php while($imageBig < 4) { ?>
<div class="modal fade" id="imageModal{{$imageBig}}" tabindex="-1" role="dialog" aria-labelledby="imageModal{{$imageBig}}Label" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      	<div class="modal-header">
        	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
      	</div>
      	<div class="modal-body">
  			<img alt="{{$merchant->display}} Image {{$imageBig}} Large" class="img-responsive center-block" src="{{$images['objects'][$imageBig]->path}}">
  			<div class="image-title-div">{{$images['objects'][$imageBig]->short_description}}</div>
	        <div class="imagetextdiv" style="">{{$images['objects'][$imageBig]->long_description}}</div>
      	</div>
    </div>
  </div>
</div>
<?php $imageBig++; ?>
<?php } ?>

@endif

@stop









