<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Error 500 | GroFresh</title>

    <link rel="shortcut icon" href="favicon.ico">

    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&amp;display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/css/vendor.min.css">
    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/vendor/icon-set/style.css">

    <link rel="stylesheet" href="{{asset('public/assets/admin')}}/css/theme.minc619.css?v=1.0">
</head>

<body>

<div class="container">
    <div class="footer-height-offset d-flex justify-content-center align-items-center flex-column">
        <div class="row align-items-sm-center w-100">
            <div class="col-sm-6">
                <div class="text-center text-sm-right mr-sm-4 mb-5 mb-sm-0">
                    <img class="w-60 w-sm-100 mx-auto" src="{{asset('public/assets/admin')}}/svg/illustrations/think.svg" alt="Image Description" style="max-width: 15rem;">
                </div>
            </div>

            <div class="col-sm-6 col-md-4 text-center text-sm-left">
                <h1 class="display-1 mb-0">500</h1>
                <p class="lead">The server encountered an internal error or misconfiguration and was unable to complete your request.</p>
                <a class="btn btn-primary" href="{{url()->current()}}">Reload page</a>
            </div>
        </div>
    </div>
</div>

<div class="footer text-center">
    <ul class="list-inline list-separator">
        <li class="list-inline-item">
            <a class="list-separator-link" target="_blank" href="https://6amtech.com/">groFresh Support</a>
        </li>
    </ul>
</div>


<!-- JS Front -->
<script src="{{asset('public/assets/admin')}}/js/theme.min.js"></script>
</body>

</html>
