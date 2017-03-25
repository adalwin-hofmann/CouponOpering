@extends('admin.master.templates.master')
@section('sidebar')
    @include('admin.content.master.templates.sidebar', array())
@stop
@section('body')
<!--BEGIN MAIN CONTENT-->
<div id="main" role="main">
    <div class="block">
        <div class="clearfix"></div>

        <div class="grid">
            <div class="grid-title">
                <div class="pull-left">
                    <span style="width: 100%">State Page Seo{{$city_image ? ' - '.$city_image->state : ''}}</span>
                </div>
            </div>
            <div class="grid-content overflow">
                <div class="row">
                    <div class="col-md-3">
                        <h4>Choose a State</h4>
                        <form action="/seo/states" method="POST">
                            <div class="form-group">
                                <select class="form-control" name="state">
                                    <option value="">Select</option>
                                    @foreach($states as $state)
                                    <option value="{{$state->state}}" {{($city_image && $city_image->state == $state->state) ? 'selected="selected"' : ''}}>{{$state->state}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Select</button>
                        </form>
                    </div>
                    <div class="col-md-9">
                        @if($city_image)
                        <legend>{{ $city_image->state }}</legend>
                        <form action="/seo/states" method="POST">
                            <input type="hidden" name="state" value="{{$city_image->state}}">
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label class="control-label">About</label>
                                    <div class="controls">
                                        <textarea id="about" class="form-control" name="about">{{ $city_image->about }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success">Save</button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop