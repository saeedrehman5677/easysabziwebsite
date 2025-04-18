@extends('layouts.admin.app')

@section('title', translate('Review List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <h1 class="page-header-title">
                <span class="page-header-icon">
                    <img src="{{asset('public/assets/admin/img/review.png')}}" class="w--24" alt="">
                </span>
                <span>
                    {{translate('product reviews')}} <span class="badge badge-pill badge-soft-secondary">{{ $reviews->total() }}</span>
                </span>
            </h1>
        </div>

        <div class="card">
            <div class="card-header  border-0">
                <div class="card--header justify-content-end">
                    <form action="{{url()->current()}}" method="GET">
                        <div class="input-group">
                            <input id="datatableSearch_" type="search" name="search"
                                    class="form-control"
                                    placeholder="{{translate('Ex : Search by ID or name')}}" aria-label="Search"
                                    value="{{$search}}" required autocomplete="off">
                            <div class="input-group-append">
                                <button type="submit" class="input-group-text">
                                    {{translate('Search')}}
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
                        <th>{{translate('#')}}</th>
                        <th>{{translate('product name')}}</th>
                        <th>{{translate('ratings')}}</th>
                        <th>{{translate('customer info')}}</th>
                        <th>{{translate('status')}}</th>
                    </tr>
                    </thead>

                    <tbody id="set-rows">
                    @foreach($reviews as $key=>$review)
                        <tr>
                            <td>{{$reviews->firstItem()+$key}}</td>
                            <td>
                                <span class="d-block font-size-sm text-body">
                                    @if($review->product)
                                        @if (!empty(json_decode($review->product['image'],true)))
                                        <a href="{{route('admin.product.view',[$review['product_id']])}}" class="short-media">
                                            <img
                                                 src="{{$review->product->identityImageFullPath[0]}}">
                                            <div class="text-cont line--limit-2 max-150px">
                                                {{$review->product['name']}}
                                            </div>
                                        </a>
                                        @endif
                                        @else
                                            <span class="badge-pill badge-soft-dark text-muted text-sm small">
                                                {{translate('Product unavailable')}}
                                            </span>
                                        @endif
                                </span>
                            </td>
                            <td>
                                <span class="text-info">
                                    {{$review->rating}} <i class="tio-star"></i>
                                </span>
                                <div class="max-200px line--limit-3">
                                    {{$review->comment}}
                                </div>
                            </td>
                            <td>
                                @if(isset($review->customer))
                                    <a href="{{route('admin.customer.view',[$review->user_id])}}" class="text-body">
                                        <h6 class="text-capitalize short-title max-w--160px">
                                            {{$review->customer->f_name." ".$review->customer->l_name}}
                                        </h6>
                                        <span>{{$review->customer->phone}}</span>
                                    </a>
                                @else
                                    <span class="badge-pill badge-soft-dark text-muted text-sm small">
                                        {{translate('Customer unavailable')}}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <label class="toggle-switch">
                                    <input type="checkbox" class="toggle-switch-input status-change-alert" id="stocksCheckbox{{ $review->id }}"
                                           data-route="{{ route('admin.reviews.status', [$review->id, $review->is_active ? 0 : 1]) }}"
                                           data-message="{{ $review->is_active? translate('you_want_to_disable_this_review'): translate('you_want_to_active_this_review') }}"
                                        {{ $review->is_active ? 'checked' : '' }}>
                                    <span class="toggle-switch-label text">
                                        <span class="toggle-switch-indicator"></span>
                                    </span>
                                </label>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <hr>
                <div class="page-area">
                    <table>
                        <tfoot>
                        {!! $reviews->links() !!}
                        </tfoot>
                    </table>
                </div>
                @if(count($reviews) == 0)
                    <div class="text-center p-4">
                        <img class="w-120px mb-3" src="{{asset('/public/assets/admin/svg/illustrations/sorry.svg')}}" alt="Image Description">
                        <p class="mb-0">{{translate('No_data_to_show')}}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection
