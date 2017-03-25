@extends('master.templates.master', array('width'=>'full', 'hideSubmenu'=>'true'))

@section('page-title')
<h1>Home Improvement: <small>Get Save Certified</small></h1>
@stop
@section('body')

<div class="row margin-top-20">
    <div class="col-xs-12">
        <div class="btn-group steps four-steps">
          <button type="button" class="disabled spaced btn btn-default"><strong>1.</strong> <span class="hidden-xs">Create Your Account</span> <div class="forward-arrow"></div></button>
          <button type="button" class="disabled spaced active btn btn-default"><strong>2.</strong> <span class="hidden-xs">Pick Your Leads</span> <div class="forward-arrow"></div></button>
          <button type="button" class="disabled spaced btn btn-default"><strong>3.</strong> <span class="hidden-xs">Get Save Certified</span> <div class="forward-arrow"></div></button>
          <button type="button" class="disabled spaced btn btn-default"><strong>4.</strong> <span class="hidden-xs">Next Steps</span> <div class="forward-arrow"></div></button>
        </div>
    </div>
</div>
<div class="content-bg margin-top-20">
	<h2>Please select all lead types that you would like to receive:</h2>
    <p>If you do not wish to receive leads, proceed to the next page.</p>
	<p>Choose all that apply</p>
    <form action="/homeimprovement/get-certified2" method="POST">
	    <div class="row">
            <div class="col-sm-6">
                <?php $i=0;$half = floor(count($tags) / 2); ?>
                @foreach($tags as $tag)
                <div class="checkbox">
                  <label>
                    <input type="checkbox" name="{{$tag->slug}}" {{Input::old($tag->slug) || in_array($tag->slug, $existingTags) ? 'checked' : ''}}>
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
        <input type="hidden" name="application_id" value="{{Input::has('application_id') ? Input::get('application_id') : Input::old('application_id')}}">
        <div class="row">
            <div class="col-md-12">
                <a class="btn btn-grey pull-left" href="/homeimprovement/get-certified?application_id={{Input::has('application_id') ? Input::get('application_id') : Input::old('application_id')}}"><span class="glyphicon glyphicon-chevron-left"></span> Back to Step 1</a>
                <button class="btn btn-green pull-right" type="submit">CONTINUE <span class="glyphicon glyphicon-chevron-right"></span></button>
            </div>
        </div>
    </form>
</div>
@stop