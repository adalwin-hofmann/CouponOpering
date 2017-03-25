@extends('admin.master.templates.master')
@section('sidebar')
    @include('admin.sales.master.templates.sidebar', array())
@stop
@section('body')

<?php 
    $logged_user = Auth::User();
    $userRepo = \App::make('UserRepositoryInterface');
    $features = App::make('FeatureRepositoryInterface');
    $new_training = $features->findByName('new_training_toggle');
    $new_training = empty($new_training) ? 0 : $new_training->value;
?>

<!--BEGIN MAIN CONTENT-->
<script>
    frameHeights = new Object();
    currentPage = 0;
    activePage = {{isset($activePage) ? $activePage->id : 0}};
    selectedPage = {{isset($activePage) ? $activePage->id : 0}};
    staticUrl = '';
</script>

<script type="text/ejs" id="template_section">
<% list(sections, function(section){ %>
    <tr>
        <td><input type="text" class="form-control input-sm section-name" name="sectionName" placeholder="Enter Name" value="<%= section.name %>"></td>
        <td><input class="section-sales-type" name="sectionType" type="checkbox" value="sales" <%= section.roles.indexOf('sales') != -1 ? 'checked="checked"' : '' %>></td>
        <td><input class="section-content-type" name="sectionType" type="checkbox" value="content" <%= section.roles.indexOf('content') != -1 ? 'checked="checked"' : '' %>></td>
        <td><input class="section-digital-type" name="sectionType" type="checkbox" value="digital" <%= section.roles.indexOf('digital') != -1 ? 'checked="checked"' : '' %>></td>
        <td><input class="section-production-type" name="sectionType" type="checkbox" value="production" <%= section.roles.indexOf('production') != -1 ? 'checked="checked"' : '' %>></td>
        <td><input class="section-order form-control input-sm" name="sectionOrder" type="text" placeholder="Order" size="12" value="<%= section.order %>"></td>
        <td>
            <select class="section-parent form-control" name="sectionParent">
                <option value="0" <%= section.parent_id == 0 ? 'selected="selected"' : '' %>>None</option>
            <% list(sections, function(section_section){ %>
                <option value="<%= section_section.id %>" <%= section.parent_id == section_section.id ? 'selected="selected"' : '' %>><%= section_section.name %></option>
            <% }) %>
            </select>
        </td>
        <td><button class="btn btn-success btn-xs margin-top-5 save-section-button" data-section_id="<%= section.id %>">Save</button></td>
        <td><button class="btn btn-danger btn-xs margin-top-5 delete-section-button" data-section_id="<%= section.id %>">Delete</button></td>
    </tr>
<% }); %>
</script>

<script type="text/ejs" id="template_section_option">
<% list(sections, function(section){ %>
    <option value="<%= section.id %>"><%= section.name %></option>
<% }) %>
</script>

<script type="text/ejs" id="template_section_child">
<% list(children, function(child){ %>
    <% if(child.type == 'page'){ %>
    <li><a @if($isAdmin)class="<%= child.object.type == 'static' ? 'static-page-link' : '' %>"@endif data-page_id="<%= child.object.id %>" <%= child.object.type == 'static' ? 'target="_blank"' : '' %> href="<%= child.object.type == 'static' ? child.object.url : (child.object.type == 'content' ? '/onlinetraining/content-page/'+parent_slug+'/'+(parent_slug == child.object.section_slug ? '' : child.object.section_slug+'/')+child.object.slug : child.object.url) %>"><%= child.object.name %></a></li>
    <% }else{ %>
        <form>
    <li class="dropdown-submenu">
        <a class="content-subdropdown" tabindex="-1" data-parent_id="<%= child.object.id %>" data-parent_slug="<%= child.object.slug %>" href="#"><%= child.object.name %></a>
        <ul id="parent-<%= child.object.id %>" class="dropdown-menu">
        </ul>
    </li>
    </form>
    <% } %>
<% }) %>
</script>

<div id="main" role="main">
    <div class="block">
        <div class="clearfix"></div>
        
         <!--page title-->
        <div class="pagetitle">
            <h1>Training</h1> 
            <div class="clearfix"></div>
        </div>
         <!--page title end-->
        <div class="clearfix"></div>
         
        @if($new_training)
        <ul class="nav nav-tabs margin-top-10 training-nav">
            @if(!isset($sections['Home']))
            <li class="@if($active)active@endif"><a href="/onlinetraining/content-page/Home/home">Home</a></li>
            @endif
            @foreach($sections as $section)
            @if(count($section['pages']))
            <li class="{{count($section['pages']) > 1 ? 'dropdown' : ''}} {{$section['active'] == 1 ? 'active' : ''}}">
                @if(count($section['pages']) > 1)
                <a class="dropdown-toggle content-dropdown" data-toggle="dropdown" data-parent_id="{{$section['pages'][0]->section_id}}" data-parent_slug="{{$section['pages'][0]->section_slug}}" href="#" role="button">{{$section['pages'][0]->section_name}} <span class="caret"></span></a>
                <ul id="parent-{{ $section['pages'][0]->section_id }}" class="dropdown-menu">

                </ul>
                @else
                @foreach($section['pages'] as $page)
                <a href="{{$page->type == 'content' ? '/onlinetraining/content-page/'.$page->section_name.'/'.$page->slug : $page->url}}">{{$page->name}}</a>
                @endforeach
                @endif
            </li>
            @endif
            @endforeach
        </ul>
        @endif

         <!-- info-box -->
        <div class="grid">
            <div class="grid-title">
                <div class="pull-left">
                    <span>Quick Reference Guides</span>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="grid-content overflow">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box">
                            <div class="padded">
                                <div class="padded">
                                    <p><a href="http://s3.amazonaws.com/saveoneverything_assets/training/pdfs/contest-training-1.pdf" target="_blank">SaveOn.com Contesting From A-Z Quick Reference Guide</a></p>
                                    <p><a href="http://s3.amazonaws.com/saveoneverything_assets/training/pdfs/backoffice-reporting-quick-reference-guide.pdf" target="_blank">SaveOn.com Backoffice Reporting Quick Reference Guide</a></p>
                                    <p><a href="http://s3.amazonaws.com/saveoneverything_assets/training/pdfs/what-makes-a-great-merchant-page-training.pdf" target="_blank">What Makes A Great Merchant Page Guide</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="staticModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h3>View or Edit?</h3>
                    </div>
                    <div class="modal-body">
                        <p>Do you want to view or edit?</p>
                        <div class="row">
                            <div class="col-xs-6 text-center">
                                <button class="btn btn-success btn-lg btn-static-view">View</button>
                            </div>
                            <div class="col-xs-6 text-center">
                                <button class="btn btn-default btn-lg btn-static-edit">Edit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
@stop