<div class="item new-car">
    <div class="item-type new-car"></div>
    <div class="top-pic ">
        <a href="{{URL::abs('/')}}/cars/research/{{$vehicle->year}}/{{($vehicle->make_slug && $vehicle->model_slug) ? $vehicle->make_slug.'/'.$vehicle->model_slug.'/' : ''}}{{$vehicle->id}}">
            <div class="expired-banner"><span class="h3 spaced">Expired</span></div>
            <img alt="{{$vehicle->year}} {{$vehicle->make_name}} {{$vehicle->model_name}}" class="img-responsive" src="{{$vehicle->display_image ? $vehicle->display_image : 'http://s3.amazonaws.com/saveoneverything_assets/assets/images/soct/soct-not-found.jpg'}}" onerror="BrokenVehicleImage(this)">
        </a>
    </div>
    <a class="item-info" href="{{URL::abs('/')}}/cars/research/{{$vehicle->year}}/{{($vehicle->make_slug && $vehicle->model_slug) ? $vehicle->make_slug.'/'.$vehicle->model_slug.'/' : ''}}{{$vehicle->id}}">
        <div class="h3">{{$vehicle->year}} {{$vehicle->make_name}} {{$vehicle->model_name}}</div>
        <div class="more-info">
            <p><strong>MPG</strong> {{($vehicle->city_epa != 0)?$vehicle->city_epa.'/'.$vehicle->highway_epa.' mpg':'N/A'}}</p>
            <p><strong>Body Type</strong> {{$vehicle->primary_body_type}}</p>
            <p><strong>MSRP</strong> {{($vehicle->price != 0)?'$'.number_format($vehicle->price,0):'N/A'}}</p>
        </div>
        <div class="incentives special-info" style="{{count($vehicle->incentives) == 0 ? 'display:none;' : ''}}">
            <p><span class="glyphicon glyphicon-ok"></span> {{count($vehicle->incentives) ? $vehicle->incentives[0]['name'] : ''}}</p>
        </div>
    </a>
    <div class="btn-group">
        <button type="button" class="btn btn-default btn-view-new-car" data-url="/cars/research/{{$vehicle->year}}/{{($vehicle->make_slug && $vehicle->model_slug) ? $vehicle->make_slug.'/'.$vehicle->model_slug.'/' : ''}}{{$vehicle->id}}" data-toggle="tooltip" data-placement="top" title="Get More Info on the {{$vehicle->year}} {{$vehicle->make_name}} {{$vehicle->model_name}}"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-burgundy-view-it.png" alt="Get More Info on the {{$vehicle->year}} {{$vehicle->make_name}} {{$vehicle->model_name}}"></button>
        <button type="button" class="btn btn-default btn-new-car-quote" data-vehicle_id="{{$vehicle->id}}" data-toggle="tooltip" data-placement="top" title="Get a Quote on the {{$vehicle->year}} {{$vehicle->make_name}} {{$vehicle->model_name}}"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-burgundy-quote-it.png" alt="Get a Quote on the {{$vehicle->year}} {{$vehicle->make_name}} {{$vehicle->model_name}}"></button>
        <button type="button" class="btn btn-default btn-new-share" data-vehicle_id="{{$vehicle->id}}" data-toggle="tooltip" data-placement="top" title="Share the {{$vehicle->year}} {{$vehicle->make_name}} {{$vehicle->model_name}}"><img src="http://saveoneverything_assets.s3.amazonaws.com/assets/images/icons/icon-burgundy-share-it.png" alt="Share the {{$vehicle->year}} {{$vehicle->make_name}} {{$vehicle->model_name}}"></button>
    </div>
</div>