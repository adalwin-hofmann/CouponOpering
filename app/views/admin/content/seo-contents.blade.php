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
                    <span style="width: 100%">Page Seo Content</span>
                </div>
            </div>
            <div class="grid-content overflow">
                <div class="row">
                    <div class="col-md-12">
                        <h4>Search</h4>
                        <form action="/seo" method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Page URL</label>
                                        <select class="form-control" name="page_url">
                                            <option value="">Select</option>
                                            @foreach($urls as $url)
                                            <option value="{{$url->page_url}}" {{($content && $content->page_url == $url->page_url) ? 'selected="selected"' : ''}}>{{$url->page_url}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Content Type</label>
                                        <select class="form-control" name="content_type">
                                            <option value="Title" {{$content && $content->content_type == 'Title' ? 'selected="selected"' : ''}}>Page-Title</option>
                                            <option value="Meta-Description" {{$content && $content->content_type == 'Meta-Description' ? 'selected="selected"' : ''}}>Meta-Description</option>
                                            <option value="Sub-Header" {{$content && $content->content_type == 'Sub-Header' ? 'selected="selected"' : ''}}>Sub-Header</option>
                                            <option value="Page-About" {{$content && $content->content_type == 'Page-About' ? 'selected="selected"' : ''}}>Page-About</option>
                                            <option value="Header-About" {{$content && $content->content_type == 'Header-About' ? 'selected="selected"' : ''}}>Header-About</option>
                                            <option value="Footer-About" {{$content && $content->content_type == 'Footer-About' ? 'selected="selected"' : ''}}>Footer-About</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Search</button> <button name="create_new" type="submit" class="btn btn-success {{$content ? '' : 'disabled'}}" value="1">Create New</button>
                        </form>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        @if($content)
                        <form action="/seo" method="POST">
                            <input type="hidden" name="id" value="{{$content->id}}">
                            <input type="hidden" name="page_url" value="{{$content->page_url}}">
                            <input type="hidden" name="content_type" value="{{$content->content_type}}">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="control-label">Page URL:</label><br>
                                    <span>{{ $content->page_url }}</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label class="control-label">Content Type:</label><br>
                                    <span>{{$content->content_type}}</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label class="control-label">Content</label>
                                    <div class="controls">
                                        <textarea class="form-control" name="content" rows="5">{{ $content->content }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success">Save</button>
                        </form>
                        @else
                        <form action="/seo" method="POST">
                            <input type="hidden" name="id" value="0">
                            <div class="row">
                                <div class="col-md-12 form-group {{$errors->has('page_url') ? 'has-error' : ''}}">
                                    <label class="control-label">Page URL</label>
                                    <div class="controls">
                                        <input class="form-control" type="text" name="page_url" value="{{$searchUrl}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group {{$errors->has('content_type') ? 'has-error' : ''}}">
                                    <label class="control-label">Content Type</label>
                                    <div class="controls">
                                        <select class="form-control" name="content_type">
                                            <option value="Page-Title" {{$searchType == 'Page-Title' ? 'selected="selected"' : ''}}>Page-Title</option>
                                            <option value="Meta-Description" {{$searchType == 'Meta-Description' ? 'selected="selected"' : ''}}>Meta-Description</option>
                                            <option value="Sub-Header" {{$searchType == 'Sub-Header' ? 'selected="selected"' : ''}}>Sub-Header</option>
                                            <option value="Page-About" {{$searchType == 'Page-About' ? 'selected="selected"' : ''}}>Page-About</option>
                                            <option value="Header-About" {{$searchType == 'Header-About' ? 'selected="selected"' : ''}}>Header-About</option>
                                            <option value="Footer-About" {{$searchType == 'Footer-About' ? 'selected="selected"' : ''}}>Footer-About</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <label class="control-label">Content</label>
                                    <div class="controls">
                                        <textarea id="about" class="form-control" name="content" rows="5"></textarea>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-success">Save New</button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop