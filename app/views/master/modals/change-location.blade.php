  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <span class="h1 modal-title fancy"id="changeLocationModalLabel">Edit Location</span>
      </div>
      <div class="modal-body">
        <div class="update-location-modal">
            <div class="form-group">
              <input type="text" class="form-control" id="changeLocation" name="changeLocation" placeholder="Search for a new city or zip code...">
              <ul class="change-location-dropdown dropdown-menu">
              </ul>
            </div>
              <p class="margin-bottom-0"><strong>Current Location</strong></p>
              <div class="h2 block" id="changeLocationCurrentLocation"><span class="glyphicon glyphicon-map-marker"></span> <span class="current-location-city">{{$geoip->city_name}}</span>, <span class="current-location-state">{{$geoip->region_name}}</span></div>
        </div>
        <hr>
        <div class="suggested-location-modal">
          <div class="h2 block">Suggested Locations</div>
          <div class="row">
            <img src="http://s3.amazonaws.com/saveoneverything_assets/images/ajax-loader.gif" alt="Loading...">
          </div>
        </div>
      </div>
    </div>
  </div>