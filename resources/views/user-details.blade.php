@extends ('admin.layouts.app')
@section ('title') Welcome to {{ config('app.name') }} @endsection
@section ('content')

 <!--begin::Content-->
 <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">User Detail</h1>
                <span class="h-20px border-gray-300 border-start mx-4"></span>
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('index') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-300 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-dark">User</li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-300 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-dark">User Detail</li>
                </ul>
                <!--end::Breadcrumb-->
            </div>
        </div>
    </div>
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <div class="d-flex flex-column flex-xl-row">
                <div class="flex-column flex-lg-row-auto w-100 mw-xl-48">
                    <div class="card mb-5 mb-xl-8">
                        <div class="card-body pt-15 position-relative">
                            <div class="edtquestion-main">
                                <div class="d-flex align-items-center py-1">
                                    <a href="{{ route('user-question-answer-list',$user->id) }}" class="btn btn-sm btn-success">View Question</a>
                                </div>
                            </div>
                            <div class="d-flex flex-center flex-column mb-5">
                                <div class="symbol symbol-100px symbol-circle mb-7">

                                    <img @if($user->image == null) src="{{ asset('uploads/default_user.png') }}"  @else src="{{ asset("uploads/user/".$user->image) }}" @endif alt="image">
                                </div>
                                <a href="#" class="fs-3 text-gray-800 text-hover-primary fw-bolder mb-1">
                                    @if($user->located_in != null) {{ $user->name }} @else {{ "Known" }} @endif
                                </a>
                            </div>
                            <div class="d-flex flex-stack fs-4">
                                <div class="fw-bolder rotate collapsible active">User Details
                                </div>
                            </div>
                            <div class="separator separator-dashed my-3"></div>
                            <div id="kt_customer_view_details" class="collapse show">
                                <div class="py-0 fs-6">
                                    <div class="d-flex  justify-content-between mt-8">
                                        <div class="fw-bolder fs-7">Contact No.</div>
                                        <div class="text-gray-600">
                                            <span class="text-gray-600 text-hover-primary">{{$user->phone }}</span>
                                        </div>
                                    </div>
                                    <div class="separator separator-dashed my-5"></div>
                                    <div class="d-flex  justify-content-between mt-5">
                                        <div class="fw-bolder fs-7">Date Of Registration</div>
                                        <div class="text-gray-600">
                                            <span class="text-gray-600 text-hover-primary">{{ date_format($user->created_at, "d/m/Y") }}</span>
                                        </div>
                                    </div>
                                    <div class="separator separator-dashed my-5"></div>
                                    <div class="d-flex  justify-content-between mt-5">
                                        <div class="fw-bolder fs-7">Status</div>
                                        <div class="text-warning">
                                            @if ($user->image_varified == 0)
                                                <span class="text-warning text-hover-warning">Pending</span>
                                            @else
                                                <span class="text-success text-hover-success">Approved</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="separator separator-dashed my-5"></div>
                                    <div class="d-flex  justify-content-between mt-5">
                                        <div class="fw-bolder fs-7">Country</div>
                                        <div class="text-gray-600"> @if($user->located_in != null) {{ $user->located_in }} @else {{ "N/A" }} @endif </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if ($user->front_doc_image != null)
                    <div class="flex-column flex-lg-row-auto w-100 mw-xl-48 ms-lg-13">
                        <div class="card mb-5 mb-xl-8">
                            <div class="card-title p-8 pt-5 pb-1 mb-0">
                                <h3 class="fw-bolder m-0">Doucument Details</h3>
                            </div>
                            <div class="card-body pt-2">
                                <div class="notice notice-background mb-10">
                                    <ul>
                                        <li>
                                            <div class="bg-truckbgrund" style="background-image:url({{ asset('uploads/userDocs/'.$user->front_doc_image) }})">
                                            </div>
                                        </li>
                                        <li>
                                            <div class="bg-truckbgrund" style="background-image:url({{ asset('uploads/userDocs/'.$user->back_doc_image) }})">
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <div class="flex-lg-row-fluid mw-xl-100">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="kt_customer_view_overview_tab" role="tabpanel">
                        <div class="card card-flush">
                            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                                <div class="card-title">
                                    <div class="d-flex align-items-center position-relative my-1">
                                        <span class="svg-icon svg-icon-1 position-absolute ms-4">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                height="24" viewBox="0 0 24 24" fill="none">
                                                <rect opacity="0.5" x="17.0365" y="15.1223"
                                                    width="8.15546" height="2" rx="1"
                                                    transform="rotate(45 17.0365 15.1223)"
                                                    fill="black" />
                                                <path
                                                    d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                                    fill="black" />
                                            </svg>
                                        </span>
                                        <input type="text" data-kt-ecommerce-order-filter="search" class="form-control form-control-solid w-250px ps-14" placeholder="Search Here.." />
                                    </div>
                                </div>
                                <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                                    <div class="w-100 mw-150px">
                                        <select class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Matched Status" data-kt-ecommerce-order-filter="status">
                                            <option value="All">All</option>
                                            <option value="Running">Running</option>
                                            <option value="Completed">Completed</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="table-responsive">
                                    <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                        <thead>
                                            <tr class="fw-bolder text-muted">
                                                <th class="w-25px">#</th>
                                                <th class="min-w-150px">Matched User</th>
                                                <th class="text-end min-w-70px">User Match Type</th>
                                                <th class="text-end min-w-100px">Points</th>
                                                <th class="text-end min-w-120px">Date Time</th>
                                                <th class="text-end min-w-120px">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <span class="fw-bolder">1</span>
                                                </td>
                                                <td>
                                                    <div class=" d-flex align-items-center ">
                                                        <div class=" d-flex justify-content-start flex-column ">
                                                            <span class=" text-dark fw-bolder fs-6 ">David Willey</span>
                                                            <span class=" text-muted fw-bold text-muted d-block fs-7">User Id:#12356</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end pe-0">
                                                    <span class="">Matched (25%)</span>
                                                </td>
                                                <td class="text-end">
                                                    <span class="">100 Points</span>
                                                </td>
                                                <td class="text-end pe-0">
                                                    <span class="dtofjoin "><em>12-05-2022</em>
                                                        04:58PM</span>
                                                </td>
                                                <td class="text-end pe-0">
                                                    <span class="badge badge-success brdr-rds-6 fs-9">Running</span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <span class="fw-bolder">2</span>
                                                </td>
                                                <td>
                                                    <div class=" d-flex align-items-center ">
                                                        <div class=" d-flex justify-content-start flex-column ">
                                                            <span class=" text-dark fw-bolder fs-6 ">David Willey</span>
                                                            <span class=" text-muted fw-bold text-muted d-block fs-7">User Id:#12356</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end pe-0">
                                                    <span class="">Matched (25%)</span>
                                                </td>
                                                <td class="text-end">
                                                    <span class="">100 Points</span>
                                                </td>
                                                <td class="text-end pe-0">
                                                    <span class="dtofjoin"><em>12-05-2022</em>
                                                        04:58PM</span>
                                                </td>
                                                <td class="text-end pe-0">
                                                    <span class="badge badge-danger brdr-rds-6 fs-9">Completed</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <span class="fw-bolder">3</span>
                                                </td>
                                                <td>
                                                    <div class=" d-flex align-items-center ">
                                                        <div class=" d-flex justify-content-start flex-column ">
                                                            <span class=" text-dark fw-bolder fs-6 ">David Willey</span>
                                                            <span class=" text-muted fw-bold text-muted d-block fs-7">User Id:#12356</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end pe-0">
                                                    <span class="">Matched (25%)</span>
                                                </td>
                                                <td class="text-end">
                                                    <span class="">100 Points</span>
                                                </td>
                                                <td class="text-end pe-0">
                                                    <span class="dtofjoin "><em>12-05-2022</em>
                                                        04:58PM</span>
                                                </td>
                                                <td class="text-end pe-0">
                                                    <span class="badge badge-danger brdr-rds-6 fs-9">Completed</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <span class="fw-bolder">4</span>
                                                </td>
                                                <td>
                                                    <div class=" d-flex align-items-center ">
                                                        <div class=" d-flex justify-content-start flex-column ">
                                                            <span class=" text-dark fw-bolder fs-6 ">David Willey</span>
                                                            <span class=" text-muted fw-bold text-muted d-block fs-7">User Id:#12356</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end pe-0">
                                                    <span class="">Matched (25%)</span>
                                                </td>
                                                <td class="text-end">
                                                    <span class="">100 Points</span>
                                                </td>
                                                <td class="text-end pe-0">
                                                    <span class="dtofjoin "><em>12-05-2022</em>
                                                        04:58PM</span>
                                                </td>
                                                <td class="text-end pe-0">
                                                    <span class="badge badge-danger brdr-rds-6 fs-9">Completed</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <span class="fw-bolder">5</span>
                                                </td>
                                                <td>
                                                    <div class=" d-flex align-items-center ">
                                                        <div class=" d-flex justify-content-start flex-column ">
                                                            <span class=" text-dark fw-bolder fs-6 ">David Willey</span>
                                                            <span class=" text-muted fw-bold text-muted d-block fs-7">User Id:#12356</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-end pe-0">
                                                    <span class="">Matched (25%)</span>
                                                </td>
                                                <td class="text-end">
                                                    <span class="">100 Points</span>
                                                </td>
                                                <td class="text-end pe-0">
                                                    <span class="dtofjoin"><em>12-05-2022</em>
                                                        04:58PM</span>
                                                </td>
                                                <td class="text-end pe-0">
                                                    <span class="badge badge-danger brdr-rds-6 fs-9">Completed</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

