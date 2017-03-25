@extends('admin.master.templates.master')
@section('sidebar')
    @include('admin.content.master.templates.sidebar', array())
@stop
@section('body')
<script>
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
            <div class="grid-title">
                <div class="pull-left">
                    <span>Finished!</span>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="grid-content overflow">
                <legend>Merchant Setup Complete!</legend>
                <div class="row">
                    <div class="col-md-6">
                        The merchant is complete and ready to be published.  You can either publish the merchant now, or wait for it to be reviewed and published later.
                    </div>      
                </div>
                <div class="form-group">
                    <label>Publish?</label>
                    <div class="row">
                      <div class="col-md-3">
                          <select id="demo" class="form-control fill-up">
                              <option value="1">No</option>
                              <option value="0">Yes</option>
                          </select>
                      </div>
                    </div>
                </div>

                <div class="divider"><hr></div>

                <div class="" style="padding-bottom: 0px;">
                    <div class="pull-left">
                        <a href="/syndication?viewing={{$franchise_id}}" type="button" class="btn btn-primary"><i class="icon-hand-left"></i>  Back</a>
                    </div>
                    <div class="pull-right">
                        <button id="btnFinish" type="button" class="btn btn-success">Finish  <i class="icon-thumbs-up"></i></button>
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