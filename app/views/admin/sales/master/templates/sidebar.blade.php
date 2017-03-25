<!--BEGIN SIDEBAR-->
<?php 
    $logged_user = Auth::User();
    $userRepo = \App::make('UserRepositoryInterface');
    $features = App::make('FeatureRepositoryInterface');
    $new_training = $features->findByName('new_training_toggle');
    $new_training = empty($new_training) ? 0 : $new_training->value;
?>
<div id="menu" role="navigation">
  <ul class="main-menu">
     <li class="{{$primary_nav == 'sales' ? 'active' : ''}}"><a href="/"><i class="general"></i> Sales</a></li>
     @if($userRepo->checkType($logged_user, 'leads'))<li class="{{$primary_nav == 'leads' ? 'active' : ''}}"><a href="/leads"><i class="statistics"></i> Leads</a></li>@endif
     @if($userRepo->checkType($logged_user, 'reports'))<li class="{{$primary_nav == 'reports' ? 'active' : ''}}"><a href="/user-ltv"><i class="statistics"></i> Reports</a></li>@endif
  </ul>

  <ul class="additional-menu">
     @if($primary_nav == 'sales')
     <li class="{{$secondary_nav == 'dashboard' ? 'active' : ''}}"><a href="/"><i class="icon-home"></i> Dashboard</a></li>
     <li class="{{$secondary_nav == 'merchants' ? 'active' : ''}}"><a href="/merchant-list"><i class="icon-star"></i> Merchant Report</a></li>
     <!-- <li class="{{$secondary_nav == 'contests' ? 'active' : ''}}"><a href="/contest-report"><i class="icon-star"></i> Contest Report</a></li>-->
     <!--<li class="{{$secondary_nav == 'leads' ? 'active' : ''}}"><a href="/lead-report"><i class="icon-user"></i> Lead Report</a></li>-->
     @if($userRepo->checkType($logged_user, 'Admin'))
     <!--<li class="{{$secondary_nav == 'revenue' ? 'active' : ''}}"><a href="/revenue-report"><i class="icon-user"></i> Revenue Report</a></li>-->
     @endif
     <li class="{{$secondary_nav == 'gallery' ? 'active' : ''}}"><a href="/gallery"><i class="icon-picture"></i> Gallery</a></li>
     <!--<li class="{{$secondary_nav == 'intake' ? 'active' : ''}}"><a href="/intake1"><i class="icon-picture"></i> Intake Forms</a></li>-->
     <li class="{{$secondary_nav == 'forms' ? 'active' : ''}}"><a href="/forms"><i class="icon-inbox"></i> Forms</a></li>
     <li class="{{$secondary_nav == 'training' ? 'active' : ''}}"><a href="{{($new_training)?'/onlinetraining':'trainingwelcome'}}"><i class="icon-check"></i> Training</a></li>
     <li><a target="_blank" href="http://www.callsource.com/home/reporting-login"><i class="icon-chevron-right"></i> Call Capture</a></li>
     <li class="{{$secondary_nav == 'fileupload' ? 'active' : ''}}"><a href="/fileupload"><i class="icon-folder-open"></i> FTP Upload</a></li>
     @elseif($primary_nav == 'leads')
     <li class="{{$secondary_nav == 'assign' ? 'active' : ''}}"><a href="/leads"><i class="icon-user"></i> Assign Leads</a></li>
     @elseif($primary_nav == 'reports')
     <li class="{{$secondary_nav == 'user_ltv' ? 'active' : ''}}"><a href="/user-ltv"><i class="icon-user"></i> User LTV</a></li>
     @endif
  </ul>

  <div class="clearfix"></div>
</div>
<!--SIDEBAR END-->
