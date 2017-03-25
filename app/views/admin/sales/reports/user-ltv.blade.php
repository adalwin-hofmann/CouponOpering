@extends('admin.master.templates.master')
@section('sidebar')
    @include('admin.sales.master.templates.sidebar', array())
@stop
@section('body')

<script>
    lastpage = "{{$lastpage}}";
</script>

<div id="main" role="main">
    <div class="block">
        <div class="clearfix"></div>

        <!-- info-box -->
        <div class="grid">
            <div class="grid-title">
                <div class="pull-left">
                    <span>User LTV Report</span>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="grid-content overflow">
                <div class="row">
                    <div id="ltvGrid" class="col-md-12 box">
                        <div class="row padded">
                            <div class="col-md-12">
                                <form id="searchForm" action="/user-ltv" method="POST">
                                    <!-- <div class="row">
                                        <div class="col-md-4">
                                            <label>Start Date</label>
                                            <input id="startDate" type="text" value="">
                                        </div>
                                        <div class="col-md-4">
                                            <label>End Date</label>
                                            <input id="endDate" type="text" value="">
                                        </div>
                                    </div> -->
                                    <div class="row">
                                        <div class="col-md-6 form-group">
                                            <label>Sort By</label>
                                            <select class="form-control" id="sortBy" name="sortBy">
                                                <option value="ltv" {{Input::old('sortBy') == 'ltv' ? 'selected="selected"' : ''}}>LTV</option>
                                                <option value="views" {{Input::old('sortBy') == 'views' ? 'selected="selected"' : ''}}>Views</option>
                                                <option value="prints" {{Input::old('sortBy') == 'prints' ? 'selected="selected"' : ''}}>Prints</option>
                                                <option value="apps" {{Input::old('sortBy') == 'apps' ? 'selected="selected"' : ''}}>Contest Apps</option>
                                                <option value="sohi_quotes" {{Input::old('sortBy') == 'sohi_quotes' ? 'selected="selected"' : ''}}>SOHI Quotes</option>
                                                <option value="soct_quotes" {{Input::old('sortBy') == 'soct_quotes' ? 'selected="selected"' : ''}}>Auto Quotes</option>
                                                <option value="most_recent_activity" {{Input::old('sortBy') == 'most_recent_activity' ? 'selected="selected"' : ''}}>Last Activity Date</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label>Sort Direction</label>
                                            <div class="input-group">
                                              <select class="form-control" id="sortDir" name="sortDir">
                                                  <option value="desc" {{Input::old('sortDir') == 'desc' ? 'selected="selected"' : ''}}>DESC</option>
                                                  <option value="asc" {{Input::old('sortDir') == 'asc' ? 'selected="selected"' : ''}}>ASC</option>
                                              </select>
                                              <span class="input-group-btn">
                                                <button id="btnSearch" class="btn btn-primary" data-loading-text="Searching...">Go</button>
                                              </span>
                                            </div>
                                        </div>
                                    </div>
                                    <input type="hidden" id="page" name="page" value="{{Input::old('page') ? Input::old('page') : 0}}">
                                </form>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12" style="overflow-x: scroll">
                                <table class="table table-bordered box">
                                    <thead>
                                        <tr>
                                          <th>Email</th>
                                          <th>Type</th>
                                          <th>Views</th>
                                          <th>Prints</th>
                                          <th>Contest Apps</th>
                                          <th>SOHI Quotes</th>
                                          <th>Auto Quotes</th>
                                          <th>LTV</th>
                                          <th>Last Activity</th>
                                        </tr>
                                    </thead>
                                    <tbody id="resultsArea">
                                        @foreach($members as $member)
                                        <tr>
                                          <td>{{$member->email}}</td>
                                          <td>{{$member->type}}</td>
                                          <td style="text-align:center;">{{$member->views ? $member->views : 0}}</td>
                                          <td style="text-align:center;">{{$member->prints ? $member->prints : 0}}</td>
                                          <td style="text-align:center;">{{$member->apps ? $member->apps : 0}}</td>
                                          <td style="text-align:center;">{{$member->sohi_quotes ? $member->sohi_quotes : 0}}</td>
                                          <td style="text-align:center;">{{$member->soct_quotes ? $member->soct_quotes : 0}}</td>
                                          <td style="text-align:center;">{{$member->ltv ? $member->ltv : 0}}</td>
                                          <td>{{date('m-d-Y', strtotime($member->most_recent_activity))}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                          <td>
                                            <strong>Total Users: {{$stats[0]->total_members}}</strong>
                                          </td>
                                          <td style="text-align:right;">
                                            <strong>Averages:</strong>
                                          </td>
                                          <td style="text-align:center;">{{$stats[0]->avg_views}}</td>
                                          <td style="text-align:center;">{{$stats[0]->avg_prints}}</td>
                                          <td style="text-align:center;">{{$stats[0]->avg_apps}}</td>
                                          <td style="text-align:center;">{{$stats[0]->avg_sohi_quotes}}</td>
                                          <td style="text-align:center;">{{$stats[0]->avg_soct_quotes}}</td>
                                          <td style="text-align:center;">{{$stats[0]->avg_ltv}}</td>
                                          <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                          <td colspan="9">
                                              <div id="paginationTop" class="pagination col-md-12">
                                                <ul class="pagination">
                                                  <li><a class="pagingButton" style="cursor:pointer;" id="first">&lsaquo;&lsaquo; First</a></li>
                                                  <li><a class="pagingButton" style="cursor:pointer;" id="prev">&lsaquo; Prev</a></li>
                                                  <li><a class="pagingButton" style="cursor:pointer;" id="next">Next &rsaquo;</a></li>
                                                  <li><a class="pagingButton" style="cursor:pointer;" id="last">Last &rsaquo;&rsaquo;</a></li>
                                                </ul>
                                              </div>
                                          </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop