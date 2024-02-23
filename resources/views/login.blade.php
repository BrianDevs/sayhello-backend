<!DOCTYPE html>
<html lang="en">

<head>
    <base href="">
    <title>Say Hello</title>
    <meta charset="utf-8" />
    <meta name="author" content="Codemeg Solution Pvt. Ltd., info@codemeg.com">
    <meta name="url" content="http://codemeg.com">
    <meta name="description" content="Say Hello" />
    <meta name="keywords" content="Say Hello" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="Say Hello" />
    <meta property="og:url" content="Say Hello" />
    <meta property="og:site_name" content="Say Hello" />
    <link rel="shortcut icon" href="assets/media/logos/favicon.ico" />
    <!--Css Stylesheets-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <link href="{{ asset('assets/plugins/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!--Css Stylesheets-->
</head>

<body id="kt_body" class="bg-body">
    <div class="d-flex flex-column flex-root">
        <div class="d-flex flex-column flex-column-fluid bgi-position-y-bottom position-x-center bgi-no-repeat bgi-size-contain bgi-attachment-fixed">
            <div class="d-flex flex-center flex-column flex-column-fluid p-10 pb-lg-20">
                <div class="w-lg-500px bg-body rounded shadow-sm p-10 p-lg-15 mx-auto">
                    <div class="login-logo">
                        <a href="index.html">
                            <img alt="Logo" src="assets/media/logos/logo.png" class="" />
                        </a>
                    </div>
                        <form action="{{ route('admin-login')}}" method="POST" class="form w-100" novalidate="novalidate">
                            @csrf
                            <div class="mb-5">
                                <h1 class="text-dark fs-2">Login to Say Hello Admin Panel</h1>
                            </div>
                            @error('credentials')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <div class="fv-row mb-10">
                                <label class="form-label fs-6 fw-bolder text-dark">Email</label>
                                <input class="form-control form-control-lg" type="text" name="email" autocomplete="off" />
                                @error('email')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="fv-row mb-10">
                                <div class="d-flex flex-stack mb-2">
                                    <label class="form-label fw-bolder text-dark fs-6 mb-0">Password</label>
                                    <a href="password-reset.html" class="link-primary fs-6 fw-bolder">Forgot Password ?</a>
                                </div>
                                <input class="form-control form-control-lg" type="password" name="password" autocomplete="off" />
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-lg btn-shadow btn-primary w-100 mb-5">Login</button>
                                {{-- <a  type="submit" class="btn btn-lg btn-shadow btn-primary w-100 mb-5">Login</a> --}}
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>
    <!--begin::Javascript-->
    <script src="{{ asset('assets/plugins/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
</body>

</html>