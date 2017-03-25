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
                    <span style="width: 100%">Seo Admin{{$category ? ' - '.$category->name : ''}}{{$subcategory ? ' - '.$subcategory->name : ''}}</span>
                </div>
            </div>
            <div class="grid-content overflow">
                <div class="row">
                    <div class="col-md-3">
                        <h4>Choose a {{ $subs['stats']['returned'] == 0 ? 'Category' : 'Subcategory' }}</h4>
                        <form action="/seo" method="POST">
                            <div class="form-group">
                                <select class="form-control" name="cat">
                                    <option value="0">Select</option>
                                    @foreach($parents['objects'] as $parent)
                                    <option value="{{ $parent->id }}" {{($category && $category->id == $parent->id) ? 'selected="selected"' : ''}}>{{ $parent->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if($category)
                            <div class="form-group">
                                <select class="form-control" name="subcat">
                                    <option value="0">Select</option>
                                    @foreach($subs['objects'] as $sub)
                                    <option value="{{ $sub->id }}" {{($subcategory && $subcategory->id == $sub->id) ? 'selected="selected"' : ''}}>{{ $sub->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            <button type="submit" class="btn btn-primary">Select</button>
                        </form>
                    </div>
                    <div class="col-md-9">
                        @if($selected)
                        <legend>{{ $selected->name }}</legend>
                        <form action="/seo" method="POST">
                            <input class="" type="hidden" name="cat" value="{{$category ? $category->id : ''}}">
                            <input type="hidden" name="subcat" value="{{$subcategory ? $subcategory->id : ''}}">
                            <input type="hidden" name="category_id" value="{{$selected->id}}">
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label class="control-label">Page Title</label>
                                    <div class="controls">
                                        <input class="form-control" type="text" name="title" value="{{ $selected->title }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label class="control-label">Page Description</label>
                                    <div class="controls">
                                        <textarea class="form-control" name="description" rows="4">{{ $selected->description }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label class="control-label">Header Content</label>
                                    <div class="controls">
                                        <textarea class="form-control" name="above_heading" rows="4">{{ $selected->above_heading }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label class="control-label">Sub Header Content</label>
                                    <div class="controls">
                                        <textarea class="form-control" name="sub_heading" rows="4">{{ $selected->sub_heading }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label class="control-label">Footer Content</label>
                                    <div class="controls">
                                        <textarea class="form-control" name="footer_heading" rows="4">{{ $selected->footer_heading }}</textarea>
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