@extends('layouts.admin.app')

@section('title', translate('new sale'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
        <div class="content container-fluid">
            <div class="d-flex flex-wrap">
                <div class="order--pos-left">
                    <div class="card">
                        <div class="card-header m-1 bg-light border-0">
                            <h5 class="card-title">
                                <span>
                                {{translate('Product section')}}
                            </span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4 g-3">
                                <div class="col-sm-6">
                                    <div class="input-group header-item">
                                        <select name="category" id="category" class="form-control js-select2-custom mx-1" title="{{ translate('select category') }}">
                                            <option value="">{{ translate('All Categories') }}</option>
                                            @foreach ($categories as $item)
                                                <option value="{{ $item->id }}" {{ $category == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <form id="search-form">
                                        <div class="input-group input-group-merge input-group-flush">
                                            <div class="input-group-prepend w--30 justify-content-center">
                                                <div class="input-group-text">
                                                    <i class="tio-search"></i>
                                                </div>
                                            </div>
                                            <input id="datatableSearch" type="search" value="{{$keyword?$keyword:''}}" name="search"
                                                class="form-control rounded border"
                                                placeholder="{{translate('Search by product name')}}"
                                                aria-label="Search here">
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div id="items">
                                <div class="row g-1">
                                    @foreach($products as $product)
                                        <div class="order--item-box item-box">
                                            @include('admin-views.pos._single_product',['product'=>$product])
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="pt-4">
                                {!!$products->withQueryString()->links()!!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="order--pos-right">
                    <div class="card">
                        <div class="card-header bg-light border-0 m-1">
                            <h5 class="card-title">
                                <span>
                                    {{translate('Billing section')}}
                                </span>
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="px-4">
                                <div class="w-100">
                                    <div class="d-flex flex-wrap flex-row py-2 add--customer-btn">
                                        <select id='customer' name="customer_id" data-placeholder="{{ translate('Walk In Customer') }}" class="js-data-example-ajax form-control m-1">
                                            <option value="" selected disabled>{{ translate('select customer') }}</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user['id'] }}" {{ session('customer_id') == $user['id'] ? 'selected' : '' }}>{{ $user['f_name'] . ' ' . $user['l_name'] }}</option>
                                            @endforeach
                                        </select>
                                        <button class="btn btn--primary rounded font-regular" data-toggle="modal" data-target="#add-customer" type="button">{{translate('Add New Customer')}}</button>
                                    </div>
                                </div>
                                <div class="w-100 py-2">
                                    <h5>{{translate('Select Branch')}}</h5>
                                    <select id="branch" name="branch_id" class="js-data-example-ajax-2 form-control">
                                        @foreach($branches as $branch)
                                            <option value="{{ $branch['id'] }}" {{ session('branch_id') == $branch['id'] ? 'selected' : '' }}>{{ $branch['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="w-100 py-2">
                                    <div class="form-group">
                                        <label class="input-label font-weight-semibold fz-16 text-dark">{{translate('Select Order Type')}}</label>
                                        <div>
                                            <div class="form-control d-flex flex-column-3">
                                                <label class="custom-radio d-flex gap-2 align-items-center m-0">
                                                    <input type="radio" class="order-type-radio" name="order_type" value="take_away" {{ !session()->has('order_type') || session()->get('order_type') == 'take_away' ? 'checked' : '' }}>
                                                    <span class="media align-items-center mb-0">
                                                        <span class="media-body ml-1">{{ translate('Take Away') }}</span>
                                                    </span>
                                                </label>
                                                <label class="custom-radio d-flex gap-2 align-items-center m-0 ml-3">
                                                    <input type="radio" class="order-type-radio" name="order_type" value="home_delivery" {{ session()->has('order_type') && session()->get('order_type') == 'home_delivery' ? 'checked' : '' }}>
                                                    <span class="media align-items-center mb-0">
                                                        <span class="media-body ml-1">{{ translate('Home Delivery') }}</span>
                                                    </span>
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="w-100 py-2">
                                    <div class="form-group d-none" id="home_delivery_section">
                                        <div class="d-flex justify-content-between">
                                            <label for="" class="font-weight-semibold fz-16 text-dark">{{translate('Delivery Information')}}
                                                <small>({{ translate('Home Delivery') }})</small>
                                            </label>
                                            <span class="edit-btn cursor-pointer" id="delivery_address" data-toggle="modal"
                                                  data-target="#AddressModal"><i class="tio-edit"></i>
                                        </span>
                                        </div>
                                        <div class="pos--delivery-options-info d-flex flex-wrap" id="del-add">
                                            @include('admin-views.pos._address')
                                        </div>
                                    </div>
                                </div>
                                <div class='w-100' id="cart">
                                    @include('admin-views.pos._cart')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="quick-view" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content" id="quick-view-modal">

                </div>
            </div>
        </div>

    @php($order=\App\Model\Order::find(session('last_order')))
    @if($order)
        @php(session(['last_order'=> false]))
        <div class="modal fade" id="print-invoice" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{translate('Print Invoice')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="text-center">
                            <input type="button" class="btn btn-primary non-printable print-button"
                                    value="{{ translate('Proceed, If thermal printer is ready.') }}"/>
                            <a href="{{url()->previous()}}"
                                class="btn btn-danger non-printable">{{translate('Back')}}</a>
                        </div>
                        <hr class="non-printable">
                        <div id="printableArea">
                            @include('admin-views.pos.order.invoice')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

        @include('admin-views.pos.add-customer-modal')

        <div class="modal fade" id="AddressModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-light border-bottom py-3">
                        <h5 class="modal-title flex-grow-1 text-center">{{ translate('Delivery Information') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <?php
                        if(session()->has('address')) {
                            $old = session()->get('address');
                        }else {
                            $old = null;
                        }
                        ?>
                        <form id='delivery_address_store'>
                            @csrf

                            <div class="row g-2" id="delivery_address">
                                <div class="col-md-6">
                                    <label class="input-label" for="">{{ translate('contact_person_name') }}
                                        <span class="input-label-secondary text-danger">*</span></label>
                                    <input type="text" class="form-control" name="contact_person_name"
                                           value="{{ $old ? $old['contact_person_name'] : '' }}" placeholder="{{ translate('Ex :') }} {{ translate('Jhon')}}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="input-label" for="">{{ translate('Contact Number') }}
                                        <span class="input-label-secondary text-danger">*</span></label>
                                    <input type="tel" class="form-control" name="contact_person_number"
                                           value="{{ $old ? $old['contact_person_number'] : '' }}"  placeholder="{{ translate('Ex :') }} +3264124565" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="input-label" for="">{{ translate('Road') }}</label>
                                    <input type="text" class="form-control" name="road" value="{{ $old ? $old['road'] : '' }}"  placeholder="{{ translate('Ex :') }} {{ translate('4th')}}">
                                </div>
                                <div class="col-md-4">
                                    <label class="input-label" for="">{{ translate('House') }}</label>
                                    <input type="text" class="form-control" name="house" value="{{ $old ? $old['house'] : '' }}" placeholder="{{ translate('Ex :') }} {{ translate('45/C')}}">
                                </div>
                                <div class="col-md-4">
                                    <label class="input-label" for="">{{ translate('Floor') }}</label>
                                    <input type="text" class="form-control" name="floor" value="{{ $old ? $old['floor'] : '' }}"  placeholder="{{ translate('Ex :') }} {{ translate('1A')}}">
                                </div>
                                <div class="col-md-12">
                                    <label class="input-label">{{ translate('address') }}</label>
                                    <textarea name="address" id="address" class="form-control" required>{{ $old ? $old['address'] : '' }}</textarea>
                                </div>

                                <?php
                                    $branchId =(int) session('branch_id') ?? 1;
                                    $branch = \App\Model\Branch::with(['delivery_charge_setup', 'delivery_charge_by_area'])
                                        ->where(['id' => $branchId])
                                        ->first(['id', 'name', 'status']);

                                    $deliveryType = $branch->delivery_charge_setup->delivery_charge_type ?? 'fixed';
                                    $deliveryType = $deliveryType == 'area' ? 'area' : ($deliveryType == 'distance' ? 'distance' : 'fixed');

                                    if (isset($branch->delivery_charge_setup) && $branch->delivery_charge_setup->delivery_charge_type == 'distance') {
                                        unset($branch->delivery_charge_by_area);
                                        $branch->delivery_charge_by_area = [];
                                    }
                                ?>

                                @php($googleMapStatus = \App\CentralLogics\Helpers::get_business_settings('google_map_status'))
                                @if($googleMapStatus)
                                    @if($deliveryType == 'distance')
                                        <div class="col-md-6">
                                            <label class="input-label" for="">{{ translate('longitude') }}<span
                                                    class="input-label-secondary text-danger">*</span></label>
                                            <input type="text" class="form-control" id="longitude" name="longitude"
                                                   value="{{ $old ? $old['longitude'] : '' }}" readonly required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="input-label" for="">{{ translate('latitude') }}<span
                                                    class="input-label-secondary text-danger">*</span></label>
                                            <input type="text" class="form-control" id="latitude" name="latitude"
                                                   value="{{ $old ? $old['latitude'] : '' }}" readonly required>
                                        </div>

                                        <div class="col-12">
                                            <div class="d-flex justify-content-between">
                                        <span class="text-primary">
                                            {{ translate('* pin the address in the map to calculate delivery fee') }}
                                        </span>
                                            </div>
                                            <div id="location_map_div">
                                                <input id="pac-input" class="controls rounded initial-8"
                                                       title="{{ translate('search_your_location_here') }}" type="text"
                                                       placeholder="{{ translate('search_here') }}" />
                                                <div id="location_map_canvas" class="overflow-hidden rounded"></div>
                                            </div>
                                        </div>
                                    @endif
                                @endif

                                @if($deliveryType == 'area')
                                    <div class="col-md-6">
                                        <label class="input-label">{{ translate('Delivery Area') }}</label>
                                        <select name="selected_area_id" class="form-control js-select2-custom-x mx-1" id="areaDropdown" >
                                            <option value="">{{ translate('Select Area') }}</option>
                                            @foreach($branch->delivery_charge_by_area as $area)
                                                <option value="{{$area['id']}}" {{ (isset($old) && $old['area_id'] == $area['id']) ? 'selected' : '' }} data-charge="{{$area['delivery_charge']}}" >{{ $area['area_name'] }} - ({{ Helpers::set_symbol($area['delivery_charge']) }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="input-label" for="">{{ translate('Delivery Charge') }} ({{ Helpers::currency_symbol() }})</label>
                                        <input type="number" class="form-control" name="delivery_charge" id="deliveryChargeInput" value="" readonly>
                                    </div>
                                @endif

                            </div>
                            <div class="col-md-12 mt-2">
                                <div class="btn--container justify-content-end">
                                    <button class="btn btn-sm btn--primary w-100 delivery-address-update-button" type="button" data-dismiss="modal">
                                        {{ translate('Update') }} {{ translate('Delivery address') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
@endsection


@push('script_2')
    <script src="https://maps.googleapis.com/maps/api/js?key={{ \App\Model\BusinessSetting::where('key', 'map_api_client_key')->first()?->value }}&libraries=places&v=3.51"></script>
    <script>

        $('#category').on('change', function() {
            var selectedCategoryId = $(this).val();
            set_category_filter(selectedCategoryId);
        });

        $('#customer').on('change', function() {
            var selectedCustomerId = $(this).val();
            store_key('customer_id', selectedCustomerId);
        });

        $('#branch').on('change', function() {
            var selectedBranchId = $(this).val();
            // store_key('branch_id', selectedBranchId);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': "{{csrf_token()}}"
                }
            });
            $.post({
                url: '{{route('admin.pos.store-keys')}}',
                data: {
                    key: 'branch_id',
                    value: selectedBranchId,
                },
                success: function (data) {
                    toastr.success('{{translate('Branch Selected')}}!', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                    location.reload();
                },
            });
        });

        $('.order-type-radio').on('change', function() {
            var orderType = $(this).val();
            select_order_type(orderType);
        });

        $('.delivery-address-update-button').on('click', function() {
            deliveryAddressStore();
        });

        $('.quick-view-trigger').on('click', function() {
            var productId = $(this).data('product-id');
            quickView(productId);
        });

        $(document).on('ready', function () {
            @if($order)
            $('#print-invoice').modal('show');
            @endif
        });

        $('.print-button').on('click', function() {
            printDiv('printableArea');
        });

        function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        }

        function set_category_filter(id) {
            var nurl = new URL('{!!url()->full()!!}');
            nurl.searchParams.set('category_id', id);
            location.href = nurl;
        }

        $('#search-form').on('submit', function (e) {
            e.preventDefault();
            var keyword = $('#datatableSearch').val();
            var nurl = new URL('{!!url()->full()!!}');
            nurl.searchParams.set('keyword', keyword);
            location.href = nurl;
        });

        function quickView(product_id) {
            $.ajax({
                url: '{{route('admin.pos.quick-view')}}',
                type: 'GET',
                data: {
                    product_id: product_id
                },
                dataType: 'json',
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('#quick-view').modal('show');
                    $('#quick-view-modal').empty().html(data.view);
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        }

        function checkAddToCartValidity() {
            return true;
        }

        function cartQuantityInitialize() {
            $('.btn-number').click(function (e) {
            e.preventDefault();

            var fieldName = $(this).attr('data-field');
            var type = $(this).attr('data-type');
            var input = $("input[name='" + fieldName + "']");
            var currentVal = parseInt(input.val());

            if (!isNaN(currentVal)) {
                if (type == 'minus') {

                    if (currentVal > input.attr('min')) {
                        input.val(currentVal - 1).change();
                    }
                    if (parseInt(input.val()) == input.attr('min')) {
                        $(this).attr('disabled', true);
                    }

                } else if (type == 'plus') {

                    if (currentVal < input.attr('max')) {
                        input.val(currentVal + 1).change();
                    }

                    var qty_max_val = parseInt($('#check_max_qty').val());
                    var qty_max_val = qty_max_val + 1;
                    if (parseInt(input.val()) >= qty_max_val) {
                        Swal.fire({
                            icon: 'error',
                            title: '{{translate("Cart")}}',
                            text: '{{translate('stock limit exceeded')}}.',
                            confirmButtonText: '{{translate("Yes")}}',
                        });
                        input.val(qty_max_val-1);
                    }

                }
            } else {
                input.val(0);
            }
        });

        $('.input-number').focusin(function () {
            $(this).data('oldValue', $(this).val());
        });

        $('.input-number').change(function () {

            minValue = parseInt($(this).attr('min'));
            maxValue = parseInt($(this).attr('max'));
            valueCurrent = parseInt($(this).val());

            var input_qty_max_val = parseInt($('#check_max_qty').val());
            var input_qty_max_val = input_qty_max_val + 1;


            var name = $(this).attr('name');
            if (valueCurrent >= minValue) {
                $(".btn-number[data-type='minus'][data-field='" + name + "']").removeAttr('disabled');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: '{{translate("Cart")}}',
                    text: '{{translate('Sorry, the minimum value was reached')}}',
                    confirmButtonText: '{{translate("Yes")}}',
                });
                $(this).val($(this).data('oldValue'));
            }

            if(valueCurrent >= input_qty_max_val){
                console.log(input_qty_max_val);
                Swal.fire({
                    icon: 'error',
                    title: '{{translate("Cart")}}',
                    text: '{{translate('the maximum value was reached')}}',
                    confirmButtonText: '{{translate("Yes")}}',
                });
                $(this).val(input_qty_max_val-1)
            } else if (valueCurrent <= maxValue) {
                $(".btn-number[data-type='plus'][data-field='" + name + "']").removeAttr('disabled')
            } else {
                Swal.fire({
                    icon: 'error',
                    title: '{{translate("Cart")}}',
                    text: '{{translate('Sorry, stock limit exceeded')}}.',
                    confirmButtonText: '{{translate("Yes")}}',
                });
                $(this).val(1)
            }
        });
        $(".input-number").keydown(function (e) {
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
                (e.keyCode == 65 && e.ctrlKey === true) ||
                (e.keyCode >= 35 && e.keyCode <= 39)) {
                return;
            }
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });
    }

    function getVariantPrice() {
        if (1) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: '{{ route('admin.pos.variant_price') }}',
                data: $('#add-to-cart-form').serializeArray(),
                success: function (data) {
                    $('#add-to-cart-form #chosen_price_div').removeClass('d-none');
                    $('#add-to-cart-form #chosen_price_div #chosen_price').html(data.price);
                }
            });
        }
    }

    function addToCart(form_id = 'add-to-cart-form') {
        if (checkAddToCartValidity()) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.pos.add-to-cart') }}',
                data: $('#' + form_id).serializeArray(),
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    if (data.data == 1) {
                        Swal.fire({
                            icon: 'info',
                            title: "{{translate('Cart')}}",
                            text: "{{translate('Product already added in cart')}}",
                            confirmButtonText: '{{translate("Yes")}}',
                        });
                        return false;
                    } else if (data.quantity <= 0) {
                        Swal.fire({
                            icon: 'info',
                            title: "{{translate('Cart')}}",
                            text: "{{translate('Product is out of stock')}}",
                            confirmButtonText: '{{translate("Yes")}}',
                        });
                        return false;

                    }   else if (data.data == 0) {
                        Swal.fire({
                            icon: 'error',
                            title: "{{translate('Cart')}}",
                            text: '{{translate('product out of stock')}}.',
                            confirmButtonText: '{{translate("Yes")}}',
                        });
                        return false;
                    }
                    $('.call-when-done').click();

                    toastr.success('{{translate('Item has been added in your cart')}}!', {
                        CloseButton: true,
                        ProgressBar: true
                    });

                    updateCart();
                },
                complete: function () {
                    $('#loading').hide();
                }
            });
        } else {
            Swal.fire({
                type: 'info',
                title: "{{translate('Cart')}}",
                text: '{{translate('Please choose all the options')}}',
                confirmButtonText: '{{translate("Yes")}}',
            });
        }
    }

    function removeFromCart(key) {
        $.post('{{ route('admin.pos.remove-from-cart') }}', {_token: '{{ csrf_token() }}', key: key}, function (data) {
            if (data.errors) {
                for (var i = 0; i < data.errors.length; i++) {
                    toastr.error(data.errors[i].message, {
                        CloseButton: true,
                        ProgressBar: true
                    });
                }
            } else {
                updateCart();
                toastr.info('{{translate('Item has been removed from cart')}}', {
                    CloseButton: true,
                    ProgressBar: true
                });
            }

        });
    }

    function emptyCart() {
        $.post('{{ route('admin.pos.emptyCart') }}', {_token: '{{ csrf_token() }}'}, function (data) {
            updateCart();
            location.reload();
            toastr.info('{{translate('Item has been removed from cart')}}', {
                CloseButton: true,
                ProgressBar: true
            });
        });
    }

    function updateCart() {
        $.post('<?php echo e(route('admin.pos.cart_items')); ?>', {_token: '<?php echo e(csrf_token()); ?>'}, function (data) {
            $('#cart').empty().html(data);
        });
    }

    function store_key(key, value) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': "{{csrf_token()}}"
            }
        });
        $.post({
            url: '{{route('admin.pos.store-keys')}}',
            data: {
                key:key,
                value:value,
            },
            success: function (data) {
                key = key=='customer_id' ? "{{translate('customer_id')}}" : (key=='branch_id' ? "{{translate('branch_id')}}":'');
                toastr.success(key+' '+'{{translate('selected')}}!', {
                    CloseButton: true,
                    ProgressBar: true
                });
            },
        });
    }

    $(function () {
        $(document).on('click', 'input[type=number]', function () {
            this.select();
        });
    });

    function updateQuantity(e) {
        var element = $(e.target);
        var minValue = parseInt(element.attr('min'));
        var maxValue = parseInt(element.attr('max'));
        var valueCurrent = parseInt(element.val());

        var key = element.data('key');
        var product_id = element.attr("id");
        if (valueCurrent >= minValue && valueCurrent <= maxValue) {
            $.post('{{ route('admin.pos.updateQuantity') }}', {
                _token: '{{ csrf_token() }}',
                key: key,
                quantity: valueCurrent
            }, function (data) {
                updateCart();
            });
        } else if(valueCurrent >= maxValue) {Swal.fire({
            icon: 'error',
            title: '{{translate("Cart")}}',
            text: '{{translate('Product out of stock!!!')}}',
            confirmButtonText: '{{translate("Yes")}}',
        });
            element.val(element.data('oldValue'));
            updateCart();
        } else {
            Swal.fire({
                icon: 'error',
                title: "{{translate('Cart')}}",
                text: '{{translate('Sorry, the minimum value was reached')}}',
                confirmButtonText: '{{translate("Yes")}}',
            });
            element.val(element.data('oldValue'));
            updateCart();
        }

        if (e.type == 'keydown') {
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
                (e.keyCode == 65 && e.ctrlKey === true) ||
                (e.keyCode >= 35 && e.keyCode <= 39)) {
                return;
            }
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        }

    }

    $('.js-select2-custom').each(function () {
        var select2 = $.HSCore.components.HSSelect2.init($(this));
    });

    $('.js-data-example-ajax').select2({
        ajax: {
            url: '{{route('admin.pos.customers')}}',
            data: function (params) {
                return {
                    q: params.term,
                    page: params.page
                };
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            __port: function (params, success, failure) {
                var $request = $.ajax(params);

                $request.then(success);
                $request.fail(failure);

                return $request;
            }
        }
    });

    $(document).ready(function() {
        var orderType = {!! json_encode(session('order_type')) !!};

        if (orderType === 'home_delivery') {
            $('#home_delivery_section').removeClass('d-none');
        } else {
            $('#home_delivery_section').addClass('d-none');
        }
    });

    function select_order_type(order_type) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': "{{csrf_token()}}"
            }
        });

        $.post({
            url: '{{route('admin.pos.order_type.store')}}',
            data: {
                order_type:order_type,
            },
            beforeSend: function () {
                $('#loading').show();
            },
            success: function (data) {
                console.log(data);
                updateCart();
            },
            complete: function () {
                $('#loading').hide();
            }
        });

       if(order_type == 'home_delivery') {
            $('#home_delivery_section').removeClass('d-none');
        }else{
            $('#home_delivery_section').addClass('d-none')
        }
    }

    $( document ).ready(function() {
        function initAutocomplete() {
            var myLatLng = {

                lat: 23.811842872190343,
                lng: 90.356331
            };
            const map = new google.maps.Map(document.getElementById("location_map_canvas"), {
                center: {
                    lat: 23.811842872190343,
                    lng: 90.356331
                },
                zoom: 13,
                mapTypeId: "roadmap",
            });

            var marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
            });

            marker.setMap(map);
            var geocoder = geocoder = new google.maps.Geocoder();
            google.maps.event.addListener(map, 'click', function(mapsMouseEvent) {
                var coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
                var coordinates = JSON.parse(coordinates);
                var latlng = new google.maps.LatLng(coordinates['lat'], coordinates['lng']);
                marker.setPosition(latlng);
                map.panTo(latlng);

                document.getElementById('latitude').value = coordinates['lat'];
                document.getElementById('longitude').value = coordinates['lng'];

                geocoder.geocode({
                    'latLng': latlng
                }, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[1]) {
                            document.getElementById('address').value = results[1].formatted_address;
                        }
                    }
                });
            });
            const input = document.getElementById("pac-input");
            const searchBox = new google.maps.places.SearchBox(input);
            map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);
            map.addListener("bounds_changed", () => {
                searchBox.setBounds(map.getBounds());
            });
            let markers = [];
            searchBox.addListener("places_changed", () => {
                const places = searchBox.getPlaces();

                if (places.length == 0) {
                    return;
                }
                markers.forEach((marker) => {
                    marker.setMap(null);
                });
                markers = [];
                const bounds = new google.maps.LatLngBounds();
                places.forEach((place) => {
                    if (!place.geometry || !place.geometry.location) {
                        console.log("Returned place contains no geometry");
                        return;
                    }
                    var mrkr = new google.maps.Marker({
                        map,
                        title: place.name,
                        position: place.geometry.location,
                    });
                    google.maps.event.addListener(mrkr, "click", function(event) {
                        document.getElementById('latitude').value = this.position.lat();
                        document.getElementById('longitude').value = this.position.lng();

                    });

                    markers.push(mrkr);

                    if (place.geometry.viewport) {
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
            });
        }
        initAutocomplete();
    });

    function deliveryAddressStore(form_id = 'delivery_address_store') {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
        $.post({
            url: '{{ route('admin.pos.add-delivery-address') }}',
            data: $('#' + form_id).serializeArray(),
            beforeSend: function() {
                $('#loading').show();
            },
            success: function(data) {
                if (data.errors) {
                    for (var i = 0; i < data.errors.length; i++) {
                        toastr.error(data.errors[i].message, {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }
                } else {
                    $('#del-add').empty().html(data.view);
                }
                updateCart();
                $('.call-when-done').click();
            },
            complete: function() {
                $('#loading').hide();
            }
        });
    }

    $('.js-data-example-ajax-2').select2()

    $('#order_place').submit(function (eventObj) {
        if ($('#customer').val()) {
            $(this).append('<input type="hidden" name="user_id" value="' + $('#customer').val() + '" /> ');
        }
        return true;
    });

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
