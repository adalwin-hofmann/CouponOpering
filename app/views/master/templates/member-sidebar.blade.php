<div class="panel panel-default mystuff-sidebar">
    <div class="panel-heading">
      <span class="h4 hblock panel-title">
        <a data-toggle="collapse" href="#collapseTwo">My Stuff <span class="glyphicon glyphicon-minus-sign pull-right"></span><span class="glyphicon glyphicon-plus-sign pull-right"></span></a>
        <div class="clearfix"></div>
      </span>
    </div>
    <div id="collapseTwo" class="panel-collapse collapse in">
        <div class="panel-body explore-links">
            <ul>
                <li>@if($page != 'dashboard')<a href="{{URL::abs('/')}}/members/dashboard">@endif Dashboard @if($page != 'dashboard')</a>@endif</li>
                <li>@if($page != 'coupons')<a href="{{URL::abs('/')}}/members/mycoupons/">@endif My Coupons @if($page != 'coupons')</a>@endif</li>
                <!--<li>@if($page != 'savetodays')<a href="{{URL::abs('/')}}/members/mysavetodays">@endif My Save Todays @if($page != 'savetodays')</a>@endif</li>-->
                <li>@if($page != 'contests')<a href="{{URL::abs('/')}}/members/mycontestentries">@endif My Contest Entries @if($page != 'contests')</a>@endif</li>
                <li>@if($page != 'favoritemerchants')<a href="{{URL::abs('/')}}/members/myfavoritemerchants">@endif My Favorite Merchants @if($page != 'favoritemerchants')</a>@endif</li>
                <?php $soct_redirect = Feature::findByName('soct_redirect'); ?>
                @if((!empty($soct_redirect)) && (Feature::findByName('soct_redirect')->value == 1))
                <li>@if($page != 'cars')<a href="{{URL::abs('/')}}/members/mycars">@endif My Cars @if($page != 'cars')</a>@endif</li>
                @endif
                <li>@if($page != 'settings')<a href="{{URL::abs('/')}}/members/mysettings">@endif My Account Settings @if($page != 'settings')</a>@endif
                @if(isset($subpage) && $subpage == 'settings')
                <ul>
                    <li>@if($page != 'interests')<a href="{{URL::abs('/')}}/members/myinterests">@endif My Interests @if($page != 'interests')</a>@endif</li>
                    <li>@if($page != 'locations')<a href="{{URL::abs('/')}}/members/mylocations">@endif My Favorite Locations @if($page != 'locations')</a>@endif</li>
                    <li>@if($page != 'notifications')<a href="{{URL::abs('/')}}/members/mynotifications">@endif My Notifications @if($page != 'notifications')</a>@endif</li>
                </ul>
                @endif
                </li>
            </ul>
        </div>
    </div>
</div>