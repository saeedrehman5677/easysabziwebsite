@extends('layouts.admin.app')

@section('title', translate('flash_sale'))

@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{asset('public/assets/admin/img/flash_sale.png')}}" class="w--20" alt="">
                </span>
                <span>
                    {{translate('flash sale')}}
                </span>
            </h1>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <form action="{{route('admin.offer.flash.store')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="form-group mb-0">
                                        <label class="input-label" for="exampleFormControlInput1">{{translate('title')}}</label>
                                        <input type="text" name="title" value="{{old('title')}}" class="form-control" placeholder="{{ translate('enter title') }}" maxlength="255" required>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group mb-0">
                                        <label for="name" class="title-color font-weight-medium text-capitalize">{{ translate('start_date')}}</label>
                                        <input type="date" name="start_date" required id="start_date"
                                               class="js-flatpickr form-control flatpickr-custom" placeholder="{{ translate('dd/mm/yy') }}" data-hs-flatpickr-options='{ "dateFormat": "Y/m/d", "minDate": "today" }'>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group mb-0">
                                        <label for="name" class="title-color font-weight-medium text-capitalize">{{ translate('end_date')}}</label>
                                        <input type="date" name="end_date" required id="end_date"
                                               class="js-flatpickr form-control flatpickr-custom" placeholder="{{ translate('dd/mm/yy') }}" data-hs-flatpickr-options='{ "dateFormat": "Y/m/d", "minDate": "today" }'>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex flex-column justify-content-center h-100">
                                <h5 class="text-center mb-3 text--title text-capitalize">
                                    {{translate('image')}}
                                    <small class="text-danger">* ( {{translate('ratio')}} 3:1 )</small>
                                </h5>
                                <label class="upload--vertical">
                                    <input type="file" name="image" id="customFileEg1" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" hidden>
                                    <img id="viewer" src="{{asset('public/assets/admin/img/upload-vertical.png')}}" alt="{{translate('banner image')}}"/>
                                </label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="btn--container justify-content-end">
                                <button type="reset" class="btn btn--reset">{{translate('reset')}}</button>
                                <button type="submit" class="btn btn--primary">{{translate('submit')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header border-0">
                <div class="card--header justify-content-between max--sm-grow">
                    <h5 class="card-title">{{translate('Flash Sale List')}} <span class="badge badge-soft-secondary">{{ $flashDeals->total() }}</span></h5>
                    <form action="{{url()->current()}}" method="GET">
                        <div class="input-group">
                            <input type="search" name="search" class="form-control"
                                   placeholder="{{translate('Search_by_flash_sale_title')}}" aria-label="Search"
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
                        <th class="border-0">{{translate('image')}}</th>
                        <th class="border-0">{{translate('title')}}</th>
                        <th class="border-0">{{translate('duration')}}</th>
                        <th class="border-0">{{translate('status')}}</th>
                        <th class="border-0">{{translate('is_publish')}}?</th>
                        <th class="text-center border-0">{{translate('active_products')}}</th>
                        <th class="text-center border-0">{{translate('action')}}</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($flashDeals as $key=>$flash_deal)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>
                                <div>
                                    <img class="img-vertical-150" src="{{$flash_deal->imageFullPath}}">
                                </div>
                            </td>
                            <td>
                                <span class="d-block font-size-sm text-body text-trim-25">
                                    {{$flash_deal['title']}}
                                </span>
                            </td>
                            <td>{{date('d-M-y',strtotime($flash_deal['start_date']))}} - {{date('d-M-y',strtotime($flash_deal['end_date']))}}</td>
                            <td>
                                @if(\Carbon\Carbon::parse($flash_deal['end_date'])->endOfDay()->isPast())
                                    <span class="badge badge-soft-danger">{{ translate('expired')}} </span>
                                @else
                                    <span class="badge badge-soft-success"> {{ translate('active')}} </span>
                                @endif
                            </td>
                            <td>
                                <label class="toggle-switch my-0">
                                    <input type="checkbox"class="toggle-switch-input status-change-alert" id="stocksCheckbox{{ $flash_deal->id }}"
                                           data-route="{{ route('admin.offer.flash.status', [$flash_deal->id, $flash_deal->status ? 0 : 1]) }}"
                                           data-message="{{ $flash_deal->status? translate('you_want_to_disable_this_deal'): translate('you_want_to_active_this_deal') }}"
                                        {{ $flash_deal->status ? 'checked' : '' }}>
                                    <span class="toggle-switch-label mx-auto text">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                </label>
                            </td>
                            <td class="text-center">{{ $flash_deal->products_count }}</td>
                            <td>
                                <div class="btn--container justify-content-center">
                                    <a class="h-30 d-flex gap-2 align-items-center btn btn-soft-info btn-sm border-info" href="{{route('admin.offer.flash.add-product',[$flash_deal['id']])}}">
                                        <img src="{{asset('/public/assets/back-end/img/plus.svg')}}" class="svg" alt="">
                                        {{translate('Add Product')}}
                                    </a>
                                    <a class="action-btn"
                                       href="{{route('admin.offer.flash.edit',[$flash_deal['id']])}}">
                                        <i class="tio-edit"></i></a>
                                    <a class="action-btn btn--danger btn-outline-danger form-alert" href="javascript:"
                                       data-id="deal-{{$flash_deal['id']}}"
                                       data-message="{{translate('Want to delete this')}}?">
                                        <i class="tio-delete-outlined"></i>
                                    </a>
                                </div>
                                <form action="{{route('admin.offer.flash.delete',[$flash_deal['id']])}}"
                                      method="post" id="deal-{{$flash_deal['id']}}">
                                    @csrf @method('delete')
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <table>
                    <tfoot>
                    {!! $flashDeals->links() !!}
                    </tfoot>
                </table>

            </div>
            @if(count($flashDeals) == 0)
                <div class="text-center p-4">
                    <img class="w-120px mb-3" src="{{asset('/public/assets/admin/svg/illustrations/sorry.svg')}}" alt="{{translate('Image Description')}}">
                    <p class="mb-0">{{translate('No_data_to_show')}}</p>
                </div>
            @endif
        </div>
    </div>

@endsection

@push('script_2')
    <script src="{{ asset('public/assets/admin/js/flatpicker.js') }}"></script>
    <script src="{{ asset('public/assets/admin/js/upload-single-image.js') }}"></script>
@endpush
