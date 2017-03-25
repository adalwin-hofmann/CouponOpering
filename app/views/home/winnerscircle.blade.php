@extends('master.templates.master', array('width'=>'full', 'hideSubmenu'=>'true'))

@section('page-title')
<h1>Contest Winner's Circle</h1>
@stop


@section('body')
<script id="template_winner" type="text/ejs">
<% list(winners, function(winner)
{ %>
    <div class="row">
        <div class="col-md-12" style="padding: 5px 10px; position:relative;">
            <img style="max-width:70px;" alt="Winner: <%= winner.first_name %> <%= winner.last_name.charAt(0) %>.<% if (winner.city != '') { %> from <%= winner.city %>, <%= winner.state %><% } %>" class="pull-left img-responsive" src="http://s3.amazonaws.com/saveoneverything_assets/assets/images/sohi/trophy.jpg">
            <p class="spaced" style="margin-left: 75px;"><strong>Winner</strong></p>
            <p style="margin-left: 75px;"><%= winner.first_name %> <%= winner.last_name.charAt(0) %>.<% if (winner.city != '') { %> from <%= winner.city %>, <%= winner.state %><% } %></p>
        </div>
    </div>
<% }) %>
</script>
<div class="content-bg margin-bottom-20">
	<span style="font-size:40px;" class="h1 fancy red">Contest Winner's Circle</span>
	<p class="spaced">Congratulations to our contest winners!</p>
	<div class="row"><div class="col-sm-3"><hr style="margin-top:0;" class="red"></div></div>
	<p>SaveOn.com has awarded well over $50,000 in prizes to our lucky contest winners so far! Sign up as a member today to enter all of our amazing contests. It’s always free and easy to join!</p>
	<a href="{{URL::abs('/')}}/contests/{{$geoip->region_name}}/{{SoeHelper::getSlug($geoip->city_name)}}/all" class="btn btn-red">View Our Contests</a>
</div>
<div id="container" class="js-masonry offer-results-grid">
	<p class="ajax-loader"><img src="/img/loader-transparent.gif" alt=""></p>
</div>
<script>
	page = 0;
</script>

<div class="modal fade" id="winnersModal" tabindex="-1" role="dialog" aria-labelledby="winnersModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
            <span class="h1 modal-title fancy" id="winnersModalLabel">All Winners</span>
        </div>
        <div class="modal-body">

        </div>
    </div>
  </div>
</div>
@stop
@stop