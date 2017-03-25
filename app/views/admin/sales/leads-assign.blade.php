@extends('admin.master.templates.master')
@section('sidebar')
    @include('admin.sales.master.templates.sidebar', array())
@stop
@section('body')
<script type='text/ejs' id='template_lead'>
<% list(leads, function(lead){ %>
    <tr class="lead-row row-selectable" data-lead_id="<%= lead.lead.id %>">
      <td><%= lead.first+' '+lead.last %></td>
      <td><%= lead.email %></td>
      <td><%= lead.lead.details_type.slice(0, -7) %></td>
      <td><%= lead.lead.details.category %></td>
      <td><%= assign_control.GetDate(lead.created_at) %></td>
    </tr>
<% }); %>
</script>

<script>
    typing = false;
    doneTypingInterval = 400;
    typingTimer = '';
</script>

<div id="main" role="main">
    <div class="block">
        <div class="clearfix"></div>

        <!-- info-box -->
        <div class="grid">
            <div class="grid-title">
                <div class="pull-left">
                    <span>Lead Assignment</span>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="grid-content overflow">
                <div class="row">
                    <div id="assignGrid" class="col-md-12 box">
                        <div class="row padded">
                            <div class="col-md-4 form-group input-group">
                                <input class="form-control" id="nameSearch" type="text">
                                <span class="input-group-btn">
                                    <button id="btnSearch" class="btn btn-primary" data-loading-text="Searching...">Search</button>
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered box">
                                    <thead>
                                        <tr>
                                          <th>Name</th>
                                          <th>Email</th>
                                          <th>Type</th>
                                          <th>Category</th>
                                          <th>Created</th>
                                        </tr>
                                    </thead>
                                    <tbody id="resultsArea">
                                        
                                    </tbody>
                                    <!-- <tfoot>
                                        <tr>
                                          <td colspan="7">
                                            <div class="row">
                                              <div id="paginationTop" class="pagination col-md-12">
                                                <ul>
                                                  <li><a class="pagingButton" style="cursor:pointer;" id="first">&lsaquo;&lsaquo; First</a></li>
                                                  <li><a class="pagingButton" style="cursor:pointer;" id="prev">&lsaquo; Prev</a></li>
                                                  <li><a class="pagingButton" style="cursor:pointer;" id="next">Next &rsaquo;</a></li>
                                                  <li><a class="pagingButton" style="cursor:pointer;" id="last">Last &rsaquo;&rsaquo;</a></li>
                                                </ul>
                                              </div>
                                            </div>
                                          </td>
                                        </tr>
                                    </tfoot> -->
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="assignmentModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="assignmentModalLabel" aria-hidden="true">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="assignmentModalLabel">Assign Lead
                    <div class="pull-right" style="padding-right: 10px;">
                        
                    </div>
                </h3>
            </div>
            <div class="modal-body" style="min-height:200px;">
                <div class="row">
                    <div class="col-md-12 form-group">
                        <label>Find Merchant</label>
                        <div class="input-group">
                            <input class="form-control" id="merchantSearch" type="text">
                            <span class="input-group-btn">
                                <button id="merchantClear" class="btn btn-danger">Clear</button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <span id="assignMessages" style="display:none; margin-right:5px;"></span> 
                <button id="btnAssign" class="btn btn-success">Assign</button>
                <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
            </div>
        </div>
    </div>
</div>
@stop