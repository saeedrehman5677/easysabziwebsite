@extends('layouts.admin.app')

@section('title', translate('Update Employee'))

@section('content')
<div class="content container-fluid">

    <div class="page-header">
        <h1 class="page-header-title">
            <span class="page-header-icon">
                <img src="{{asset('public/assets/admin/img/employee.png')}}" class="w--24" alt="{{ translate('employee') }}">
            </span>
            <span>
                {{translate('Update Employee')}}
            </span>
        </h1>
    </div>

    <form action="{{route('admin.employee.update',[$employee['id']])}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <span class="card-header-icon">
                        <i class="tio-user"></i>
                    </span> {{translate('General Information')}}
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">{{translate('First Name')}}</label>
                                <input type="text" name="first_name" value="{{$employee['f_name']}}" class="form-control" id="name"
                                        placeholder="{{translate('Ex')}} : {{translate('John')}}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{translate('Last Name')}}</label>
                                <input type="text" name="last_name" value="{{$employee['l_name']}}" class="form-control" id="name"
                                        placeholder="{{translate('Ex')}} : {{translate('Joe')}}">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">{{translate('Phone')}}</label>
                                <input type="text" value="{{$employee['phone']}}" required name="phone" class="form-control" id="phone"
                                        placeholder="{{translate('Ex')}} : +88017********">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">{{translate('Role')}}</label>
                                <select class="form-control w-100" name="role_id">
                                        <option value="0" selected disabled>---{{translate('select')}}---</option>
                                        @foreach($adminRoles as $role)
                                            <option
                                                value="{{$role->id}}" {{$role['id']==$employee['admin_role_id']?'selected':''}}>{{$role->name}}</option>
                                        @endforeach
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">{{translate('Identity Type')}}</label>
                                <select name="identity_type" class="form-control">
                                    <option value="passport" {{$employee['identity_type'] == 'passport' ? 'selected' : ''}}>{{translate('passport')}}</option>
                                    <option value="driving_license" {{$employee['identity_type'] == 'driving_license' ? 'selected' : ''}}>{{translate('driving')}} {{translate('license')}}</option>
                                    <option value="nid" {{$employee['identity_type'] == 'nid' ? 'selected' : ''}}>{{translate('nid')}}</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">{{translate('Identity Number')}}</label>
                                <input type="text" class="form-control" name="identity_number" value="{{$employee['identity_number']}}" placeholder="{{translate('Identity Number')}} : 674-5596-9854" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label d-none d-md-block ">
                                &nbsp;
                            </label>
                            <div class="text-center mb-4">
                                <img class="initial-24" id="viewer"
                                    src="{{$employee->imageFullPath}}"
                                     alt="{{ translate('employee') }}"/>
                            </div>
                            <div class="form-group mb-0">
                                <label class="form-label d-block">
                                    {{ translate('Employee Image') }} <span class="text-danger">(Ratio 1:1)</span>
                                </label>
                                <div class="custom-file">
                                    <input type="file" name="image" id="customFileUpload" class="custom-file-input h--45px" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" >
                                    <label class="custom-file-label h--45px" for="customFileUpload"></label>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="form-label d-block mb-2 pb-1">
                                {{ translate('Identity Image') }}
                            </label>
                            <div class="product--coba">
                                    <div class="row g-2" id="coba">
                                            @foreach($employee->identityImageFullPath as $identification_image)
                                            <div class="two__item w-50">
                                                <div class="max-h-140px existing-item">
                                                    <img src="{{$identification_image}}"
                                                         alt="{{ translate('identity_image') }}">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title">
                    <span class="card-header-icon">
                        <i class="tio-user"></i>
                    </span> {{translate('Account Information')}}
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4 col-sm-6">
                        <label class="form-label">{{translate('Email')}}</label>
                        <input type="email" value="{{$employee['email']}}" name="email" class="form-control" id="email"
                                placeholder="{{translate('Ex')}} : ex@gmail.com" required>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <label class="form-label">{{translate('Password')}}</label><small class="text-danger">( {{translate('input if you want to change')}} )</small>
                        <input type="text" name="password" class="form-control" id="password" placeholder="{{translate('Ex : 8+ Characters')}}">
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <label class="form-label">{{translate('Confirm Password')}}</label>
                        <input type="text" name="password_confirmation" class="form-control" id="password" placeholder="{{translate('Ex : 8+ Characters')}}">
                    </div>
                </div>
            </div>
        </div>
        <div class="btn--container justify-content-end mt-3">
            <button type="reset" class="btn btn--reset">{{translate('reset')}}</button>
            <button type="submit" class="btn btn--primary">{{translate('Update')}}</button>
        </div>
    </form>

</div>
@endsection

@push('script_2')

<script src="{{asset('public/assets/back-end')}}/js/select2.min.js"></script>
<script src="{{asset('public/assets/admin/js/spartan-multi-image-picker.js')}}"></script>

    <script>
        "use strict";

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileUpload").change(function () {
            readURL(this);
        });


        $(".js-example-theme-single").select2({
            theme: "classic"
        });

        $(".js-example-responsive").select2({
            width: 'resolve'
        });

    $(function () {
        $("#coba").spartanMultiImagePicker({
            fieldName: 'identity_image[]',
            maxCount: 2,
            rowHeight: '138px',
            groupClassName: 'two__item',
            maxFileSize: '',
            placeholderImage: {
                image: '{{asset('public/assets/admin/img/upload-vertical.png')}}',
                width: '100%'
            },
            dropFileLabel: "Drop Here",
            onAddRow: function (index, file) {

            },
            onRenderedPreview: function (index) {

            },
            onRemoveRow: function (index) {

            },
            onExtensionErr: function (index, file) {
                toastr.error('{{ translate("Please only input png or jpg type file") }}', {
                    CloseButton: true,
                    ProgressBar: true
                });
            },
            onSizeErr: function (index, file) {
                toastr.error('{{ translate("File size too big") }}', {
                    CloseButton: true,
                    ProgressBar: true
                });
            }
        });
    });
</script>
@endpush
