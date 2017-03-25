@extends('admin.master.templates.master')
@section('sidebar')
    @include('admin.sales.master.templates.sidebar', array())
@stop
@section('body')
<script type='text/ejs' id='image'>
    <% list(images, function(image){ if(image.id){%>
    <div class="item">
      <a class="image-link" title="<%= image.name %>" data-img_id="<%= image.id %>"  rel="gallery" href="#galModal" role="button" data-toggle="modal">
        <img src="<%= image.path %>">
      </a>
    </div>
<% }}) %>
</script>

<script type="text/ejs" id="template_subcat">
<% list(results, function(result){ %>
  <option value="<%= result.id %>"><%= result.name %></option>
<% }) %>
</script>

<script>
  allow_upload = false;
  currentPage = 0;
</script>
    <!--BEGIN MAIN CONTENT-->
    <div id="main" role="main">
      <div class="block">
      <div class="clearfix"></div>
        
         <!--page title-->
         <div class="pagetitle">
            <h1>Gallery</h1> 
            <div class="clearfix"></div>
         </div>
         <!--page title end-->
         <div class="clearfix"></div>

          <div class="grid">
            <div class="grid-title">
              <div class="pull-left">
                <span>Find an Image</span>

              </div>
            </div>
            <div class="grid-content">
              <!-- Search begin -->
               <div class="row">
                <div class="col-md-12">
                  <div class="form-search">
                    <div class="row">
                      <div class="col-xs-9 col-lg-6 form-group">
                        <label>Search</label>
                        <div class="input-group">
                          <input id="tags" placeholder="Find an image..." type="text" class="form-control input-medium search-query" autocomplete="off">
                          <span class="input-group-btn">
                            <button id="imgSearch" type="button" class="btn">Search</button>
                          </span>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <label>Refine Search</label>
                      <div class="row">
                        <div class="col-xs-4">
                          <select class="form-control" id="selCategory">
                            <option value="">Category</option>
                            @foreach($categories as $cat)
                            <option value="{{$cat->id}}">{{$cat->name}}</option>
                            @endforeach
                          </select>
                        </div>
                        <div class="col-xs-4">
                          <select class="form-control" id="selSubCategory" disabled="disabled">
                            <option value="">Subcategory</option>
                          </select>
                        </div>
                        <div class="col-xs-4">
                          <select class="form-control" id="selSubSubCategory" disabled="disabled">
                            <option value="">Minor Category</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="clearfix"></div>
               <!-- Search end -->
            </div>
          </div>
        </div>
        <div class="clearfix"></div>
        <div class="masonry-content">
          <div id="container" class="js-masonry">
            
          </div>
        </div>
        <div class="clearfix"></div>
        <div class="pagination" style="margin: 0px;">
          <ul class="pagination" id="paginationBottom">
            <li><a id="prev" href="#">&lsaquo; Prev</a></li>
            <li><span id="lblCurrentPage" style="color: #0088CC;"></span></li>
            <li><a id="next" href="#">Next &rsaquo;</a></li>
          </ul>
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

     <!-- Modal -->
      <div id="galModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="galModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
          <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">Close ×</button>
          <h3 class="modal-title" id="galModalLabel">IMAGE NAME</h3>
          </div>
          <div class="modal-body">
          <p><img id="galImage" src="http://placehold.it/500x400"></p>
          <!-- <div class="well">
            <p>Tags:</p>
            <a href="#">Pizza</a>, <a href="#">Food</a>, <a href="#">Restaurants</a>, <a href="#">Yummy</a>,
          </div> -->
          </div>
          <div class="modal-footer">
            <!-- <button class="btn btn-default">Previous</button>
            <button class="btn btn-default">Next</button>
            <button class="btn btn-primary">Save Image</button> -->
          </div>
          </div>
        </div>
      </div>

@stop