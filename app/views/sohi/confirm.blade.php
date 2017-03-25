@extends('master.templates.master', array('width'=>'full', 'hideSubmenu'=>'true'))

@section('page-title')
<h1>Home Improvement: <small>Get A Quote</small></h1>
@stop
@section('body')
<script>
    user_id = '{{Auth::User()->id}}';
    signup = '{{$signup}}';
</script>
<div class="row margin-top-20">
    <div class="col-xs-12">
        <div class="btn-group steps">
          <button type="button" class="disabled spaced btn btn-default"><strong>1.</strong> <span class="hidden-xs">Project Type</span> <div class="forward-arrow"></div></button>
          <button type="button" class="disabled spaced btn btn-default"><strong>2.</strong> <span class="hidden-xs">Project Brief</span> <div class="forward-arrow"></div></button>
          <button type="button" class="disabled spaced active btn btn-default"><strong>3.</strong> <span class="hidden-xs">Review &amp; Submit</span> <div class="forward-arrow"></div></button>
        </div>
    </div>
</div>
<div id="confirm-details" class="content-bg margin-top-20">
    <p class="margin-bottom-20">Please review the information below and hit submit!</p>
    <div class="row">
        <div class="col-md-8">
            <div class="row">
                <div class="col-sm-6">
                    <span class="h3 spaced" style="color:#E3582C;">Project Type</span><br>
                    <span class="h3">{{$tag->name}}</span>
                </div>
                <div class="col-sm-6">
                    <span class="h3 spaced" style="color:#E3582C;">Time Preference</span><br>
                    <p>{{$timeframe}}</p>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-xs-12">
                    <span class="h3 spaced" style="color:#E3582C;">About Your Project</span><br>
                    <p>{{$description}}</p>
                </div>
            </div>
        </div>
    </div>
    <br>
    <div class="row margin-top-20">
        <div class="col-xs-6">
            <a href="{{URL::abs('/')}}/homeimprovement/projectbrief?quote_id={{$quote_id}}" class="btn btn-grey pull-left"><span class="glyphicon glyphicon-chevron-left"></span> BACK</a>
        </div>
        <form action="/homeimprovement/confirm" method="POST">
            <input type="hidden" name="quote_id" value="{{$quote_id}}">
            <input type="hidden" name="timeframe" value="{{$timeframe}}">
            <div class="col-xs-6">
                <button id="btnSubmitQuote" type="submit" class="btn btn-green pull-right" data-loading-text="SUBMITTING...">SUBMIT <span class="glyphicon glyphicon-chevron-right"></span></button>
            </div>
        </form>
    </div>
</div>

@stop