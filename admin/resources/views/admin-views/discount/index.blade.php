@extends('layouts.admin.app')

@section('title', translate('Category Discount'))

@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{asset('public/assets/admin/img/coupon.png')}}" class="w--20" alt="{{ translate('discount') }}">
                </span>
                <span>
                    {{translate('discount')}}
                </span>
            </h1>
        </div>
        <div class="card mb-3">
            <div class="card-body">
                <form action="{{route('admin.discount.store')}}" method="post">
                    @csrf
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="form-group mb-0">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('name')}}</label>
                                <input type="text" name="name" value="{{old('name')}}" class="form-control" placeholder="{{ translate('New discount') }}" maxlength="255" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group mb-0" id="type-category">
                                <label class="input-label" for="exampleFormControlSelect1">{{translate('category')}} <span
                                        class="input-label-secondary">*</span></label>
                                <select name="category_id" class="form-control js-select2-custom" required>
                                    @foreach($categories as $category)
                                        <option value="{{$category['id']}}">{{$category['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('start')}} {{translate('date')}}</label>
                                <label class="input-date">
                                    <input type="text" name="start_date" id="start_date" value="{{ old('start_date') }}" class="js-flatpickr form-control flatpickr-custom" placeholder="{{ translate('dd/mm/yy') }}" data-hs-flatpickr-options='{ "dateFormat": "Y/m/d", "minDate": "today" }' required>
                                </label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('expire')}} {{translate('date')}}</label>
                                <label class="input-date">
                                    <input type="text" name="expire_date" id="expire_date" value="{{ old('expire_date') }}" class="js-flatpickr form-control flatpickr-custom" placeholder="{{ translate('dd/mm/yy') }}" data-hs-flatpickr-options='{ "dateFormat": "Y/m/d", "minDate": "today" }' required>
                                </label>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group mb-0">
                                <label class="input-label" for="exampleFormControlSelect1">{{translate('discount')}} {{translate('type')}}<span
                                        class="input-label-secondary">*</span></label>
                                <select name="discount_type" class="form-control change-discount-type">
                                    <option value="percent">{{translate('percent')}}</option>
                                    <option value="amount">{{translate('amount')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group mb-0">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('discount_amount')}}</label>
                                <input type="number" step="0.1" name="discount_amount" value="{{old('discount_amount')}}" class="form-control" placeholder="{{ translate('discount_amount') }}" required>
                            </div>
                        </div>
                        <div class="col-6" id="max_amount_div">
                            <div class="form-group mb-0">
                                <label class="input-label" for="exampleFormControlInput1">{{translate('maximum_amount')}}</label>
                                <input type="number" step="0.1" name="maximum_amount" value="{{old('maximum_amount')}}" class="form-control" placeholder="{{ translate('maximum_amount') }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="btn--container justify-content-end">
                            <button type="reset" class="btn btn--reset">{{translate('reset')}}</button>
                            <button type="submit" class="btn btn--primary">{{translate('submit')}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header border-0">
                <div class="card--header justify-content-between max--sm-grow">
                    <h5 class="card-title">{{translate('discount_list')}} <span class="badge badge-soft-secondary">{{ $discounts->total() }}</span></h5>
                    <form action="{{url()->current()}}" method="GET">
                        <div class="input-group">
                            <input type="search" name="search" class="form-control"
                                   placeholder="{{translate('Search_by_name')}}" aria-label="Search"
                                   value="{{$search}}" required autocomplete="off">
                            <div class="input-group-append">
                                <button type="submit" class="input-group-text">
                                    {{translate('search')}}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="table-responsive datatable-custom">
                <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                    <thead class="thead-light">
                    <tr>
                        <th class="border-0">{{translate('#')}}</th>
                        <th class="border-0">{{translate('title')}}</th>
                        <th class="border-0">{{translate('discount type')}}</th>
                        <th class="border-0">{{translate('discount on')}}</th>
                        <th class="border-0">{{translate('discount amount')}}</th>
                        <th class="border-0">{{translate('maximum amount')}}</th>
                        <th class="border-0">{{translate('duration')}}</th>
                        <th class="text-center border-0">{{translate('status')}}</th>
                        <th class="text-center border-0">{{translate('action')}}</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($discounts as $key=>$discount)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>
                                <span class="d-block font-size-sm text-body text-trim-25">
                                    {{$discount['name']}}
                                </span>
                            </td>
                            <td>{{translate($discount->discount_type)}}</td>
                            <td>{{$discount->category ? $discount->category->name:''}}</td>
                            <td>
                                {{ $discount->discount_type == 'percent' ? $discount->discount_amount . '%' : Helpers::set_symbol($discount->discount_amount)}}
                            </td>
                            <td>{{$discount->discount_type == 'percent' ? Helpers::set_symbol($discount->maximum_amount) : '-'}}</td>
                            <td>
                                {{$discount->start_date->format('d M, Y')}} - {{$discount->expire_date->format('d M, Y')}}
                            </td>
                            <td>
                                <label class="toggle-switch my-0">
                                    <input type="checkbox"
                                           data-route="{{ route('admin.discount.status', [$discount->id, $discount->status ? 0 : 1]) }}"
                                           data-message="{{ $discount->status? translate('you_want_to_disable_this_discount'): translate('you_want_to_active_this_discount') }}"
                                           class="toggle-switch-input status-change-alert" id="stocksCheckbox{{ $discount->id }}"
                                        {{ $discount->status ? 'checked' : '' }}>
                                    <span class="toggle-switch-label mx-auto text">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                </label>
                            </td>
                            <td>
                                <div class="btn--container justify-content-center">
                                    <a class="action-btn"
                                       href="{{route('admin.discount.edit',[$discount['id']])}}">
                                        <i class="tio-edit"></i></a>
                                    <a class="action-btn btn--danger btn-outline-danger form-alert" href="javascript:"
                                       data-id="discount-{{$discount['id']}}"
                                       data-message="{{ translate("Want to delete this") }}">
                                        <i class="tio-delete-outlined"></i>
                                    </a>
                                </div>
                                <form action="{{route('admin.discount.delete',[$discount['id']])}}"
                                      method="post" id="discount-{{$discount['id']}}">
                                    @csrf @method('delete')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <table>
                    <tfoot>
                    {!! $discounts->links() !!}
                    </tfoot>
                </table>

            </div>
            @if(count($discounts) == 0)
                <div class="text-center p-4">
                    <img class="w-120px mb-3" src="{{asset('/public/assets/admin/svg/illustrations/sorry.svg')}}" alt="{{ translate('image') }}">
                    <p class="mb-0">{{translate('No_data_to_show')}}</p>
                </div>
            @endif
        </div>
    </div>

@endsection

@push('script_2')
    <script src="{{ asset('public/assets/admin/js/discount.js') }}"></script>
@endpush
