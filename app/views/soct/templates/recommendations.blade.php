<?php
foreach($entities as &$ent)
{
    if($ent->object_type == 'UsedVehicle')
    {
        $images = explode(',', str_replace('|', ',', $ent->image_urls));
        if(count($images))
            $ent->display_image = $images[0];
        else
        {
            // TODO: Add placeholder car image.
            $ent->display_image = '';
        }
    }
    else if($ent->object_type == 'VehicleStyle')
    {
        if(count($ent->display_image))
            $ent->display_image = $ent->display_image[0]->path;
        else
        {
            // TODO: Add placeholder car image.
            $image = count($ent->assets) ? $ent->assets[0]['path'] : '';
            $ent->display_image = $image;
        }
    }
}
?>
@foreach($entities as $entity)
    @if($entity->object_type == 'Entity')
        @include('master.templates.entity', array('entity'=>$entity))
    @elseif($entity->object_type == 'VehicleStyle')
        @include('soct.templates.new-car', array('vehicle'=>$entity))
    @elseif($entity->object_type == 'UsedVehicle')
        @include('soct.templates.grid-vehicle-entity', array('vehicle'=>$entity))
    @endif
 @endforeach