<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ translate('Branch') }} | {{ translate('Login') }}</title>

    @php($icon = \App\Model\BusinessSetting::where(['key' => 'fav_icon'])->first()->value)
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/app/public/restaurant/' . $icon ?? '') }}">

    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/css/vendor.min.css">
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/vendor/icon-set/style.css">
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/css/theme.minc619.css?v=1.0">
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/css/style.css">
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/css/toastr.css">

</head>

<body>
<main id="content" role="main" class="main">
    <div class="auth-wrapper">

        <div class="auth-wrapper-left">
            <div class="auth-left-cont">
                <img src="{{ $logo }}" alt="{{ translate('logo') }}">
                <h2 class="title">{{translate('Your')}} <span class="d-block">{{translate('All Fresh Food')}}</span> <strong class="text--039D55">{{translate('in one Place')}}....</strong></h2>
            </div>
        </div>

        <div class="auth-wrapper-right">
            <div class="auth-wrapper-form">

                <form id="form-id" action="{{route('branch.auth.login')}}" method="post">
                    @csrf
                    <div class="auth-header">
                        <div class="mb-5">
                            <div class="auth-wrapper-right-logo">
                                <img src="{{ $logo }}" alt="{{ translate('logo') }}">
                            </div>
                            <h2 class="title">{{ translate('sign in')}}</h2>
                            <div>{{ translate('welcome back') }}</div>
                            <p class="mb-0">{{ translate('Want to login your admin account') }}?
                                <a href="{{route('admin.auth.login')}}">
                                    {{ translate('Admin Login') }}
                                </a>
                            </p>
                            <span class="badge badge-soft-info mt-2">( {{translate('branch login')}} )</span>
                        </div>
                    </div>

                    <div class="js-form-message form-group">
                        <label class="input-label" for="signinSrEmail">{{ translate('Your email') }}</label>

                        <input type="email" class="form-control form-control-lg" name="email" id="signinSrEmail"
                                tabindex="1" placeholder="{{ translate('email@address.com') }}" aria-label="email@address.com"
                                required data-msg="Please enter a valid email address.">
                    </div>

                    <div class="js-form-message form-group">
                        <label class="input-label" for="signupSrPassword" tabindex="0">
                            <span class="d-flex justify-content-between align-items-center">
                                {{ translate('Password') }}
                            </span>
                        </label>

                        <div class="input-group input-group-merge">
                            <input type="password" class="js-toggle-password form-control form-control-lg"
                                    name="password" id="signupSrPassword" placeholder="{{ translate('8+ characters required') }}"
                                    aria-label="8+ characters required" required
                                    data-msg="Your password is invalid. Please try again."
                                    data-hs-toggle-password-options='{
                                                "target": "#changePassTarget",
                                    "defaultClass": "tio-hidden-outlined",
                                    "showClass": "tio-visible-outlined",
                                    "classChangeTarget": "#changePassIcon"
                                    }'>
                            <div id="changePassTarget" class="input-group-append">
                                <a class="input-group-text" href="javascript:">
                                    <i id="changePassIcon" class="tio-visible-outlined"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox d-flex align-items-center">
                            <input type="checkbox" class="custom-control-input" id="termsCheckbox"
                                name="remember">
                            <label class="custom-control-label text-muted m-0" for="termsCheckbox">
                                {{translate('remember')}} {{translate('me')}}
                            </label>
                        </div>
                    </div>

                    @php($recaptcha = Helpers::get_business_settings('recaptcha'))
                    @if(isset($recaptcha) && $recaptcha['status'] == 1)
                        <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">
                    @else
                        <div class="row pt-2 pb-2 align-items-center">
                            <div class="col-6 pr-0">
                                <input type="text" class="form-control form-control-lg" name="default_captcha_value" value=""
                                    placeholder="{{translate('Enter captcha value')}}" autocomplete="off">
                            </div>
                            <div class="col-6 input-icons bg-white rounded">
                                <div class="d-flex align-items-center refresh-recaptcha">
                                    <img src="{{ URL('/branch/auth/code/captcha/1') }}" class="rounded" id="default_recaptcha_id">
                                    <i class="tio-refresh icon"></i>
                                </div>
                            </div>
                        </div>
                    @endif

                    <button type="submit" class="btn btn-block btn--primary">{{translate('login')}}</button>
                </form>

                @if(env('APP_MODE')=='demo')
                <div class="auto-fill-data-copy">
                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                        <div>
                            <span class="d-block"><strong>Email</strong> : test.branch@gmail.com</span>
                            <span class="d-block"><strong>Password</strong> : 12345678</span>
                        </div>
                        <div>
                            <button class="btn action-btn btn--primary m-0" id="copyButton"><i class="tio-copy"></i></button>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</main>


<script src="{{asset('public/assets/admin')}}/js/vendor.min.js"></script>

<script src="{{asset('public/assets/admin')}}/js/theme.min.js"></script>
<script src="{{asset('public/assets/admin')}}/js/toastr.js"></script>
{!! Toastr::message() !!}

@if ($errors->any())
    <script>
        @foreach($errors->all() as $error)
        toastr.error('{{$error}}', Error, {
            CloseButton: true,
            ProgressBar: true
        });
        @endforeach
    </script>
@endif


<script>

    $(document).on('ready', function () {
        $('.js-toggle-password').each(function () {
            new HSTogglePassword(this).init()
        });

        $('.js-validate').each(function () {
            $.HSCore.components.HSValidation.init($(this));
        });
    });
</script>

@if(isset($recaptcha) && $recaptcha['status'] == 1)
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://www.google.com/recaptcha/api.js?render={{$recaptcha['site_key']}}"></script>
    <script>
        "use strict";
        $('#signInBtn').click(function (e) {
            e.preventDefault();

            if (typeof grecaptcha === 'undefined') {
                toastr.error('Invalid recaptcha key provided. Please check the recaptcha configuration.');
                return;
            }

            grecaptcha.ready(function () {
                grecaptcha.execute('{{$recaptcha['site_key']}}', {action: 'submit'}).then(function (token) {
                    document.getElementById('g-recaptcha-response').value = token;
                    document.querySelector('form').submit();
                });
            });

            window.onerror = function(message) {
                var errorMessage = 'An unexpected error occurred. Please check the recaptcha configuration';
                if (message.includes('Invalid site key')) {
                    errorMessage = 'Invalid site key provided. Please check the recaptcha configuration.';
                } else if (message.includes('not loaded in api.js')) {
                    errorMessage = 'reCAPTCHA API could not be loaded. Please check the recaptcha API configuration.';
                }
                toastr.error(errorMessage)
                return true;
            };
        });
    </script>
@else
    <script type="text/javascript">
        $('.refresh-recaptcha').on('click', function() {
            re_captcha();
        });

        function re_captcha() {
            var $url = "{{ URL('/branch/auth/code/captcha') }}";
            var $url = $url + "/" + Math.random();
            document.getElementById('default_recaptcha_id').src = $url;
        }
    </script>
@endif

@if(env('APP_MODE')=='demo')
    <script>
        $('#copyButton').on('click', function() {
            copy_cred();
        });

        function copy_cred() {
            $('#signinSrEmail').val('test.branch@gmail.com');
            $('#signupSrPassword').val('12345678');
            toastr.success('Copied successfully!', 'Success!', {
                CloseButton: true,
                ProgressBar: true
            });
        }
    </script>
@endif

<script>
    if (/MSIE \d|Trident.*rv:/.test(navigator.userAgent)) document.write('<script src="{{asset('public/assets/admin')}}/vendor/babel-polyfill/polyfill.min.js"><\/script>');
</script>
</body>
</html>
