@php($logo=\App\Model\BusinessSetting::where(['key'=>'logo'])->first()->value)
<div class="initial-38-1">
    <div class="pt-3">
        <img src="{{ App\CentralLogics\Helpers::onErrorImage($logo, asset('storage/app/public/restaurant') . '/' . $logo, asset('/public/assets/admin/img/food.png'), 'restaurant/')}}"
             class="initial-38-2" alt="{{ translate('logo') }}">
    </div>
    <div class="text-center pt-2 mb-3">
        <h2  class="initial-38-3">{{ $order->branch->name }}</h2>
        <h5 class="text-break initial-38-4">
            {{ $order->branch->address }}
        </h5>
        <h5 class="initial-38-4 initial-38-3">
            {{ translate('Phone') }} : {{\App\Model\BusinessSetting::where(['key'=>'phone'])->first()->value}}
        </h5>
        @if ($order->branch->gst_status)
            <h5 class="initial-38-4 initial-38-3 fz-12px">
                {{ translate('Gst No') }} : {{ $order->branch->gst_code }}
            </h5>
        @endif
    </div>
    <span class="initial-38-5">---------------------------------------------------------------------------------</span>
    <div class="row mt-3">
        <div class="col-6">
            <h5>{{ translate('Order ID') }} :
                <span class="font-light"> {{$order['id']}}</span>
            </h5>
        </div>
        <div class="col-6">
            <h5>
                <span class="font-light">
                {{date('d M Y h:i a',strtotime($order['created_at']))}}
                </span>
            </h5>
        </div>
        <div class="col-12">
            @if(isset($order->customer))
                <h5>
                    {{ translate('Customer Name') }} :
                    <span class="font-light">
                        {{$order->customer['f_name'].' '.$order->customer['l_name']}}
                    </span>
                </h5>
                <h5>
                    {{ translate('phone') }} :
                    <span class="font-light">
                        {{$order->customer['phone']}}
                    </span>
                </h5>
                @if($order->order_type != 'pos')
                    @php($address=\App\Model\CustomerAddress::find($order['delivery_address_id']))
                    <h5 class="text-break">{{ translate('address') }} :
                        <span class="font-light">{{isset($address)?$address['address']:''}}</span>
                    </h5>
                @endif
            @endif
        </div>
    </div>
    <h5 class="text-uppercase"></h5>
    <span class="initial-38-5">---------------------------------------------------------------------------------</span>
    <table class="table table-bordered mt-3">
        <thead>
        <tr>
            <th class="initial-38-6 border-top-0 border-bottom-0">{{ translate('QTY') }}</th>
            <th class="initial-38-7 border-top-0 border-bottom-0">{{ translate('DESC') }}</th>
            <th class="initial-38-7 border-top-0 border-bottom-0">{{ translate('Price') }}</th>
        </tr>
        </thead>

        <tbody>
        @php($subTotal=0)
        @php($totalTax=0)
        @php($total_dis_on_pro=0)
        @php($updatedTotalTax=0)
        @php($vatStatus = '')
        @foreach($order->details as $detail)

            @if($detail->product_details !=null)
                @php($product = json_decode($detail->product_details, true))
                <tr>
                    <td>
                        {{$detail['quantity']}}
                    </td>
                    <td class="">
                        {{$product['name']}} <br>
                        @if(count(json_decode($detail['variation'],true))>0)
                            <strong><u>Variation : </u></strong>
                            @foreach(json_decode($detail['variation'],true)[0] ?? json_decode($detail['variation'],true) as $key1 =>$variation)
                                <div class="font-size-sm text-body">
                                    <span class="text-capitalize">{{$key1}} :  </span>
                                    <span class="font-weight-bold">{{$variation}} {{$key1=='price'?\App\CentralLogics\Helpers::currency_symbol():''}}</span>
                                </div>
                            @endforeach
                        @endif
                        <span>{{ translate('Unit Price') }} : {{ Helpers::set_symbol($detail['price']) }}</span><br>
                        <span>{{ translate('Qty') }} : {{ $detail['quantity']}}</span><br>
                        <span>{{ translate('Discount') }} : {{ Helpers::set_symbol($detail['discount_on_product']) }}</span>

                    </td>
                    <td class="w-28p">
                        @php($amount=($detail['price']-$detail['discount_on_product'])*$detail['quantity'])
                        {{ Helpers::set_symbol($amount) }}
                    </td>
                </tr>

                @php($subTotal+=$amount)
                @php($totalTax+=$detail['tax_amount']*$detail['quantity'])
                @php($updatedTotalTax+= $detail['vat_status'] === 'included' ? 0 : $detail['tax_amount']*$detail['quantity'])
                @php($vatStatus = $detail['vat_status'])
            @endif

        @endforeach
        </tbody>
    </table>
    <div class="px-3">
        <dl class="row text-right justify-content-center">
            <dt class="col-6">{{ translate('Items Price') }}:</dt>
            <dd class="col-6">{{ Helpers::set_symbol($subTotal) }}</dd>
            <dt class="col-6">{{translate('Tax / VAT')}} {{ $vatStatus == 'included' ? translate('(included)') : '' }}:</dt>
            <dd class="col-6">{{ Helpers::set_symbol($totalTax) }}</dd>

            <dt class="col-6">{{ translate('Subtotal') }}:</dt>
            <dd class="col-6">
                {{ Helpers::set_symbol($subTotal+$updatedTotalTax) }}</dd>
            <dt class="col-6">{{ translate('Coupon Discount') }}:</dt>
            <dd class="col-6">
                - {{ Helpers::set_symbol($order['coupon_discount_amount']) }}</dd>
            <dt class="col-sm-6">{{translate('extra Discount')}}:</dt>
            <dd class="col-sm-6">
                - {{ Helpers::set_symbol($order['extra_discount']) }}</dd>
            <dt class="col-6">{{ translate('Delivery Fee') }}:</dt>
            <dd class="col-6">
                @if($order['order_type']=='take_away')
                    @php($deliveryCharge=0)
                @else
                    @php($deliveryCharge=$order['delivery_charge'])
                @endif
                {{ Helpers::set_symbol($deliveryCharge+$order['weight_charge_amount']) }}
                <hr>
            </dd>

            <dt class="col-6 font-20px">{{ translate('Total') }}:</dt>
            <dd class="col-6 font-20px">{{ Helpers::set_symbol($subTotal+$deliveryCharge+$updatedTotalTax-$order['coupon_discount_amount']-$order['extra_discount']+$order['weight_charge_amount']) }}</dd>
        </dl>
        <span class="initial-38-5">---------------------------------------------------------------------------------</span>
        <h5 class="text-center pt-1">
            <span class="d-block">"""{{ translate('THANK YOU') }}"""</span>
        </h5>
        <span class="initial-38-5">---------------------------------------------------------------------------------</span>
        <span class="d-block text-center">{{ $footer_text = \App\Model\BusinessSetting::where(['key' => 'footer_text'])->first()->value }}</span>
    </div>
</div>
