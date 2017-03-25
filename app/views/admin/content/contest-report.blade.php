@extends('admin.master.templates.master')
@section('sidebar')
    @include('admin.content.master.templates.sidebar', array())
@stop
@section('body')
<script>
    currentPage = 0;
    currentAppPage = 0;
    selectedContest = 0;
    selectedEmailSent = 0;
    doneTypingInterval = 500;
    typingTimer = '';
    typingAppTimer = '';
    typingUserTimer = '';
</script>
<!--BEGIN MAIN CONTENT-->
<div id="main" role="main">
    <div class="block">
        <div class="clearfix"></div>

        <!-- info-box -->
        <div class="grid">
            <div class="grid-title">
                <div class="pull-left">
                    <span>Contest Winners</span>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="grid-content overflow">
                <div class="row">
                  <div id="resultsGrid" class="col-md-12 box">
                    <div class="row margin-bottom-20 search-controls">
                        <div class="col-md-12 box">
                            <div class="row margin-bottom-10">
                                <div class="col-md-4">
                                    <input id="filterName" class="form-control search-query input-small" type="text" name="filter" value="{{Input::get('filter')}}" placeholder="Search by Contest Name">
                                </div>
                                <div class="col-md-4">
                                    <input id="filterMerchant" class="form-control search-query input-small" type="text" name="filter" value="{{Input::get('filter')}}" placeholder="Search by Merchant Name">
                                </div>
                                <div class="col-md-2">
                                    <select id="orderBy" class="form-control">
                                        <option value="date">Order By Date</option>
                                        <option value="contestName">Order By Contest Name</option>
                                        <option value="merchantName">Order By Merchant Name</option>
                                        <option value="winnerLastName">Order By Winner Last Name</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select id="orderByOrder" class="form-control">
                                        <option value="desc">DESC</option>
                                        <option value="asc">ASC</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <input id="filterEmail" class="form-control search-query input-small" type="text" name="filter" value="{{Input::get('filter')}}" placeholder="Search by Email">
                                </div>
                                <div class="col-md-4">
                                    <input id="filterLastName" class="form-control search-query input-small" type="text" name="filter" value="{{Input::get('filter')}}" placeholder="Search by Last Name">
                                </div>
                            </div>
                        </div>
                    </div>

                  <script type='text/ejs' id='template_contest_report'>
                      <% 
                      list(contests, function(contest)
                      { %>
                        <tr class="contest-row">
                          <td><%= contest.display_name %></td>
                          <td><%= contest.merchant_name %></td>
                          <td><%= contest.winner_first_name %> <%= contest.winner_last_name %></td>
                          <td><%= contest.date %></td>
                          <td><%== (contest.winner_address)?contest.winner_address+', '+contest.winner_city.toLowerCase().toUpperCase()+', '+contest.winner_state+' '+contest.winner_zip:'' %>
                          <td><button class="btn btn-success btn-xs btn-edit" data-winner_id="<%= contest.winner_id %>">Edit</button></td>
                        </tr>
                      <% }); %>
                    </script>

                    <table class="table table-bordered box">
                      <thead>
                        <tr>
                          <th>Contest</th>
                          <th>Merchant Name</th>
                          <th>Winner Name</th>
                          <th>Date Awarded</th>
                          <th>Address</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody id="contestReportResults">
                        @foreach ($contests as $contest)
                          <tr class="contest-row">
                            <td>{{$contest->display_name}}</td>
                            <td>{{$contest->merchant_name}}</td>
                            <td>{{$contest->winner_first_name}} {{$contest->winner_last_name}}</td>
                            <td>{{date('m/d/Y',strtotime($contest->winner_state_verified_at))}}</td>
                            <td>{{($contest->winner_address)?$contest->winner_address.', '.ucwords(strtolower($contest->winner_city)).', '.$contest->winner_state.' '.$contest->winner_zip:''}}</td>
                            <td><button class="btn btn-success btn-xs btn-edit" data-winner_id="{{$contest->winner_id}}">Edit</button></td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>

                </div>
            </div>
        </div>
    </div>

<div id="winnerEdit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close-new-winner" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Edit Contest Winner Info</h3>
      </div>
      <div class="modal-body">
        <p><strong class="contest-title"></strong></p>
        <form class="contest-winner-form">
            <div class="row">
                <div class="col-sm-6 form-group">
                    <label>First Name</label>
                    <input id="first_name" name="first_name" class="form-control" readonly="readonly" type="text">
                </div>
                <div class="col-sm-6 form-group">
                    <label>Last Name</label>
                    <input id="last_name" name="last_name" class="form-control" readonly="readonly" type="text">
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 form-group">
                    <label>Email</label>
                    <input id="email" name="email" class="form-control" type="email">
                </div>
                <div class="col-sm-6 form-group">
                    <label>Address</label>
                    <input id="address" name="address" class="form-control" type="text">
                </div>
            </div>
            <div class="row">
                <div class="col-sm-5 form-group">
                    <label>City</label>
                    <input id="city" name="city" class="form-control" type="text">
                </div>
                <div class="col-sm-3 form-group">
                    <label>State</label>
                    <input id="state" name="state" class="form-control" type="text">
                </div>
                <div class="col-sm-4 form-group">
                    <label>Zip</label>
                    <input id="zip" name="zip" class="form-control" type="text">
                </div>
            </div>
            <div>
                <div class="form-group">
                    <label>Reward URL</label>
                    <input id="reward_url" name="reward_url" class="form-control" readonly="readonly" type="text">
                </div>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-success btn-save">Save</button>
            </div>
        </form>
      </div>
      <!--<div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
        <button class="btn btn-primary">Save changes</button>
      </div>-->
      </div>
  </div>
  </div>
</div>

@stop