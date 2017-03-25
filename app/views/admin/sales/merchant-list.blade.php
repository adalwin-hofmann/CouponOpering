@extends('admin.master.templates.master')
@section('sidebar')
    @include('admin.sales.master.templates.sidebar', array())
@stop
@section('body')
<script>
    currentPage = 0;
    order = '{{ $order }}';
    direction = '{{ $direction }}';
</script>
<script type="text/ejs" id="template_subcategory">
<% list(subcategories, function(subcategory)
{ %>
    <option value="<%= subcategory.id %>"><%= subcategory.name %></option>
<% }) %>
</script>
    <!--BEGIN MAIN CONTENT-->
    <div id="main" role="main">
      <div class="block">
      <div class="clearfix"></div>
        
         <!--page title-->
         <div class="pagetitle">
            <h1>Merchant Reports</h1> 
            <div class="clearfix"></div>
         </div>
         <!--page title end-->
         <div class="clearfix"></div>

          <!-- New Stuff -->
          <div class="row margin-bottom-10">
            <div id="filterDiv" class="col-xs-3 form-group" style="{{ Input::get('options') == 'group-cat' ? 'display:none;' : ''}}">
              <label>Merchant Name</label>
              <input id="merchantName" type="text" class="form-control fill-up" value="{{ Input::get('filter') }}">
            </div>
            <div class="col-xs-3">
              <label>Date Range</label>
              <select id="selRange" name="choose" class="form-control">
                  <option value="last-3-months">Last 3 Months</option>
                  <option value="today" {{Input::get('date-range') == 'today' ? 'selected="selected"' : ''}}>Today</option>
                  <option value="yesterday" {{Input::get('date-range') == 'yesterday' ? 'selected="selected"' : ''}}>Yesterday</option>
                  <option value="this-week" {{Input::get('date-range') == 'this-week' ? 'selected="selected"' : ''}}>This Week</option>
                  <option value="last-7" {{Input::get('date-range') == 'last-7' ? 'selected="selected"' : ''}}>Last 7 Days</option>
                  <option value="this-month" {{Input::get('date-range') == 'this-month' ? 'selected="selected"' : ''}}>This Month</option>
                  <option value="last-month" {{Input::get('date-range') == 'last-month' ? 'selected="selected"' : ''}}>Last Month</option>
                  <option value="custom" {{Input::get('date-range') == 'custom' ? 'selected="selected"' : ''}}>Custom Range</option>
              </select>
            </div>
            <div class="col-xs-3 form-group">
              <label>Category</label>
              <select id="selCategory" class="form-control">
                  <option value="">-- Choose --</option>
                  @foreach($categories['objects'] as $category)
                  <option value="{{ $category->id }}" {{Input::get('category') == $category->id ? 'selected="selected"' : ''}}>{{ $category->name }}</option>
                  @endforeach
              </select>
            </div>
            <div id="subcatDiv" class="col-xs-3 form-group" style="{{ Input::get('options') == 'group-cat' ? 'display:none;' : ''}}">
              <label>Subcategory</label>
              <select id="selSubcategory" class="form-control" {{ Input::has('category') ? '' : 'disabled="disabled"' }}>
                  <option value="">-- Choose --</option>
                  @foreach($subcategories['objects'] as $subcategory)
                  <option value="{{ $subcategory->id }}" {{Input::get('subcategory') == $subcategory->id ? 'selected="selected"' : ''}}>{{ $subcategory->name }}</option>
                  @endforeach
              </select>
            </div>
          </div>

          <div class="row margin-bottom-10">
            <div class="col-xs-3 form-group">
              <label>Sales Rep</label>
              <select id="selRep" name="" class="form-control">
                  <option value="">-- Choose --</option>
                  @foreach($reps['objects'] as $rep)
                  <option value="{{ $rep->id }}" {{Input::get('rep') == $rep->id ? 'selected="selected"' : ''}}>{{ $rep->name }}</option>
                  @endforeach
              </select>
            </div>
            <div class="col-xs-3 form-group">
              <label>Market</label>
              <select id="selMarket" name="" class="form-control">
                  <option value="">-- Choose --</option>
                  <option value="mi" {{Input::get('market') == 'mi' ? 'selected="selected"' : ''}}>Detroit</option>
                  <option value="il" {{Input::get('market') == 'il' ? 'selected="selected"' : ''}}>Chicago</option>
                  <option value="mn" {{Input::get('market') == 'mn' ? 'selected="selected"' : ''}}>Minneapolis</option>
              </select>
            </div>
            <div class="col-xs-3 form-group">
              <label>Options</label>
              <select id="selOptions" name="" class="form-control">
                  <option value="" {{Input::get('options') == '' ? 'selected="selected"' : ''}}>-- Choose --</option>
                  <option value="group-cat" {{Input::get('options') == 'group-cat' ? 'selected="selected"' : ''}}>Group Categories</option>
                  <!-- Will Mentioned Adding Detroit Trading to view all of their traffic/data -->
              </select>
            </div>
          </div>

          <div class="row margin-bottom-10">
            <div class="col-xs-3 form-group">
                <label>Order By</label>
                <select id="selOrder" class="form-control">
                    <option value="name" {{$order == 'name' ? 'selected="selected"' : ''}}>Name</option>
                    <option value="views" {{$order == 'views' ? 'selected="selected"' : ''}}>Views</option>
                    <!-- <option value="prints" {{$order == 'prints' ? 'selected="selected"' : ''}}>Prints</option> -->
                    <option value="applications" {{$order == 'applications' ? 'selected="selected"' : ''}}>Contest Apps</option>
                    <!-- <option value="offers" {{$order == 'offers' ? 'selected="selected"' : ''}}>Offers</option> -->
                    <option value="quotes" {{$order == 'quotes' ? 'selected="selected"' : ''}}>Quotes</option>
                </select>
            </div>
            <div class="col-xs-3 form-group">
                <label>Order Direction</label>
                <select id="selDirection" class="form-control">
                    <option value="asc" {{$direction == 'asc' ? 'selected="selected"' : ''}}>Asc.</option>
                    <option value="desc" {{$direction == 'desc' ? 'selected="selected"' : ''}}>Desc.</option>
                </select>
            </div>
            <div class="col-xs-3 form-group" style="{{Input::get('date-range') == 'custom' ? '' : 'display:none;'}}">
                <label>Custom Start</label>
                <input id="customStart" type="text" value="{{$customStart}}" class="form-control">
            </div>
            <div class="col-xs-3 form-group" style="{{Input::get('date-range') == 'custom' ? '' : 'display:none;'}}">
                <label>Custom End</label>
                <input id="customEnd" type="text" value="{{$customEnd}}" class="form-control">
            </div>
          </div>

          <div class="row margin-bottom-10">
            <div class="col-xs-3">
                <button id="btnSearch" type="button" class="btn btn-default btn-block">Search</button>
            </div>
          </div>
            <span><a href="/merchant-list-pdf?date-range={{ $dateRange }}{{$dateRange == 'custom' ? '&custom-start='.$customStart.'&custom-end='.$customEnd : ''}}&page={{ $page }}&limit={{ $limit }}&order={{ $order }}&direction={{ $direction }}&options={{ $options }}&filter={{ $filter }}&category={{ $cat }}&subcategory={{ $subcat }}&rep={{ $salesRep }}&market={{ $market }}">Download PDF</a></span>
            <div class="grid">
              <div class="grid-title">
                <div class="pull-left">
                  <span>Details</span>
                </div>
              </div>
              <div class="grid-content overflow">
                <table class="table table-striped">
                  <thead>
                    <tr>
                      <th><span class="sort-link" data-order="name" style="cursor: pointer;text-decoration:underline;">{{ $rowTitle }} @if($order == 'name')<i class="{{ $direction == 'asc' ? 'icon-arrow-up' : 'icon-arrow-down' }}"></i>@endif</a></th>
                      <th><span class="sort-link" data-order="views" style="cursor: pointer;text-decoration:underline;">Views @if($order == 'views')<i class="{{ $direction == 'asc' ? 'icon-arrow-up' : 'icon-arrow-down' }}"></i>@endif</a></th>
                      <th><span class="" data-order="prints" style="">Prints @if($order == 'prints')<i class="{{ $direction == 'asc' ? 'icon-arrow-up' : 'icon-arrow-down' }}"></i>@endif</a></th>
                      <th><span class="sort-link" data-order="applications" style="cursor: pointer;text-decoration:underline;">Contest Apps @if($order == 'applications')<i class="{{ $direction == 'asc' ? 'icon-arrow-up' : 'icon-arrow-down' }}"></i>@endif</a></th>
                      <th><span class="" data-order="offers" style="">Current Offers @if($order == 'offers')<i class="{{ $direction == 'asc' ? 'icon-arrow-up' : 'icon-arrow-down' }}"></i>@endif</a></th>
                      <th><span class="sort-link" data-order="quotes" style="cursor: pointer;text-decoration:underline;">Leads @if($order == 'quotes')<i class="{{ $direction == 'asc' ? 'icon-arrow-up' : 'icon-arrow-down' }}"></i>@endif</a></th>
                    </tr>
                  </thead>
                  <tbody id="merchants">
                    @foreach($objects['objects'] as $object)
                    @if(!Input::has('options'))
                    <tr>
                      <td><a href="/merchant-report?franchise={{$object->franchise_id}}{{Input::has('date-range') ? '&date-range='.Input::get('date-range') : ''}}{{$filters}}">{{$object->merchant_display}}</a></td>
                      <td>{{ $object->views }}</td>
                      <td>{{ $object->prints }}</td>
                      <td>{{ $object->apps ? $object->apps : 0 }}</td>
                      <td>{{ $object->offers }}</td>
                      <td>{{ $object->quotes ? $object->quotes : 0 }}</td>
                    </tr>
                    @elseif(Input::get('options') == 'group-cat')
                    <tr>
                      <td>{{ Input::has('category') ? $object->subcategory_name : $object->category_name }}</td>
                      <td>{{ $object->views }}</td>
                      <td>{{ $object->prints }}</td>
                      <td>{{ $object->apps ? $object->apps : 0 }}</td>
                      <td>{{ $object->offers }}</td>
                      <td>N/A</td>
                    </tr>
                    @endif
                    @endforeach
                  </tbody>
                  <tfoot>
                    <tr>
                        <td>
                            <ul class="pagination">
                              @if($page != 0)<li><button class="btn btn-link" data-page="{{$page - 1}}">Prev</button></li>@endif
                              @if($page != $lastPage)<li><button class="btn btn-link" data-page="{{$page + 1}}">Next</button></li>@endif
                            </ul>
                        </td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>

        
           
           <!--BEGIN FOOTER-->
           <div class="footer">
              <div class="left">Copyright &copy; 2013</div>
              <div class="right"><!--<a href="#">Buy Template</a>--></div>
              <div class="clearfix"></div>
           </div>
           <!--BEGIN FOOTER END-->
          
      <div class="clearfix"></div> 
      </div><!--end .block-->
    </div>
    <!--MAIN CONTENT END-->
    <div class="loading-window-fade" style="display:none;"></div>
    <div id="printSection"></div>

@stop