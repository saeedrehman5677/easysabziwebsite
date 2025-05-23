@extends('layouts.admin.app')

@section('title', translate('Order List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{asset('public/assets/admin/img/empty-cart.png')}}" class="w--20" alt="">
                </span>
                <span>
                    {{translate('pos')}} {{translate('orders')}} <span class="badge badge-pill badge-soft-secondary ml-2">{{ $orders->total() }}</span>
                </span>
            </h1>
        </div>

        <div class="card">
            <div class="card-header shadow flex-wrap p-20px border-0">
                <h5 class="form-bold w-100 mb-3">{{ translate('Select Date Range') }}</h5>
                <form class="w-100">
                    <div class="row g-3 g-sm-4 g-md-3 g-lg-4">
                        <div class="col-sm-6 col-md-4 col-lg-2">
                            <select class="custom-select custom-select-sm text-capitalize min-h-45px" name="branch_id">
                                <option disabled>--- {{translate('select')}} {{translate('branch')}} ---</option>
                                <option value="all" {{ $branchId == 'all' ? 'selected': ''}}>{{translate('all')}} {{translate('branch')}}</option>
                                @foreach($branches as $branch)
                                    <option value="{{$branch['id']}}" {{ $branch['id'] == $branchId ? 'selected' : ''}}>{{$branch['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="input-date-group">
                                <label class="input-label" for="start_date">{{ translate('Start Date') }}</label>
                                <label class="input-date">
                                    <input type="text" id="start_date" name="start_date" value="{{$startDate}}" class="js-flatpickr form-control flatpickr-custom min-h-45px" placeholder="yy-mm-dd" data-hs-flatpickr-options='{ "dateFormat": "Y-m-d"}'>
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="input-date-group">
                                <label class="input-label" for="end_date">{{ translate('End Date') }}</label>
                                <label class="input-date">
                                    <input type="text" id="end_date" name="end_date" value="{{$endDate}}" class="js-flatpickr form-control flatpickr-custom min-h-45px" placeholder="yy-mm-dd" data-hs-flatpickr-options='{ "dateFormat": "Y-m-d"}'>
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-12 col-lg-4 __btn-row">
                            <a href="{{ route('admin.pos.orders') }}" id="" class="btn w-100 btn--reset min-h-45px">{{translate('clear')}}</a>
                            <button type="submit" id="show_filter_data" class="btn w-100 btn--primary min-h-45px">{{translate('show data')}}</button>
                        </div>
                    </div>
                </form>
            </div>


            <div class="card-body p-20px">
                <div class="order-top">
                    <div class="card--header">
                        <form action="{{url()->current()}}" method="GET">
                            <div class="input-group">
                                <input id="datatableSearch_" type="search" name="search"
                                        class="form-control"
                                        placeholder="{{translate('Search by ID, order or payment status')}}" aria-label="Search"
                                        value="{{$search}}" required autocomplete="off">
                                <div class="input-group-append">
                                    <button type="submit" class="input-group-text">
                                        {{translate('Search')}}
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="hs-unfold mr-2">
                            <a class="js-hs-unfold-invoker btn btn-sm btn-outline-primary-2 dropdown-toggle min-height-40" href="javascript:;"
                                data-hs-unfold-options='{
                                        "target": "#usersExportDropdown",
                                        "type": "css-animation"
                                    }'>
                                <i class="tio-download-to mr-1"></i> {{ translate('export') }}
                            </a>

                            <div id="usersExportDropdown"
                                class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-sm-right">
                                <span class="dropdown-header">{{ translate('download') }}
                                    {{ translate('options') }}</span>
                                <a id="export-excel" class="dropdown-item" href="{{route('admin.pos.orders.export', ['branch_id'=>Request::get('branch_id'), 'start_date'=>Request::get('start_date'), 'end_date'=>Request::get('end_date'), 'search'=>Request::get('search')])}}">
                                    <img class="avatar avatar-xss avatar-4by3 mr-2"
                                        src="{{ asset('public/assets/admin') }}/svg/components/excel.svg"
                                        alt="Image Description">
                                    {{ translate('excel') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive datatable-custom">
                    <table class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                        <thead class="thead-light">
                        <tr>
                            <th>
                                {{translate('#')}}
                            </th>
                            <th>{{translate('order id')}}</th>
                            <th>{{translate('order date')}}</th>
                            <th>{{translate('customer info')}}</th>
                            <th>{{translate('Branch')}}</th>
                            <th>{{translate('total amount')}}</th>
                            <th class="text-center">{{translate('order')}} {{translate('status')}}</th>
                            <th class="text-center">{{translate('order')}} {{translate('type')}}</th>
                            <th class="text-center">{{translate('actions')}}</th>
                        </tr>
                        </thead>

                        <tbody id="set-rows">
                        @foreach($orders as $key=>$order)
                            <tr class="status-{{$order['order_status']}} class-all">
                                <td class="">
                                    {{$key+$orders->firstItem()}}
                                </td>
                                <td>
                                    <a href="{{route('admin.pos.order-details',['id'=>$order['id']])}}">{{$order['id']}}</a>
                                </td>
                                <td>{{date('d M Y',strtotime($order['created_at']))}}</td>

                                <td>
                                    @if(isset($order->customer))
                                        <div>
                                            <a class="text-body text-capitalize font-medium"
                                               href="{{route('admin.customer.view',[$order['user_id']])}}">{{$order->customer['f_name'].' '.$order->customer['l_name']}}</a>
                                        </div>
                                        <div class="text-sm">
                                            <a href="Tel:{{$order->customer['phone']}}">{{$order->customer['phone']}}</a>
                                        </div>
                                    @elseif($order->user_id != null && !isset($order->customer))
                                        <label
                                            class="text-danger">{{translate('Customer_not_available')}}
                                        </label>
                                    @else
                                        <label
                                            class="text-success">{{translate('Walking Customer')}}
                                        </label>
                                    @endif
                                </td>
                                <td>
                                    <label class="badge badge-soft-primary">{{$order->branch?$order->branch->name: translate('Branch deleted!')}}</label>
                                </td>
                                <td>
                                    <div class="mw-85">
                                        <div>
                                                <?php
                                                $vatStatus = $order->details[0] ? $order->details[0]->vat_status : '';
                                                if($vatStatus == 'included'){
                                                    $orderAmount = $order['order_amount'] - $order['total_tax_amount'];
                                                }else{
                                                    $orderAmount = $order['order_amount'];
                                                }
                                                ?>
                                            {{ Helpers::set_symbol($orderAmount) }}
                                        </div>
                                        @if($order->payment_status=='paid')
                                            <span class="text-success">
                                                {{translate('paid')}}
                                            </span>
                                        @else
                                            <span class="text-danger">
                                                {{translate('unpaid')}}
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-capitalize text-center">
                                    @if($order['order_status']=='pending')
                                        <span class="badge badge-soft-info">
                                            {{translate('pending')}}
                                        </span>
                                    @elseif($order['order_status']=='confirmed')
                                        <span class="badge badge-soft-info">
                                        {{translate('confirmed')}}
                                        </span>
                                    @elseif($order['order_status']=='processing')
                                        <span class="badge badge-soft-warning">
                                        {{translate('packaging')}}
                                        </span>
                                    @elseif($order['order_status']=='picked_up')
                                        <span class="badge badge-soft-warning">
                                        {{translate('out_for_delivery')}}
                                        </span>
                                    @elseif($order['order_status']=='delivered')
                                        <span class="badge badge-soft-success">
                                        {{translate('delivered')}}
                                        </span>
                                    @else
                                        <span class="badge badge-soft-danger">
                                        {{ translate(str_replace('_',' ',$order['order_status'])) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="text-capitalize text-center">
                                    @if($order['order_type']=='take_away')
                                        <span class="badge badge-soft-info">
                                            {{translate('take_away')}}
                                        </span>
                                    @elseif($order['order_type']=='pos')
                                        <span class="badge badge-soft-info">
                                        {{translate('POS')}}
                                    </span>
                                    @else
                                        <span class="badge badge-soft-success">
                                        {{translate('delivery')}}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn--container justify-content-center">
                                        <a class="action-btn btn--primary btn-outline-primary"
                                           href="{{route('admin.pos.order-details',['id'=>$order['id']])}}"><i
                                                class="tio-visible-outlined"></i></a>
                                        <button class="action-btn btn-outline-primary-2 print-invoice-btn" data-order-id="{{ $order->id }}" target="_blank" type="button"><i
                                                class="tio-print"></i></button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @if(count($orders) == 0)
                <div class="text-center p-4">
                    <img class="w-120px mb-3" src="{{asset('/public/assets/admin/svg/illustrations/sorry.svg')}}" alt="{{translate('Image Description')}}">
                    <p class="mb-0">{{translate('No_data_to_show')}}</p>
                </div>
                @endif
                <div class="card-footer px-0">
                    {!! $orders->links() !!}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="print-invoice" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{translate('print')}} {{translate('invoice')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <input type="button" class="btn btn-primary non-printable print-button"
                            value="{{translate('Proceed, If thermal printer is ready.')}}"/>
                        <a href="{{url()->previous()}}" class="btn btn-danger non-printable">Back</a>
                    </div>
                    <hr class="non-printable">
                    <div class="row m-auto" id="printableArea">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_2')
    <script src="{{asset('public/assets/admin/js/flatpicker.js')}}"></script>
    <script src="{{asset('public/assets/admin/js/order.js')}}"></script>
    <script>
        "use strict";

        $('.print-invoice-btn').on('click', function() {
            var orderId = $(this).data('order-id');
            print_invoice(orderId);
        });

        function print_invoice(order_id) {
            $.get({
                url: '{{url('/')}}/admin/pos/invoice/'+order_id,
                dataType: 'json',
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('#print-invoice').modal('show');
                    $('#printableArea').empty().html(data.view);
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        }
    </script>
@endpush
