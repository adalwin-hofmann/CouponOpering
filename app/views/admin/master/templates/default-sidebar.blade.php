<?php
    $logged_user = Auth::User();
    $userRepo = \App::make('UserRepositoryInterface');
 ?>
<!--BEGIN SIDEBAR-->
<div id="menu" role="navigation">
  <ul class="main-menu">
     <li class="{{$primary_nav == $user_mode ? 'active' : ''}}"><a href="/"><i class="general"></i> Dashboard</a></li>
  </ul>
  
  <ul class="additional-menu">
    @if($primary_nav == $user_mode)
     <li class="{{$secondary_nav == 'training' ? 'active' : ''}}"><a href="/onlinetraining"><i class="icon-home"></i> Training</a></li>
    @endif
  </ul>
  
  <div class="clearfix"></div>
  
  
</div>
<!--SIDEBAR END-->