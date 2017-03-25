@extends('master.templates.master')
@section('page-title')
<h1>Distribution Maps</h1>
@stop

@section('breadcrumbs')
    <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
        <a href="{{URL::abs('/')}}" itemprop="url"><span itemprop="title">Home</span></a>
    </li>
    <li class="active">Distribution Maps</li>
@stop

@section('sidebar')
@include('master.templates.corporatesidebar')
@stop
@section('body')
<div class="content-bg">
    <div class="row margin-bottom-10">
        <div class="col-xs-3">
            <a class="" href="#" data-toggle="modal" data-target="#detroitModal"><img class="img-thumbnail" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/maps/2016/2016_DT_SaveOn_Map_thumb.jpg"></a>
            <a class="" href="#" data-toggle="modal" data-target="#detroitModal">View Detroit Map</a>
        </div>
        <div class="col-xs-3">
            <a class="" href="#" data-toggle="modal" data-target="#twinCitiesModal"><img class="img-thumbnail" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/maps/2016/2016_TC_SaveOn_Map_thumb.jpg"></a>
            <a class="" href="#" data-toggle="modal" data-target="#twinCitiesModal">View Twin Cities Map</a>
        </div>
        <div class="col-xs-3">
            <a class="" href="#" data-toggle="modal" data-target="#chicagoModal"><img class="img-thumbnail" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/maps/2016/2016_CH_SaveOn_Map_thumb.jpg"></a>
            <a class="" href="#" data-toggle="modal" data-target="#chicagoModal">View Chicago Map</a>
        </div>
        <div class="col-xs-3">
            <a class="" href="#" data-toggle="modal" data-target="#annarborModal"><img class="img-thumbnail" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/maps/2016/2016_AA_SaveOn_Map_thumb.jpg"></a>
            <a class="" href="#" data-toggle="modal" data-target="#annarborModal">View Ann Arbor Map</a>
        </div>
    </div>
    <div class="row margin-bottom-10">
        <div class="col-xs-3">
            <a class="" href="#" data-toggle="modal" data-target="#grandrapidsModal"><img class="img-thumbnail" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/maps/2016/2016_GR_SaveOn_Map_thumb.jpg"></a>
            <a class="" href="#" data-toggle="modal" data-target="#grandrapidsModal">View Grand Rapids Map</a>
        </div>
        <div class="col-xs-3">
            <a class="" href="#" data-toggle="modal" data-target="#kalamazooModal"><img class="img-thumbnail" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/maps/2016/2016_KZ_SaveOn_Map_thumb.jpg"></a>
            <a class="" href="#" data-toggle="modal" data-target="#kalamazooModal">View Kalamazoo Map</a>
        </div>
        <div class="col-xs-3">
            <a class="" href="#" data-toggle="modal" data-target="#lakeshoreModal"><img class="img-thumbnail" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/maps/2016/2016_SaveOn_Map_LS_thumb.jpg"></a>
            <a class="" href="#" data-toggle="modal" data-target="#lakeshoreModal">View Lakeshore Map</a>
        </div>
        <div class="col-xs-3">
            <a class="" href="#" data-toggle="modal" data-target="#lansingModal"><img class="img-thumbnail" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/maps/2016/2016_LN_SaveOn_Map_thumb.jpg"></a>
            <a class="" href="#" data-toggle="modal" data-target="#lansingModal">View Lansing Map</a>
        </div>
    </div>
    <div class="row margin-bottom-10">
        <div class="col-xs-3">
            <a class="" href="#" data-toggle="modal" data-target="#detroitSoctModal"><img class="img-thumbnail" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/maps/2016/2016_SOCT_map_thumb.jpg"></a>
            <a class="" href="#" data-toggle="modal" data-target="#detroitSoctModal">View Detroit SOCT Map</a>
        </div>
        <div class="col-xs-3">
            <a class="" href="#" data-toggle="modal" data-target="#chicagoSofhModal"><img class="img-thumbnail" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/maps/2016/2016_CH_SOFH_Map_thumb.jpg"></a>
            <a class="" href="#" data-toggle="modal" data-target="#chicagoSofhModal">View Chicago SOFH Map</a>
        </div>
       <div class="col-xs-3">
            <a class="" href="#" data-toggle="modal" data-target="#grandrapidsSofhModal"><img class="img-thumbnail" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/maps/2016/2016_DT_SOFH_Map_thumb.jpg"></a>
            <a class="" href="#" data-toggle="modal" data-target="#grandrapidsSofhModal">View Detroit SOFH Map</a>
        </div>
    </div>
    <!-- <div class="row">
=======
>>>>>>> develop
        <div class="col-xs-3">
            <a class="" href="#" data-toggle="modal" data-target="#kalamazooSofhModal"><img class="img-thumbnail" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/maps/2015/kalamazoo-sofh-thumb.jpg"></a>
            <a class="" href="#" data-toggle="modal" data-target="#kalamazooSofhModal">View Kalamazoo SOFH Map</a>
        </div>
    </div>
    <div class="row">      
        <div class="col-xs-3">
            <a class="" href="#" data-toggle="modal" data-target="#lakeshoreSofhModal"><img class="img-thumbnail" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/maps/2015/lakeshore-sofh-thumb.jpg"></a>
            <a class="" href="#" data-toggle="modal" data-target="#lakeshoreSofhModal">View Lakeshore SOFH Map</a>
        </div>
        <div class="col-xs-3">
            <a class="" href="#" data-toggle="modal" data-target="#lansingSofhModal"><img class="img-thumbnail" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/maps/2015/lansing-sofh-thumb.jpg"></a>
            <a class="" href="#" data-toggle="modal" data-target="#lansingSofhModal">View Lansing SOFH Map</a>
        </div>
        <div class="col-xs-3">
            <a class="" href="#" data-toggle="modal" data-target="#twincitiesSofhModal"><img class="img-thumbnail" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/maps/2015/twincities-sofh-thumb.jpg"></a>
            <a class="" href="#" data-toggle="modal" data-target="#twincitiesSofhModal">View Twin Cities SOFH Map</a>
        </div>
    </div> -->
</div>

<div class="modal fade contest-rules" id="detroitModal" tabindex="-1" role="dialog" aria-labelledby="detroitModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <span class="h1 modal-title fancy" id="detroitModalLabel">Detroit Zones</span>
      </div>
      <div class="modal-body">
        <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/maps/2016/2016_DT_SaveOn_Map.jpg"/>
      </div>
      <div class="modal-footer">
        <a href="http://s3.amazonaws.com/saveoneverything_assets/corporate/maps/2016/2016_DT_SaveOn_Map.pdf">Download a high resolution PDF</a>
      </div>
    </div>
  </div>
</div>

<div class="modal fade contest-rules" id="twinCitiesModal" tabindex="-1" role="dialog" aria-labelledby="twinCitiesModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <span class="h1 modal-title fancy" id="twinCitiesModalLabel">Twin Cities Zones</span>
      </div>
      <div class="modal-body">
        <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/maps/2016/2016_TC_SaveOn_Map.jpg"/>
      </div>
      <div class="modal-footer">
        <a href="http://s3.amazonaws.com/saveoneverything_assets/corporate/maps/2016/2016_TC_SaveOn_Map.pdf">Download a high resolution PDF</a>
      </div>
    </div>
  </div>
</div>

<div class="modal fade contest-rules" id="chicagoModal" tabindex="-1" role="dialog" aria-labelledby="chicagoModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <span class="h1 modal-title fancy" id="chicagoModalLabel">Chicago Zones</span>
      </div>
      <div class="modal-body">
        <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/maps/2016/2016_CH_SaveOn_Map.jpg"/>
      </div>
      <div class="modal-footer">
        <a href="http://s3.amazonaws.com/saveoneverything_assets/corporate/maps/2016/2016_CH_SaveOn_Map.pdf">Download a high resolution PDF</a>
      </div>
    </div>
  </div>
</div>

<div class="modal fade contest-rules" id="annarborModal" tabindex="-1" role="dialog" aria-labelledby="annarborModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <span class="h1 modal-title fancy" id="annarborModalLabel">Ann Arbor Zones</span>
      </div>
      <div class="modal-body">
        <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/maps/2016/2016_AA_SaveOn_Map.jpg"/>
      </div>
      <div class="modal-footer">
        <a href="http://s3.amazonaws.com/saveoneverything_assets/corporate/maps/2016/2016_AA_SaveOn_Map.pdf">Download a high resolution PDF</a>
      </div>
    </div>
  </div>
</div>

<div class="modal fade contest-rules" id="grandrapidsModal" tabindex="-1" role="dialog" aria-labelledby="grandrapidsModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <span class="h1 modal-title fancy" id="grandrapidsModalLabel">Grand Rapids Zones</span>
      </div>
      <div class="modal-body">
        <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/maps/2016/2016_GR_SaveOn_Map.jpg"/>
      </div>
      <div class="modal-footer">
        <a href="http://s3.amazonaws.com/saveoneverything_assets/corporate/maps/2016/2016_GR_SaveOn_Map.pdf">Download a high resolution PDF</a>
      </div>
    </div>
  </div>
</div>

<div class="modal fade contest-rules" id="greenvilleModal" tabindex="-1" role="dialog" aria-labelledby="greenvilleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-footer">
        <a href="http://s3.amazonaws.com/saveoneverything_assets/corporate/maps/2016/2016_GVL_SO_Map.pdf">Download a high resolution PDF</a>
      </div>
    </div>
  </div>
</div>

<div class="modal fade contest-rules" id="kalamazooModal" tabindex="-1" role="dialog" aria-labelledby="kalamazooModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <span class="h1 modal-title fancy" id="kalamazooModalLabel">Kalamazoo Zones</span>
      </div>
      <div class="modal-body">
        <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/maps/2016/2016_KZ_SaveOn_Map.jpg"/>
      </div>
      <div class="modal-footer">
        <a href="http://s3.amazonaws.com/saveoneverything_assets/corporate/maps/2016/2016_KZ_SaveOn_Map.pdf">Download a high resolution PDF</a>
      </div>
    </div>
  </div>
</div>

<div class="modal fade contest-rules" id="lakeshoreModal" tabindex="-1" role="dialog" aria-labelledby="lakeshoreModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <span class="h1 modal-title fancy" id="lakeshoreModalLabel">Lakeshore Zones</span>
      </div>
      <div class="modal-body">
        <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/maps/2016/2016_LS_SaveOn_Map.jpg"/>
      </div>
      <div class="modal-footer">
        <a href="http://s3.amazonaws.com/saveoneverything_assets/corporate/maps/2016/2016_LS_SaveOn_Map.pdf">Download a high resolution PDF</a>
      </div>
    </div>
  </div>
</div>

<div class="modal fade contest-rules" id="lansingModal" tabindex="-1" role="dialog" aria-labelledby="lansingModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <span class="h1 modal-title fancy" id="lansingModalLabel">Lansing Zones</span>
      </div>
      <div class="modal-body">
        <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/maps/2016/2016_LN_SaveOn_Map.jpg"/>
      </div>
      <div class="modal-footer">
        <a href="http://s3.amazonaws.com/saveoneverything_assets/corporate/maps/2016/2016_LN_SaveOn_Map.pdf">Download a high resolution PDF</a>
      </div>
    </div>
  </div>
</div>

<div class="modal fade contest-rules" id="detroitSoctModal" tabindex="-1" role="dialog" aria-labelledby="detroitSoctModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <span class="h1 modal-title fancy" id="detroitSoctModalLabel">Detroit SOCT Zones</span>
      </div>
      <div class="modal-body">
        <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/maps/2016/2016_SOCT_map.jpg"/>
      </div>
      <div class="modal-footer">
        <a href="http://s3.amazonaws.com/saveoneverything_assets/corporate/maps/2016/2016_SOCT_Map.pdf">Download a high resolution PDF</a>
      </div>
    </div>
  </div>
</div>

<div class="modal fade contest-rules" id="chicagoSofhModal" tabindex="-1" role="dialog" aria-labelledby="chicagoSofhModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <span class="h1 modal-title fancy" id="chicagoSofhModalLabel">Chicago SOFH Zones</span>
      </div>
      <div class="modal-body">
        <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/maps/2016/2016_CH_SOFH_Map.jpg"/>
      </div>
      <div class="modal-footer">
        <a href="http://s3.amazonaws.com/saveoneverything_assets/corporate/maps/2016/2016_CH_SOFH_Map.pdf">Download a high resolution PDF</a>
      </div>
    </div>
  </div>
</div>

<div class="modal fade contest-rules" id="grandrapidsSofhModal" tabindex="-1" role="dialog" aria-labelledby="grandrapidsSofhModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <span class="h1 modal-title fancy" id="grandrapidsSofhModalLabel">Detroit SOFH Zones</span>
      </div>
      <div class="modal-body">
        <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/maps/2016/2016_DT_SOFH_Map.jpg"/>
      </div>
      <div class="modal-footer">
        <a href="http://s3.amazonaws.com/saveoneverything_assets/corporate/maps/2016/2016_DT_SOFH_Map.pdf">Download a high resolution PDF</a>
      </div>
    </div>
  </div>
</div>

<div class="modal fade contest-rules" id="kalamazooSofhModal" tabindex="-1" role="dialog" aria-labelledby="kalamazooSofhModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <span class="h1 modal-title fancy" id="kalamazooSofhModalLabel">Kalamazoo SOFH Zones</span>
      </div>
      <div class="modal-body">
        <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/maps/2015/kalamazoo-sofh-map.jpg"/>
      </div>
      <div class="modal-footer">
        <a href="http://s3.amazonaws.com/saveoneverything_assets/corporate/maps/2015/2015_KAL_SOFH_Map.pdf">Download a high resolution PDF</a>
      </div>
    </div>
  </div>
</div>

<div class="modal fade contest-rules" id="lakeshoreSofhModal" tabindex="-1" role="dialog" aria-labelledby="lakeshoreSofhModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <span class="h1 modal-title fancy" id="lakeshoreSofhModalLabel">Lakeshore SOFH Zones</span>
      </div>
      <div class="modal-body">
        <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/maps/2015/lakeshore-sofh-map.jpg"/>
      </div>
      <div class="modal-footer">
        <a href="http://s3.amazonaws.com/saveoneverything_assets/corporate/maps/2015/2015_LKSH_SOFH_Map.pdf">Download a high resolution PDF</a>
      </div>
    </div>
  </div>
</div>

<div class="modal fade contest-rules" id="lansingSofhModal" tabindex="-1" role="dialog" aria-labelledby="lansingSofhModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <span class="h1 modal-title fancy" id="lansingSofhModalLabel">Lansing SOFH Zones</span>
      </div>
      <div class="modal-body">
        <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/maps/2015/lansing-sofh-map.jpg"/>
      </div>
      <div class="modal-footer">
        <a href="http://s3.amazonaws.com/saveoneverything_assets/corporate/maps/2015/2015_LAN_SOFH_Map.pdf">Download a high resolution PDF</a>
      </div>
    </div>
  </div>
</div>

<div class="modal fade contest-rules" id="twincitiesSofhModal" tabindex="-1" role="dialog" aria-labelledby="twincitiesSofhModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
        <span class="h1 modal-title fancy" id="twincitiesSofhModalLabel">Twin Cities SOFH Zones</span>
      </div>
      <div class="modal-body">
        <img class="img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/corporate/maps/2015/twincities-sofh-map.jpg"/>
      </div>
      <div class="modal-footer">
        <a href="http://s3.amazonaws.com/saveoneverything_assets/corporate/maps/2015/2015_TC_SOFH_Map.pdf">Download a high resolution PDF</a>
      </div>
    </div>
  </div>
</div>

@stop