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
                  @if($error)
                  <h3 style="color:red;">An error has occured, please try again shortly.</h3>
                  @endif
               </div>
               <div class="clearfix"></div>
            <!--page title end-->
          </div>
          <div class="col-xs-6 col-sm-3 form-group">
            <select id="selMarket" class="margin-top-20 form-control">
                <option value="all">All Markets</option>
                <option value="michigan" {{Input::get('market') == 'michigan' ? 'selected="selected"' : ''}}>Detroit</option>
                <option value="illinois" {{Input::get('market') == 'illinois' ? 'selected="selected"' : ''}}>Chicago</option>
                <option value="minnesota" {{Input::get('market') == 'minnesota' ? 'selected="selected"' : ''}}>Minneapolis</option>
            </select>
          </div>
          <div class="col-xs-6 col-sm-3 form-group">
            <select id="selDate" class="margin-top-20 form-control">
              <option value="last-month" {{Input::get('start') == 'last-month' ? 'selected="selected"' : ''}}>Last Month</option>
              <option value="this-month" {{Input::get('start') == 'this-month' ? 'selected="selected"' : ''}}>This Month</option>
              <option value="last-3-months" {{Input::get('start') == 'last-3-months' ? 'selected="selected"' : ''}}>Last 3 Months</option>
              <option value="this-year" {{Input::get('start') == 'this-year' ? 'selected="selected"' : ''}}>This Year</option>
              <option value="last-year" {{Input::get('start') == 'last-year' ? 'selected="selected"' : ''}}>Last Year</option>
              <option value="custom" {{Input::get('start') == 'custom' ? 'selected="selected"' : ''}}>Custom Range</option>
            </select>
          </div>
          <div class="col-xs-12 col-lg-6 pull-right text-right">
            <div id="custDiv" class="form-inline" style="{{ Input::get('start') == 'custom' ? '' : 'display:none;' }}">
                <input type="text" id="custStart" class="form-control margin-right-10" style="width:206px;" value="{{ $custStart }}" placeholder="Start...">
                <input type="text" id="custEnd" class="form-control" style="width:206px;" value="{{ $custEnd }}" placeholder="End...">
                <button id="custGo" class="btn btn-primary">Go</button>
            </div>
          </div>
        </div>
        
         <!--page title-->
         <!--<div class="pagetitle">
            <h1>Dashboard - {{date('m/d/Y', strtotime('-3 months')).' - '.date('m/d/Y')}}</h1> 
            <div class="clearfix"></div>
         </div>-->
         <!--page title end-->

        <div class="pull-right">
            

            

            
        </div>
        
        <!-- info-box -->
        <div class="info-box">
          <div class="row stats-box">
            <div class="col-xs-4 col-lg-2">
              <div class="stats-box-title">Visits</div>
              <div class="stats-box-all-info"><img src="/nightsky/images/icon/icon_vizitors_stats.png" alt=""> {{$visits}} <div style="color:{{$visitsTrend < 0 ? 'red' : 'green'}};">({{ $visitsTrend ? number_format($visitsTrend, 2, '.', '') : '--'}}%)</div></div>
            </div>
            <div class="col-xs-4 col-lg-3">
              <div class="stats-box-title">Prints</div>
              <div class="stats-box-all-info"><img src="/nightsky/images/icon/icon_print.png" alt=""> {{$prints}} <div style="color:{{$printsTrend < 0 ? 'red' : 'green'}};">({{ $printsTrend ? number_format($printsTrend, 2, '.', '') : '--'}}%)</div></div>
            </div>
            <div class="col-xs-4 col-lg-3">
              <div class="stats-box-title">Mobile Redemptions</div>
              <div class="stats-box-all-info"><img src="/nightsky/images/icon/icon_cont_check.png" alt=""> {{$redemptions}} <div style="color:{{$redemptionsTrend < 0 ? 'red' : 'green'}};">({{ $redemptionsTrend ? number_format($redemptionsTrend, 2, '.', '') : '--'}}%)</div></div>
            </div>
            <div class="clearfix hidden-lg margin-bottom-20"></div>
            <div class="col-xs-6 col-lg-2">
              <div class="stats-box-title">Active Merchants</div>
              <div class="stats-box-all-info"><img src="/nightsky/images/icon/icon_orders_stats.png" alt=""> {{$merchants}}</div>
            </div>
            <div class="col-xs-6 col-lg-2">
              <div class="stats-box-title">Member Total</div>
              <div class="stats-box-all-info"><img src="/nightsky/images/icon/icon_like_stats.png" alt=""> {{$members}}</div>
            </div>
          </div>
        </div>

        <div class="info-box padding-10">
          <div class="stats-box">
            <div class="row margin-bottom-20">
              <div class="col-xs-5">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="stats-box-title">User Details</div>
                        <table class="table">
                          <tr>
                            <th>New Visits:</th>
                            <td>{{number_format($visits * ($newSessions) / 100, 0, '.', '')}}<td>
                          </tr>
                          <tr>
                            <th>Repeat Visits:</th>
                            <td>{{number_format($visits * (100 - $newSessions) / 100, 0, '.', '')}}<td>
                          </tr>
                          <tr>
                            <th>% Repeat Visits:</th>
                            <td>{{number_format((100 - $newSessions), 2, '.', '')}}</td>
                          </tr><tr>
                            <th>Avg. Visit Duration (sec):</th>
                            <td>{{number_format($avgDuration, 2, '.', '')}}</td>
                          </tr><tr>
                            <th>New Members:</th>
                            <td>{{$newMembers}} <span style="color:{{$memTrend < 0 ? 'red' : 'green'}};">({{ $memTrend ? number_format($memTrend, 2, '.', '') : '--'}}%)</span></td>
                          </tr>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <div class="stats-box-title">Overall Visits Growth</div>
                        <table class="table">
                          <tr>
                            <th>Week to Week:</th>
                            <td><span style="color:{{$weekTrafficChange < 0 ? 'red' : 'green'}}">{{$prevWeekTraffic ? number_format(($weekTrafficChange / $prevWeekTraffic * 100), 2, '.', '') : '--'}}%</span></td>
                          </tr><tr>
                            <th>Month to Month:</th>
                            <td><span style="color:{{$monthTrafficChange < 0 ? 'red' : 'green'}}">{{$prevMonthTraffic ? number_format(($monthTrafficChange / $prevMonthTraffic * 100), 2, '.', '') : '--'}}%</span></td>
                          </tr>
                          <tr>
                            <th>Visit Trend</th>
                            <td>{{floor($trendVisits)}} <span style="color:{{$trendVisitsChange < 0 ? 'red' : 'green'}}">({{ $trendVisitsChange ? number_format($trendVisitsChange, 2, '.', '') : '--' }}%)</span></td>
                          </tr>
                          <tr>
                            <th>Merchant View Trend</th>
                            <td>{{floor($trendLocationViews)}} <span style="color:{{$trendLocationViewsChange < 0 ? 'red' : 'green'}}">({{ $trendLocationViewsChange ? number_format($trendLocationViewsChange, 2, '.', '') : '--' }}%)</span></td>
                          </tr>
                        </table>
                    </div>
                </div>
              </div>
              <div class="col-xs-2"></div>
              <div class="col-xs-5">
                <div class="row">
                    <div class="stats-box-title">Loyalty</div>
                    <table class="table">
                      <tr>
                        <th>Repeat Printers:</th>
                        <td>{{$repeaters}}</td>
                      </tr><tr>
                        <th>Repeat Prints:</th>
                        <td>{{$repeats['total']}}</td>
                      </tr><tr>
                        <th>Avg. Prints / Printer</th>
                        <td>{{number_format(($repeats['average']), 2, '.', '')}}</td>
                      </tr><tr>
                        <th>Contests:</th>
                        <td>{{$contests}} Contests Run / {{$applicants}} Participants</td>
                      </tr><tr>
                        <th>New Favorites Trend:</th>
                        <td>{{$newFavorites}} <span style="color:{{$favTrend < 0 ? 'red' : 'green'}};">({{$favTrend ? number_format($favTrend, 2, '.', '') : '--'}}%)</span></td>
                      </tr><tr>
                        <th>New Shares Trend:</th>
                        <td>{{$newShares}} <span style="color:{{$shareTrend < 0 ? 'red' : 'green'}};">({{$shareTrend ? number_format($shareTrend, 2, '.', '') : '--'}}%)</span></td>
                      </tr>
                    </table>
                </div>
                <div class="row">
                    <div class="stats-box-title">Member Newsletters</div>
                    <table class="table">
                      <tr>
                        <th>Newsletters Sent:</th>
                        <td>{{$memberMailStats['delivered']}}</td>
                      </tr><tr>
                        <th>Unique Opens:</th>
                        <td>{{$memberMailStats['opens']}}</td>
                      </tr><tr>
                        <th>Unique Clicks:</th>
                        <td>{{$memberMailStats['clicks']}}</td>
                      </tr>
                    </table>
                </div>
            </div>
            <div class="row">
              <div class="col-xs-5">
              </div>
              <div class="col-xs-2"></div>
              <div class="col-xs-5 hidden">
                <div class="stats-box-title">Other/Marketing</div>
                <table class="table">
                  <tr>
                    <th>Newsletter Emails Sent:<br>
                      &nbsp;&nbsp;Open/Click</th>
                    <td>###<br>
                      ###</td>
                  </tr><tr>
                    <th>Merchant Emails:</th>
                    <td>###</td>
                  </tr><tr>
                    <th>Market Breakdown</th>
                    <td>###</td>
                  </tr>
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

@stop