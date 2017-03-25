@extends('admin.master.templates.master')
@section('sidebar')
    @include('admin.sales.master.templates.sidebar', array())
@stop
@section('body')

<script>
    franchise_id = '{{$franchise->id}}';
    range = '{{$range}}';
    offers = '{{Input::get("offers", 10)}}';
    customStart = '{{$customStart}}';
    customEnd = '{{$customEnd}}';
</script>

    <!--BEGIN MAIN CONTENT-->
    <div id="main" role="main">
      <div class="block">
      <div class="clearfix"></div>
        
        <ul class="breadcrumb">
          <li><a href="/">Dashboard</a></li>
          <li><a href="/merchant-list?date-range={{$range.'&page='.$page.'&limit='.$limit.'&order='.$order.'&direction='.$direction.'&options='.$options.'&filter='.$filter.'&category='.$category.'&subcategory='.$subcategory.'&rep='.$rep.'&market='.$market}}{{$range == 'custom' ? '&custom-start='.$customStart.'&custom-end='.$customEnd : ''}}">Merchant List</a></li>
          <li class="active">Merchant Report</li>
        </ul>

         <!--page title-->
         <div class="pagetitle">
            <h1>Merchant Report -- {{ date('m/d/Y', strtotime($start)).' - '.date('m/d/Y', strtotime($end)) }}</h1> 
            <div class="clearfix"></div>
         </div>
         <!--page title end-->
         <div class="clearfix"></div>
         <!-- Search begin -->
         <div class="">
          <!--<div class="span4">
            <form class="search">
              <input class="search-query" placeholder="Search For A Merchant" type="text">
              <input id="currentLocation" type="hidden" value="-1">
            </form>
          </div>-->
          <div class="clearfix"></div>
          <div class="row-fluid">
            <!--<div class="span3">
                <select id="searchOptions" name="choose">
                    <option value="">-- Choose --</option>
                    <option value="Today">Today</option>
                    <option value="Yesterday">Yesterday</option>
                    <option value="ThisWeek">This Week</option>
                    <option value="Last7">Last 7 Days</option>
                    <option value="ThisMonth">This Month</option>
                    <option value="LastMonth">Last Month</option>
                    <option value="Custom">Custom Range</option>
                </select>
            </div>-->
            <!-- <div class="span3">
                <input id="startdatepicker" name="start" type="text" placeholder="Start Date…" class="search-date-picker" value="{{date('m/d/Y', strtotime('-3 month'))}}">
            </div>
            <div class="span1 text-center padding-top-10">To</div>
            <div class="span3">
                <input id="enddatepicker" name="end" type="text" placeholder="End Date…" class="search-date-picker" value="{{date('m/d/Y')}}">
            </div> -->
          </div>

          <!-- New July 2014 -->
          <h4>{{ $merchant->display }}</h4>
          <span><a href="/merchant-pdf?franchise={{ $franchise->id }}&date-range={{Input::get('date-range')}}&offers={{Input::get('offers', 10)}}{{$range == 'custom' ? '&custom-start='.$customStart.'&custom-end='.$customEnd : ''}}">Download PDF</a></span>

            <div class="grid">
              <div class="grid-title">
                <div class="pull-left">
                  <span>Overview</span>
                </div>
              </div>
              <div class="grid-content overflow">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th></th>
                      <th>{{ $dateText }}</th>
                    </tr>
                  </thead>
                  <tbody id="details">
                    <tr>
                      <th>Merchant Views</th>
                      <td>{{ $franchise->current_views }}</td>
                    </tr>
                    <tr>
                      <th>Prints</th>
                      <td>{{ $franchise->current_prints }}</td>
                    </tr>
                    <tr>
                      <th>Shares</th>
                      <td>{{ $franchise->current_shares }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <div class="grid">
              <div class="grid-title">
                <div class="pull-left">
                  <span style="width:100%">Offer Details (Last {{Input::get('offers', 10)}} Offers and Last {{Input::get('offers', 10)}} Contests)</span>
                </div>
                <div class="pull-right">
                  <span class="margin-right-10"><a id="showMore" href="#">Show Last {{Input::get('offers', 10) + 20}} Offers</a></span>@if(Input::get('offers', 10) > 10)<span class="margin-right-10"><a id="showLess" href="#">Show Less</a></span>@endif
                </div>
              </div>
              <div class="grid-content overflow">
                <table class="table">
                  <thead>
                    <tr>
                      <th>Offer</th>
                      <th>Type</th> <!-- Coupon, Daily Deal, Contest --> 
                      <th>Offer Views</th>
                      <th>Prints</th>
                      <th>Entries</th>
                      <th>Starts</th>
                      <th>Expires</th>
                    </tr>
                  </thead>
                  <tbody id="offers">
                    @foreach($franchise->offers as $offer)
                    <tr class="{{$offer['offer']->is_followup_for == 0 ? '' : 'is-follow-up'}}">
                        <td>{{ $offer['offer']->name }}</td>
                        <td>{{ $offer['offer']->is_dailydeal ? 'Daily Deal' : 'Offer' }}</td>
                        <td>{{ $offer['total_views'] }}</td>
                        <td>{{ $offer['total_prints'] }}</td>
                        <td>---</td>
                        <td>{{ date('m/d/Y', strtotime($offer['offer']->starts_at)) }}</td>
                        <td>{{ date('m/d/Y', strtotime($offer['offer']->expires_at)) }}</td>
                    </tr>
                    @endforeach
                    @foreach($franchise->contests as $contest)
                    <tr>
                        <td>{{ $contest->display_name }} <button class="btn btn-xs" data-toggle="modal" data-target="#{{$contest->id}}ContestModal" {{!isset($contest->data['entity']) ? 'disabled' : ''}}>More Info</button></td>
                        <td>Contest</td>
                        <td>---</td>
                        <td>---</td>
                        <td>{{ $contest->apps ? $contest->apps : 0 }}</td>
                        <td>{{ date('m/d/Y', strtotime($contest->starts_at)) }}</td>
                        <td>{{ date('m/d/Y', strtotime($contest->expires_at)) }}</td>
                    </tr>
                    @endforeach
                  </tbody>
                  <tfoot>
                    <tr>
                        <td colspan="7">
                            <button class="btn btn-xs is-follow-up">&nbsp</button> - <small>Denotes Follow Up Offer</small>
                        </td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>

          <!-- Limit to 5 locations with a show more button -->
                <div class="grid">
                    <div class="grid-title">
                        <div class="pull-left">
                            <span>Location Details</span>
                        </div>
                    </div>
                    <div class="grid-content overflow">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Location</th>
                                    <th>Location Views</th>
                                    <th>Prints</th>
                                </tr>
                            </thead>
                            <tbody id="locations">
                                @foreach($franchise->locations as $location)
                                <tr>
                                    <td>{{ $location['name'] }}</td>
                                    <td>{{ $location['views'] }}</td>
                                    <td>{{ $location['prints'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

          <!-- Should Look into Adding SoCT data for dealers -->
@if(count($franchise->leads))          
<div class="grid">
    <div class="grid-title">
        <div class="pull-left">
            <span>Leads Purchased</span>
        </div>
    </div>
    <div class="grid-content overflow">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Purchased Date</th>
                    <th>Lead Name</th>
                    <th>Lead Email</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($franchise->leads as $lead)
                    <tr>
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
@endif

<div class="hidden">
          <div class="row-fluid">
            <div class="span12">
                <span id="searchMessage" style="display:none;"></span>
            </div>
          </div>
          <div class="clearfix"></div>
         <!-- Search end -->
         <h1 id="merchantTitle"></h1>
         <hr>
         <!-- date range start -->
         <div class="row-fluid">
          <p>Showing Results For:</p>
          <h3 id="dateRange">{{date('m/d/Y', strtotime('-3 month'))}} &ndash; {{date('m/d/Y')}}</h3> <!-- <button class="btn btn-success">Change Date Range</button> -->
        </div>
        <div class="row-fluid">
            <div class="span12 btn-group" data-toggle="buttons-radio">
                <button id="viewNewSite" type="button" class="btn btn-primary btn-archive active">New Site</button>
                <button id="viewOldSite" type="button" class="btn btn-primary btn-archive">Old Site</button>
            </div>
        </div>
        <br>
         <!-- date range end -->
         <div class="row-fluid">
          <a id="pdfDownload" href="" class="btn btn-success download-pdf"><i class="icon-file"></i> PDF</a>
         </div>
         <!-- info-box -->
         <div class="grid">
            <div class="grid-title">
              <div class="pull-left">
                <span>Merchant Details</span>
              </div>
            </div>
          <div class="grid-content">
           <div class="row-fluid stats-box">
              <div class="span6">
                <div class="stats-box-title">Views</div>
                <div class="stats-box-all-info"><img src="/nightsky/images/icon/icon_view.png" alt=""><span id="totalViews" style="padding-left:5px;">0</span></div>
              </div>
              
              <div class="span6">
                <div class="stats-box-title">Prints</div>
                <div class="stats-box-all-info"><img src="/nightsky/images/icon/icon_print.png" alt=""><span id="totalPrints" style="padding-left:5px;">0</span></div>
              </div>
            </div>
           </div>
         </div>
         <!-- info-box end -->
         <div class="row-fluid">
          <div class="grid span12">
            <div class="grid-title">
              <div class="pull-left">
                <span>Location Details</span>
              </div>
            </div>
            <div class="grid-content overflow">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Location</th>
                    <th>Location Views</th>
                    <th>Prints</th>
                  </tr>
                </thead>
                <tbody id="locations">

                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="row-fluid">
            <div id="locationTitle" class="span12"></div>
        </div>
        <div class="row-fluid">
          <div class="grid span12">
            <div class="grid-title">
              <div class="pull-left">
                <span>Offer Details</span>
              </div>
            </div>
            <div class="grid-content overflow">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Offer</th>
                    <th>Offer Views</th>
                    <th>Prints</th>
                    <th>Sign Ups</th>
                    <th>Expires</th>
                  </tr>
                </thead>
                <tbody id="offers">
                  
                </tbody>
              </table>
            </div>
          </div>
        </div>
</div>
           
           <!--BEGIN FOOTER-->
           <div class="footer">
              <div class="left">Copyright &copy; 2013</div>
              <div class="right"><!--<a href="#">Buy Template</a>--></div>
              <div class="clearfix"></div>
           </div>
           <!--BEGIN FOOTER END-->
          
      <div class="clearfix"></div> 
      </div><!--end .block-->
    </div>
    <!--MAIN CONTENT END-->

<!-- Modals -->
@foreach($franchise->contests as $contest)
@if(isset($contest->data['entity']))
<div class="modal fade contest-info-modal" id="{{$contest->id}}ContestModal" tabindex="-1" role="dialog" aria-labelledby="{{$contest->id}}ContestModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title">{{ $contest->display_name }}</h4>
      </div>
      <div class="modal-body">
        <p>The follow up email should go out after {{ date('m/d/Y', strtotime($contest->expires_at. ' + 10 days')) }}. Here is a preview of the email below:</p>

        <div class="email-view">
        @include('emails.contestended', array('data'=>$contest->data))
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endif
@endforeach


@stop