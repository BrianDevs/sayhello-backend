@extends ('admin.layouts.app')
@section ('title') Welcome to {{ config('app.name') }} @endsection
@section ('content')
         <!--begin::Content-->
         <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
            <div class="toolbar" id="kt_toolbar">
                <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                    <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                        <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Dashboard
                        </h1>
                    </div>
                </div>
            </div>
            <div class="post d-flex flex-column-fluid" id="kt_post">
                <div id="kt_content_container" class="container-xxl">
                    <div class="row g-5 g-xl-10 mb-xl-10">
                        <div class="col-md-6 col-lg-6 col-xl-4 col-xxl-3">
                            <div class="card">
                                <div class="card-header py-6">
                                    <div class="card-title flex-column align-items-start">
                                        <div class="d-flex align-items-center">
                                            <span class="fs-2hx fw-bolder text-dark me-2 lh-1 ls-n2">{{ count($TotalUsers) > 0 ? count($TotalUsers) : 0  }}</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span class="text-gray-400 pt-1 fw-bold fs-6">Total Users</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-lg-6 col-xl-4 col-xxl-3">
                            <div class="card">
                                <div class="card-header py-6">
                                    <div class="card-title flex-column align-items-start">
                                        <div class="d-flex align-items-center">
                                            <span class="fs-2hx fw-bolder text-dark me-2 lh-1 ls-n2">183</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span class="text-gray-400 pt-1 fw-bold fs-6">Total Matched User</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6 col-xl-4 col-xxl-3">
                            <div class="card">
                                <div class="card-header py-6">
                                    <div class="card-title flex-column align-items-start">
                                        <div class="d-flex align-items-center">
                                            <span class="fs-2hx fw-bolder text-dark me-2 lh-1 ls-n2">{{ count($totalSponsers) > 0 ? count($totalSponsers) : 0  }}</span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span class="text-gray-400 pt-1 fw-bold fs-6">Total Sponsers</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-xl-12 col-xxl-12 mb-5 mb-xl-0">
                            <div class="card card-flush overflow-hidden h-md-100">
                                <div class="card-header py-5">
                                    <h3 class="card-title align-items-start flex-column">
                                        <span class="card-label fw-bolder text-dark">Sales This Months</span>
                                        <span class="text-gray-400 mt-1 fw-bold fs-6">Users from all
                                            channels</span>
                                    </h3>
                                    <div class="card-toolbar">
                                        <a href="{{ route('users') }}"
                                            class="btn btn-sm btn-light-primary fw-bolder">Users List</a>
                                    </div>
                                </div>
                                <div class="card-body d-flex justify-content-between flex-column pb-1 px-0">
                                    
                                    <div id="kt_charts_widget_3" class="min-h-auto ps-4 pe-6"
                                        style="height: 300px"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <!--end::Content-->
@endsection

