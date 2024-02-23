<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

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
    <!--start::Stylesheets link-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <link href="{{ asset('assets/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Stylesheets link-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
    <style>
        #question-error{
            color: red;
        }
        #Question_question-error{
            color: red;
        }
    </style>
</head>

<!--end::Head-->
<!--begin::Body-->

<body id="kt_body" class="header-fixed header-tablet-and-mobile-fixed toolbar-enabled toolbar-fixed toolbar-tablet-and-mobile-fixed aside-enabled aside-fixed" style="--kt-toolbar-height:55px;--kt-toolbar-height-tablet-and-mobile:55px">
    <!--begin::Main-->
    <div class="d-flex flex-column flex-root">
        <div class="page d-flex flex-row flex-column-fluid">
            <!--start::aside-->
            <div id="kt_aside" class="aside aside-dark aside-hoverable" data-kt-drawer="true" data-kt-drawer-name="aside" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}"
                data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_aside_mobile_toggle">
                <div class="aside-logo flex-column-auto" id="kt_aside_logo">
                    <a href="{{ route('index') }}">
                        <img alt="Logo" src="{{ asset('assets/media/logos/logo.png') }}" class="logo" />
                    </a>
                    <div id="kt_aside_toggle" class="btn btn-icon w-auto px-0 btn-active-color-success aside-toggle" data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body" data-kt-toggle-name="aside-minimize">
                        <span class="svg-icon svg-icon-1 rotate-180">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none">
                                <path
                                    d="M11.2657 11.4343L15.45 7.25C15.8642 6.83579 15.8642 6.16421 15.45 5.75C15.0358 5.33579 14.3642 5.33579 13.95 5.75L8.40712 11.2929C8.01659 11.6834 8.01659 12.3166 8.40712 12.7071L13.95 18.25C14.3642 18.6642 15.0358 18.6642 15.45 18.25C15.8642 17.8358 15.8642 17.1642 15.45 16.75L11.2657 12.5657C10.9533 12.2533 10.9533 11.7467 11.2657 11.4343Z"
                                    fill="black" />
                            </svg>
                        </span>
                    </div>
                </div>
                <div class="aside-menu flex-column-fluid">
                    <div class="hover-scroll-overlay-y my-2 py-5 py-lg-8" id="kt_aside_menu_wrapper" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_aside_logo, #kt_aside_footer" data-kt-scroll-wrappers="#kt_aside_menu"
                        data-kt-scroll-offset="0">
                        <div class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500" id="#kt_aside_menu" data-kt-menu="true">
                            <div class="menu-item here">
                                <a class="menu-link  {{ request()->routeIs('index') ? 'active' : '' }}" href="{{ route('index') }}">
                                    <span class="menu-icon">
                                        <i class="bi bi-speedometer2 fs-3"></i>
                                    </span>
                                    <span class="menu-title">Dashboard</span>
                                </a>
                            </div>
                            <div class="menu-item here">
                                <a class="menu-link {{ request()->routeIs('users') ? 'active' : '' }}" href="{{ route('users') }}">
                                    <span class="menu-icon">
                                        <i class="bi bi-people-fill fs-3"></i>
                                    </span>
                                    <span class="menu-title">Users</span>
                                </a>
                            </div>
                            <div class="menu-item here">
                                <a class="menu-link {{ request()->routeIs('questions') ? 'active' : '' }}" href="{{ route('questions') }}">
                                    <span class="menu-icon">
                                        <i class="bi bi-people-fill fs-3"></i>
                                    </span>
                                    <span class="menu-title">Questions</span>
                                </a>
                            </div>
                            <div class="menu-item here">
                                <a class="menu-link {{ request()->routeIs('sponsers') ? 'active' : '' }}"  href="{{ route('sponsers') }}">
                                    <span class="menu-icon">
                                        <i class="bi bi-people-fill fs-3"></i>
                                    </span>
                                    <span class="menu-title">Sponsers</span>
                                </a>
                            </div>
                            <div class="menu-item here">
                                <a class="menu-link {{ request()->routeIs('banner') ? 'active' : '' }}" href="{{ route('banner') }}">
                                    <span class="menu-icon">
                                        <i class="bi bi-people-fill fs-3"></i>
                                    </span>
                                    <span class="menu-title">Banner</span>
                                </a>
                            </div>
                            <div class="menu-item here">
                                <a class="menu-link" href="{{ route('logout') }}">
                                    <span class="menu-icon">
                                        <i class="bi bi-box-arrow-right fs-3"></i>
                                    </span>
                                    <span class="menu-title">Log Out</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::aside-->
            <!--begin::Wrapper-->
            <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
                <!--start::Header-->
                <div id="kt_header" class="header align-items-stretch">
                    <div class="container-fluid d-flex align-items-stretch justify-content-between">
                        <div class="d-flex align-items-center d-lg-none ms-n3 me-1" title="Show aside menu">
                            <div class="btn btn-icon btn-active-color-white" id="kt_aside_mobile_toggle">
                                <i class="bi bi-list fs-1"></i>
                            </div>
                        </div>
                        <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
                            <a href="#" class="d-lg-none">
                                <img alt="Logo" src="assets/media/logos/logo.png" class="h-25px" />
                            </a>
                        </div>
                        <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1">
                            <div class="d-flex align-items-stretch" id="kt_header_nav">
                            </div>
                            <div class="topbar d-flex align-items-stretch flex-shrink-0">
                                {{-- <div class="d-flex align-items-stretch">
                                    <div class="topbar-item px-3 px-lg-5 position-relative" id="kt_drawer_chat_toggle">
                                        <i class="bi bi-bell fs-2"></i>
                                        <span class="bullet bullet-dot bg-success h-9px w-9px position-absolute translate-middle mt-4"></span>
                                    </div>
                                </div> --}}
                                <div class="d-flex align-items-stretch d-lg-none px-3 me-n3" title="Show header menu">
                                    <div class="topbar-item" id="kt_header_menu_mobile_toggle">
                                        <i class="bi bi-text-left fs-1"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Header-->

    @yield('content')

    <!--begin::Footer-->
    <div class="footer py-4 d-flex flex-lg-column" id="kt_footer">
        <div class="container-fluid d-flex flex-column flex-md-row align-items-center justify-content-between">
            <div class="text-dark order-2 order-md-1">
                <span class="text-muted fw-bold me-1">2023©</span>
                <span class="text-gray-800">Say Hello</span>
            </div>
        </div>
    </div>
    <!--end::Footer-->
</div>
<!--end::Wrapper-->
</div>
</div>
<!--end::main-->
<!--begin::Notification-->
{{-- <div id="kt_drawer_chat" class="bg-body" data-kt-drawer="true" data-kt-drawer-name="chat" data-kt-drawer-activate="true" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'300px', 'md': '500px'}" data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_drawer_chat_toggle"
data-kt-drawer-close="#kt_drawer_chat_close">
<div class="card w-100 rounded-0 border-0" id="kt_drawer_chat_messenger">
<div class="card-header pe-5" id="kt_drawer_chat_messenger_header">
<div class="card-title">
    <div class="d-flex justify-content-center flex-column me-3">
        <h2 class="fs-4 fw-bolder text-gray-900  me-1 mb-2 lh-1 ">
            Notification<span class="badge badge-warning badge-circle w-10px h-10px ms-1"></span>
        </h2>
    </div>
</div>
<div class="card-toolbar">
    <div class="btn btn-sm btn-icon btn-active-light-success" id="kt_drawer_chat_close">
        <span class="svg-icon svg-icon-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                fill="none">
                <rect opacity="0.5" x="6" y="17.3137" width="16" height="2" rx="1"
                    transform="rotate(-45 6 17.3137)" fill="black" />
                <rect x="7.41422" y="6" width="16" height="2" rx="1" transform="rotate(45 7.41422 6)"
                    fill="black" />
            </svg>
        </span>
    </div>
</div>
</div>
<div class="card-body" id="kt_drawer_chat_messenger_body">
<div class="scroll-y me-n5 pe-5" data-kt-element="messages" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_drawer_chat_messenger_header, #kt_drawer_chat_messenger_footer" data-kt-scroll-wrappers="#kt_drawer_chat_messenger_body"
    data-kt-scroll-offset="0px">
   <div class=" mb-5">
        <div class="p-4 rounded bg-light-info text-dark fw-bold  text-start" data-kt-element="message-text">
            <h5 class="m-0">New User added</h5>
            <span class="text-muted fw-bold text-muted d-block fs-7">Rahul is currently Joined</span>
        </div>
    </div>
    <div class=" mb-5">
        <div class="p-4 rounded bg-light-success text-dark fw-bold  text-start" data-kt-element="message-text">
            <h5 class="m-0">New Matched</h5>
            <span class="text-muted fw-bold text-muted d-block fs-7">Rahul and Shivani Recently Matched.</span>
        </div>
    </div>
</div>
</div>
</div>
</div> --}}
<!--end::Notification-->

<!--begin::Scrolltop-->
<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
<span class="svg-icon">
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
    <rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)"
        fill="black" />
    <path
        d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z"
        fill="black" />
</svg>
</span>
</div>
<!--end::Scrolltop-->


<script>
    var baseURL = "{{ url('/') }}";
    console.log(baseURL);
</script>
<!--begin::Javascript-->
<script src="{{ asset('assets/plugins/plugins.bundle.js') }}"></script>
<script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
<script src="{{ asset('assets/datatables/datatables.bundle.js') }}"></script>
<script src="{{ asset('assets/js/widgets.bundle.js') }}"></script>
<script src="{{ asset('assets/js/widgets.js') }}"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

<script>
    function questionAcDac(id)
    {
        // alert(id)
        // console.log(id);
        $.ajax({
            url : baseURL+"/questionAcDac/"+id,
            type:"get",
            success: function(response){
                // console.log(response);
                if(response.message === 'deactive')
                {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'You have deactive!',
                        showConfirmButton: false,
                        timer: 1500
                    })
                }else{
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'You have active!',
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
            },
            error:function(err){
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: err,
                    showConfirmButton: false,
                    timer: 1500
                })
            },
        })
    }
    function deleteQuestion(id){
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                url: baseURL+'/deleteQuestion/'+id,
                type:"GET",
                success: function(response) {
                    Swal.fire(
                        'Deleted!',
                        'Question has been deleted.',
                        'success'
                    )
                    location.reload() 
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: 'Something went wrong',
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
        })
            }
        })
    }

    function QuestionEdit(id){
        $.ajax({
            type:'get',
            url:baseURL+'/QuestionEdit/'+id,

            success : function(response){
                if(response.status == 200){
                    console.log(response.data);
                    document.getElementById("Question_id").value = response.data.id;
                    document.getElementById("Question_question").value = response.data.question;
                    // document.getElementById('EditMealImage').style.backgroundImage='url('+ baseURL+'/uploads/meal/'+ response.data.image +')'; 
                    // document.getElementById('avatar_value').value= response.data.image;
                }
            }
        });
	}
</script>
<script>
    $('#questionForm').validate({
        rules: {
            question: {
                required: true,
                // number: true
            },
        },
        messages: {
            question: {
                required: "Question is required.",
                // number: "Number or decimal numbers only"
            },
        },
        submitHandler: function(form) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            let formData = new FormData($('#questionForm')[0]);
            $.ajax({
                type: 'POST',
                url: baseURL + '/questionForm',
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    // console.log(data);
                    if(data.message === 'added'){
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'You have added Question!',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        location.reload();
                    }else{
                        // console.log(data);
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: 'Something went wrong!',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        location.reload();
                    }
                }
            });
        }
    });
</script>
<script>
    $('#updateQuestionForm').validate({
        rules: {
            question: {
                required: true,
            },
        },
        messages: {
            question: {
                required: "Question is required.",
            },
        },
        submitHandler: function(form) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            let formData = new FormData($('#updateQuestionForm')[0]);
            $.ajax({
                type: 'POST',
                url: baseURL + '/updateQuestionForm',
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    if(data.message === 'updated'){
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'You have updated Question!',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        location.reload();
                    }else{
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: 'Something went wrong!',
                            showConfirmButton: false,
                            timer: 1500
                        })
                        location.reload();
                    }
                }
            });
        }
    });
</script>
<script>
    $(document).ready(function() {
        // When the user types in the searchUsers input field
        $("#searchUsers").keyup(function() {
            // Get the value of the input field
            var value = $("#searchUsers").val();
            // var url = value.length > 0 ?   : "{{ route('users') }}"; 
            // Send a GET request to the baseURL + '/search/' + value URL using AJAX
            if(value.length > 0){
                $.ajax({
                    url: baseURL + '/search/' + value,
                    type: "GET",
                    success: function(response) {
                        if(response.status == 200){
                            // If the request was successful, log the response to the console
                            // console.log(response.data);
                            var no = 0;
                            $('#hideThis').hide();
                            $('#showThis').show();
                            $('#showThis').html('');
                            var userData = response.data;
                            for (let i = 0; i < userData.length; i++) {
                                // console.log(userData[i].name)
                                var image = 0;
                                if(userData[i].image == null){
                                    image = baseURL+ '/uploads/default_user.png';
                                }else{
                                    image = baseURL+ '/uploads/user/'+userData[i].image;
                                }
                                var varified = 0;
                                if(userData[i].varified == null){
                                    varified = `<span class="badge badge-warning fs-9">Pending </span>` ;
                                }else{
                                    varified = `<span class="badge badge-success fs-9">Approved </span>` ;
                                }
                                var acitve = 0;
                                if(userData[i].is_active == 1){
                                    acitve = `<label><input checked onchange="userACDAC(`+ userData[i].id+`)" type="checkbox" name="prprtyrelation" /><span><i></i></span></label>`
                                }else{
                                    acitve = `<label><input  onchange="userACDAC(`+ userData[i].id+`)" type="checkbox" name="prprtyrelation" /><span><i></i></span></label>`
                                }
                                var userDetailRoute = baseURL+"/user-detail/" + userData[i].id;
                                var created_at = new Date(userData[i].created_at);
                                var day = created_at.getDate();
                                var month = created_at.getMonth() + 1;
                                var year = created_at.getFullYear();
                                var created_day = day > 9 ? day : '0'+day;
                                var created_month = month > 9 ? month : '0'+month;
                                var formatted_date = created_day + '/' + created_month + '/' + year;
                                $('#showThis').append(`<tr>
                                                                <td>
                                                                    <span class="">`+ (no += 1) +`</span>
                                                                </td>
                                                                <td>
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="symbol symbol-45px me-5">
                                                                            <img src="`+image +`" alt="">
                                                                        </div>
                                                                        <div class="d-flex justify-content-start flex-column">
                                                                            <span class="text-dark fw-bolder fs-6">`+userData[i].name +`</span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td class="">
                                                                    <div class="d-flex justify-content-start flex-column">
                                                                    <span class="text-muted text-muted d-block fs-7"><i
                                                                        class="bi bi-telephone-fill"></i>
                                                                        `+userData[i].phone+`</span>
                                                                    </div>
                                                                </td>
                                                                <td class="">
                                                                    <div class="d-flex">
                                                                        `+ varified +`
                                                                    </div>
                                                                </td>
                                                                <td class="">
                                                                    <div class="d-flex">
                                                                        <span class="text-muted">`+ formatted_date +`</span>
                                                                    </div>
                                                                </td>
                                                                <td class="text-end pe-0">
                                                                    <div class="d-flex justify-content-end align-items-center">
                                                                        <div class="svcrd-togl me-3">
                                                                            <div class="tgl-sld">
                                                                                `+acitve+`
                                                                            </div>
                                                                        </div>
                                                                        <div class="d-flex justify-content-end flex-shrink-0 align-items-center">
                                                                            <a href="`+userDetailRoute+`" class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary me-3">
                                                                                <span class="svg-icon svg-icon-2">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                                        <rect opacity="0.5" x="18" y="13" width="13" height="2" rx="1" transform="rotate(-180 18 13)" fill="black">
                                                                                        </rect>
                                                                                        <path d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z" fill="black"></path>
                                                                                    </svg>
                                                                                </span>
                                                                            </a>
                    
                                                                            <a href="#" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm" data-bs-toggle="modal" data-bs-target="#kt_modal_create_campaign">
                                                                                <span class="svg-icon svg-icon-2">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" opacity="0.3">
                                                                                        <path d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z">
                                                                                        </path>
                                                                                        <path opacity="0.5" d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z">
                                                                                        </path>
                                                                                        <path opacity="0.5" d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z">
                                                                                        </path>
                                                                                    </svg>
                                                                                </span>
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>`
                                );
                            }
                            
                        }
                    },
                    error: function(error) {
                        // If there was an error with the request, do nothing
                        console.log(error);
                    }
                });
            }else{
                var url = "{{ route('users') }}";
                location.href = url;
            }
        });
    });
</script>
<script>
    function userACDAC(id){
        $.ajax({
            url: baseURL+"/is_varified_user/"+id,
            type: "GET",
            success: function(response){
                if(response.message === 'deactive')
                {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'You have deactive!',
                        showConfirmButton: false,
                        timer: 1500
                    })
                }else{
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'You have active!',
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
            },
            error: function(error){
                console.log('fail', error);

            },
        })
    }
</script>
<script>
    function deleteUser(id){
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                url: baseURL+"/delete_User/"+id,
                type:"GET",
                success: function(response) {
                    Swal.fire(
                        'Deleted!',
                        'Your file has been deleted.',
                        'success'
                    )
                    location.reload() 
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: 'Something went wrong',
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
        })
            }
        })
    }
</script>
<!--end::Javascript-->
</body>
<!--end::Body-->

</html>