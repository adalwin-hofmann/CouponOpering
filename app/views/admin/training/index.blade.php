@extends('admin.master.templates.master')
@section('sidebar')
@if($user_mode == 'sales')
	@include('admin.sales.master.templates.sidebar', array())
@elseif($user_mode == 'content')
    @include('admin.content.master.templates.sidebar', array())
@else
    @include('admin.master.templates.default-sidebar', array())
@endif
@stop

@section('body')

<script>
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
		<!--page title-->
        <div class="pagetitle">
            <h1>Training</h1> 
            <div class="clearfix"></div>
        </div>
         <!--page title end-->
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

		<!-- info-box -->
        <div class="grid margin-top-10">
            <div class="grid-title">
                <div class="pull-left">
                    <span>{{isset($activePage) ? $activePage->name : 'Welcome'}}</span>
                    <div class="clearfix"></div>
                </div>
                <div class="pull-right">
                    <span class="print-button"><button class="btn btn-success btn-sm">Print</button></span>
                    @if($isAdmin)
                	<span class="edit-button"><button class="btn btn-primary btn-sm">Edit</button></span>
                    @endif
                	<span class="cancel-button" style="display:none;"><button class="btn btn-danger btn-sm">Cancel</button></span>
                	<span class="save-button" style="display:none;"><button class="btn btn-success btn-sm">Save</button></span>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="grid-content overflow print-area" style="min-height: 400px">
            	<div class="training-editable" style="display:none">
            		<div class="row margin-bottom-10 training-controls-top-row">
            			<div class="col-sm-4">
            				<input id="trainingPageTitle" type="text" class="form-control page-title" name="pageTitle" placeholder="Title">
            			</div>
                        <div class="col-sm-2">
                            <select id="trainingPageType" class="form-control page-type" name="pageType">
                                <option value="">Type</option>
                                <option value="content">Content</option>
                                <option value="static">Static</option>
                            </select>
                        </div>
                        <div class="col-sm-1">
                            <input class="page-order form-control" name="trainingPageOrder" id="trainingPageOrder" type="text" placeholder="Order">
                        </div>
            			<div class="col-sm-3">
            				<select id="trainingPageSection" class="form-control page-section" name="pageSection">
            					<option value="">Choose a section</option>
                                @foreach($availableSections as $available)
            					<option value="{{$available->id}}">{{$available->name}}</option>
                                @endforeach
            				</select>
            			</div>
            			<div class="col-sm-2">
            				<button class="btn btn-default" id="editSectionsBtn">Edit Sections</button>
            			</div>
            		</div>
                    <div class="training-textbox-holder">
                        <textarea id="training" name="training" class="fill-up" rows="10"></textarea>
                    </div>
                    <input id="trainingUrl" type="text" class="form-control page-static-url" name="pageStaticUrl" placeholder="URL" style="display:none;">
            	</div>
                <!--<div class="print-only">
                    <p style="text-align: center; margin-bottom: 20px"><img alt="SaveOn" src="http://www.saveon.com/img/logo.png" style="height:100px; width:280px"></p>
                </div>-->
            	<div class="training-content">
                    @if(isset($activePage))
                        {{$activePage->content}}
                    @else
            		<div class="header h2">Welcome To Training!</div>
                    @endif
            	</div>
            </div>
            <div class="grid-title">
                <div class="pull-left">
                    <span id="editPageMessages"></span>
                </div>
                <div class="pull-right">
                	<span class="remove-button" style="display:none;"><button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#removeModal">Remove</button></span>
                	<span class="add-button" style="display:none;"><button class="btn btn-primary btn-sm">Add</button></span>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>


        <!-- Sections Modal -->
        <div id="sectionsModal" class="modal fade">
		    <div class="modal-dialog" style="width: 80%;">
		        <div class="modal-content">
		            <div class="modal-header">
		            	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                		<h3>Edit Sections</h3>
                	</div>
            		<div class="modal-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Section Name</th>
                                    <th>Sales</th>
                                    <th>Content</th>
                                    <th>Digital</th>
                                    <th>Production</th>
                                    <th>Order</th>
                                    <th>Parent</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <td><input type="text" class="form-control input-sm section-name" name="sectionName" placeholder="Enter Name"></td>
                                    <td><input class="section-sales-type" name="sectionType" type="checkbox" value="sales"></td>
                                    <td><input class="section-content-type" name="sectionType" type="checkbox" value="content"></td>
                                    <td><input class="section-digital-type" name="sectionType" type="checkbox" value="digital"></td>
                                    <td><input class="section-production-type" name="sectionType" type="checkbox" value="production"></td>
                                    <td><input class="section-order form-control input-sm" name="sectionOrder" type="text" placeholder="Order" size="12"></td>
                                    <td>
                                        <select id="sectionDropdown" class="section-parent form-control" name="sectionParent">

                                        </select>
                                    </td>
                                    <td colspan="2"><button class="btn btn-success btn-xs margin-top-5 save-section-button" data-section_id="0">Save New</button></td>
                                </tr>
                            </thead>
                            <tbody id="sectionArea">

                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="8">
                                        <button class="btn btn-primary pull-left prev-page paginate" disabled="disabled">Prev</button>
                                        <button class="btn btn-primary pull-right next-page paginate" disabled="disabled">Next</button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
            		</div>
                    <div class="modal-footer">
                        <span id="editSectionMessages" style="display:none;"></span>
                        <button class="btn btn-primary pull-right btn-apply">Apply</button>
                    </div>
            	</div>
            </div>
        </div>

        <!-- Remove Modal -->
        <div id="removeModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h3>Remove</h3>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to remove this page?</p>
                        <div class="row">
                            <div class="col-xs-6 text-center">
                                <button class="btn btn-danger btn-lg btn-remove-confirm">Yes</button>
                            </div>
                            <div class="col-xs-6 text-center">
                                <button class="btn btn-default btn-lg btn-remove-deny" data-dismiss="modal">No</button>
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

        <!-- Remove Sections Modal -->
        <div id="removeSectionModal" class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h3>Remove Section</h3>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to remove this section?</p>
                        <div class="row">
                            <div class="col-xs-6 text-center">
                                <button class="btn btn-danger btn-lg btn-remove-section-confirm">Yes</button>
                            </div>
                            <div class="col-xs-6 text-center">
                                <button class="btn btn-default btn-lg btn-remove-section-deny" data-dismiss="modal">No</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

	</div>
</div>
@stop