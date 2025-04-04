@extends('layouts.admin.app')

@section('title', translate('Deliveryman List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{asset('public/assets/admin/img/employee.png')}}" class="w--24" alt="{{ translate('deliveryman') }}">
                </span>
                <span>
                    {{translate('deliveryman')}} {{translate('list')}}
                </span>
                <span class="badge badge-soft-info badge-pill">{{ $deliverymen->total() }}</span>
            </h1>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="card--header">
                    <form action="{{url()->current()}}" method="GET">
                        <div class="input-group">
                            <input id="datatableSearch_" type="search" name="search"
                                    class="form-control"
                                    placeholder="{{translate('Search by Name or Phone or Email')}}" aria-label="Search"
                                    value="{{$search}}" required autocomplete="off">
                            <div class="input-group-append">
                                <button type="submit" class="input-group-text">
                                    {{translate('Search')}}
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="hs-unfold ml-sm-auto">
                        <a class="js-hs-unfold-invoker btn btn-sm btn-white dropdown-toggle btn export-btn btn-outline-primary-2 btn--primary font--sm" href="javascript:;"
                           data-hs-unfold-options='{
                            "target": "#usersExportDropdown",
                            "type": "css-animation"
                            }'>
                            <i class="tio-download-to mr-1"></i> {{translate('export')}}
                        </a>

                        <div id="usersExportDropdown" class="hs-unfold-content dropdown-unfold dropdown-menu dropdown-menu-sm-right">
                            <span class="dropdown-header">{{translate('download')}} {{translate('options')}}</span>
                            <a id="export-excel" class="dropdown-item"
                               href="{{route('admin.delivery-man.export', ['search'=>Request::get('search')])}}">
                                <img class="avatar avatar-xss avatar-4by3 mr-2" src="{{asset('public/assets/admin')}}/svg/components/excel.svg" alt="{{ translate('excel') }}">
                                {{translate('excel')}}
                            </a>
                        </div>
                    </div>
                    <a href="{{route('admin.delivery-man.add')}}" class="btn btn--primary py-2"><i class="tio-add-circle"></i> {{translate('add deliveryman')}}</a>
                </div>
            </div>

            <div class="table-responsive datatable-custom">
                <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                    <thead class="thead-light">
                    <tr>
                        <th>{{translate('#')}}</th>
                        <th>{{translate('name')}}</th>
                        <th>{{translate('Contact Info')}}</th>
                        <th>{{translate('Total Orders')}}</th>
                        <th class="text-center">{{translate('Status')}}</th>
                        <th class="text-center">{{translate('action')}}</th>
                    </tr>
                    </thead>

                    <tbody id="set-rows">
                    @foreach($deliverymen as $key=>$deliveryman)
                        <tr>
                            <td>{{$deliverymen->firstItem()+$key}}</td>
                            <td>
                                <div class="table--media">
                                    <img class="rounded-full"
                                         src="{{$deliveryman->imageFullPath}}" alt="{{ translate('deliveryman') }}">
                                    <div class="table--media-body">
                                        <h5 class="title">
                                            {{$deliveryman['f_name'] }} {{$deliveryman['l_name'] }}
                                        </h5>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <h5 class="m-0">
                                    <a href="mailto:{{$deliveryman['email']}}" class="text-hover">{{$deliveryman['email']}}</a>
                                </h5>
                                <div>
                                    <a href="tel:{{$deliveryman['phone']}}" class="text-hover">{{$deliveryman['phone']}}</a>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-soft-info py-2 px-3 font-bold">
                                    {{$deliveryman->orders->count()}}
                                </span>
                            </td>
                            <td>
                                <label class="toggle-switch my-0">
                                    <input type="checkbox"
                                           data-route="{{ route('admin.delivery-man.status', [$deliveryman->id, $deliveryman->is_active ? 0 : 1]) }}"
                                           data-message="{{ $deliveryman->is_active? translate('you_want_to_disable_this_deliveryman'): translate('you_want_to_active_this_deliveryman') }}"
                                           class="toggle-switch-input status-change-alert" id="stocksCheckbox{{ $deliveryman->id }}"
                                        {{ $deliveryman->is_active ? 'checked' : '' }}>
                                    <span class="toggle-switch-label mx-auto text">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                </label>
                            </td>
                            <td>
                                <div class="btn--container justify-content-center">
                                    <a class="action-btn"
                                        href="{{route('admin.delivery-man.edit',[$deliveryman['id']])}}">
                                    <i class="tio-edit"></i>
                                </a>
                                    <a class="action-btn btn--danger btn-outline-danger form-alert" href="javascript:"
                                       data-id="delivery-man-{{$deliveryman['id']}}"
                                       data-message="{{translate('Want to remove this deliveryman')}}?">
                                        <i class="tio-delete-outlined"></i>
                                    </a>
                                    <form action="{{route('admin.delivery-man.delete',[$deliveryman['id']])}}"
                                            method="post" id="delivery-man-{{$deliveryman['id']}}">
                                        @csrf @method('delete')
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="page-area">
                    <table>
                        <tfoot>
                        {!! $deliverymen->links() !!}
                        </tfoot>
                    </table>
                </div>
                @if(count($deliverymen)==0)
                    <div class="text-center p-4">
                        <img class="w-120px mb-3" src="{{asset('public/assets/admin')}}/svg/illustrations/sorry.svg" alt="{{ translate('image') }}">
                        <p class="mb-0">{{ translate('No_data_to_show')}}</p>
                    </div>
                @endif

            </div>
        </div>
    </div>

@endsection

