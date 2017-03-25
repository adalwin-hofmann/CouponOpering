@extends('admin.master.templates.master')
@section('sidebar')
    @include('admin.content.master.templates.sidebar', array())
@stop
@section('body')

<script>
    currentPage = 0;
    selectedFranchise = "{{ $franchise_id }}";
    selectedMerchant = "{{ $merchant_id }}";
    img_path = "";
</script>
    <!--BEGIN MAIN CONTENT-->
    <div id="main" role="main">
      <div class="block">
      <div class="clearfix"></div>
        
         <!--page title-->
         <div class="pagetitle">
            <h1>Dashboard</h1> 
            <div class="clearfix"></div>
         </div>
         <div class="clearfix"></div>
         <!--page title end-->
         
         <!-- info-box -->
         <div class="grid">
            <div class="grid-title"></div>
            <ul id="aboutTab" class="tabs-nav">
                <li class="active">
                    <a data-toggle="tab" href="#default">Default</a>
                </li>
                @if(!$national_prospect)
                <li>
                    <a data-toggle="tab" href="#locations">Location Specific</a>
                </li>
                @endif
            </ul>
            <div class="clearfix"></div>
            <div class="grid-content overflow">
                <div class="tab-content">
                    <div id="default" class="tab-pane active in">
                        <form accept-charset="UTF-8" action="/save-about" method="POST" onsubmit="return true;">
                            <input name="franchise_id" type="hidden" value="{{$franchise_id}}">
                            <div class="row">
                                <div class="col-md-8">
                                    <label>About Us</label>
                                    <textarea id="about" name="about" class="fill-up"; rows="10">{{$about}}</textarea>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Page Title</label>
                                        <input id="page_title" name="page_title" class="form-control fill-up" type="text" value="{{$page_title}}">
                                    </div>
                                    <div class="form-group">
                                        <label>Keywords</label>
                                        <input id="keywords" name="keywords" class="form-control fill-up" type="text" value="{{$keywords}}">
                                    </div>
                                    <div class="form-group">
                                        <label>Meta Description</label>
                                        <textarea id="meta_description" name="meta_description" class="form-control fill-up" rows="6">{{$meta_description}}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Directions Subheading</label>
                                        <textarea id="subheading" name="subheading" class="form-control fill-up" rows="6">{{$subheading}}</textarea>
                                    </div>
                                    <div id="divDynamic" style="{{$national_prospect ? '' : 'display:none;'}}">
                                        <legend>Dynamic Text</legend>
                                        <div class="row">
                                            <button type="button" class="col-md-4 btn dynamic property-top" data-text="[merchant]">Merchant</button>
                                            <button type="button" class="col-md-4 btn dynamic property-top" data-text="[address]">Address</button>
                                            <button type="button" class="col-md-4 btn dynamic property-top" data-text="[city]">City</button>
                                        </div>
                                        <div class="row">
                                            <button type="button" class="col-md-4 btn dynamic property-bottom" data-text="[state]">State</button>
                                            <button type="button" class="col-md-4 btn dynamic property-bottom" data-text="[phone]">Phone</button>
                                            <button type="button" class="col-md-4 btn dynamic property-bottom" data-text="[website]">Website</button>
                                        </div>
                                        <div class="row vpadded">
                                            <button id="btnSynonym" type="button" class="col-md-6 btn dynamic" data-text="">Synonym List</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="divider"><hr></div>

                            <div class="" style="padding-bottom: 0px;">
                                <div class="pull-left">
                                    <a href="/banner?viewing={{$franchise_id}}" class="btn btn-primary"><i class="icon-hand-left"></i>  Back</a>
                                </div>
                                <div class="pull-right">
                                    <button type="submit" class="btn btn-success">Next  <i class="icon-hand-right"></i></button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div id="locations" class="tab-pane">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="row form-group">
                                    <div class="col-md-12">
                                        <label>Location</label>
                                        <select class="form-control" id="selLocation" name="location_id">
                                            <option value="0">Default</option>
                                            @foreach($locations as $location)
                                            <option value="{{$location->id}}">{{$location->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>About Us</label>
                                        <textarea id="locAbout" name="about" class="fill-up"; rows="10">{{$about}}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Page Title</label>
                                    <input id="locTitle" name="page_title" class="form-control fill-up" type="text" value="{{$page_title}}">
                                </div>
                                <div class="form-group">
                                    <label>Keywords</label>
                                    <input id="locKeywords" name="keywords" class="form-control fill-up" type="text" value="{{$keywords}}">
                                </div>
                                <div class="form-group">
                                    <label>Meta Description</label>
                                    <textarea id="locDescription" name="meta_description" class="form-control fill-up" rows="6">{{$meta_description}}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="divider"><hr></div>

                        <div class="" style="padding-bottom: 0px;">
                            <div class="pull-right">
                                <span id="locMessages" style="color:green;display:none;"></span>
                                <button id="btnLocSave" type="button" class="btn btn-success">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
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