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
                    <span>Videos</span>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="grid-content overflow">
                <div class="row margin-bottom-20">
                    <div class="col-sm-4">
                        <p><a href="https://www.youtube.com/watch?v=dBfQ3pQEofs" target="_blank">SaveOn.com Contesting From A-Z</a></p>
                        <iframe width="560" height="300" src="//www.youtube.com/embed/dBfQ3pQEofs" frameborder="0" allowfullscreen style="width:100%"></iframe>
                    </div>
                    <div class="col-sm-4">
                        <p><a href="http://youtu.be/Hgm8XA6KJvc" target="_blank">SaveOn.com Backoffice Reporting</a></p>
                        <iframe width="560" height="300" src="//www.youtube.com/embed/Hgm8XA6KJvc" frameborder="0" allowfullscreen style="width:100%"></iframe>
                    </div>
                    <div class="col-sm-4">
                        <p><a href="http://youtu.be/qXxKycCeVUY" target="_blank">What Makes a Great Merchant Page?</a></p>
                        <iframe width="560" height="300" src="//www.youtube.com/embed/qXxKycCeVUY" frameborder="0" allowfullscreen style="width:100%"></iframe>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <p><a href="https://www.youtube.com/watch?v=q-NVLGKfAag" target="_blank">Mike Training Video Clip</a></p>
                        <iframe width="560" height="300" src="https://www.youtube.com/embed/q-NVLGKfAag?rel=0" frameborder="0" allowfullscreen style="width:100%"></iframe>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid">
            <div class="grid-title">
                <div class="pull-left">
                    <span>Magazine Manager Sales Videos</span>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="grid-content overflow">
                <div class="row margin-bottom-20">
                    <div class="col-sm-4">
                        <p><a href="http://youtu.be/vcFJXHOoiYU" target="_blank">Magazine Manager 1 Sales I Training: Part 1 - All Markets</a></p>
                        <iframe width="560" height="300" src="//www.youtube.com/embed/vcFJXHOoiYU" frameborder="0" allowfullscreen style="width:100%"></iframe>
                    </div>
                    <div class="col-sm-4">
                        <p><a href="http://youtu.be/lEPz7n2t3jE" target="_blank">Magazine Manager 1 Sales I Training: Part 2 - Detroit</a></p>
                        <iframe width="560" height="300" src="//www.youtube.com/embed/lEPz7n2t3jE" frameborder="0" allowfullscreen style="width:100%"></iframe>
                    </div>
                    <div class="col-sm-4">
                        <p><a href="http://youtu.be/9zvdmeSJc5k" target="_blank">Magazine Manager 1 Sales I Training: Part 2 - Chicago</a></p>
                        <iframe width="560" height="300" src="//www.youtube.com/embed/9zvdmeSJc5k" frameborder="0" allowfullscreen style="width:100%"></iframe>
                    </div>
                </div>
                <div class="row margin-bottom-20">
                    <div class="col-sm-4">
                        <p><a href="http://youtu.be/xp4ACSDFpi0" target="_blank">Magazine Manager 1 Sales I Training: Part 2 - Minneapolis</a></p>
                        <iframe width="560" height="300" src="//www.youtube.com/embed/xp4ACSDFpi0" frameborder="0" allowfullscreen style="width:100%"></iframe>
                    </div>
                    <div class="col-sm-4">
                        <p><a href="http://youtu.be/JZUEpqArhZU" target="_blank">Magazine Manager 3 Sales Q&amp;A - Mobile Device Training</a></p>
                        <iframe width="560" height="300" src="//www.youtube.com/embed/JZUEpqArhZU" frameborder="0" allowfullscreen style="width:100%"></iframe>
                    </div>
                    <div class="col-sm-4">
                        <p><a href="https://youtu.be/wnkQnd_w_bY" target="_blank">Magazine Manager 4 Sales Q&amp;A - Refresher Training</a></p>
                        <iframe width="560" height="300" src="https://www.youtube.com/embed/wnkQnd_w_bY" frameborder="0" allowfullscreen style="width:100%"></iframe>
                    </div>
                </div>
            </div>
        </div>    
        
        <div class="grid">
            <div class="grid-title">
                <div class="pull-left">
                    <span>Magazine Manager Admin Videos</span>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="grid-content overflow">
                <div class="row margin-bottom-20">
                    <div class="col-sm-4">
                        <p><a href="http://youtu.be/IFZxzFvpm8w" target="_blank">Magazine Manager - Admin I Training</a></p>
                        <iframe width="560" height="300" src="//www.youtube.com/embed/IFZxzFvpm8w" frameborder="0" allowfullscreen style="width:100%"></iframe>
                    </div>
                    <div class="col-sm-4">
                        <p><a href="http://youtu.be/mIIRN_GN3Gw" target="_blank">Magazine Manager - Admin II Training</a></p>
                        <iframe width="560" height="300" src="//www.youtube.com/embed/mIIRN_GN3Gw" frameborder="0" allowfullscreen style="width:100%"></iframe>
                    </div>
                    <div class="col-sm-4">
                        <p><a href="http://youtu.be/BvuTx_0un5Q" target="_blank">Magazine Manager - Pre Sales I Training with City Managers</a></p>
                        <iframe width="560" height="300" src="//www.youtube.com/embed/BvuTx_0un5Q" frameborder="0" allowfullscreen style="width:100%"></iframe>
                    </div>
                    <!--<div class="col-sm-4">
                        <p><a href="http://youtu.be/mIIRN_GN3Gw" target="_blank">Magazine Manager - Production Initial Review</a></p>
                        <iframe width="560" height="300" src="//www.youtube.com/embed/mIIRN_GN3Gw" frameborder="0" allowfullscreen style="width:100%"></iframe>
                    </div>-->
                </div>
                <div class="row margin-bottom-20">
                    <div class="col-sm-4">
                        <p><a href="http://youtu.be/ncmfEm403Zc" target="_blank">Magazine Manager - Admin Q &amp; A #1</a></p>
                        <iframe width="560" height="300" src="//www.youtube.com/embed/ncmfEm403Zc" frameborder="0" allowfullscreen style="width:100%"></iframe>
                    </div>
                    <div class="col-sm-4">
                        <p><a href="http://youtu.be/_5tpTOHTQ9s" target="_blank">Magazine Manager - Admin Q &amp; A #2</a></p>
                        <iframe width="560" height="300" src="//www.youtube.com/embed/_5tpTOHTQ9s" frameborder="0" allowfullscreen style="width:100%"></iframe>
                    </div>
                    <div class="col-sm-4">
                        <p><a href="http://youtu.be/jzsBL993gD0" target="_blank">Magazine Manager - Pre production Training #1</a></p>
                        <iframe width="560" height="300" src="//www.youtube.com/embed/jzsBL993gD0" frameborder="0" allowfullscreen style="width:100%"></iframe>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <p><a href="http://youtu.be/8MMh8X-1RAk" target="_blank">Magazine Manager - Pre production Training #2</a></p>
                        <iframe width="560" height="300" src="//www.youtube.com/embed/8MMh8X-1RAk" frameborder="0" allowfullscreen style="width:100%"></iframe>
                    </div>
                    <div class="col-sm-4">
                        <p><a href="http://youtu.be/V0Ey8ElVZ2k" target="_blank">Magazine Manager - Order Entry Presentation</a></p>
                        <iframe width="560" height="300" src="//www.youtube.com/embed/V0Ey8ElVZ2k" frameborder="0" allowfullscreen style="width:100%"></iframe>
                    </div>
                    <div class="col-sm-4">
                        <p><a href="http://youtu.be/W8u9OyyzT5c" target="_blank">Magazine Manager - Accounting Q &amp; A Training</a></p>
                        <iframe width="560" height="300" src="//www.youtube.com/embed/W8u9OyyzT5c" frameborder="0" allowfullscreen style="width:100%"></iframe>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid">
            <div class="grid-title">
                <div class="pull-left">
                    <span>Home Improvement Advertisements</span>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="grid-content overflow">
                <div class="row margin-bottom-20">
                    <div class="col-sm-4">
                        <p><a href="http://youtu.be/j-jTT3NdeYQ" target="_blank">SaveOn Home Improvement TV Spot 11-5-2014 1</a></p>
                        <iframe width="560" height="300" src="//www.youtube.com/embed/j-jTT3NdeYQ" frameborder="0" allowfullscreen style="width:100%"></iframe>
                    </div>
                    <div class="col-sm-4">
                        <p><a href="http://youtu.be/dbM7S1LMQ4M" target="_blank">SaveOn Home Improvement TV Spot 11-5-2014 2</a></p>
                        <iframe width="560" height="300" src="//www.youtube.com/embed/dbM7S1LMQ4M" frameborder="0" allowfullscreen style="width:100%"></iframe>
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