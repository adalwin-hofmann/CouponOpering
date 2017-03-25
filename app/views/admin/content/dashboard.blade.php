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
            <h1>Dashboard</h1> 
            <div class="clearfix"></div>
         </div>
         <!--page title end-->
         
         <!-- info-box -->
         <div class="info-box">
           <div class="stats-box">
            <div class="row">
              <div class="col-md-4">
                <div class="stats-box-title">Vizitor</div>
                <div class="stats-box-all-info"><img src="/nightsky/images/icon/icon_vizitors_stats.png" alt=""> 365K</div>
                <div class="wrap-chart"><div id="visitor-stat" class="chart"></div></div>
              </div>
              
              <div class="col-md-4">
                <div class="stats-box-title">Likes</div>
                <div class="stats-box-all-info"><img src="/nightsky/images/icon/icon_like_stats.png" alt=""> 35.00</div>
                <div class="wrap-chart"><div id="order-stat" class="chart"></div></div>
              </div>
              
              <div class="col-md-4">
                <div class="stats-box-title">Orders</div>
                <div class="stats-box-all-info"><img src="/nightsky/images/icon/icon_orders_stats.png" alt=""> 1.234</div>
                <div class="wrap-chart"><div id="user-stat" class="chart"></div></div>
              </div>
           </div>
          </div>
          <div class="row">
           <div class="information-data">
            <div class="data">
                <p class="date-figures">935</p>
                <p class="date-title">Tikets</p>
            </div>
            <div class="data">
                <p class="date-figures">2316$</p>
                <p class="date-title">Earnings</p>
            </div>
            <div class="data">
                <p class="date-figures">165</p>
                <p class="date-title">Comments</p>
            </div>
            <div class="data data-last">
                <p class="date-figures">95%</p>
                <p class="date-title">Updates</p>
            </div>
            </div>
         </div>
        </div>
         <!-- info-box end -->
         
          <!--quick stats box-->
             <div class="grid-transparent row quick-stats-box">
               <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
        <div class="color-box">
                  <span>21</span> twetts<br><br>
                </div>
               </div>
               <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                <div class="color-box red">
                  <span>11</span> COMMENTS
                </div>
               </div>
               <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                <div class="color-box orange">
                  <span>28</span> photos
                </div>
               </div>
               <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3 ">
                <div class="color-box green">
                  <span>51</span> followerss
                </div>
               </div>  
             </div>
             <div class="clearfix"></div>
       
             <!--quick stats box END-->
           
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