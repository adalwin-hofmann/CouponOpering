<!--BEGIN HEADER-->
<div id="header" role="banner">
   <a id="menu-link" class="head-button-link menu-hide" href="#menu"><span>Menu</span></a>
   <!--Logo--><a href="/" class="logo"><img src="/img/logo.png" alt="Save On" class="img-responsive center-block"></a><!--Logo END-->
   
   <!--Search-->
   <!-- <form class="search" action="#">
     <input type="text" name="q" placeholder="Search...">
   </form> -->
   <!--Search END-->
   
   <div class="right">
   
   <!--message box-->
     <!-- <div class="dropdown left">
      
      <a class="dropdown-toggle head-button-link" data-toggle="dropdown" href="#"><span class="notice-new">3</span></a> 
      
      <div class="dropdown-menu pull-right messages-list">
      <div class="triangle"></div>
      
        <div class="notice-title">You Have 3 Massages</div>
       
        <a href="#" class="notice-more">View All Messages</a>
      </div>
    </div> -->
   <!--message box end-->
   
   <!--notification box-->
     <!-- <div class="dropdown left">
      <a class="dropdown-toggle head-button-link notification" data-toggle="dropdown" href="#"><span class="notice-new">2</span></a>
      
      <div class="dropdown-menu pull-right messages-list">
      <div class="triangle"></div>
      
        <div class="notice-title">You Have 2 Notifications</div>
       
        <a href="#" class="notice-more">View All Notification</a>
      </div>
    </div> -->
   <!--notification box end-->
   
   <!--config box-->
     <!-- <div class="dropdown left">
      <a class="dropdown-toggle head-button-link config" data-toggle="dropdown" href="#"></a>
      <div class="dropdown-menu pull-right settings-box">
      <div class="triangle-2"></div>
      
        <a href="javascript:chooseStyle('none', 30)" class="settings-link"></a>
        <a href="javascript:chooseStyle('blue-theme', 30)" class="settings-link blue"></a>
        <a href="javascript:chooseStyle('green-theme', 30)" class="settings-link green"></a>
        <a href="javascript:chooseStyle('purple-theme', 30)" class="settings-link purple"></a>
        <a href="javascript:chooseStyle('orange-theme', 30)" class="settings-link yellow"></a>
        <a href="javascript:chooseStyle('red-theme', 30)" class="settings-link red"></a>
        <div class="clearfix"></div>
      </div>
    </div> -->
   <!--config box end-->
   
   <!--profile box-->
     <div class="dropdown left profile">
      <a class="dropdown-toggle" data-toggle="dropdown" href="#">
        <span class="double-spacer"></span>
        <!--<div class="profile-avatar"><img src="/nightsky/images/avatar.png" alt=""></div>-->
        <div class="profile-username"><span>Welcome,</span> {{Auth::user()->name}}</div>
        <div class="profile-caret"> <span class="caret"></span></div>
        <span class="double-spacer"></span>
      </a>
      <div class="dropdown-menu pull-right profile-box">
      <div class="triangle-3"></div>
      
        <ul class="profile-navigation">
          <!--<li><a href="#"><i class="icon-user"></i> My Profile</a></li>
          <li><a href="#"><i class="icon-cog"></i> Settings</a></li>
          <li><a href="#"><i class="icon-info-sign"></i> Help</a></li>-->
          <li><a href="/logout"><i class="icon-off"></i> Logout</a></li>
        </ul>
      </div>
    </div>
    <div class="clearfix"></div>
   <!--profile box end-->
   
   </div>
   
  
</div>
<!--END HEADER-->