@extends('layouts.admin.app')

@section('title', translate('Product Setup'))

@section('content')
<div class="content container-fluid">
    @include('admin-views.business-settings.partial.business-settings-navmenu')

    <div class="tab-content">
        <div class="tab-pane fade show active" id="business-setting">
            <div class="card">

                <div class="card-body">
                    <form action="{{route('admin.business-settings.store.order-setup-update')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 pt-5">
                                <?php
                                $max_amount_status=\App\CentralLogics\Helpers::get_business_settings('maximum_amount_for_cod_order_status');
                                $max_status = $max_amount_status == 1 ? 0 : 1;
                                ?>
                                <div class="form-group">
                                    <label class="toggle-switch h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control">
                                            <span class="pr-1 d-flex align-items-center switch--label">
                                                <span class="line--limit-1">
                                                    <strong>{{translate('Maximum Amount for COD Order Status')}}</strong>
                                                </span>
                                                <span class="form-label-secondary text-danger d-flex ml-1" data-toggle="tooltip" data-placement="right" data-original-title="{{translate('If this field is active the maximum amount for Cash on Delivery order is apply')}}"><img src="{{asset('public/assets/admin/img/info-circle.svg')}}" alt="info">
                                                </span>
                                            </span>
                                        <input type="checkbox" class="toggle-switch-input" name="maximum_amount_for_cod_order_status" {{ $max_amount_status == 1 ? 'checked' : '' }}>
                                        <span class="toggle-switch-label text">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                    </label>
                                </div>
                            </div>
                            @php($maximum_amount_for_cod_order=\App\CentralLogics\Helpers::get_business_settings('maximum_amount_for_cod_order'))
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group mb-0">
                                    <label class="input-label text-capitalize">{{translate('Maximum Amount for COD Order')}}
                                        <i class="tio-info-outined"
                                           data-toggle="tooltip"
                                           data-placement="top"
                                           title="{{ translate('The maximum amount for Cash on Delivery order.') }}"></i>
                                    </label>
                                    <input type="number" value="{{$maximum_amount_for_cod_order}}"  step="0.1" name="maximum_amount_for_cod_order" class="form-control" placeholder=""
                                           {{ $max_amount_status == 0 ? 'readonly' : '' }} required>
                                </div>
                            </div>

                            @php($mov=\App\Model\BusinessSetting::where('key','minimum_order_value')->first()->value)
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{translate('min order value')}}( {{\App\CentralLogics\Helpers::currency_symbol()}} )</label>
                                    <input type="number" min="1" value="{{$mov}}"
                                         step="0.1"  name="minimum_order_value" class="form-control" placeholder="" required>
                                </div>
                            </div>

                            <div class="col-md-4 pt-5">
                                <?php
                                $orderImageStatus=\App\CentralLogics\Helpers::get_business_settings('order_image_status');
                                ?>
                                <div class="form-group">
                                    <label class="toggle-switch h--45px toggle-switch-sm d-flex justify-content-between border rounded px-3 py-0 form-control">
                                            <span class="pr-1 d-flex align-items-center switch--label">
                                                <span class="line--limit-1">
                                                    <strong>{{translate('Order Image Status')}}</strong>
                                                </span>
                                                <span class="form-label-secondary text-danger d-flex ml-1" data-toggle="tooltip" data-placement="right"
                                                      data-original-title="{{translate('If this field is active the users can add image while placing an order')}}">
                                                    <img src="{{asset('public/assets/admin/img/info-circle.svg')}}" alt="info">
                                                </span>
                                            </span>
                                        <input type="checkbox" class="toggle-switch-input" name="order_image_status" {{ $orderImageStatus == 1 ? 'checked' : '' }}>
                                        <span class="toggle-switch-label text">
                                                <span class="toggle-switch-indicator"></span>
                                            </span>
                                    </label>
                                </div>
                            </div>
                            @php($orderImageLabelName=\App\CentralLogics\Helpers::get_business_settings('order_image_label_name'))
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group mb-0">
                                    <label class="input-label text-capitalize">{{translate('Order Image Label Name')}}
                                        <i class="tio-info-outined"
                                           data-toggle="tooltip"
                                           data-placement="top"
                                           title="{{ translate('Order image label name. (ex: Prescription Image)') }}"></i>
                                    </label>
                                    <input type="text" value="{{ $orderImageLabelName ?? '' }}" name="order_image_label_name" class="form-control"
                                           placeholder="{{ translate('prescription_image') }}" maxlength="30">
                                </div>
                            </div>
                        </div>

                        <div class="btn--container justify-content-end">
                            <button type="reset" class="btn btn--reset">{{translate('reset')}}</button>
                            <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                    class="btn btn--primary call-demo">{{translate('save')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
