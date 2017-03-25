<div class="panel panel-default">
	    <div class="panel-heading">
	      <span id="navCurrentCityMobile" class="h4 hblock panel-title"><span class="glyphicon glyphicon-map-marker"></span> Set Location</span>
	    </div>
	    <div class="panel-collapse collapse in">
		    <div class="panel-body">
		    	<ul>
		  			<li class="location-search">
						<form class="navbar-form" role="search">
							<div class="form-group">
								<input type="text" class="form-control" style="display:none">
	                    		<input type="text" class="form-control navlocation" placeholder="Search for a new city or zipcode..." title="" data-toggle="tooltip" data-original-title="Search for a City or Zipcode">
	                  		</div>
	                  	</form>
              		</li>
              		<div class="search-locations"></div>
					<div class="static-locations">
		                <li class="current-city"><a href="#"><span class="current-location-city">{{ucwords(strtolower($geoip->city_name))}}</span>, <span class="current-location-state">{{$geoip->region_name}}</span> @if(Auth::check())<span href="" class="glyphicon glyphicon-heart favorite pull-right"></span>@endif</a></li>
					    @if(Auth::check())
					    <hr>
					    <li class="panel-inside-header">Saved Locations</li>
					    <div class="saved-location-area">
	                        
	                    </div>
					    <li><a href="{{URL::abs('/')}}/member/mylocations" class= "btn btn-default">View All</a></li>
					    <li class="divider"></li>
					    <li class="panel-inside-header">Want to find locations nearby?</li>
					    <li><a id="btnChangeLocation" data-toggle="modal" data-target="#changeLocationModal">See our suggested locations &gt;</a></li>
					    @else
					    <hr>
					    <li class="panel-inside-header">Suggested Locations</li>
					    <div class="nearbyLocations">
						    <li><a><img src="http://s3.amazonaws.com/saveoneverything_assets/images/ajax-loader.gif" alt="Loading..."></a></li>
						</div>
					    <li><a id="btnChangeLocation" data-toggle="modal" data-target="#changeLocationModal"><strong>View More</strong> &gt;</a></li>
					    <hr>
					    <li class="panel-inside-header">Saved Locations</li>
					    <li class="dropdown-disclaimer">You can easily save your favorite locations by becoming a member. <a class="inline" href="#" data-toggle="modal" data-target="#signUpBenefitsModal"><strong>Find our more.</strong></a></li>
					    @endif
					</div>
				</ul>
			</div>
		</div>
	</div>
