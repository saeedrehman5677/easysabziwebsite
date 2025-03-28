@extends('layouts.admin.app')

@section('title', translate('Customer Settings'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush


@section('content')
    <div class="content container-fluid">
        @include('admin-views.business-settings.partial.business-settings-navmenu')

        <div class="tab-content">
            <div class="tab-pane fade show active" id="business-setting">
                <form action="{{ route('admin.business-settings.store.customer-setup-update') }}" method="post" enctype="multipart/form-data"
                      id="update-settings">
                    @csrf
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-sm-4 col-12">
                                    <div class="form-group mb-0">
                                        <label
                                            class="toggle-switch toggle-switch-sm d-flex justify-content-between border rounded px-4 form-control"
                                            for="customer_wallet">
                                            <span class="pr-2">{{ translate('customer_wallet') }} :</span>
                                            <input type="checkbox" class="toggle-switch-input section-visibility"
                                                   data-id="customer_wallet"
                                                   name="customer_wallet"
                                                   id="customer_wallet" value="1" data-section="wallet-section"
                                                {{ isset($data['wallet_status']) && $data['wallet_status'] == 1 ? 'checked' : '' }}>
                                            <span class="toggle-switch-label text">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-12">
                                    <div class="form-group mb-0">
                                        <label
                                            class="toggle-switch toggle-switch-sm d-flex justify-content-between border rounded px-4 form-control"
                                            for="customer_loyalty_point">
                                            <span class="pr-2">{{ translate('customer_loyalty_point') }}:</span>
                                            <input type="checkbox" class="toggle-switch-input section-visibility"
                                                   data-id="customer_loyalty_point"
                                                   name="customer_loyalty_point"
                                                   id="customer_loyalty_point" data-section="loyalty-point-section" value="1"
                                                {{ isset($data['loyalty_point_status']) && $data['loyalty_point_status'] == 1 ? 'checked' : '' }}>
                                            <span class="toggle-switch-label text">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-12">
                                    <div class="form-group mb-0">
                                        <label
                                            class="toggle-switch toggle-switch-sm d-flex justify-content-between border rounded px-4 form-control">
                                    <span
                                        class="pr-2">{{ translate('customer_referrer_earning') }}:</span>
                                            <input type="checkbox" class="toggle-switch-input section-visibility"
                                                   data-id="ref_earning_status"
                                                   name="ref_earning_status" id="ref_earning_status"
                                                   data-section="referrer-earning" value="1"
                                                {{ isset($data['ref_earning_status']) && $data['ref_earning_status'] == 1 ? 'checked' : '' }}>
                                            <span class="toggle-switch-label text">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3 wallet-section">
                        <div class="card-header">
                            <h5 class="card-title">
                        <span class="card-header-icon">

                        </span>
                                <span>{{ translate('Add Fund to Wallet') }}</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-sm-6 col-lg-4">
                                    <div class="form-group m-0">
                                        <label class="toggle-switch toggle-switch-sm d-flex justify-content-between border rounded px-4 form-control" for="add_fund_to_wallet">
                                            <span class="pr-2">{{ translate('Add Fund to Wallet') }}</span>
                                            <input type="checkbox" class="toggle-switch-input" name="add_fund_to_wallet" id="add_fund_to_wallet" value="1"
                                                {{ isset($data['add_fund_to_wallet']) && $data['add_fund_to_wallet'] == 1 ? 'checked' : '' }}>
                                            <span class="toggle-switch-label text">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3 loyalty-point-section">
                        <div class="card-header">
                            <h5 class="card-title">
                                <span>
                            {{ translate('customer_loyalty_point_settings') }}
                        </span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-sm-6 col-lg-4">
                                    <div class="form-group m-0">
                                        <label class="input-label"
                                               for="loyalty_point_exchange_rate">{{ translate('1 '.\App\CentralLogics\Helpers::currency_code().' Equal To How Much Loyalty Points?') }}</label>
                                        <input type="number" class="form-control" name="loyalty_point_exchange_rate"
                                               value="{{ $data['loyalty_point_exchange_rate'] ?? '0' }}">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-4">
                                    <div class="form-group m-0">
                                        <label class="input-label"
                                               for="intem_purchase_point">{{ translate('Percentage Of Loyalty Point On Order Amount') }}
                                        </label>
                                        <input type="number" class="form-control" name="loyalty_point_percent_on_item_purchase" step=".01"
                                               value="{{ $data['loyalty_point_percent_on_item_purchase'] ?? '0' }}">
                                    </div>
                                </div>
                                <div class="col-sm-6 col-lg-4">
                                    <div class="form-group m-0">
                                        <label class="input-label"
                                               for="intem_purchase_point">{{ translate('Minimum Loyalty Points To Transfer Into Wallet') }}</label>
                                        <input type="number" class="form-control" name="minimun_transfer_point"
                                               value="{{ $data['loyalty_point_minimum_point'] ?? '1' }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3 referrer-earning">
                        <div class="card-header">
                            <h5 class="card-title">
                                <span>
                            {{ translate('customer_referrer_settings') }}
                        </span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-sm-6 col-12">
                                    <div class="form-group m-0">
                                        <label class="input-label"
                                               for="referrer_earning_exchange_rate">{{ translate('One Referrer Equal To How Much ' .\App\CentralLogics\Helpers::currency_code())  }}</label>
                                        <input type="number step=0.01" class="form-control" name="ref_earning_exchange_rate"
                                               value="{{ $data['ref_earning_exchange_rate'] ?? '0' }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="btn--container justify-content-end">
                        <button type="submit" id="submit" class="btn btn-primary">{{ translate('submit') }}</button>
                    </div>
                </form>

            </div>

        </div>

    </div>
@endsection

@push('script_2')
    <script>
        "use strict";

        $(document).on('ready', function() {
            @if (isset($data['wallet_status']) && $data['wallet_status'] != 1)
            $('.wallet-section').hide();
            @endif
            @if (isset($data['loyalty_point_status']) && $data['loyalty_point_status'] != 1)
            $('.loyalty-point-section').hide();
            @endif
            @if (isset($data['ref_earning_status']) && $data['ref_earning_status'] != 1)
            $('.referrer-earning').hide();
            @endif

        });

        $('.section-visibility').on('click', function(){
            let id = $(this).data('id');

            if ($('#' + id).is(':checked')) {
                console.log('checked');
                $('.' + $('#' + id).data('section')).show();
            } else {
                console.log('unchecked');
                $('.' + $('#' + id).data('section')).hide();
            }
        })

    </script>
@endpush

