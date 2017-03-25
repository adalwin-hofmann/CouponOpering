<?php
    $logged_user = Auth::User();
    $userRepo = \App::make('UserRepositoryInterface');
    $features = App::make('FeatureRepositoryInterface');
    $new_training = $features->findByName('new_training_toggle');
    $new_training = empty($new_training) ? 0 : $new_training->value;
 ?>
<!--BEGIN SIDEBAR-->
<div id="menu" role="navigation">
  <ul class="main-menu">
     <li class="{{$primary_nav == 'dashboard' ? 'active' : ''}}"><a href="/"><i class="general"></i> Dashboard</a></li>
     <li class="{{$primary_nav == 'wizard' ? 'active' : ''}}"><a href="/wizard"><i class="components"></i> Wizard</a></li>
     <li class="{{$primary_nav == 'contests' ? 'active' : ''}}"><a href="/contests"><i class="pages"></i> Contests</a></li>
     <li class="{{$primary_nav == 'gallery' ? 'active' : ''}}"><a href="/gallery-upload"><i class="forms"></i> Gallery</a></li>
     @if($userRepo->checkType($logged_user, 'seo'))
     <li class="{{$primary_nav == 'seo' ? 'active' : ''}}"><a href="/seo"><i class="forms"></i> Seo Admin</a></li>
     @endif
     @if($userRepo->checkType($logged_user, 'Admin'))
     <li class="{{$primary_nav == 'users' ? 'active' : ''}}"><a href="/users"><i class="forms"></i> Users Admin</a></li>
     <li class="{{$primary_nav == 'newsletters' ? 'active' : ''}}"><a href="/newsletter-admin"><i class="forms"></i> Newsletter Admin</a></li>
     <li class="{{$primary_nav == 'events' ? 'active' : ''}}"><a href="/events"><i class="forms"></i> Event</a></li>
     @endif
  </ul>
  
  <ul class="additional-menu">
    @if($primary_nav == 'dashboard')
     <li class="{{$secondary_nav == 'dashboard' ? 'active' : ''}}"><a href="/"><i class="icon-home"></i> Dashboard</a></li>
     @if($new_training)
     <li class="{{$secondary_nav == 'training' ? 'active' : ''}}"><a href="/onlinetraining"><i class="icon-home"></i> Training</a></li>
     @endif
    @elseif($primary_nav == 'wizard')
     <li id="wizardFranchise" class="wizard-step {{$secondary_nav == 'wizard' ? 'active' : ''}}"><a href="/wizard"><i class="icon-picture"></i> Wizard Start</a></li>
     <li id="wizardLocations" class="wizard-step {{(isset($viewing) && $viewing == 0) ? 'wizard-disabled' : ($secondary_nav == 'locations' ? 'active' : '')}}" {{isset($viewing) && $viewing == 0 ? 'disabled="disabled"' : ''}}><a href="/location{{isset($viewing) && $viewing != 0 ? '?viewing='.$viewing : ''}}" onclick="{{isset($viewing) && $viewing != 0 ? '' : 'return false;'}}"><i class="icon-picture"></i> Locations</a></li>
     <li id="wizardCoupons" class="wizard-step {{(isset($viewing) && $viewing == 0) ? 'wizard-disabled' : ($secondary_nav == 'coupons' ? 'active' : '')}}" {{isset($viewing) && $viewing == 0 ? 'disabled="disabled"' : ''}}><a href="/coupon{{isset($viewing) && $viewing != 0 ? '?viewing='.$viewing : ''}}" onclick="{{isset($viewing) && $viewing != 0 ? '' : 'return false;'}}"><i class="icon-picture"></i> Coupons</a></li>
     <li id="wizardEvents" class="wizard-step {{(isset($viewing) && $viewing == 0) ? 'wizard-disabled' : ($secondary_nav == 'events' ? 'active' : '')}}" {{isset($viewing) && $viewing == 0 ? 'disabled="disabled"' : ''}}><a href="/event{{isset($viewing) && $viewing != 0 ? '?viewing='.$viewing : ''}}" onclick="{{isset($viewing) && $viewing != 0 ? '' : 'return false;'}}"><i class="icon-picture"></i> Events</a></li>
     <li id="wizardBanners" class="wizard-step {{(isset($viewing) && $viewing == 0) ? 'wizard-disabled' : ($secondary_nav == 'banners' ? 'active' : '')}}" {{isset($viewing) && $viewing == 0 ? 'disabled="disabled"' : ''}}><a href="/banner{{isset($viewing) && $viewing != 0 ? '?viewing='.$viewing : ''}}" onclick="{{isset($viewing) && $viewing != 0 ? '' : 'return false;'}}"><i class="icon-picture"></i> Banners</a></li>
     <li id="wizardAbout" class="wizard-step {{(isset($viewing) && $viewing == 0) ? 'wizard-disabled' : ($secondary_nav == 'about' ? 'active' : '')}}" {{isset($viewing) && $viewing == 0 ? 'disabled="disabled"' : ''}}><a href="/about{{isset($viewing) && $viewing != 0 ? '?viewing='.$viewing : ''}}" onclick="{{isset($viewing) && $viewing != 0 ? '' : 'return false;'}}"><i class="icon-picture"></i> About</a></li>
     <li id="wizardPictures" class="wizard-step {{(isset($viewing) && $viewing == 0) ? 'wizard-disabled' : ($secondary_nav == 'pictures' ? 'active' : '')}}" {{isset($viewing) && $viewing == 0 ? 'disabled="disabled"' : ''}}><a href="/pictures{{isset($viewing) && $viewing != 0 ?  '?viewing='.$viewing : ''}}" onclick="{{isset($viewing) && $viewing != 0 ? '' : 'return false;'}}"><i class="icon-picture"></i> Pictures</a></li>
     <li id="wizardVideo" class="wizard-step {{(isset($viewing) && $viewing == 0) ? 'wizard-disabled' : ($secondary_nav == 'video' ? 'active' : '')}}" {{isset($viewing) && $viewing == 0 ? 'disabled="disabled"' : ''}}><a href="/video{{isset($viewing) && $viewing != 0 ? '?viewing='.$viewing : ''}}" onclick="{{isset($viewing) && $viewing != 0 ? '' : 'return false;'}}"><i class="icon-picture"></i> Video</a></li>
     <li id="wizardPdfs" class="wizard-step {{(isset($viewing) && $viewing == 0) ? 'wizard-disabled' : ($secondary_nav == 'pdfs' ? 'active' : '')}}" {{isset($viewing) && $viewing == 0 ? 'disabled="disabled"' : ''}}><a href="/pdf{{isset($viewing) && $viewing != 0 ? '?viewing='.$viewing : ''}}" onclick="{{isset($viewing) && $viewing != 0 ? '' : 'return false;'}}"><i class="icon-picture"></i> PDFs</a></li>
     <li id="wizardSyndication" class="wizard-step {{(isset($viewing) && $viewing == 0) ? 'wizard-disabled' : ($secondary_nav == 'syndication' ? 'active' : '')}}" {{isset($viewing) && $viewing == 0 ? 'disabled="disabled"' : ''}}><a href="/syndication{{isset($viewing) && $viewing != 0 ? '?viewing='.$viewing : ''}}" onclick="{{isset($viewing) && $viewing != 0 ? '' : 'return false;'}}"><i class="icon-picture"></i> Syndication</a></li>
     <li id="wizardFinish" class="wizard-step {{(isset($viewing) && $viewing == 0) ? 'wizard-disabled' : ($secondary_nav == 'finish' ? 'active' : '')}}" {{isset($viewing) && $viewing == 0 ? 'disabled="disabled"' : ''}}><a href="/finish{{isset($viewing) && $viewing != 0 ? '?viewing='.$viewing : ''}}" onclick="{{isset($viewing) && $viewing != 0 ? '' : 'return false;'}}"><i class="icon-picture"></i> Finish</a></li>
     <li id="dealerMatching" class="{{$secondary_nav == 'dealer-matching' ? 'active' : ''}}"><a href="/dealer-matching" ><i class="icon-picture"></i> Dealer Matching</a></li>
    @elseif($primary_nav == 'contests')
     <li class="{{$secondary_nav == 'contests' ? 'active' : ''}}"><a href="/contests"><i class="icon-certificate"></i> Contest Admin</a></li>
     <li class="{{$secondary_nav == 'winners' ? 'active' : ''}}"><a href="/winners"><i class="icon-user"></i> Contest Winners</a></li>
     <li class="{{$secondary_nav == 'contest-report' ? 'active' : ''}}"><a href="/contest-report"><i class="icon-user"></i> Contest Report</a></li>
     @elseif($primary_nav == 'gallery')
     <li class="{{$secondary_nav == 'upload' ? 'active' : ''}}"><a href="/gallery-upload"><i class="icon-picture"></i> Upload</a></li>
     @elseif($primary_nav == 'seo')
     <li class="{{$secondary_nav == 'seo' ? 'active' : ''}}"><a href="/seo"><i class="icon-certificate"></i> SEO</a></li>
    @endif
  </ul>
  
  <div class="clearfix"></div>
  
  
</div>
<!--SIDEBAR END-->