<ul>
    <!--<li class="{{($active == 0)?'active':''}}">
        <a href="{{URL::abs('/')}}/homeimprovement/coupons">All</a>
    </li>-->
    <?php $parents = $tagRepo->getParentTags(); ?>
    @foreach($parents['objects'] as $parent)
        <!--<li class="{{$active == $parent->id ? 'active' : ''}}">
            <a href="{{URL::abs('/')}}/homeimprovement/coupons/{{$parent->slug}}">{{$parent->name}}</a>
            @if($active == $parent->id)
            <ul>
                <?php $children = $tagRepo->getChildren($parent); ?>
                @foreach($children['objects'] as $child)
                <li><a href="{{URL::abs('/')}}/homeimprovement/coupons/{{$parent->slug}}/{{$child->slug}}">{{$child->name}}</a>
                @endforeach
            </ul>
            @endif
        </li>-->
        <?php $children = $tagRepo->getChildren($parent); ?>
        @foreach($children['objects'] as $child)
        <li><a href="{{URL::abs('/')}}/homeimprovement/coupons/{{$parent->slug}}/{{$child->slug}}">{{$child->name}}</a>
        @endforeach
@endforeach
</ul>
<hr>
<ul>
    <li class="{{(Feature::findByName('generic_quote')->value == 0)?'hidden':''}}"><strong><a class="red" href="{{URL::abs('/')}}/homeimprovement/quote">Get Quotes</a></strong></li>
    <li><strong><a class="red" href="{{URL::abs('/')}}/homeimprovement/get-certified">Become a Merchant</a></strong></li>
</ul>