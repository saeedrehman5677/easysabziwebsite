<div class="card-header border-0 order-header-shadow">
    <h5 class="card-title d-flex justify-content-between flex-grow-1">
        <span>{{translate('top_selling_products')}}</span>
        <a href="{{route('admin.product.list')}}" class="fz-12px font-medium text-006AE5">{{translate('view_all')}}</a>
    </h5>
</div>

<div class="card-body">
    <div class="top--selling">
        @foreach($top_sell as $key=>$item)
            @if(isset($item->product))
            <a class="grid--card" href="{{route('admin.product.view',[$item['product_id']])}}">
                @if (!empty(json_decode($item->product->image,true)))
                <img src="{{$item->product->identityImageFullPath[0]}}"
                     alt="{{$item->product->name}} {{translate('image')}}">
                @endif
                <div class="cont pt-2">
                    <h6 class="line--limit-2">{{ substr($item->product['name'],0,20) . (strlen($item->product['name'])>20?'...':'')}}</h6>
                </div>
                <div class="ml-auto">
                    <span class="badge badge-soft">{{ translate('Sold') }} : {{$item['count']}}</span>
                </div>
            </a>
            @endif
        @endforeach
    </div>
</div>
