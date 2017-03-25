<ul>
@foreach($states['objects'] as $state => $data)
    <li>
        {{ $state }} ({{$data['count']}})<br/>
        <ul>
    @foreach($data['categories'] as $category => $cat_data)
            <li>
                {{ $category }} ({{$cat_data['count']}})</br>
                <ul>
        @foreach($cat_data['subcategories'] as $subcategory => $subcat_data)
                    <li>
                        {{ $subcategory }} ({{$subcat_data['count']}})<br/>
                        <ul>
            @foreach($subcat_data['merchants'] as $merchant)
                            <li>{{ $merchant['display'] }}</li>
            @endforeach
                        </ul>
                    </li>
        @endforeach
                </ul>
            </li>
    @endforeach
        </ul>
    </li>
@endforeach
</ul>