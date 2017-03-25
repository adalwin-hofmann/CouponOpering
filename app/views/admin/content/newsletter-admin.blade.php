@extends('admin.master.templates.master')
@section('sidebar')
    @include('admin.content.master.templates.sidebar', array())
@stop
@section('body')

<script id="template_newsletter" type="text/ejs">
<% list(newsletters, function(newsletter){ %>
    <tr>
        <td><button class="btn btn-primary btn-edit" data-batch_id="<%= newsletter.batch_id %>" data-newsletter_type="<%= newsletter.type %>">Edit</button></td>
        <td><%= newsletter.type %></td>
        <td><%= newsletter.schedule_name %></td>
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
                    <span>Newsletter Admin</span>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="grid-content overflow">
                <div id="searchGrid">
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-success btn-new">Create New Schedule</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 box">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Actions</th>
                                        <th>Type</th>
                                        <th>Name</th>
                                    </tr>
                                </thead>
                                <tbody id="resultsGrid">
                                    @foreach($schedules as $schedule)
                                    <tr>
                                        <td>
                                            <button class="btn btn-primary btn-edit" data-batch_id="{{$schedule->batch_id}}" data-newsletter_type="{{$schedule->type}}">Edit</button>
                                            <button class="btn btn-danger btn-delete" data-batch_id="{{$schedule->batch_id}}" data-newsletter_type="{{$schedule->type}}">Delete</button>
                                        </td>
                                        <td>{{ucwords(str_replace('_', ' ', $schedule->type))}}</td>
                                        <td>{{$schedule->schedule_name}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div id="editGrid" style="display:none;">
                    <div class="row">
                        <div class="col-md-12 box">
                            <legend>Edit Schedule - <span id="editType"></span></legend>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label>Schedule Name</label>
                                    <input class="form-control" id="schedule_name" type="text" name="schedule_name">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Next Sending Date</label>
                                    <input class="form-control" id="editDate" type="text" name="editDate">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Sending Interval (Days)</label>
                                    <input class="form-control" id="editInterval" type="text" name="editInterval">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Hour to Send (0 - 23)</label>
                                    <input class="form-control" id="editHour" type="text" name="editHour">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label>Email Subject Line</label>
                                    <input class="form-control" id="subjectLine" type="text" name="subjectLine">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label>Email Intro Paragraph</label>
                                    <textarea class="form-control" rows="4" id="intro" name="intro"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label class="control-label" for="featuredMerchant">Featured Merchant</label>
                                    <div class="controls input-group">
                                        <input class="form-control" id="featuredMerchant" type="text">
                                        <span class="input-group-btn">
                                            <button class="btn btn-danger btn-clear-featured">Clear</button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Center Zipcode (Optional)</label>
                                    <input class="form-control" type="text" id="zipcode">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Radius (Optional)</label>
                                    <input class="form-control" type="text" id="radius">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label>Order First</label>
                                    <select id="first_category">
                                        <option value="0">Default</option>
                                        @foreach($categories['objects'] as $cat)
                                        <option value="{{$cat->id}}">{{$cat->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>Order Second</label>
                                    <select id="second_category">
                                        <option value="0">Default</option>
                                        @foreach($categories['objects'] as $cat)
                                        <option value="{{$cat->id}}">{{$cat->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label>Order Third</label>
                                    <select id="third_category">
                                        <option value="0">Default</option>
                                        @foreach($categories['objects'] as $cat)
                                        <option value="{{$cat->id}}">{{$cat->name}}</option>
                                        @endforeach
                                    </select>
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