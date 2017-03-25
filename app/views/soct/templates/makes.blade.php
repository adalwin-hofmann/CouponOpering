<?php 
$vehicle_makes = SOE\DB\VehicleMake::where('is_active','=','1')->orderBy('name')->where('edmunds_id','!=','0')->get(array('id', 'name', 'slug'));
?>
<div class="panel panel-default">
    <div class="panel-heading">
      <span class="h4 hblock panel-title">
        <a data-toggle="collapse" class="collapsed" href="#collapseMakes">All Makes<span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span></a>
        <div class="clearfix"></div>
      </span>
    </div>
    <div id="collapseMakes" class="panel-collapse collapse">
        <div class="panel-body explore-links">
            <ul>
            <?php foreach ($vehicle_makes as $vehicle_make) { ?>
                <li><a href="{{URL::abs('/')}}/cars/research/{{$vehicle_make->slug}}">{{$vehicle_make->name}}</a></li>
            <?php } ?>
            </ul>
        </div>
    </div>
</div>