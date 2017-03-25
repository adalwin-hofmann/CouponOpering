@extends('master.templates.master', array('width'=>'full', 'hideSubmenu'=>'true'))

@section('page-title')
<h1>Home Improvement: <small>Get A Quote</small></h1>
@stop
@section('body')
<div class="row margin-top-20">
    <div class="col-xs-12">
        <div class="btn-group steps">
          <button type="button" class="disabled spaced active btn btn-default"><strong>1.</strong> <span class="hidden-xs">Project Type</span> <div class="forward-arrow"></div></button>
          <button type="button" class="disabled spaced btn btn-default"><strong>2.</strong> <span class="hidden-xs">Project Brief</span> <div class="forward-arrow"></div></button>
          <button type="button" class="disabled spaced btn btn-default"><strong>3.</strong> <span class="hidden-xs">Review &amp; Submit</span> <div class="forward-arrow"></div></button>
        </div>
    </div>
</div>

<div class="content-bg margin-top-20 project-type">
@if($merchant)<span class="h1">{{$merchant->display}}</span>@endif
<h2>Please select a project type from the list below and click continue:</h2>
<p>Select one category per submission</p>
@if($errors->has('project_tag_id'))<span style="color:red;">Please choose a project type.</span>@endif
<form action="/homeimprovement/projecttype" method="POST">
    <input type="hidden" name="franchise_id" value="{{$franchise ? $franchise->id : 0}}">
    <input type="hidden" name="offer_id" value="{{$offer_id}}">
    <div class="row">
        <div class="col-sm-6">
            <?php $i=0;$half = floor(count($tags) / 2); ?>
            @foreach($tags as $tag)
            <div class="radio {{in_array($tag->id, $missing_tags) ? 'project-type-disabled' : ''}}">
              <label>
                <input type="radio" name="project_tag_id" value="{{$tag->id}}" {{in_array($tag->id, $missing_tags) ? 'disabled' : ''}} {{Input::old('project_tag_id') == $tag->id ? 'checked' : ''}}>
                {{$tag->name}}
              </label>
            </div>
            @if($i++ == $half)
            </div>
            <div class="col-sm-6">
            @endif
            @endforeach
        </div>
    </div>
    <div class="row margin-top-20">
        <div class="col-xs-12">
            <button class="btn btn-green pull-right" type="submit">CONTINUE <span class="glyphicon glyphicon-chevron-right"></span></button>
        </div>
    </div>
</form>
</div>

@stop