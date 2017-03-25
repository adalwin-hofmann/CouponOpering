<?php 
    $geoip = json_decode(GeoIp::getGeoIp('json'));
    $categories = \App::make('CategoryRepositoryInterface');
?>
<script type="text/ejs" id="template_explore_subcategory">
<% list(categories, function(category)
    { %>
        <li><a href="{{URL::abs('/')}}/<%= type+'s' %>/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/<%= parent_slug %>/<%= category.slug %>"><%= category.name %></a></li>
<% }); %>    
</script>
<li class="{{($active == 0)?'active':''}}">
    <a href="{{URL::abs('/')}}/{{$type.'s'}}/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}">All {{$type == 'coupon' ? 'Coupons' : ($type == 'dailydeal' ? 'Daily Deals' : 'Contests')}}</a>
</li>
@foreach($categories->getByParentId(0)['objects'] as $cat)
<li class="{{($active == $cat->id)?'active':''}}">
    <a href="{{URL::abs('/')}}/{{$type.'s'}}/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/{{$cat->slug}}" title="{{$cat->name}} {{$type == 'coupon' ? 'Coupons' : ($type == 'dailydeal' ? 'Daily Deals' : 'Contests')}} in {{$geoip->city_name}}, {{$geoip->region_name}}">{{$cat->name == 'Special Services' ? 'Everything Else' : $cat->name}}</a>
    @if($active == $cat->id)
    <ul>
    @foreach($categories->getByParentId($cat->id)['objects'] as $subcat)
        <li class="{{isset($category) && $category->id == $subcat->id ? 'active' : ''}}"><a href="{{URL::abs('/')}}/{{$type.'s'}}/{{strtolower($geoip->region_name)}}/{{SoeHelper::getSlug($geoip->city_name)}}/{{$cat->slug}}/{{$subcat->slug}}" title="{{$subcat->name}} > {{$cat->name}} {{$type == 'coupon' ? 'Coupons' : ($type == 'dailydeal' ? 'Daily Deals' : 'Contests')}} in {{$geoip->city_name}}, {{$geoip->region_name}}">{{$subcat->name}}</a></li>
    @endforeach
    </ul>
    @endif
</li>
@endforeach
@if(($type != 'dailydeal') && ($type != 'contest'))
<li>
    <a href="{{URL::abs('/')}}/groceries">Groceries</a>
</li>
@endif
