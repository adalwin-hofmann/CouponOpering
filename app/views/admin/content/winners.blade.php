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
                    <div class="row" style="margin-top: 5px;">
                        <div class="col-md-6">
                            <div class="" style="padding-bottom: 5px;">
                                <label>Search Contests</label>
                                <input id="nameSearch" type="text" class="form-control search-query" placeholder="Contest...">
                            </div>
                            <div class="">
                                <table class="table table-bordered box">
                                  <thead>
                                    <tr>
                                      <th>Name</th>
                                      <th>Contest</th>
                                      <th>End Date</th>
                                    </tr>
                                  </thead>
                                  <tbody id="resultsArea">
                                    <script type='text/ejs' id='template_contest'>
                                      <% 
                                      list(contests, function(contest)
                                      { %>
                                        <tr class="contest-row" data-contest_id="<%= contest.id %>" data-email_sent="<%= contest.follow_up_sent_at == null ? '0' : '1' %>">
                                          <td><%==contest.name.length>20?contest.name.substring(0, 20)+"...":contest.name;%></td>
                                          <td><%= contest.display_name %></td>
                                          <td><%= contest.end%></td>
                                        </tr>
                                      <% }); %>
                                    </script>
                                  </tbody>
                                  <tfoot>
                                    <tr>
                                      <td colspan="3">
                                        <div class="">
                                          <div id="paginationTop" class="">
                                            <ul class="pagination">
                                              <li><a class="pagingButton" style="cursor:pointer;" id="first">&lsaquo;&lsaquo; First</a></li>
                                              <li><a class="pagingButton" style="cursor:pointer;" id="prev">&lsaquo; Prev</a></li>
                                              <li><a class="pagingButton" style="cursor:pointer;" id="next">Next &rsaquo;</a></li>
                                              <li><a class="pagingButton" style="cursor:pointer;" id="last">Last &rsaquo;&rsaquo;</a></li>
                                            </ul>
                                          </div>
                                        </div>
                                      </td>
                                    </tr>
                                  </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="clearfix visible-xs"></div>
                        <div class="col-md-6">
                            <div class="form-group" style="">
                                <label>Search Applicants</label>
                                <input id="applicantSearch" type="text" class="form-control search-query" placeholder="Applicant...">
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Earliest</label>
                                    <input id="applicantStart" type="text" class="form-control">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Latest</label>
                                    <input id="applicantEnd" type="text" class="form-control">
                                </div>
                            </div>
                            <div class="">
                                <div id="winnersArea" class="col-md-12" style="display:none;">
                                    <table class="table table-bordered box">
                                      <thead>
                                        <tr>
                                          <th>Winner</th>
                                          <th>Name</th>
                                          <th>Email</th>
                                          <th>Chosen</th>
                                        </tr>
                                      </thead>
                                      <tbody id="winnersResults">
                                        <script type='text/ejs' id='template_winner'>
                                          <% 
                                          list(winner, function(winner)
                                          { %>
                                            <tr>
                                              <td><button class="btn winner-delete" data-applicant_id="<%= winner.id %>"><i class="icon-ban-circle"></i></button></td>
                                              <td style="background-color: #4DEEAD;"><%= winner.first_name %> <%= winner.last_name %></td>
                                              <td style="background-color: #4DEEAD;"><%= winner.user_email %></td>
                                              <td style="background-color: #4DEEAD;"><%= contest_control.GetDate(winner.verified_at) %></td>
                                            </tr>
                                          <% }); %>
                                        </script>
                                      </tbody>
                                    </table>
                                </div>
                            </div>
                            <table class="table table-bordered box">
                              <thead>
                                <tr>
                                    <th colspan="2">Total Applicants: <span id="totalApplicants"></span></th>
                                    <th colspan="2"><a href="" id="applicantCSVLink" style="display:none;">Download Applicant CSV</a></th>
                                </tr>
                                <tr>
                                  <th>Name</th>
                                  <th>Email</th>
                                  <th>Entered</th>
                                  <th>Select</th>
                                </tr>
                              </thead>
                              <tbody id="applicantsArea">
                                <script type='text/ejs' id='template_applicant'>
                                  <% 
                                  list(applicants, function(applicant)
                                  { %>
                                    <tr class="applicant-row" data-applicant_id="<%= applicant.id %>" data-applicant_contest_id="<%= applicant.contest_id %>" data-applicant_user_id="<%= applicant.user_id %>">
                                      <td><%= applicant.name == '' ? applicant.user_name : applicant.name %></td>
                                      <td><%= applicant.email%></td>
                                      <td><%= contest_control.GetDate(applicant.created_at) %></td>
                                      <td><button class="btn btn-save-winner disabled" data-applicant_id="<%= applicant.id %>">Save</button>&nbsp;</td>
                                    </tr>
                                  <% }); %>
                                </script>
                              </tbody>
                              <tfoot>
                                <tr>
                                  <td colspan="4">
                                    <div class="">
                                      <div id="paginationTop" class="pagination">
                                        <ul class="pagination">
                                          <li><a class="app_pagingButton" style="cursor:pointer;" id="app_first">&lsaquo;&lsaquo; First</a></li>
                                          <li><a class="app_pagingButton" style="cursor:pointer;" id="app_prev">&lsaquo; Prev</a></li>
                                          <li class="new-winner-parent disabled"><a style="cursor:pointer;" class="new-winner-button disabled" role="button" class="btn" data-toggle="modal">Add Winner</a></li>
                                          <li><a class="app_pagingButton" style="cursor:pointer;" id="app_next">Next &rsaquo;</a></li>
                                          <li><a class="app_pagingButton" style="cursor:pointer;" id="app_last">Last &rsaquo;&rsaquo;</a></li>
                                        </ul>
                                      </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <button id="btnContestEndEmail" class="btn btn-primary pull-left" style="display:none;" data-loading-text="Loading...">Send Contest End Email</button>
                                            <div id="confirmDiv" style="display:none;">
                                                <label>Are You Sure?</label>
                                                <button id="btnEmailConfirm" class="btn btn-success pull-left">Send</button>
                                                <button id="btnEmailCancel" class="btn btn-warning pull-left">Cancel</button>
                                            </div>
                                        </div>
                                        <div id="divTestEmail" class="col-md-6" style="display:none;">
                                            <input type="text" id="testEmail" class="form-control fill-up pull-right" placeholder="Email..."/>
                                            <button id="btnContestEndTest" class="btn btn-primary pull-right" data-loading-text="Sending..."><span>Send Test Email</span></button>
                                        </div>
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

<div id="newWinner" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close close-new-winner" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Search for Users</h3>
      </div>
      <div class="modal-body">
        <div class="form-group" style="padding-bottom: 5px;">
            <label>Search Users</label>
            <input id="userSearch" type="text" class="form-control search-query" placeholder="Email address">
        </div>
          <table class="table table-bordered box hidden user-search">
            <thead>
              <tr>
                <th>Name</th>
                <th colspan="2">Email</th>
              </tr>
            </thead>
            <tbody id="usersArea">
              <script type='text/ejs' id='template_users'>
                <% 
                list(users, function(users)
                { %>
                  <tr class="users-row" data-user_id="<%= users.id %>">
                    <td><%= users.name == '' ? users.user_name : users.name %></td>
                    <td><%= users.email%></td>
                    <td><button class="btn btn-add-winner disabled" data-user_id="<%= users.id %>">Save</button>&nbsp;</td>
                  </tr>
                <% }); %>
              </script>
            </tbody>
          </table>
            <label>Enter Custom Winner</label>
            <table class="table table-bordered box">
              <tr>
                <td class="form-group"><input id="CustomFirstName" name="CustomFirstName" type="text" class="form-control" placeholder="First Name"></td>
                <td class="form-group"><input id="CustomLastName" name="CustomLastName" type="text" class="form-control" placeholder="Last Name"></td>
                <td class="form-group"><input id="CustomCity" name="CustomCity" type="text" class="form-control" placeholder="City"></td>
                <td class="form-group"><input id="CustomState" name="CustomState" type="text" class="form-control" placeholder="State"></td>
                <td><button class="btn btn-custom-winner" data-user_id="<%= users.id %>">Save</button></td>
              </tr>
            </table>
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