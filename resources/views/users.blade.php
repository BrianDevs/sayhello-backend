@extends ('admin.layouts.app')
@section ('title') Welcome to {{ config('app.name') }} @endsection
@section ('content')
          <!--begin::Content-->
          <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
            <div class="toolbar" id="kt_toolbar">
                <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                    <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                        <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">User</h1>
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
                        </ul>
                        <!--end::Breadcrumb-->
                    </div>
                </div>
            </div>
            <div class="post d-flex flex-column-fluid" id="kt_post">
                <div id="kt_content_container" class="container-xxl">
                    <div class="card card-flush">
                        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                            <div class="card-title">
                                <div class="d-flex align-items-center position-relative my-1">
                                    <span class="svg-icon svg-icon-1 position-absolute ms-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none">
                                            <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546"
                                                height="2" rx="1" transform="rotate(45 17.0365 15.1223)"
                                                fill="black" />
                                            <path
                                                d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                                fill="black" />
                                        </svg>
                                    </span>
                                    <input type="text" id="searchUsers" data-kt-ecommerce-order-filter="search" class="form-control form-control-solid w-250px ps-14" placeholder="Search User" />
                                </div>
                            </div>
                            <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <!--start::Table-->
                            <div class="table-responsive order-pgtp">
                                <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
                                    <thead>
                                        <tr class="fw-bolder text-muted">
                                            <th class="w-25px">#</th>
                                            <th class="min-w-150px">User Details</th>
                                            <th class="min-w-100px">Contact Detail</th>
                                            <th class="min-w-100px">Profile Status</th>
                                            <th class="min-w-100px">Registration Date</th>
                                            <th class="min-w-70px text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="hideThis">
                                        @php
                                            $no = 1;
                                        @endphp
                                        @if (count($users) > 0)
                                            @foreach ($users as $item)
                                                <tr>
                                                    <td>
                                                        <span class="">{{ $no++ }}</span>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="symbol symbol-45px me-5">
                                                                <img @if ($item->image == null) src="{{ asset('uploads/default_user.png') }}" @else src="{{ asset('uploads/user/'.$item->image) }}" @endif  alt="">
                                                            </div>
                                                            <div class="d-flex justify-content-start flex-column">
                                                                <span class="text-dark fw-bolder fs-6">{{ $item->name != null ?  $item->name : 'Known'   }}</span>
                                                                {{-- <span class="text-muted text-muted d-block fs-7">User
                                                                    id:#12356</span> --}}
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="">
                                                        <div class="d-flex justify-content-start flex-column">
                                                        <span class="text-muted text-muted d-block fs-7"><i
                                                            class="bi bi-telephone-fill"></i>
                                                        {{ $item->phone }}</span>
                                                        {{-- <span class="text-muted text-muted d-block fs-7"><i
                                                            class="bi bi-envelope-fill"></i>
                                                            annaSimons@gmail.com</span> --}}
                                                        </div>
                                                    </td>
                                                    <td class="">
                                                        <div class="d-flex">
                                                            
                                                            @if ($item->image_varified == 0)
                                                                <span class="badge badge-warning fs-9">Pending</span>
                                                            @else
                                                                <span class="badge badge-success fs-9">Approved</span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td class="">
                                                        <div class="d-flex">
                                                            <span class="text-muted">{{ date('d/m/Y', strtotime($item->created_at)) }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="text-end pe-0">
                                                        <div class="d-flex justify-content-end align-items-center">
                                                            <div class="svcrd-togl me-3">
                                                                <div class="tgl-sld">
                                                                    @if ($item->is_active == 1)
                                                                        <label>
                                                                            <input checked onchange="userACDAC({{ $item->id }})" type="checkbox" name="prprtyrelation" />
                                                                            <span>
                                                                                <i></i>
                                                                            </span>
                                                                        </label>
                                                                    @else
                                                                        <label>
                                                                            <input onchange="userACDAC({{ $item->id }})" type="checkbox" name="prprtyrelation" />
                                                                            <span>
                                                                                <i></i>
                                                                            </span>
                                                                        </label>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            <div class="d-flex justify-content-end flex-shrink-0 align-items-center">
                                                                <a href="{{ route('user-detail',$item->id) }}" class="btn btn-sm btn-icon btn-bg-light btn-active-color-primary me-3">
                                                                    <span class="svg-icon svg-icon-2">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                                            <rect opacity="0.5" x="18" y="13" width="13" height="2" rx="1" transform="rotate(-180 18 13)" fill="black">
                                                                            </rect>
                                                                            <path d="M15.4343 12.5657L11.25 16.75C10.8358 17.1642 10.8358 17.8358 11.25 18.25C11.6642 18.6642 12.3358 18.6642 12.75 18.25L18.2929 12.7071C18.6834 12.3166 18.6834 11.6834 18.2929 11.2929L12.75 5.75C12.3358 5.33579 11.6642 5.33579 11.25 5.75C10.8358 6.16421 10.8358 6.83579 11.25 7.25L15.4343 11.4343C15.7467 11.7467 15.7467 12.2533 15.4343 12.5657Z" fill="black"></path>
                                                                        </svg>
                                                                    </span>
                                                                </a>
        
                                                                <a href="#" onclick="deleteUser({{ $item->id }})" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm" data-bs-toggle="modal" data-bs-target="#kt_modal_create_campaign">
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
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                    
                                    <tbody style="display: none" id="showThis">
                                        
                                    </tbody>
                                </table>
                            </div>
                            {{-- {{ $users->links() }} --}}
                            
                            <!--end::Table -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::content-->
@endsection