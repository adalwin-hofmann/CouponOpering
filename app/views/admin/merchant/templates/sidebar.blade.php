<!--BEGIN SIDEBAR-->
<?php 
    $logged_user = Auth::User();
    $userRepo = \App::make('UserRepositoryInterface');
?>
<div id="menu" role="navigation">
  	<ul class="main-menu">
    	<li class="{{$primary_nav == 'sales' ? 'active' : ''}}"><a href="/"><i class="general"></i> Merchants</a></li>
  	</ul>

	<ul class="additional-menu">
		<li class="{{$secondary_nav == 'dashboard' ? 'active' : ''}}"><a href="/"><i class="icon-home"></i> Dashboard</a></li>
		<li class="{{$secondary_nav == 'upload' ? 'active' : ''}}"><a href="/fileupload"><i class="icon-upload-alt"></i> File Upload</a></li>
		<li class="{{$secondary_nav == 'contact' ? 'active' : ''}}"><a href="/contact"><i class="icon-phone"></i> Contact Us</a></li>
		<li><a href="https://save.magazinemanager.com/payonline/" target="_blank"><i class="icon-money"></i> Payment</a></li>
	</ul>
	<div class="clearfix"></div>
</div>

<!--SIDEBAR END-->