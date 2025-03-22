@extends('layouts.admin.app')

@section('title', translate('Order Details'))

@push('css_or_js')
    <style>
        figure{
            margin-bottom: -1px;
        }
    </style>
    <link rel="stylesheet" href="{{asset('/public/assets/admin/css/lightbox.min.css')}}">
@endpush
@section('content')

    <div class="content container-fluid">
        <div class="page-header d-flex justify-content-between">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{asset('public/assets/admin/img/order.png')}}" class="w--20" alt="">
                </span>
                <span>
                    {{translate('order details')}}
                </span>
            </h1>
        </div>

        <div class="row" id="printableArea">
            <div class="col-lg-8 order-print-area-left">
                <div class="card mb-3 mb-lg-5">
                    <div class="card-header flex-wrap align-items-start border-0">
                        <div class="order-invoice-left">
                            <h1 class="page-header-title">
                                <span class="mr-3">{{translate('order ID')}} #{{$order['id']}}</span>
                                <span class="badge badge-soft-info py-2 px-3">{{$order->branch?$order->branch->name:translate('Branch deleted!')}}</span>
                            </h1>
                            <span><i class="tio-date-range"></i>
                                {{date('d M Y',strtotime($order['created_at']))}} {{ date(config('time_format'), strtotime($order['created_at'])) }}</span>
                        </div>
                        <div class="order-invoice-right mt-3 mt-sm-0">
                            <div class="btn--container ml-auto align-items-center justify-content-end">
                                @if($order['order_type']!='self_pickup' && $order['order_type'] != 'pos')

                                    @php($googleMapStatus = \App\CentralLogics\Helpers::get_business_settings('google_map_status'))
                                    @if($googleMapStatus)
                                        @if($order['order_status']=='out_for_delivery')
                                            @php($origin=\App\Model\DeliveryHistory::where(['deliveryman_id'=>$order['delivery_man_id'],'order_id'=>$order['id']])->first())
                                            @php($current=\App\Model\DeliveryHistory::where(['deliveryman_id'=>$order['delivery_man_id'],'order_id'=>$order['id']])->latest()->first())
                                            @if(isset($origin))
                                                <a class="btn btn-outline-info font-semibold" target="_blank"
                                                   title="{{translate('Delivery Boy Last Location')}}" data-toggle="tooltip" data-placement="top"
                                                   href="https://www.google.com/maps/dir/?api=1&origin={{$origin['latitude']}},{{$origin['longitude']}}&destination={{$current['latitude']}},{{$current['longitude']}}">
                                                    <i class="tio-map"></i>
                                                    {{translate('Show Location in Map')}}
                                                </a>
                                            @else
                                                <a class="btn btn-outline-info font-semibold" href="javascript:" data-toggle="tooltip"
                                                   data-placement="top" title="{{translate('Waiting for location...')}}">
                                                    <i class="tio-map"></i>
                                                    {{translate('Show Location in Map')}}
                                                </a>
                                            @endif
                                        @else
                                            <a class="btn btn-outline-info font-semibold last-location-view" href="javascript:"
                                               data-toggle="tooltip" data-placement="top"
                                               title="{{translate('Only available when order is out for delivery!')}}">
                                                <i class="tio-map"></i>
                                                {{translate('Show Location in Map')}}
                                            </a>
                                        @endif
                                    @endif

                                @endif
                                <a class="btn btn--info print--btn" target="_blank" href={{route('admin.orders.generate-invoice',[$order['id']])}}>
                                    <i class="tio-print mr-1"></i> {{translate('print')}} {{translate('invoice')}}
                                </a>
                            </div>
                            <div class="text-right mt-3 order-invoice-right-contents text-capitalize">
                                <h6>
                                    {{translate('Status')}} :
                                    @if($order['order_status']=='pending')
                                        <span class="badge badge-soft-info ml-2 ml-sm-3 text-capitalize">
                                        {{translate('pending')}}
                                        </span>
                                    @elseif($order['order_status']=='confirmed')
                                        <span class="badge badge-soft-info ml-2 ml-sm-3 text-capitalize">
                                        {{translate('confirmed')}}
                                        </span>
                                    @elseif($order['order_status']=='processing')
                                        <span class="badge badge-soft-warning ml-2 ml-sm-3 text-capitalize">
                                        {{translate('packaging')}}
                                        </span>
                                    @elseif($order['order_status']=='out_for_delivery')
                                        <span class="badge badge-soft-warning ml-2 ml-sm-3 text-capitalize">
                                        {{translate('out_for_delivery')}}
                                        </span>
                                    @elseif($order['order_status']=='delivered')
                                        <span class="badge badge-soft-success ml-2 ml-sm-3 text-capitalize">
                                        {{translate('delivered')}}
                                        </span>
                                    @else
                                        <span class="badge badge-soft-danger ml-2 ml-sm-3 text-capitalize">
                                        {{str_replace('_',' ',$order['order_status'])}}
                                        </span>
                                    @endif
                                </h6>
                                <h6 class="text-capitalize">
                                    <span class="text-body mr-2">{{translate('payment')}} {{translate('method')}}
                                    :</span> <span class="text--title font-bold">{{ translate(str_replace('_',' ',$order['payment_method']))}}</span>
                                </h6>
                                @if(!in_array($order['payment_method'], ['cash_on_delivery', 'wallet_payment', 'offline_payment']))
                                    <h6 class="text-capitalize">
                                        @if($order['transaction_reference']==null && $order['order_type']!='pos')
                                            <span class="text-body mr-2"> {{translate('reference')}} {{translate('code')}}
                                        :</span>
                                            <button class="btn btn-outline-primary py-1 btn-sm" data-toggle="modal"
                                                    data-target=".bd-example-modal-sm">
                                                {{translate('add')}}
                                            </button>
                                        @elseif($order['order_type']!='pos')
                                            <span class="text-body mr-2">{{translate('reference')}} {{translate('code')}}
                                        :</span> <span class="text--title font-bold"> {{$order['transaction_reference']}}</span>
                                        @endif
                                    </h6>
                                @endif

                                <h6>
                                    <span class="text-body mr-2">{{ translate('payment') }} {{ translate('status') }} : </span>

                                    @if($order['payment_status']=='paid')
                                        <span class="badge badge-soft-success ml-sm-3">
                                            {{translate('paid')}}
                                        </span>
                                    @else
                                        <span class="badge badge-soft-danger ml-sm-3">
                                            {{translate($order['payment_status'])}}
                                        </span>
                                    @endif
                                </h6>
                                <h6 class="text-capitalize">
                                    <span class="text-body">{{translate('order')}} {{translate('type')}}</span>
                                    :<label class="badge badge-soft-primary ml-3">{{ translate(str_replace('_',' ',$order['order_type'])) }}</label>
                                </h6>
                            </div>
                        </div>
                        @if($order['order_type'] != 'pos')
                        <div class="w-100">
                            <h6>
                                <strong>{{translate('order')}} {{translate('note')}}</strong>
                                : <span class="text-body"> {{$order['order_note']}} </span>
                            </h6>
                        </div>
                        @endif
                    </div>

                    <div class="card-body">
                    @php($subTotal=0)
                    @php($amount=0)
                    @php($totalTax=0)
                    @php($total_dis_on_pro=0)
                    @php($totalItemDiscount=0)
                    @php($price_after_discount=0)
                    @php($updatedTotalTax=0)
                    @php($vatStatus = '')
                    <div class="table-responsive">
                        <table class="table table-borderless table-nowrap table-align-middle card-table dataTable no-footer mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th class="border-0">{{translate('SL')}}</th>
                                    <th class="border-0">{{translate('Item details')}}</th>
                                    <th class="border-0 text-right">{{translate('Price')}}</th>
                                    <th class="border-0 text-right">{{translate('Discount')}}</th>
                                    <th class="text-right border-0">{{translate('Total Price')}}</th>
                                </tr>
                            </thead>
                            @foreach($order->details as $detail)

                                @if($detail->product_details !=null)
                                    @php($product = json_decode($detail->product_details, true))
                                    <tr>
                                        <td>
                                            {{$loop->iteration}}
                                        </td>
                                        <td>
                                            <div class="media media--sm">
                                                <div class="avatar avatar-xl mr-3">
                                                    @if($detail->product && $detail->product['image'] != null )
                                                    <img class="img-fluid rounded aspect-ratio-1"
                                                         src="{{ $detail->product->identityImageFullPath[0] }}"
                                                        alt="{{translate('Image Description')}}">
                                                    @else
                                                        <img
                                                        src="{{asset('public/assets/admin/img/160x160/2.png')}}"
                                                        class="img-fluid rounded aspect-ratio-1"
                                                        >
                                                    @endif
                                                </div>
                                                <div class="media-body">
                                                    <h5 class="line--limit-1">{{$product['name']}}</h5>
                                                    @if(count(json_decode($detail['variation'],true)) > 0)
                                                        @foreach(json_decode($detail['variation'],true)[0]??json_decode($detail['variation'],true) as $key1 =>$variation)
                                                            <div class="font-size-sm text-body text-capitalize">
                                                                @if($variation != null)
                                                                <span>{{$key1}} :  </span>
                                                                @endif
                                                                <span class="font-weight-bold">{{$variation}}</span>
                                                            </div>
                                                        @endforeach
                                                    @endif
                                                    <h5 class="mt-1"><span class="text-body">{{translate('Unit')}}</span> : {{$detail['unit']}} </h5>
                                                    <h5 class="mt-1"><span class="text-body">{{translate('Unit Price')}}</span> : {{$detail['price']}} </h5>
                                                    <h5 class="mt-1"><span class="text-body">{{translate('QTY')}}</span> : {{$detail['quantity']}} </h5>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-right">
                                            <h6>{{ Helpers::set_symbol($detail['price'] * $detail['quantity']) }}</h6>
                                        </td>
                                        <td class="text-right">
                                            <h6>{{ Helpers::set_symbol($detail['discount_on_product'] * $detail['quantity']) }}</h6>
                                        </td>
                                        <td class="text-right">
                                            @php($amount+=$detail['price']*$detail['quantity'])
                                            @php($totalTax+=$detail['tax_amount']*$detail['quantity'])
                                            @php($updatedTotalTax+= $detail['vat_status'] === 'included' ? 0 : $detail['tax_amount']*$detail['quantity'])
                                            @php($vatStatus = $detail['vat_status'])
                                            @php($totalItemDiscount += $detail['discount_on_product'] * $detail['quantity'])
                                            @php($price_after_discount+=$amount-$totalItemDiscount)
                                            @php($subTotal+=$price_after_discount)
                                            <h5>{{ Helpers::set_symbol(($detail['price'] * $detail['quantity']) - ($detail['discount_on_product'] * $detail['quantity'])) }}</h5>
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                    <tr>
                                        <td colspan="12" class="td-p-0">
                                            <hr class="m-0" >
                                        </td>
                                    </tr>
                            </table>
                        </div>

                        <div class="row justify-content-md-end mb-3 mt-4">
                            <div class="col-md-9 col-lg-8">
                                <dl class="row text-right justify-content-end">
                                    <dt class="col-6 text-left">
                                        <div class="ml-auto max-w-150px">
                                            {{translate('items')}} {{translate('price')}} :
                                        </div>
                                    </dt>
                                    <dd class="col-6 col-xl-5 pr-5">
                                            {{--{{ Helpers::set_symbol($subTotal) }}--}}
                                            {{ Helpers::set_symbol($amount) }}
                                    </dd>
                                    <dt class="col-6 text-left">
                                        <div class="ml-auto max-w-150px">
                                            {{translate('Item Discount')}} :
                                        </div>
                                    </dt>
                                    <dd class="col-6 col-xl-5 pr-5">
                                        - {{ Helpers::set_symbol($totalItemDiscount) }}
                                    </dd>
                                    <dt class="col-6 text-left">
                                        <div class="ml-auto max-w-150px">
                                            {{translate('Sub Total')}} :
                                        </div>
                                    </dt>
                                    <dd class="col-6 col-xl-5 pr-5">
                                        {{ Helpers::set_symbol($total = $amount-$totalItemDiscount) }}
                                    </dd>
                                    <dt class="col-6 text-left">
                                        <div class="ml-auto max-w-150px">
                                            {{translate('TAX')}} / {{translate('VAT')}} {{ $vatStatus == 'included' ? translate('(included)') : '' }}:
                                        </div>
                                    </dt>
                                    <dd class="col-6 col-xl-5 pr-5">
                                        {{ Helpers::set_symbol($totalTax) }}
                                    </dd>
                                    @if($order['order_type'] != 'pos')
                                    <dt class="col-6 text-left">
                                        <div class="ml-auto max-w-150px">
                                            {{translate('coupon')}} {{translate('discount')}} :
                                        </div>
                                    </dt>
                                    <dd class="col-6 col-xl-5 pr-5">
                                        - {{ Helpers::set_symbol($order['coupon_discount_amount']) }}
                                    </dd>
                                    @endif
                                    <dt class="col-6 text-left">
                                        <div class="ml-auto max-w-150px">
                                            {{translate('extra Discount')}} :
                                        </div>
                                    </dt>
                                    <dd class="col-6 col-xl-5 pr-5">
                                        - {{ Helpers::set_symbol($order['extra_discount']) }}
                                    </dd>
                                    <dt class="col-6 text-left">
                                        <div class="ml-auto max-w-150px">
                                            {{translate('delivery')}} {{translate('fee')}} :
                                        </div>
                                    </dt>
                                    <dd class="col-6 col-xl-5 pr-5">
                                        @if($order['order_type']=='self_pickup')
                                            @php($del_c=0)
                                        @else
                                            @php($del_c=$order['delivery_charge'])
                                        @endif
                                        {{ Helpers::set_symbol($del_c) }}
                                    </dd>
                                    @if($order['weight_charge_amount'] > 0)
                                        <dt class="col-6 col-xl-7 text-left">
                                            <div class="ml-auto max-w-150px">
                                                {{translate('Charge_On_Weight')}} :
                                            </div>
                                        </dt>
                                        <dd class="col-6 col-xl-5 pr-5">
                                            {{ Helpers::set_symbol($order['weight_charge_amount']) }}
                                        </dd>
                                    @endif
                                    <dt class="col-6 text-left">
                                        <div class="ml-auto max-w-150px">
                                            {{translate('total')}}:
                                        </div>
                                    </dt>
                                    <dd class="col-6 col-xl-5 pr-5">
                                        {{ Helpers::set_symbol($total+$del_c+$updatedTotalTax-$order['coupon_discount_amount']-$order['extra_discount']+$order['weight_charge_amount']) }}
                                        <hr>
                                    </dd>
                                    @if ($order->partial_payment->isNotEmpty())
                                        @foreach($order->partial_payment as $partial)
                                            <dt class="col-6 text-left">
                                                <div class="ml-auto max-w-150px">
                                                    <span>{{translate('Paid By')}} ({{str_replace('_', ' ',$partial->paid_with)}})</span>
                                                    <span>:</span>
                                                </div>
                                            </dt>
                                            <dd class="col-6 col-xl-5 pr-5">
                                                {{ \App\CentralLogics\Helpers::set_symbol($partial->paid_amount) }}
                                            </dd>
                                        @endforeach
                                            <?php
                                            $due_amount = 0;
                                            $due_amount = $order->partial_payment->first()?->due_amount;
                                            ?>
                                        <dt class="col-6 text-left">
                                            <div class="ml-auto max-w-150px">
                                            <span>
                                                {{translate('Due Amount')}}</span>
                                                <span>:</span>
                                            </div>
                                        </dt>
                                        <dd class="col-6 col-xl-5 pr-5">
                                            {{ \App\CentralLogics\Helpers::set_symbol($due_amount) }}
                                        </dd>
                                    @endif
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-lg-4 order-print-area-right">
                @if($order['order_type'] != 'pos')
                <div class="card">
                    <div class="card-header border-0 pb-0 justify-content-center">
                        <h4 class="card-title">{{translate('Order Setup')}}</h4>
                    </div>

                    @if(isset($order->offline_payment))
                        <div class="card mt-3">
                            <div class="card-body text-center">
                                @if($order->offline_payment?->status == 1)
                                    <h4 class="">{{ translate('Payment_verified') }}</h4>
                                @else
                                    <h4 class="">{{ translate('Payment_verification') }}</h4>
                                    <p class="text-danger">{{ translate('please verify the payment before confirm order') }}</p>
                                    <div class="mt-3">
                                        <button class="btn btn--primary" type="button" id="verifyPaymentButton" data-id="{{ $order['id'] }}"
                                                data-target="#payment_verify_modal" data-toggle="modal">{{ translate('Verify_Payment') }}</button>
                                    </div>
                                @endif

                            </div>
                        </div>
                    @endif

                    <div class="card-body">
                        @if($order['order_type'] != 'pos')
                        <div class="hs-unfold w-100">
                            <span class="d-block form-label font-bold mb-2">{{translate('Change Order Status')}}:</span>
                            <div class="dropdown">
                                <button class="form-control h--45px dropdown-toggle d-flex justify-content-between align-items-center w-100" type="button"
                                        id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                    {{$order['order_status'] == 'processing' ? translate('packaging') : translate($order['order_status'])}}
                                </button>
                                <div class="dropdown-menu text-capitalize" aria-labelledby="dropdownMenuButton">
                                    @if($order['payment_method'] == 'offline_payment' && $order->offline_payment?->status != 1)
                                        <a class="dropdown-item manage-status" href="#" data-status="pending">{{ translate('pending') }}</a>
                                        <a class="dropdown-item manage-status" href="#" data-status="confirmed">{{ translate('confirmed') }}</a>
                                        <a class="dropdown-item manage-status" href="#" data-status="packaging">{{ translate('packaging') }}</a>
                                        <a class="dropdown-item manage-status" href="#" data-status="out_for_delivery">{{ translate('out_for_delivery') }}</a>
                                        <a class="dropdown-item manage-status" href="#" data-status="delivered">{{ translate('delivered') }}</a>
                                        <a class="dropdown-item manage-status" href="#" data-status="returned">{{ translate('returned') }}</a>
                                        <a class="dropdown-item manage-status" href="{{ route('admin.orders.status',['id'=>$order['id'],'order_status'=>'failed']) }}" data-order_status="failed">{{ translate('failed') }}</a>
                                        <a class="dropdown-item manage-status" href="{{ route('admin.orders.status',['id'=>$order['id'],'order_status'=>'canceled']) }}" data-order_status="canceled">{{ translate('canceled') }}</a>
                                    @else
                                        <a class="dropdown-item manage-status" href="{{ route('admin.orders.status',['id'=>$order['id'],'order_status'=>'pending']) }}" data-order_status="pending">{{ translate('pending') }}</a>
                                        <a class="dropdown-item manage-status" href="{{ route('admin.orders.status',['id'=>$order['id'],'order_status'=>'confirmed']) }}" data-order_status="confirmed">{{ translate('confirmed') }}</a>
                                        <a class="dropdown-item manage-status" href="{{ route('admin.orders.status',['id'=>$order['id'],'order_status'=>'processing']) }}" data-order_status="packaging">{{ translate('packaging') }}</a>
                                        <a class="dropdown-item manage-status" href="{{ route('admin.orders.status',['id'=>$order['id'],'order_status'=>'out_for_delivery']) }}" data-order_status="out_for_delivery">{{ translate('out_for_delivery') }}</a>
                                        <a class="dropdown-item manage-status" href="{{ route('admin.orders.status',['id'=>$order['id'],'order_status'=>'delivered']) }}" data-order_status="delivered">{{ translate('delivered') }}</a>
                                        <a class="dropdown-item manage-status" href="{{ route('admin.orders.status',['id'=>$order['id'],'order_status'=>'returned']) }}" data-order_status="returned">{{ translate('returned') }}</a>
                                        <a class="dropdown-item manage-status" href="{{ route('admin.orders.status',['id'=>$order['id'],'order_status'=>'failed']) }}" data-order_status="failed">{{ translate('failed') }}</a>
                                        <a class="dropdown-item manage-status" href="{{ route('admin.orders.status',['id'=>$order['id'],'order_status'=>'canceled']) }}" data-order_status="canceled">{{ translate('canceled') }}</a>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="hs-unfold w-100 mt-3">
                            <span class="d-block form-label font-bold mb-2">{{translate('Payment Status')}}:</span>
                            <div class="dropdown">
                                <button class="form-control h--45px dropdown-toggle d-flex justify-content-between align-items-center w-100" type="button"
                                        id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                    {{translate($order['payment_status'])}}
                                </button>
                                @if($order['payment_method'] == 'offline_payment' && $order->offline_payment?->status != 1)
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item offline-payment" data-message="{{ translate('You can not change status of unverified offline payment') }}"
                                           data-status="paid" href="#">{{ translate('paid') }}</a>
                                        <a class="dropdown-item offline-payment" data-message="{{ translate('You can not change status of unverified offline payment') }}"
                                           data-status="unpaid" href="#">{{ translate('unpaid') }}</a>
                                    </div>
                                @else
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item change-payment-status" data-status="paid" data-route="{{route('admin.orders.payment-status',['id'=>$order['id'],'payment_status'=>'paid'])}}">{{ translate('paid') }}</a>
                                        <a class="dropdown-item change-payment-status" data-status="unpaid" data-route="{{route('admin.orders.payment-status',['id'=>$order['id'],'payment_status'=>'unpaid'])}}">{{ translate('unpaid') }}</a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="mt-3">
                            <span class="d-block form-label mb-2 font-bold">{{translate('Delivery Date & Time')}}:</span>
                            <div class="d-flex flex-wrap g-2">
                                <div class="hs-unfold w-0 flex-grow min-w-160px">
                                    <label class="input-date">
                                        <input class="js-flatpickr form-control flatpickr-custom min-h-45px form-control" type="text" value="{{ date('d M Y',strtotime($order['delivery_date'])) }}"
                                               name="deliveryDate" id="from_date" data-id="{{ $order['id'] }}" required>
                                    </label>
                                </div>
                                <div class="hs-unfold w-0 flex-grow min-w-160px">
                                    <select class="custom-select custom-select time_slote" name="timeSlot" data-id="{{$order['id']}}">
                                        <option disabled selected>{{translate('select')}} {{translate('Time Slot')}}</option>
                                        @foreach(\App\Model\TimeSlot::all() as $timeSlot)
                                            <option value="{{$timeSlot['id']}}" {{$timeSlot->id == $order->time_slot_id ?'selected':''}}>{{date(config('time_format'), strtotime($timeSlot['start_time']))}}
                                                - {{date(config('time_format'), strtotime($timeSlot['end_time']))}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if(($order['order_type'] !='self_pickup') && ($order['order_type'] !='pos'))
                                @if (!$order->delivery_man)
                                    <div class="mt-3">
                                        <button class="btn btn--primary w-100" type="button" data-target="#assign_delivey_man_modal" data-toggle="modal">{{ translate('assign delivery man manually') }}</button>
                                    </div>
                                @endif
                                @if ($order->delivery_man)
                                    <div class="card mt-2">
                                        <div class="card-body">
                                            <h5 class="card-title mb-3 d-flex flex-wrap align-items-center">
                                    <span class="card-header-icon">
                                        <i class="tio-user"></i>
                                    </span>
                                                <span>{{ translate('deliveryman') }}</span>
                                                @if ($order->order_status != 'delivered')
                                                    <a type="button" href="#assign_delivey_man_modal" class="text--base cursor-pointer ml-auto text-sm"
                                                       data-toggle="modal" data-target="#assign_delivey_man_modal">
                                                        {{ translate('change') }}
                                                    </a>
                                                @endif
                                            </h5>
                                            <div class="media align-items-center deco-none customer--information-single">

                                                <div class="avatar avatar-circle">
                                                    <img class="avatar-img"
                                                         src="{{$order->delivery_man->imageFullPath }}"
                                                         alt="{{ translate('Image Description')}}">
                                                </div>
                                                <div class="media-body">
                                                    <a href="{{ route('admin.delivery-man.preview', [$order->delivery_man['id']]) }}">
                                                        <span class="text-body d-block text-hover-primary mb-1">{{ $order->delivery_man['f_name'] . ' ' . $order->delivery_man['l_name'] }}</span>
                                                    </a>

                                                    <span class="text--title font-semibold d-flex align-items-center">
                                                    <i class="tio-shopping-basket-outlined mr-2"></i>
                                                    {{\App\Model\Order::where(['delivery_man_id' => $order['delivery_man_id'], 'order_status' => 'delivered'])->count()}} {{ translate('orders_delivered') }}
                                                    </span>
                                                    <span class="text--title font-semibold d-flex align-items-center">
                                                       <i class="tio-call-talking-quiet mr-2"></i>
                                                        <a href="Tel:{{ $order->delivery_man['phone'] }}">{{ $order->delivery_man['phone'] }}</a>
                                                    </span>
                                                    <span class="text--title font-semibold d-flex align-items-center">
                                                        <i class="tio-email-outlined mr-2"></i>
                                                        <a href="mailto:{{$order->delivery_man['email']}}">{{$order->delivery_man['email']}}</a>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                        @endif


                            @if($order['order_type']!='self_pickup')
                                <hr>
                                @php($address=\App\Model\CustomerAddress::find($order['delivery_address_id']))

                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="card-title">
                                    <span class="card-header-icon">
                                        <i class="tio-user"></i>
                                    </span>
                                        <span>{{translate('delivery information')}}</span>
                                    </h5>
                                    @if(isset($address))
                                        <a class="link" data-toggle="modal" data-target="#shipping-address-modal"
                                           href="javascript:"><i class="tio-edit"></i></a>
                                    @endif
                                </div>

                                @if(isset($address))
                                    <div class="delivery--information-single flex-column mt-3">
                                        <div class="d-flex">
                                    <span class="name">
                                        {{translate('name')}}
                                    </span>
                                            <span class="info">{{$address['contact_person_name']}}</span>
                                        </div>
                                        <div class="d-flex">
                                            <span class="name">{{translate('phone')}}</span>
                                            <span class="info">{{ $address['contact_person_number']}}</span>
                                        </div>
                                        @if($address['road'])
                                            <div class="d-flex">
                                                <span class="name">{{translate('road')}}</span>
                                                <span class="info">#{{ $address['road']}}</span>
                                            </div>
                                        @endif
                                        @if($address['house'])
                                            <div class="d-flex">
                                                <span class="name">{{translate('house')}}</span>
                                                <span class="info">#{{ $address['house']}}</span>
                                            </div>
                                        @endif
                                        @if($address['floor'])
                                            <div class="d-flex">
                                                <span class="name">{{translate('floor')}}</span>
                                                <span class="info">#{{ $address['floor']}}</span>
                                            </div>
                                        @endif
                                        @if($address['address'])
                                            <div class="d-flex">
                                                <span class="name">{{translate('address')}}</span>
                                                <span class="info">#{{ $address['address']}}</span>
                                            </div>
                                        @endif
                                        @if($order->order_area)
                                            <div class="d-flex">
                                                <div class="name">{{translate('Area')}}</div>
                                                <div class="info edit-btn cursor-pointer">
                                                    {{ $order?->order_area?->area?->area_name }}
                                                    @if($order?->branch?->delivery_charge_setup?->delivery_charge_type == 'area')
                                                        <i class="tio-edit" data-toggle="modal" data-target="#editArea"></i>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif

                                        @php($googleMapStatus = \App\CentralLogics\Helpers::get_business_settings('google_map_status'))
                                        @if($googleMapStatus)
                                            @if(isset($address['address']) && isset($address['latitude']) && isset($address['longitude']))
                                                <hr class="w-100">
                                                <div>
                                                    <a target="_blank"
                                                       href="http://maps.google.com/maps?z=12&t=m&q=loc:{{$address['latitude']}}+{{$address['longitude']}}">
                                                        <i class="tio-poi"></i> {{$address['address']}}
                                                    </a>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                @endif
                            @endif
                    </div>
                </div>

                @endif
                @if($order->offline_payment)
                    @php($payment = json_decode($order->offline_payment?->payment_info, true))

                    <div class="card mt-2">
                        <div class="card-body">
                            <h5 class="form-label mb-3">
                                <span class="card-header-icon"><i class="tio-shopping-basket"></i></span>
                                <span>{{translate('Offline payment information')}}</span>
                            </h5>
                            <div class="offline-payment--information-single flex-column mt-3">
                                <div class="d-flex">
                                    <span class="name">{{ translate('payment_note') }}</span>
                                    <span class="info">{{ $payment['payment_note'] }}</span>
                                </div>
                                @foreach($payment['method_information'] as $infos)
                                    @foreach($infos as $infoKey => $info)
                                        <div class="d-flex">
                                            <span class="name">{{ $infoKey }}</span>
                                            <span class="info">{{ $info }}</span>
                                        </div>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                    <div class="card mt-2">
                        <div class="card-body">
                            <h5 class="form-label mb-3">
                            <span class="card-header-icon">
                            <i class="tio-user"></i>
                            </span>
                                <span>{{translate('Customer information')}}</span>
                            </h5>
                            @if($order->is_guest == 1)
                                <div class="media align-items-center deco-none customer--information-single">
                                    <div class="avatar avatar-circle">
                                        <img class="avatar-img" src="{{asset('public/assets/admin/img/admin.jpg')}}" alt="{{ translate('Image Description')}}">
                                    </div>
                                    <div class="media-body">
                                    <span class="fz--14px text--title font-semibold text-hover-primary d-block">
                                        {{translate('Guest Customer')}}
                                    </span>
                                    </div>
                                </div>
                            @else
                                @if($order->user_id == null)
                                    <div class="media align-items-center deco-none customer--information-single">
                                        <div class="avatar avatar-circle">
                                            <img class="avatar-img" src="{{asset('public/assets/admin/img/admin.jpg')}}" alt="{{ translate('Image Description')}}">
                                        </div>
                                        <div class="media-body">
                                    <span class="fz--14px text--title font-semibold text-hover-primary d-block">
                                        {{translate('Walking Customer')}}
                                    </span>
                                        </div>
                                    </div>
                                @endif
                                @if($order->user_id != null && !isset($order->customer) )
                                    <div class="media align-items-center deco-none customer--information-single">
                                        <div class="avatar avatar-circle">
                                            <img class="avatar-img" src="{{asset('public/assets/admin/img/admin.jpg')}}" alt="{{ translate('Image Description')}}">
                                        </div>
                                        <div class="media-body">
                                            <span class="fz--14px text--title font-semibold text-hover-primary d-block">
                                                {{translate('Customer_not_available')}}
                                            </span>
                                        </div>
                                    </div>
                                @endif
                                @if(isset($order->customer) )
                                    <div class="media align-items-center deco-none customer--information-single">
                                        <div class="avatar avatar-circle">
                                            <img class="avatar-img" src="{{$order->customer->imageFullPath}}" alt="{{ translate('Image Description')}}">
                                        </div>
                                        <div class="media-body">
                                    <span class="fz--14px text--title font-semibold text-hover-primary d-block">
                                        <a href="{{route('admin.customer.view',[$order['user_id']])}}">{{$order->customer['f_name'].' '.$order->customer['l_name']}}</a>
                                    </span>
                                            <span>{{\App\Model\Order::where('user_id',$order['user_id'])->count()}} {{translate("orders")}}</span>
                                            <span class="text--title font-semibold d-block">
                                <i class="tio-call-talking-quiet mr-2"></i>
                                <a href="Tel:{{$order->customer['phone']}}">{{$order->customer['phone']}}</a>
                                    </span>
                                            <span class="text--title">
                                <i class="tio-email mr-2"></i>
                                <a href="mailto:{{$order->customer['email']}}">{{$order->customer['email']}}</a>
                            </span>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>

                    @if($order->order_image && $order->order_image->isNotEmpty())
                        <div class="card mt-2">
                            <div class="card-body">
                                <h5 class="form-label mb-3">
                                    <span class="card-header-icon">
                                    <i class="tio-image"></i>
                                    </span>
                                    <span>{{translate('Order Image')}}</span>
                                </h5>
                                <div class="d-flex flex-wrap gap-2 align-items-center">
                                    @foreach($order->order_image as $orderImage)
                                        <a class="avatar m-1 w-75px h-auto" href="{{asset('storage/app/public/order/' . $orderImage->image)}}" data-lightbox>
                                            <img class="aspect-1 avatar-img object-cover" src="{{ asset('storage/app/public/order/' . $orderImage->image) }}" alt="{{ translate('Image Description')}}">
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                <div class="card mt-2">
                        <div class="card-body">
                            <h5 class="form-label mb-3">
                        <span class="card-header-icon">
                        <i class="tio-shop-outlined"></i>
                        </span>
                                <span>{{translate('Branch information')}}</span>
                            </h5>
                            <div class="media align-items-center deco-none resturant--information-single">
                                <div class="avatar avatar-circle">
                                    <img class="avatar-img w-75px" src="{{$order->branch?->imageFullPath}}" alt="{{ translate('Image Description')}}">
                                </div>
                                <div class="media-body">
                                    @if(isset($order->branch))
                                        <span class="fz--14px text--title font-semibold text-hover-primary d-block">
                                            {{$order->branch?->name}}
                                        </span>
                                        <span>{{\App\Model\Order::where('branch_id',$order['branch_id'])->count()}} {{translate('Orders')}}</span>
                                        <span class="text--title font-semibold d-block">
                                            <i class="tio-call-talking-quiet mr-2"></i>
                                            <a href="Tel:{{$order->branch?->phone}}">{{$order->branch?->phone}}</a>
                                        </span>
                                        <span class="text--title" >
                                            <i class="tio-email mr-2"></i>
                                            <a href="mailto:{{$order->branch?->email}}">{{$order->branch?->email}}</a>
                                        </span>
                                    @else
                                        <span class="fz--14px text--title font-semibold text-hover-primary d-block">{{translate('Branch Deleted')}}</span>
                                    @endif
                                </div>
                            </div>
                            @if(isset($order->branch))
                                @php($googleMapStatus = \App\CentralLogics\Helpers::get_business_settings('google_map_status'))
                                @if($googleMapStatus)
                                    <hr>
                                    <span class="d-block">
                                    <a target="_blank"
                                       href="http://maps.google.com/maps?z=12&t=m&q=loc:{{ $order->branch?->latitude}}+{{$order->branch?->longitude }}">
                                    <i class="tio-poi"></i> {{ $order->branch?->address}}
                                    </a>
                                </span>
                                @endif
                            @endif
                        </div>
                    </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h4"
                        id="mySmallModalLabel">{{translate('reference')}} {{translate('code')}} {{translate('add')}}</h5>
                    <button type="button" class="btn btn-xs btn-icon btn-ghost-secondary" data-dismiss="modal"
                            aria-label="Close">
                        <i class="tio-clear tio-lg"></i>
                    </button>
                </div>

                <form action="{{route('admin.orders.add-payment-ref-code',[$order['id']])}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" name="transaction_reference" class="form-control"
                                   placeholder="{{ translate('EX : Code123')}}" required>
                        </div>
                        <div class="btn--container justify-content-end">
                            <button type="button" class="btn btn-white" data-dismiss="modal">{{translate('close')}}</button>
                            <button class="btn btn--primary" type="submit">{{translate('submit')}}</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div id="shipping-address-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalTopCoverTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-top-cover bg-dark text-center">
                    <figure class="position-absolute right-0 bottom-0 left-0">
                        <svg preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
                             viewBox="0 0 1920 100.1">
                            <path fill="#fff" d="M0,0c0,0,934.4,93.4,1920,0v100.1H0L0,0z"/>
                        </svg>
                    </figure>

                    <div class="modal-close">
                        <button type="button" class="btn btn-icon btn-sm btn-ghost-light" data-dismiss="modal"
                                aria-label="Close">
                            <svg width="16" height="16" viewBox="0 0 18 18" xmlns="http://www.w3.org/2000/svg">
                                <path fill="currentColor"
                                      d="M11.5,9.5l5-5c0.2-0.2,0.2-0.6-0.1-0.9l-1-1c-0.3-0.3-0.7-0.3-0.9-0.1l-5,5l-5-5C4.3,2.3,3.9,2.4,3.6,2.6l-1,1 C2.4,3.9,2.3,4.3,2.5,4.5l5,5l-5,5c-0.2,0.2-0.2,0.6,0.1,0.9l1,1c0.3,0.3,0.7,0.3,0.9,0.1l5-5l5,5c0.2,0.2,0.6,0.2,0.9-0.1l1-1 c0.3-0.3,0.3-0.7,0.1-0.9L11.5,9.5z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="modal-top-cover-icon">
                    <span class="icon icon-lg icon-light icon-circle icon-centered shadow-soft">
                      <i class="tio-location-search"></i>
                    </span>
                </div>

                @php($address=\App\Model\CustomerAddress::find($order['delivery_address_id']))
                @if(isset($address))
                    <form action="{{route('admin.order.update-shipping',[$order['delivery_address_id']])}}"
                          method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    {{translate('type')}}
                                </label>
                                <div class="col-md-10 js-form-message">
                                    <input type="text" class="form-control" name="address_type"
                                           value="{{$address['address_type']}}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    {{translate('contact')}}
                                </label>
                                <div class="col-md-10 js-form-message">
                                    <input type="text" class="form-control" name="contact_person_number"
                                           value="{{$address['contact_person_number']}}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    {{translate('name')}}
                                </label>
                                <div class="col-md-10 js-form-message">
                                    <input type="text" class="form-control" name="contact_person_name"
                                           value="{{$address['contact_person_name']}}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    {{translate('address')}}
                                </label>
                                <div class="col-md-10 js-form-message">
                                    <input type="text" class="form-control" name="address" value="{{$address['address']}}" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    {{translate('road')}}
                                </label>
                                <div class="col-md-10 js-form-message">
                                    <input type="text" class="form-control" name="road" value="{{$address['road']}}">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    {{translate('house')}}
                                </label>
                                <div class="col-md-4 js-form-message">
                                    <input type="text" class="form-control" name="house" value="{{$address['house']}}">
                                </div>
                                <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                    {{translate('floor')}}
                                </label>
                                <div class="col-md-4 js-form-message">
                                    <input type="text" class="form-control" name="floor" value="{{$address['floor']}}">
                                </div>
                            </div>

                            @php($googleMapStatus = \App\CentralLogics\Helpers::get_business_settings('google_map_status'))
                            @if($googleMapStatus)
                                @if($order?->branch?->delivery_charge_setup?->delivery_charge_type == 'distance')
                                    <div class="row mb-3">
                                        <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                            {{translate('latitude')}}
                                        </label>
                                        <div class="col-md-4 js-form-message">
                                            <input type="text" class="form-control" name="latitude"
                                                   value="{{$address['latitude']}}"
                                                   required>
                                        </div>
                                        <label for="requiredLabel" class="col-md-2 col-form-label input-label text-md-right">
                                            {{translate('longitude')}}
                                        </label>
                                        <div class="col-md-4 js-form-message">
                                            <input type="text" class="form-control" name="longitude"
                                                   value="{{$address['longitude']}}" required>
                                        </div>
                                    </div>
                                @endif
                            @endif

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-white"
                                    data-dismiss="modal">{{translate('close')}}</button>
                            <button type="submit"
                                    class="btn btn-primary">{{translate('save')}} {{translate('changes')}}</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>


    <div class="modal fade" id="assign_delivey_man_modal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{translate('Assign Delivery Man')}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 my-2">
                            <ul class="list-group overflow-auto initial--23">
                                @foreach ($deliverymanList as $deliveryman)
                                    <li class="list-group-item">
                                        <span class="dm_list" role='button' data-id="{{ $deliveryman['id'] }}">
                                            <img class="avatar avatar-sm avatar-circle mr-1"
                                                 src="{{ $deliveryman->imageFullPath }}"
                                                 alt="{{ $deliveryman['f_name'] }}">
                                            {{ $deliveryman['f_name'] }} {{ $deliveryman['l_name'] }}
                                        </span>

                                        <a class="btn btn-primary btn-xs float-right assign-deliveryman" data-deliveryman-id="{{ $deliveryman['id'] }}">{{ translate('assign') }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($order->offline_payment)
        <div class="modal fade" id="payment_verify_modal">
            <div class="modal-dialog modal-lg offline-details">
                <div class="modal-content">
                    <div class="modal-header justify-content-center">
                        <h4 class="modal-title pb-2">{{translate('Payment_Verification')}}</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    </div>
                    <div class="card">
                        <div class="modal-body mx-2">
                            <p class="text-danger">{{translate('Please Check & Verify the payment information whether it is correct or not before confirm the order.')}}</p>
                            <h5>{{translate('customer_Information')}}</h5>

                            <div class="card-body">
                                @if($order->is_guest == 0)
                                    <p>{{ translate('name') }} : {{ $order->customer ? $order->customer->f_name.' '. $order->customer->l_name: ''}} </p>
                                    <p>{{ translate('contact') }} : {{ $order->customer ? $order->customer->phone: ''}}</p>
                                @else
                                    <p>{{ translate('guest_customer') }} </p>
                                @endif
                            </div>

                            <h5>{{translate('Payment_Information')}}</h5>
                            @php($payment = json_decode($order->offline_payment?->payment_info, true))
                            <div class="row card-body">
                                <div class="col-md-6">
                                    <p>{{ translate('Payment_Method') }} : {{ $payment['payment_name'] }}</p>
                                    @foreach($payment['method_fields'] as $fields)
                                        @foreach($fields as $fieldKey => $field)
                                            <p>{{ $fieldKey }} : {{ $field }}</p>
                                        @endforeach
                                    @endforeach
                                </div>
                                <div class="col-md-6">
                                    <p>{{ translate('payment_note') }} : {{ $payment['payment_note'] }}</p>
                                    @foreach($payment['method_information'] as $infos)
                                        @foreach($infos as $infoKey => $info)
                                            <p>{{ $infoKey }} : {{ $info }}</p>
                                        @endforeach
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btn--container justify-content-end my-2 mx-3">
                        <a type="reset" class="btn btn--reset verify-offline-payment" data-status="2">{{ translate('Payment_Did_Not_Received') }}</a>
                        <a type="submit" class="btn btn--primary verify-offline-payment" data-status="1">{{ translate('Yes,_Payment_Received') }}</a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="editArea" id="editArea"
         aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title h4" id="mySmallModalLabel">{{translate('Update_Delivery_Area')}}</h5>
                    <button type="button" class="btn btn-xs btn-icon btn-ghost-secondary" data-dismiss="modal" aria-label="Close">
                        <i class="tio-clear tio-lg"></i>
                    </button>
                </div>
                <form action="{{ route('admin.orders.update-order-delivery-area', ['order_id' => $order->id]) }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="row">

                            <?php
                            $branch = \App\Model\Branch::with(['delivery_charge_setup', 'delivery_charge_by_area'])
                                ->where(['id' => $order['branch_id']])
                                ->first(['id', 'name', 'status']);
                            ?>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>{{translate('Delivery Area')}}</label>
                                    <select name="selected_area_id" class="form-control js-select2-custom-x mx-1" id="areaDropdown" >
                                        <option value="">{{ translate('Select Area') }}</option>
                                        @foreach($branch->delivery_charge_by_area as $area)
                                            <option value="{{$area['id']}}" {{ (isset($order->order_area) && $order->order_area->area_id == $area['id']) ? 'selected' : '' }}
                                            data-charge="{{$area['delivery_charge']}}" >{{ $area['area_name'] }} - ({{ Helpers::set_symbol($area['delivery_charge']) }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="input-label" for="">{{ translate('Delivery Charge') }} ({{ Helpers::currency_symbol() }})</label>
                                <input type="number" class="form-control" name="delivery_charge" id="deliveryChargeInput" value="" readonly>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button class="btn btn-primary">{{translate('update')}}</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection

@push('script_2')
    <script src="{{ asset('public/assets/admin/js/flatpicker.js') }}"></script>

    <script>
        "use strict";

        $('.last-location-view').on('click', function () {
            last_location_view();
        })

        $('#verifyPaymentButton').on('click', function() {
            var orderId = $(this).data('id');
            get_offline_payment_data(orderId);
        });

        $('.manage-status').on('click', function(event) {
            event.preventDefault();
            var status = $(this).data('status');
            var order_status = $(this).data('order_status');
            if (status === 'pending' || status === 'confirmed' || status === 'packaging' || status === 'out_for_delivery' || status === 'delivered' || status === 'returned') {
                var message = '{{ translate("You can not change order status to this status. Please Check & Verify the payment information whether it is correct or not. You can only change order status to failed or cancel if payment is not verified.") }}';
                offline_payment_order_alert(message);
            } else {
                var route = $(this).attr('href');
                var confirmMessage = '{{ translate("Change status to ") }}' + order_status + ' ?';
                if(order_status == 'out_for_delivery'){
                    var confirmMessage = '{{ translate("Change status to out for delivery") }}' + ' ?';
                }
                route_alert(route, confirmMessage);
            }
        });

        $('.change-payment-status').on('click', function(event) {
            event.preventDefault();
            var status = $(this).data('status');
            var message = '{{ translate("Change status to") }} ' + status + ' ?';
            var route = $(this).data('route');
            console.log(status);
            console.log(message);
            console.log(route);
            route_alert(route, message);
        });

        $('.offline-payment').on('click', function(event) {
            event.preventDefault();
            var message = $(this).data('message');
            offline_payment_status_alert(message);
        });

        $('.assign-deliveryman').on('click', function(event) {
            event.preventDefault();
            var deliverymanId = $(this).data('deliveryman-id');
            addDeliveryMan(deliverymanId);
        });

        $('.verify-offline-payment').on('click', function(event) {
            event.preventDefault();
            var status = $(this).data('status');
            verify_offline_payment(status);
        });

        function offline_payment_order_alert(message) {
            Swal.fire({
                title: '{{translate("Payment_is_Not_Verified")}}',
                text: message,
                type: 'question',
                showCancelButton: true,
                showConfirmButton: false,
                cancelButtonColor: 'default',
                confirmButtonColor: '#01684b',
                cancelButtonText: '{{translate("Close")}}',
                confirmButtonText: '{{translate("Proceed")}}',
                reverseButtons: true
            }).then((result) => {

            })
        }

        function offline_payment_status_alert(message) {
            Swal.fire({
                title: '{{translate("Payment_is_Not_Verified")}}',
                text: message,
                type: 'question',
                showCancelButton: true,
                showConfirmButton: false,
                cancelButtonColor: 'default',
                confirmButtonColor: '#01684b',
                cancelButtonText: '{{translate("Close")}}',
                confirmButtonText: '',
                reverseButtons: true
            }).then((result) => {

            })
        }

        function addDeliveryMan(id) {
            $.ajax({
                type: "GET",
                url: '{{url('/')}}/admin/orders/add-delivery-man/{{$order['id']}}/' + id,
                data: $('#product_form').serialize(),
                success: function (data) {
                    //console.log(data);
                    location.reload();
                    if(data.status == true) {
                        toastr.success('{{ translate("Deliveryman successfully assigned/changed") }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }else{
                        toastr.error('{{ translate("Deliveryman man can not assign/change in that status") }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }

                },
                error: function () {
                    toastr.error('Add valid data', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            });
        }

        function verify_offline_payment(status) {
            $.ajax({
                type: "GET",
                url: '{{url('/')}}/admin/orders/verify-offline-payment/{{$order['id']}}/' + status,
                success: function (data) {
                    //console.log(data);
                    location.reload();
                    if(data.status == true) {
                        toastr.success('{{ translate("offline payment verify status changed") }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }else{
                        toastr.error('{{ translate("offline payment verify status not changed") }}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }

                },
                error: function () {
                }
            });
        }

        function last_location_view() {
            toastr.warning('{{ translate("Only available when order is out for delivery!") }}', {
                CloseButton: true,
                ProgressBar: true
            });
        }

        $(document).on('change', '#from_date', function () {
            var id = $(this).attr("data-id");
            console.log(id);
            var value = $(this).val();
            console.log(value);
            Swal.fire({
                title: '{{ translate("Are you sure Change this Delivery date?") }}',
                text: "{{ translate("You won't be able to revert this!") }}",
                showCancelButton: true,
                confirmButtonColor: '#01684b',
                cancelButtonColor: 'secondary',
                confirmButtonText: '{{ translate("Yes, Change it!") }}'
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.post({
                        url: "{{route('admin.order.update-deliveryDate')}}",

                        data: {
                            "id": id,
                            "deliveryDate": value
                        },

                        success: function (data) {
                            console.log(data);
                            toastr.success('Delivery Date Change successfully');
                            location.reload();
                        }
                    });
                }
            })
        });

        $(document).on('change', '.time_slote', function () {
            var id = $(this).attr("data-id");
            var value = $(this).val();
            Swal.fire({
                title: '{{ translate("Are you sure Change this?") }}',
                text: "{{ translate("You won't be able to revert this!") }}",
                showCancelButton: true,
                confirmButtonColor: '#01684b',
                cancelButtonColor: 'secondary',
                confirmButtonText: 'Yes, Change it!'
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.post({
                        url: "{{route('admin.order.update-timeSlot')}}",

                        data: {
                            "id": id,
                            "timeSlot": value
                        },

                        success: function (data) {
                            toastr.success('{{ translate("Time Slot Change successfully") }}');
                            location.reload();
                        }
                    });
                }
            })
        });

        $(document).on('ready', function () {
            $('.js-select2-custom').each(function () {
                var select2 = $.HSCore.components.HSSelect2.init($(this));
            });
        });
    </script>

    <script>
        var lightbox = function (o) {
            var s = void 0,
                c = void 0,
                u = void 0,
                d = void 0,
                i = void 0,
                p = void 0,
                m = document,
                e = m.body,
                l = "fadeIn .3s",
                v = "fadeOut .3s",
                t = "scaleIn .3s",
                f = "scaleOut .3s",
                a = "lightbox-btn",
                n = "lightbox-gallery",
                b = "lightbox-trigger",
                g = "lightbox-active-item",
                y = function () {
                    return e.classList.toggle("remove-scroll");
                },
                r = function (e) {
                    if (
                        ("A" === o.tagName && (e = e.getAttribute("href")),
                            e.match(/\.(jpeg|jpg|gif|png)/))
                    ) {
                        var t = m.createElement("img");
                        return (
                            (t.className = "lightbox-image"),
                                (t.src = e),
                            "A" === o.tagName &&
                            (t.alt = o.getAttribute("data-image-alt")),
                                t
                        );
                    }
                    if (e.match(/(youtube|vimeo)/)) {
                        var a = [];
                        return (
                            e.match("youtube") &&
                            ((a.id = e
                                .split(/v\/|v=|youtu\.be\//)[1]
                                .split(/[?&]/)[0]),
                                (a.url = "youtube.com/embed/"),
                                (a.options = "?autoplay=1&rel=0")),
                            e.match("vimeo") &&
                            ((a.id = e
                                .split(/video\/|https:\/\/vimeo\.com\//)[1]
                                .split(/[?&]/)[0]),
                                (a.url = "player.vimeo.com/video/"),
                                (a.options = "?autoplay=1title=0&byline=0&portrait=0")),
                                (a.player = m.createElement("iframe")),
                                a.player.setAttribute("allowfullscreen", ""),
                                (a.player.className = "lightbox-video-player"),
                                (a.player.src = "https://" + a.url + a.id + a.options),
                                (a.wrapper = m.createElement("div")),
                                (a.wrapper.className = "lightbox-video-wrapper"),
                                a.wrapper.appendChild(a.player),
                                a.wrapper
                        );
                    }
                    return m.querySelector(e).children[0].cloneNode(!0);
                },
                h = function (e) {
                    var t = {
                        next: e.parentElement.nextElementSibling,
                        previous: e.parentElement.previousElementSibling,
                    };
                    for (var a in t)
                        null !== t[a] && (t[a] = t[a].querySelector("[data-lightbox]"));
                    return t;
                },
                x = function (e) {
                    p.removeAttribute("style");
                    var t = h(u)[e];
                    if (null !== t)
                        for (var a in ((i.style.animation = v),
                            setTimeout(function () {
                                i.replaceChild(r(t), i.children[0]),
                                    (i.style.animation = l);
                            }, 200),
                            u.classList.remove(g),
                            t.classList.add(g),
                            (u = t),
                            c))
                            c.hasOwnProperty(a) && (c[a].disabled = !h(t)[a]);
                },
                E = function (e) {
                    var t = e.target,
                        a = e.keyCode,
                        i = e.type;
                    ((("click" == i && -1 !== [d, s].indexOf(t)) ||
                        ("keyup" == i && 27 == a)) &&
                    d.parentElement === o.parentElement &&
                    (N("remove"),
                        (d.style.animation = v),
                        (p.style.animation = [f, v]),
                        setTimeout(function () {
                            if ((y(), o.parentNode.removeChild(d), "A" === o.tagName)) {
                                u.classList.remove(g);
                                var e = m.querySelector("." + b);
                                e.classList.remove(b), e.focus();
                            }
                        }, 200)),
                        c) &&
                    ((("click" == i && t == c.next) || ("keyup" == i && 39 == a)) &&
                    x("next"),
                    (("click" == i && t == c.previous) ||
                        ("keyup" == i && 37 == a)) &&
                    x("previous"));
                    if ("keydown" == i && 9 == a) {
                        var l = ["[href]", "button", "input", "select", "textarea"];
                        l = l.map(function (e) {
                            return e + ":not([disabled])";
                        });
                        var n = (l = d.querySelectorAll(l.toString()))[0],
                            r = l[l.length - 1];
                        e.shiftKey
                            ? m.activeElement == n && (r.focus(), e.preventDefault())
                            : (m.activeElement == r && (n.focus(), e.preventDefault()),
                                r.addEventListener("blur", function () {
                                    r.disabled && (n.focus(), e.preventDefault());
                                }));
                    }
                },
                N = function (t) {
                    ["click", "keyup", "keydown"].forEach(function (e) {
                        "remove" !== t
                            ? m.addEventListener(e, function (e) {
                                return E(e);
                            })
                            : m.removeEventListener(e, function (e) {
                                return E(e);
                            });
                    });
                };
            !(function () {
                if (
                    ((s = m.createElement("button")).setAttribute(
                        "aria-label",
                        "Close"
                    ),
                        (s.className = a + " " + a + "-close"),
                        ((i = m.createElement("div")).className = "lightbox-content"),
                        i.appendChild(r(o)),
                        ((p = i.cloneNode(!1)).className = "lightbox-wrapper"),
                        (p.style.animation = [t, l]),
                        p.appendChild(s),
                        p.appendChild(i),
                        ((d = i.cloneNode(!1)).className = "lightbox-container"),
                        (d.style.animation = l),
                        (d.onclick = function () {}),
                        d.appendChild(p),
                    "A" === o.tagName && "gallery" === o.getAttribute("data-lightbox"))
                )
                    for (var e in (d.classList.add(n),
                        (c = { previous: "", next: "" })))
                        c.hasOwnProperty(e) &&
                        ((c[e] = s.cloneNode(!1)),
                            c[e].setAttribute("aria-label", e),
                            (c[e].className = a + " " + a + "-" + e),
                            (c[e].disabled = !h(o)[e]),
                            p.appendChild(c[e]));
                "A" === o.tagName &&
                (o.blur(), (u = o).classList.add(g), o.classList.add(b)),
                    o.parentNode.insertBefore(d, o.nextSibling),
                    y();
            })(),
                N();
        };

        Array.prototype.forEach.call(
            document.querySelectorAll("[data-lightbox]"),
            function (t) {
                t.addEventListener("click", function (e) {
                    e.preventDefault(), new lightbox(t);
                });
            }
        );

        $(document).ready(function() {
            const $areaDropdown = $('#areaDropdown');
            const $deliveryChargeInput = $('#deliveryChargeInput');

            $areaDropdown.change(function() {
                const selectedOption = $(this).find('option:selected');
                const charge = selectedOption.data('charge');
                $deliveryChargeInput.val(charge);
            });
        });

    </script>

@endpush
