@extends('admin.master.templates.master')
@section('sidebar')
    @include('admin.content.master.templates.sidebar', array())
@stop
@section('body')
<script>
    currentPage = 0;
    doneTypingInterval = 500;
    typingTimer = '';
    selectedUser = 0;
</script>

<script id="template_user" type="text/ejs">
<% list(users, function(user){ %>
    <tr>
        <td><button class="btn btn-primary btn-edit" data-user_id="<%= user.id %>">Edit</button></td>
        <td><%= user.name %></td>
        <td><%= user.email %></td>
        <td><%= user.type %></td>
    </tr>
<% }) %>
</script>

<script id="template_franchise_association" type="text/ejs">

    <tr class="row">
        <td style="width:15%;">
            <button class="btn btn-danger btn-remove-association" data-franchise_id="<%= franchise.id %>">Remove</button>
        </td>
        <td>
            <input disabled="disabled" class="form-control" value="<%= franchise.display %>">
        </td>
    </tr>

</script>

<script id="template_franchise" type="text/ejs">
    <tr class="row">
        <td style="width:15%;">
            <button class="btn btn-danger btn-remove-association" data-franchise_id="<%= franchise.id %>">Remove</button>
        </td>
        <td>
            <input disabled="disabled" class="form-control" value="<%= franchise.name %>">
        </td>
    </tr>
</script>

<!--BEGIN MAIN CONTENT-->
<div id="main" role="main">
    <div class="block">
        <div class="clearfix"></div>

        <!-- info-box -->
        <div class="grid">
            <div class="grid-title">
                <div class="pull-left">
                    <span>Users Admin</span>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="grid-content overflow">
                <div id="searchGrid">
                    <div class="row">
                        <div class="col-md-12 box">
                            <div class="row">
                                <div class="col-md-4">
                                    <input id="filter" class="form-control search-query input-small" type="text" name="filter" value="{{Input::get('filter')}}" placeholder="Name or Email...">
                                </div>
                                <div class="col-md-4">
                                    <input id="typeFilter" class="form-control search-query input-small" type="text" name="typeFilter" value="{{Input::get('typeFilter')}}" placeholder="User Type...">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 box">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Actions</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Types</th>
                                    </tr>
                                </thead>
                                <tbody id="resultsGrid">

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td>
                                            <div class="">
                                              <div id="paginationTop" class="pagination col-md-12">
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
                </div>

                <div id="editGrid" style="display:none;">
                    <div class="row">
                        <div class="col-md-12 box">
                            <legend>Edit Name</legend>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Name</label>
                                    <input class="form-control" id="editName" type="text" name="name">
                                </div>
                            </div>
                            <legend>Edit Types</legend>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <input class="form-control" id="editTypes" type="text" name="type">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Assignment Types</label>
                                    @foreach($assignmentTypes as $assignmentType)
                                    <div class="checkbox">
                                      <label>
                                        <input type="checkbox" style="display:block" class="assignment-type" value="{{$assignmentType->id}}">
                                        {{$assignmentType->name}}
                                      </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <legend>Franchise Association</legend>
                            <div class="row">
                                <div class="col-md-6">
                                    <input class="form-control" id="franchiseSearch" type="text" name="type">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-striped">
                                        <tbody id="franchiseAssociations">

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <legend>Edit Password</legend>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Password</label>
                                    <input class="form-control" id="password" type="password" name="password">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Password Confirm</label>
                                    <input class="form-control" id="password_confirmation" type="password" name="password_confirmation">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button class="btn btn-primary btn-save">Save</button>
                                    <button class="btn btn-danger btn-close">Close</button>
                                    <span id="editMessage" class="pull-right"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@stop