<?php
$geoip = json_decode(GeoIp::getGeoIp('json'));
$features = App::make('FeatureRepositoryInterface');
$new_year_start = $features->findByName('new_car_start_year');
$new_year_end = $features->findByName('new_car_end_year');
$new_year_end = $new_year_end ? $new_year_end->value : date('Y');
$new_year_start = $new_year_start ? $new_year_start->value : date('Y');
$vehicle_years = SOE\DB\VehicleYear::where('year', '<=', $new_year_end)->groupBy('year')->orderBy('year','desc')->get(array('year'));
$earliest_year = SOE\DB\VehicleYear::groupBy('year')->orderBy('year','asc')->first(array('year'));
$vehicle_makes = SOE\DB\VehicleMake::orderBy('name')->get(array('id', 'name', 'slug'));
$full_detroit_only = $features->findByName('full_soct_detroit_only');
$full_detroit_only = empty($full_detroit_only) ? 120 : $full_detroit_only->value;
if($full_detroit_only)
{
    $distance = GeometryHelper::getDistance($geoip->latitude, $geoip->longitude, 42.38, -83.10);
    $full_detroit_only = ($distance > $full_detroit_only || $geoip->region_name != 'MI') ? 1 : 0;
}

?>

<script type="text/ejs" id="template_featured_dealer">
<% list(featured, function(feat){ %>
<div class="item featured-dealer">
    <div class="item-type featured"></div>
    <div class="row">
        <div class="col-sm-6 col-md-4 banner-img">
            <a href="{{URL::abs('/')}}/coupons/auto-transportation/auto-dealers/<%= feat.merchant.slug %>"><img class="img-responsive relative" src="<%= feat.display_image.path %>">
            </a>
        </div>
        <div class="col-sm-6 col-md-8 banner-info">
            <a class="item-info" href="{{URL::abs('/')}}/coupons/auto-transportation/auto-dealers/<%= feat.merchant.slug %>">
                <span class="h3"><%= feat.merchant.display %></span>
                <div class="banner-catchphrase"><%== feat.merchant.catchphrase %></div>
            </a>
        </div>
    </div>
</div>
<% }); %>
</script>

<script type="text/ejs" id="template_model">
<% list(models, function(model){ %>
    <option value="<%= model.slug %>"><%= model.name %></option>
<% }); %>
</script>

<script type="text/ejs" id="template_search_year">
<% list(years, function(year){ %>
    <option value="<%= year %>" <%= year == search_year ? 'selected="selected"' : '' %>><%= year %></option>
<% }); %>
</script>

<script>
    search_year = "{{isset($search_year) ? $search_year : 'all'}}";
    new_year_start = "{{$new_year_start}}";
    new_year_end = "{{$new_year_end}}";
    earliest_year = "{{$earliest_year->year}}";
</script>

<div class="panel panel-default panel-filter">
    <div class="panel-heading">
      <span class="h4 hblock panel-title">
        <a data-toggle="collapse" class="collapsed" href="#collapseFilter">Search Makes/Models<span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span></a>
        <div class="clearfix"></div>
      </span>
    </div>
    <div id="collapseFilter" class="panel-collapse collapse">
        <div class="panel-body">
           <form role="form" action="/cars/vehicle-search" method="post">
                <div class="form-group row">
                    <div class="col-xs-6">
                        <div class="radio margin-top-0">
                            <label>
                                <input type="radio" name="carType" class="radioCarType" value="new" checked="checked">
                                New
                            </label>
                        </div>
                    </div>
                    @if(isset($full_detroit_only) && !$full_detroit_only)
                    <div class="col-xs-6">
                        <div class="radio margin-top-0">
                            <label>
                                <input type="radio" name="carType" class="radioCarType" value="used">
                                Used
                            </label>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="form-group">
                    <select class="form-control" id="filterBodyType" name="filterBodyType" style="">
                        <option value="all">All Body Types</option>
                            <option value="car" {{isset($search_body) && $search_body == 'car' ? 'selected="selected"' : ''}}>Car</option>
                            <option value="truck" {{isset($search_body) && $search_body == 'truck' ? 'selected="selected"' : ''}}>Truck</option>
                            <option value="suv" {{isset($search_body) && $search_body == 'suv' ? 'selected="selected"' : ''}}>SUV</option>
                            <option value="van" {{isset($search_body) && $search_body == 'van' ? 'selected="selected"' : ''}}>Van</option>
                    </select>
                </div>
                <div class="form-group">
                    <select class="form-control" id="filterYear" name="filterYear">
                        <option value="all">All Years</option>
                        <?php foreach ($vehicle_years as $vehicle_year) { ?>
                            <option value="{{$vehicle_year->year}}" {{(isset($search_year) && $search_year == $vehicle_year->year) ? 'selected="selected"' : ''}}>{{$vehicle_year->year}}</option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <select class="form-control" id="filterMake" name="filterMake">
                        <option value="all">All Makes</option>
                        <?php foreach ($vehicle_makes as $vehicle_make) { ?>
                            <option value="{{$vehicle_make->slug}}" {{(isset($search_make) && $search_make == $vehicle_make->slug) ? 'selected="selected"' : ''}}>{{$vehicle_make->name}}</option>
                        <?php } ?>
                        
                    </select>
                </div>
                <div class="form-group">
                    <input type="hidden" name="filterModel" value="all">
                    <select class="form-control {{isset($search_make) && $search_make || !isset($search_make) == 'all'? 'disabled' : ''}}" id="filterModel" name="filterModel" {{isset($search_make) && $search_make == 'all' || !isset($search_make)? 'disabled="disabled"' : ''}}>
                        <option value="all">All Models</option>
                        @if(isset($search_models))
                        @foreach($search_models as $m)
                        <option value="{{$m->slug}}" {{(isset($search_model) && $search_model == $m->slug) ? 'selected="selected"' : ''}}>{{$m->name}}</option>
                        @endforeach
                        @endif
                    </select>
                </div>
                <div class="form-group">
                    <label for="filterPrice">Price Range</label>
                    <div class="row">
                        <div class="col-xs-5" style="padding-right:0">
                            <select class="form-control" id="filterPriceMin" name="filterPriceMin">
                                <option value="low">$1,000</option>
                                @for($i=0; $i<100;)
                                <?php $i = $i + 5; ?>
                                <option value="{{1000*$i}}" {{isset($search_min) && $search_min == (1000*$i) ? 'selected="selected"' : ''}}>${{number_format(1000*$i, 0, '.', ',')}}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-xs-2 text-center">
                            <p style="margin-top:5px"><strong>to</strong></p>
                        </div>
                        <div class="col-xs-5" style="padding-left:0">
                            <select class="form-control" id="filterPriceMax" name="filterPriceMax">
                                <option value="high">$100,000+</option>
                                @for($i=0; $i<95;)
                                <?php $i = $i + 5; ?>
                                <option value="{{100000-(1000*$i)}}" {{isset($search_max) && $search_max == (100000-(1000*$i)) ? 'selected="selected"' : ''}}>${{number_format(100000-(1000*$i), 0, '.', ',')}}</option>
                                @endfor
                                <option value="1000" {{isset($search_max) && $search_max == 1000 ? 'selected="selected"' : ''}}>$1,000</option>
                            </select>
                        </div>
                    </div>
                    
                </div>
                <div class="form-group">
                    <select class="form-control" id="filterDistance" name="filterDistance">
                        <option value="high" {{isset($search_distance) && $search_distance == 'high' ? 'selected="selected"' : ''}}>Within 30+ Miles</option>
                        <option value="15" {{isset($search_distance) && $search_distance == '15' ? 'selected="selected"' : ''}}>Within 15 Miles</option>
                        <option value="10" {{isset($search_distance) && $search_distance == '10' ? 'selected="selected"' : ''}}>Within 10 Miles</option>
                        <option value="5" {{isset($search_distance) && $search_distance == '5' ? 'selected="selected"' : ''}}>Within 5 Miles</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-red btn-block btn-auto-filter" data-loading-text="Searching...">Search</span></button>
            </form>
        </div>
    </div>
</div>