@extends('admin.master.templates.master')
@section('sidebar')
    @include('admin.content.master.templates.sidebar', array())
@stop
@section('body')
    <!--BEGIN MAIN CONTENT-->
    <div id="main" role="main">
      <div class="block">
      <div class="clearfix"></div>
        
         <!--page title-->
         <div class="pagetitle">
            <h1>Dealer Matching</h1> 
            <div class="clearfix"></div>
         </div>
         <div class="clearfix"></div>
         <!--page title end-->
         
         <!-- info-box -->
         <div class="grid">
            <div class="grid-title">
                <div class="pull-left">
                    <span>Dealer Matching</span>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="grid-content overflow">
                <form action="/dealer-matching" method="POST">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Unlinked Dealers</label>
                            <select name="unlinked_id" class="form-control">
                                <option value="0">-- Choose --</option>
                                @foreach($unlinked as $unlink)
                                <option value="{{$unlink->id}}">{{$unlink->dealer_name.' - '.$unlink->dealer_address}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label>Linkable Dealers</label>
                            <select name="franchise_id" class="form-control">
                                <option value="0">-- Choose --</option>
                                @foreach($dealers as $dealer)
                                <option value="{{$dealer->id}}">{{$dealer->name.' - '.$dealer->address}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">Link Dealer</button>
                        </div>
                    </div>
                </form>

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