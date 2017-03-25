@extends('admin.master.templates.master')
@section('sidebar')
    @include('admin.sales.master.templates.sidebar', array())
@stop

@section('body')
<div id="main" role="main">
<div class="block">

<div class="pagetitle">
    <h1>Contest Report</h1>
    <div class="clearfix"></div>
</div>
<div class="clearfix"></div>

<script>
    query_showexpired = "{{($variables)?$variables['show_expired']:'0'}}";
    query_orderbyname = "{{($variables)?$variables['orderby']['name']:'expires_at'}}";
    query_orderbydir = "{{($variables)?$variables['orderby']['dir']:'desc'}}";
</script>

<div class="">
    <form class="" method="get">
        @include('admin.sales.contests.search')
    </form>
</div>
<div class="clearfix"></div>


<div class="grid">
    <div class="grid-title">
      <div class="pull-left">
        <span>Contests</span>
      </div>
    </div>

    <div class="grid-content overflow">
        @include('admin.sales.contests.results')
    </div>

    <div class="pagination pagination-centered center-block">
        <table class="table table-striped">
            <ul>
                {{ $links }}
            </ul>
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

@stop

<!-- Include this page's custom javascript at the end -->
@section('code')
    @parent
    <script type='text/ejs' id='location'>
    <% list(locations, function(location){ %>
    <tr>
    <td><a class="location-link" data-location_id="<%= location.id %>"><%= location.name %></a></td>
    <td><%= location.views %></td>
    <td><%= location.prints %></td>
    </tr>
    <% }) %>
    </script>

    <script type="text/ejs" id="no_location">
    <tr>
    <td colspan="3">No location data available for the Old Site.</td>
    </tr>
    </script>

    <script type='text/ejs' id='offer'>
    <% list(offers, function(offer){ %>
    <tr>
    <td><%= offer.name %></td>
    <td><%= offer.type == 'contest' ? 'n/a' : (archive == 'no' ? offer.views : 'n/a') %></td>
    <td><%= offer.type == 'contest' ? 'n/a' : offer.prints %></td>
    <td><%= offer.type == 'contest' ? offer.signups : 'n/a' %></td>
    <td><%= offer.expires %></td>
    </tr>
    <% }) %>
    </script>

    <script type='text/ejs' id='tableTitle'>
    <h4>Offer Statistics for Location: <span><%= location %></span></h4>
    </script>

    <script>
    archive = 'no';
    </script>

    <script type="text/javascript" src="/js/jscode/admin.sales.contests.js"></script>
@stop

