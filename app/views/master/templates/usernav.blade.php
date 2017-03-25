@if(Auth::check())
<div class="panel panel-default">
	    <div class="panel-heading">
	      <span class="h4 hblock panel-title"><span class="glyphicon glyphicon-user"></span> My Stuff</span>
	    </div>
	    <div class="panel-collapse collapse in">
		    <div class="panel-body">
				<ul>
				    <li><a href="{{URL::abs('/')}}/members/dashboard">Dashboard</a></li>
				    <li><a href="{{URL::abs('/')}}/members/mycoupons">My Saved Coupons</a></li>
				    <!--<li><a href="{{URL::abs('/')}}/members/mysavetodays">My Save Todays</a></li>-->
				    <li><a href="{{URL::abs('/')}}/members/mycontestentries">My Contest Entries</a></li>
				    <li><a href="{{URL::abs('/')}}/members/myfavoritemerchants">My Favorite Merchants</a></li>
				    <li><a href="{{URL::abs('/')}}/members/mysettings">My Account Settings</a></li>
				    <!--<li><a href="{{URL::abs('/')}}/members/myinterests">My Interests</a></li>
				    <li><a href="{{URL::abs('/')}}/members/mylocations">My Favorite Locations</a></li>
				    <li><a href="{{URL::abs('/')}}/members/mynotifications">My Notifications</a></li>-->
				    <li><a href="{{URL::abs('/')}}/logout" type="button">Sign Out</a></li>
				</ul>
			</div>
		</div>
	</div>
@endif