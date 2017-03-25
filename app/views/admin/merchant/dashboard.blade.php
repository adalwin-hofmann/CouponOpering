@extends('admin.master.templates.master')
@section('sidebar')
    @include('admin.merchant.templates.sidebar', array())
@stop
@section('body')

<!--BEGIN MAIN CONTENT-->
<script>
    franchise_id = {{$franchise_id}};
    location_id = {{$location_id}};
</script>

<script type="text/ejs" id="template_offer">
<% list(offers, function(offer){ %>
    <tr class="<%= offer.offer.is_followup_for == '0' ? '' : 'is-follow-up' %>">
        <td><%= offer.offer.name %></td>
        <td><%= offer.offer.is_dailydeal == '1' ? 'Daily Deal' : 'Offer' %></td>
        <td><%= offer.total_views %></td>
        <td><%= offer.total_prints %></td>
        <td>---</td>
        <td><%= dash_control.GetDate(offer.offer.starts_at) %></td>
        <td><%= dash_control.GetDate(offer.offer.expires_at) %></td>
    </tr>
<% }) %>
</script>

<script type="text/ejs" id="template_contest">
<% list(contests, function(contest){ %>
    <tr>
        <td><%= contest.display_name %></td>
        <td>Contest</td>
        <td>---</td>
        <td>---</td>
        <td><%= contest.apps ? contest.apps : 0 %></td>
        <td><%= dash_control.GetDate(contest.starts_at) %></td>
        <td><%= dash_control.GetDate(contest.expires_at) %></td>
    </tr>
<% }) %>
</script>

<div id="main" role="main" class="main-window">
    <div class="block">
        <form id="dashboardSearch" action="/" method="GET">
        	<div class="row">
              	<div class="col-xs-12 col-sm-6 col-md-9">
    		        <div class="pagetitle">
    					<h1>{{$merchant->display}}</h1>
    					<div class="clearfix"></div>
    					<div class="row">
    						<div class="col-xs-6 col-sm-3 form-group">
    							<select name="location_id" id="location" class="form-control">
    		              			<option value="0" {{Input::get('location_id') == '0' ? 'selected="selected"' : ''}}>All Locations</option>
                                    @foreach($locations as $location)
    		              			<option value="{{$location->id}}" {{Input::get('location_id') == $location->id ? 'selected="selected"' : ''}}>{{$location->display_name != '' ? $location->display_name : $location->name}}</option>
                                    @endforeach
    		              		</select>
    						</div>
    					</div>
    				</div>
    			</div>
    			<div class="col-xs-6 col-sm-3 form-group">
    				<div class="margin-top-20"></div>
    				<div class="margin-bottom-10"><strong>Date Range</strong></div>
    				<select id="selDate" name="date-range" class="form-control">
    	              	<option value="last-30-days" {{Input::get('date-range') == 'last-30-days' ? 'selected="selected"' : ''}}>Last 30 Days</option>
    	              	<option value="last-90-days" {{Input::get('date-range') == 'last-90-days' ? 'selected="selected"' : ''}}>Last 90 Days</option>
    	              	<option value="this-year" {{Input::get('date-range') == 'this-year' ? 'selected="selected"' : ''}}>This Year</option>
    	              	<option value="last-year" {{Input::get('date-range') == 'last-year' ? 'selected="selected"' : ''}}>Last Year</option>
    	            </select>
    			</div>
    		</div>
            <div class="row">
                <div class="col-xs-12">
                    <button class="btn btn-primary">Search</button>
                </div>
            </div>
        </form>


		<!-- info-box -->
        <div class="info-box">
            <div class="stats-box merchant-reports">

                <p>Below are up to date stats on how your business is doing. @if($rep) If you have any questions, please contact your sales rep at <a href="mailto:{{$rep->email}}">{{$rep->email}}</a>. @endif </p>
                <p><a class="pointer" onclick="tourFirst();tour.restart()">Take the tour</a></p>

                @if($report->errored)
                <div class="alert alert-danger" role="alert">
                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                    There was an error, please reload the page.
                </div>
                @endif

                <div class="row text-center">
                    <div class="col-sm-3 tour-stats" data-toggle="tooltip" data-placement="top" title="You have {{$report->current_views}} total visits.">
                        <p><span>Visits</span><br>
                        <strong>{{$report->current_views}}</strong></p>
                    </div>
                    <div class="col-sm-3" data-toggle="tooltip" data-placement="top" title="You have {{$report->current_prints}} total prints.">
                        <p><span>Prints:</span><br>
                        <strong>{{$report->current_prints}}</strong></p>
                    </div>
                    <div class="col-sm-3" data-toggle="tooltip" data-placement="top" title="Your offers have been shared {{$report->current_shares}} times.">
                        <p><span>Shares:</span><br>
                        <strong>{{$report->current_shares}}</strong></p>
                    </div>
                    <div class="col-sm-3" data-toggle="tooltip" data-placement="top" title="{{$report->favorites_count}} people like this location.">
                        <p><span>Favorites:</span><br>
                        <strong>{{$report->favorites_count}}</strong></p>
                    </div>
                </div>

                <div class="row text-center">
                    <div class="col-sm-3" data-toggle="tooltip" data-placement="top" title="You have purchased {{count($report->leads)}} leads.">
                        <p>@if(count($report->leads))<a href="#" id="leadsDetails">@endif<span>Leads:</span>@if(count($report->leads))</a>@endif<br>
                        <strong>{{count($report->leads)}}</strong></p>
                    </div>
                    <div class="col-sm-3" data-toggle="tooltip" data-placement="top" title="You have {{count($offers)}} offers currently running.">
                        <p><a href="#" id="offerModal"><span>Active Offers:</span></a><br>
                        <strong>{{count($offers)}}</strong></p>
                    </div>
                    <div class="col-sm-3" data-toggle="tooltip" data-placement="top" title="Your contests have been entered {{$report->contest_apps}} times.">
                        <p><a href="#" id="contestModal"><span>Contest Applications:</span></a><br>
                        <strong>{{$report->contest_apps}}</strong></p>
                    </div>
                </div>
            </div>
        </div>

	    <!--<div class="info-box">
	        <div class="stats-box merchant-reports">
                @if($report->errored)
                <div class="alert alert-danger" role="alert">
                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                    There was an error, please reload the page.
                </div>
                @endif
	        	<p><span>Visits:</span> <strong>{{$report->current_views}}</strong></p>
	        	<p><span>Prints:</span> <strong>{{$report->current_prints}}</strong></p>
	        	<p><span>Shares:</span> <strong>{{$report->current_shares}}</strong></p>
	        	<p><span>Favorites:</span> <strong>{{$report->favorites_count}}</strong></p>
	        	<p><span>Leads:</span> <strong>0</strong></p>
	        	<p><a href="#" id="offerModal"><span>Active Offers:</span></a> <strong>{{count($offers)}}</strong></p>
	        	<p><a href="#" id="contestModal"><span>Contest Applications:</span></a> <strong>{{$report->contest_apps}}</strong></p>
	        </div>
	    </div>-->

	</div>

    <div class="modal fade offer-details-modal" id="offerDetails" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
                    <h3 class="modal-title">Past Offer Details</h3>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Offer</th>
                                <th>Type</th> <!-- Coupon, Daily Deal, Contest --> 
                                <th>Views</th>
                                <th>Prints</th>
                                <th>Entries</th>
                                <th>Starts</th>
                                <th>Expires</th>
                            </tr>
                        </thead>
                        <tbody id="offers">
                            <tr class="">
                                <td>Get Up To A $160 Reward Card with Goodyear Tire Purchase</td>
                                <td>Offer</td>
                                <td>154</td>
                                <td>14</td>
                                <td>---</td>
                                <td>10/03/2014</td>
                                <td>02/01/2015</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade leads-details-modal" id="leadsDetails" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
                    <h3 class="modal-title">Leads Purchased</h3>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Purchased Date</th>
                                <th>Lead Name</th>
                                <th>Lead Email</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody id="leads$0">
                            @foreach($report->leads as $lead)
                            <tr class="">
                                <td>{{ date('m/d/Y', strtotime($lead->purchased_at)) }}</td>
                                <td>{{ $lead->name }}</td>
                                <td>{{ $lead->email }}</td>
                                <td>{{ '$'.$lead->price }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<div class="modal fade first-time-modal" id="firstTimeModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close &times;</button>
                <h3 class="modal-title">Welcome to the Merchant Portal</h3>
            </div>
            <div class="modal-body">
                <p>Thanks for being a part of the SaveOn Family. We are excited to bring you an additional feature of your partnership with SaveOn. This new feature is called Merchant Portal and allows you to get up to date, real-time stats on how your business is doing.</p>

                <p>In your Merchant Portal you can see:</p>
                <ul>
                    <li>Page visits</li>
                    <li>Total prints</li>
                    <li>Offer shares</li>
                    <li>Favorites</li>
                    <li>Active offers</li>
                    <li>Contest applications</li>
                </ul>

                <p class="text-center"><button class="btn btn-default" data-dismiss="modal">Continue</button> <button class="btn btn-primary" data-dismiss="modal" onclick="tourFirst();tour.restart()">Take the Tour</button></p>

            </div>
        </div>
    </div>
</div>

@if($firstTimeModal)
<script>
    fireFirstTimeModal = 1;
</script>
@endif

<!-- UserVoice JavaScript SDK (only needed once on a page) -->
<script>(function(){var uv=document.createElement('script');uv.type='text/javascript';uv.async=true;uv.src='//widget.uservoice.com/HAH8F2Z9BZLITZLdlQJg.js';var s=document.getElementsByTagName('script')[0];s.parentNode.insertBefore(uv,s)})()</script>

<script type="text/javascript">
  var uvOptions = {};
  (function() {
    var uv = document.createElement('script'); 
    uv.type = 'text/javascript'; 
    uv.async = true;
    uv.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'widget.uservoice.com/SPVBLeZBQxhfj9ChtztBGg.js';
    var s = document.getElementsByTagName('script')[0]; 
    s.parentNode.insertBefore(uv, s);
  })();
</script> 
<!-- A function to launch the Classic Widget -->
<script>
UserVoice = window.UserVoice || [];
function showClassicWidget() {
 UserVoice.push(['showLightbox', 'classic_widget', {
   mode: 'support',
   primary_color: '#1F9F5F',
   link_color: '#1F9F5F'
 }]);
}
</script>

@stop