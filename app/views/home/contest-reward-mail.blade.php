@extends('master.templates.master')
@section('page-title')
<h1>Congratulations!</h1>
@stop

@section('body')
<div class="content-bg">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="h2">Your Prize is Coming</div>
            <div class="margin-bottom-15"></div>
            <p>Congratulations, {{ $winner->first_name.' '.$winner->last_name }}, on winning your prize of {{$date->prize_name}}. Due to the lack of advancement in teleportation technology, please allow 2 to 4 weeks while we mail you your prize.</p>
            <p>If you have any questions, please feel free to <a href="{{URL::abs('/')}}/contact">contact us</a>.</p>
            <p>Please check out our other contests, you could have more chances to win:</p>
            <p><a class="btn btn-red" href="{{URL::abs('/')}}/contests/all">Explore All Contests</a></p>
        </div>
    </div>
</div>
@stop
