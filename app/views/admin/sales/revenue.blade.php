@extends('admin.master.templates.master')
@section('sidebar')
    @include('admin.sales.master.templates.sidebar', array())
@stop
@section('body')

<!--BEGIN MAIN CONTENT-->
<div id="main" role="main">
    <div class="block">
    	<div class="clearfix"></div>

		<div class="row">
		  <div class="col-xs-12 col-sm-6">
		    <!--page title-->
		       <div class="pagetitle">
		          <h1>Dashboard</h1>
		          <div class="clearfix"></div>
		          <p><strong>{{$marketText}} {{$dateText}}</strong></p>
		       </div>
		       <div class="clearfix"></div>
		    <!--page title end-->
		  </div>
		  <div class="col-xs-12 col-sm-6">
            <div class="row">
                <div class="col-xs-6">
        		    <select id="selMarket" class="margin-top-20 form-control">
        		        <option value="all">All Markets</option>
        		        <option value="michigan" {{Input::get('market') == 'michigan' ? 'selected="selected"' : ''}}>Detroit</option>
        		        <option value="illinois" {{Input::get('market') == 'illinois' ? 'selected="selected"' : ''}}>Chicago</option>
        		        <option value="minnesota" {{Input::get('market') == 'minnesota' ? 'selected="selected"' : ''}}>Minneapolis</option>
        		    </select>
                </div>
                <div class="col-xs-6">
        		    <select id="selDate" class="margin-top-20 form-control">
        		      <option value="last-3-months">Last 3 Months</option>
                      <option value="today" {{Input::get('date-range') == 'today' ? 'selected="selected"' : ''}}>Today</option>
                      <option value="yesterday" {{Input::get('date-range') == 'yesterday' ? 'selected="selected"' : ''}}>Yesterday</option>
                      <option value="this-week" {{Input::get('date-range') == 'this-week' ? 'selected="selected"' : ''}}>This Week</option>
                      <option value="last-7" {{Input::get('date-range') == 'last-7' ? 'selected="selected"' : ''}}>Last 7 Days</option>
        		      <option value="this-month" {{Input::get('date-range') == 'this-month' ? 'selected="selected"' : ''}}>This Month</option>
        		      <option value="last-month" {{Input::get('date-range') == 'last-month' ? 'selected="selected"' : ''}}>Last Month</option>
        		      <option value="this-year" {{Input::get('date-range') == 'this-year' ? 'selected="selected"' : ''}}>This Year</option>
        		      <option value="custom" {{Input::get('date-range') == 'custom' ? 'selected="selected"' : ''}}>Custom Range</option>
        		    </select>
                </div>
            </div>
            <div id="custDiv" class="row" style="{{ Input::get('date-range') == 'custom' ? '' : 'display:none;' }}">
                <div class="col-xs-6">
                    <input type="text" id="custStart" class="form-control" value="{{ $custStart }}" placeholder="Start...">
                </div>
                <div class="col-xs-6">
                    <input type="text" id="custEnd" class="form-control" value="{{ $custEnd }}" placeholder="End...">
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <button class="btn btn-primary btn-search pull-right">Search</button>
                </div>
            </div>
          </div>
        </div>

		<!-- info-box -->
        <div class="info-box">
	        <div class="margin-20">
				<table class="table">
					<tr>
						<th colspan="2">Total Revenue</th>
						<td class="text-right" style="{{ $leadTotal - $leadPurchaseTotal > 0 ? 'color:green;' : 'color:red;' }}">${{ number_format($leadTotal - $leadPurchaseTotal, 2, '.', ',') }}</td>
					</tr>
					<tr>
						<th colspan="2">Total Leads</th>
						<td class="text-right" style="{{ $leadTotal - $leadPurchaseTotal > 0 ? 'color:green;' : 'color:red;' }}">${{ number_format($leadTotal - $leadPurchaseTotal, 2, '.', ',') }}</td>
					</tr>
					<tr>
						<th rowspan="3">Cars</th>
						<td>New Leads</td>
						<td class="text-right" style="color:green;">${{ number_format($newTotal, 2, '.', ',') }}</td>
					</tr>
					<tr>
						<td>Used Leads</td>
						<td class="text-right" style="color:green;">${{ number_format($usedTotal, 2, '.', ',') }}</td>
					</tr>
					<tr>
						<td>Purchased Leads</td>
						<td class="text-right" style="color:red;">${{ number_format($leadPurchaseTotal, 2, '.', ',') }}</td>
					</tr>
					<tr>
						<th colspan="2">Total Ads</th>
						<td class="text-right">$##,###</td>
					</tr>
					<tr>
						<th rowspan="4">Ads</th>
						<td>Search</td>
						<td class="text-right">$#,###</td>
					</tr>
					<tr>
						<td>Banner</td>
						<td class="text-right">$#,###</td>
					</tr>
					<tr>
						<td>Custom</td>
						<td class="text-right">$#,###</td>
					</tr>
					<tr>
						<td>Syndication</td>
						<td class="text-right">$#,###</td>
					</tr>
					<tr>
						<th colspan="2">Total Affiliate</th>
						<td class="text-right">$##,###</td>
					</tr>
					<tr>
						<th rowspan="4">Affiliate</th>
						<td>Groceries</td>
						<td class="text-right">$#,###</td>
					</tr>
					<tr>
						<td>Banner</td>
						<td class="text-right">$#,###</td>
					</tr>
				</table>
			</div>
		</div>

	</div>
</div>

@stop