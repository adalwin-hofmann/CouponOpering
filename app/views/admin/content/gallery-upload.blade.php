@extends('admin.master.templates.master')
@section('sidebar')
    @include('admin.content.master.templates.sidebar', array())
@stop
@section('body')
<script>
    currentPage = 0;
    selectedAsset = 0;
    doneTypingInterval = 750;
    typingTimer = '';
    allowDrag = true;
</script>

<script type="text/ejs" id="template_subcat">
<% list(results, function(result){ %>
  <option value="<%= result.id %>"><%= result.name %></option>
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
                    <span>Sales Gallery Upload</span>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="grid-content overflow">
                <div class="row">
                    <div id="resultsGrid" class="col-md-12 box">
                        <div class="row padded">
                            <div class="col-md-4">
                                <select id="selCategory" class="form-control">
                                    <option value="">Category</option>
                                    @foreach($categories as $cat)
                                    <option value="{{$cat->id}}">{{$cat->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select id="selSubCategory" disabled="disabled" class="form-control">
                                    <option value="">Subcategory</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select id="selSubSubCategory" disabled="disabled" class="form-control">
                                    <option value="">Minor Category</option>
                                </select>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 5px;">
                            <div id="imageArea" class="col-md-12">
                                <div id="container" class="js-masonry">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="upload-window-fade" style="display:none;"></div>
</div>
@stop