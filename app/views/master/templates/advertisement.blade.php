@if($advertisement->path != '')
    <a class="panel" style="display:block;" href="{{$advertisement->url_override ? $advertisement->url_override : URL::abs('/coupons/'.strtolower($geoip->region_name).'/'.SoeHelper::getSlug($geoip->city_name).'/'.$advertisement->category->slug.'/'.$advertisement->subcategory->slug.'/'.$advertisement->merchant->slug)}}">
        <img alt="Banner" class="img-responsive" src="{{$advertisement->path}}">
    </a>
@elseif($advertisement->adable_type == 'VehicleEntity')
    @include('soct.templates.grid-vehicle-entity', array('vehicle' => $advertisement->adable))
@elseif($advertisement->adable_type == 'VehicleStyle')
    @include('soct.templates.new-car', array('vehicle' => $advertisement->adable))
@else
    @include('master.templates.entity', array('entity' => $advertisement->adable))
@endif