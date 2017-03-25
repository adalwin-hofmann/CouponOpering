@extends('admin.master.templates.master')
@section('sidebar')
    @include('admin.content.master.templates.sidebar', array())
@stop
@section('body')

<script type="text/ejs" id="template_event_list">
<% list(events, function(event){ %>
    <tr class="row-selectable" data-event_id="<%= event.id %>">
        <td><%= event.name %></td>
        <td><%= event.dateFormat %></td>
    </tr>
<% }) %>
</script>

<!--BEGIN MAIN CONTENT-->
<div id="main" role="main">
    <div class="block">
        <div class="clearfix"></div>

        <!-- info-box -->
        <div class="grid">
            <div class="grid-title">
                <div class="pull-left">
                    <span>Events Admin</span>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="grid-content overflow">
                <div id="searchGrid">
                    <div class="row margin-bottom-20">
                        <div class="col-md-12 box">
                            <div class="row">
                                <div class="col-md-4">
                                    <input id="filterName" class="form-control search-query input-small" type="text" name="filter" value="{{Input::get('filter')}}" placeholder="Search by Event Name">
                                </div>
                                <div class="col-md-8">
                                    <button class="btn btn-primary btn-attendees-modal" disabled="disabled">View Attendees</button>
                                    <button class="btn btn-success btn-new">Add New Event</button>
                                    <button class="btn btn-danger btn-delete" disabled="disabled">Delete This Event</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody id="eventResults">
                                    @foreach($active_events as $active_event)
                                    <tr class="row-selectable" data-event_id="{{$active_event->id}}">
                                        <td>{{$active_event->name}}</td>
                                        <td>{{date('m/d/Y', strtotime($active_event->date))}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="col-sm-8">
                            <form>
                                <div class="form-group">
                                    <label>Event Title*</label>
                                    <div class="controls">
                                        <input id="name" name="name" class="form-control fill-up" type="text" placeholder="Event Title*">
                                    </div>
                                </div>
                                <div class="form-group hidden url-group">
                                    <label>Event URL*</label>
                                    <div class="controls">
                                        <input id="url" name="url" class="form-control fill-up" type="text" placeholder="Event Url*" readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 form-group">
                                        <label>Event Date*</label>
                                        <div class="controls">
                                            <input id="event_date" name="event_date" class="form-control fill-up" type="text" placeholder="Event Date">
                                        </div>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label>Event Time* <small>Format: 99:99 PM</small></label>
                                        <div class="controls">
                                            <input id="event_time" name="event_time" class="form-control fill-up" type="text" placeholder="Event Time">
                                        </div>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <label>End Time* <small>Format: 99:99 PM</small></label>
                                        <div class="controls">
                                            <input id="end_datetime" name="end_datetime" class="form-control fill-up" type="text" placeholder="End Time">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Description*</label>
                                    <div class="controls">
                                        <textarea id="description" name="description" class="form-control" rows="4"></textarea>
                                    </div>
                                </div>
                                <div class="form-group text-right">
                                    <input id="event_id" name="event_id" type="hidden" value="0">
                                    <button type="button" class="btn btn-primary btn-lg btn-save">Save</button>
                                </div>
                                <p class="bg-success saved-message"></p>

                            </form>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>

    </div>
</div>

<script type="text/ejs" id="template_attendees_list">
<% list(attendees, function(attendee){ %>
    <tr>
        <td><%= attendee.name %></td>
        <td><%= attendee.company %></td>
        <td><%= attendee.email %></td>
    </tr>
<% }) %>
</script>

<div id="attendeesModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                 <h3>List of Attendees</h3>
            </div>
            <div class="modal-body">
                <div class="attendees-results">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Company</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody id="attendeesResults">
                        </tbody>
                    </table>
                </div>
                <div class="attendees-no-results hidden">
                    <p>There are no attendees at this time.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="deleteModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                 <h3>Are You Sure?</h3>
            </div>
            <div class="modal-body text-center">
                <p>Are you sure you want to delete this event?</p>
                <p><button class="btn btn-danger btn-delete-confirm">Yes</button> <button class="btn btn-default" data-dismiss="modal">No</button></p>
            </div>
        </div>
    </div>
</div>

@stop