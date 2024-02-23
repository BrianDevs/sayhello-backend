@extends ('admin.layouts.app')
@section ('title') Welcome to {{ config('app.name') }} @endsection
@section ('content')

                <!--begin::Content-->
                <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                    <div class="toolbar" id="kt_toolbar">
                        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
                            <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Questions List</h1>
                                <span class="h-20px border-gray-300 border-start mx-4"></span>
                                <!--begin::Breadcrumb-->
                                <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                                    <li class="breadcrumb-item text-muted">
                                        <a href="{{ route('index') }}" class="text-muted text-hover-primary">Home</a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <span class="bullet bg-gray-300 w-5px h-2px"></span>
                                    </li>
                                    <li class="breadcrumb-item text-dark">Questions List</li>
                                </ul>
                                <!--end::Breadcrumb-->
                            </div>
                            {{-- <div class="d-flex align-items-center py-1">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_offer_a_deal" class="btn btn-sm btn-success">Add Questions</a>
                            </div> --}}
                        </div>
                    </div>
                    <div class="post d-flex flex-column-fluid" id="kt_post">
                        <div id="kt_content_container" class="container-xxl">
                            <div class="row gy-5 g-xl-10">
                                <div class="col-xl-12 mb-xl-10">
                                    <div class="card h-md-100">
                                        {{-- <div class="card-header align-items-center mb-5 py-3 gap-2 gap-md-5">
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
                                                    <input type="text" data-kt-ecommerce-product-filter="search" class="form-control form-control-solid w-250px ps-14" placeholder="Search questions here" />
                                                </div>
                                            </div>
                                        </div> --}}
                                        <div class="card-body pt-6">
                                            <!--start::Table-->
                                            <div class="table-responsive prdct-tbl">
                                                <table class="table table-row-dashed align-middle gs-0 gy-4 my-0">
                                                    <thead>
                                                        <tr class="fs-7 fw-bolder text-gray-500">
                                                            <th class="ps-0 w-50px">#</th>
                                                            <th class="min-w-140px">Title</th>
                                                            <th class="min-w-140px pe-0 text-end">ACTIONS</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $no =1;
                                                        @endphp
                                                        @if ( count($Questions) > 0)
                                                            @foreach ($Questions as $item)
                                                                <tr data-id="{{$item->id}}">
                                                                    <td>
                                                                        <span class="">{{ $no++ }}</span>
                                                                    </td>
                                                                    <td>
                                                                        <div class="d-flex justify-content-start flex-column">
                                                                            <span class="text-dark fs-6">{{ $item->question }}</span>
                                                                        </div>
                                                                    </td>
                                                                    <td class="text-end">
                                                                        <div class="d-flex justify-content-end flex-shrink-0">
                                                                            <div class="svcrd-togl me-3">
                                                                                <div class="tgl-sld">
                                                                                    @if ($item->is_active == 1)
                                                                                        <label>
                                                                                            <input  checked onclick="questionAcDac({{$item->id}})" type="checkbox"
                                                                                                name="prprtyrelation">
                                                                                            <span>
                                                                                                <i></i>
                                                                                            </span>
                                                                                        </label>
                                                                                    @else
                                                                                        <label>
                                                                                            <input  onclick="questionAcDac({{$item->id}})" type="checkbox"
                                                                                                name="prprtyrelation">
                                                                                            <span>
                                                                                                <i></i>
                                                                                            </span>
                                                                                        </label>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                            {{-- <a href="#" onclick="QuestionEdit({{ $item->id }})" data-bs-toggle="modal" data-bs-target="#kt_modal_create_app"  class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-3">
                                                                                <span class="svg-icon svg-icon-3">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                                        width="24" height="24"
                                                                                        viewBox="0 0 24 24" fill="none">
                                                                                        <path opacity="0.3"
                                                                                            d="M21.4 8.35303L19.241 10.511L13.485 4.755L15.643 2.59595C16.0248 2.21423 16.5426 1.99988 17.0825 1.99988C17.6224 1.99988 18.1402 2.21423 18.522 2.59595L21.4 5.474C21.7817 5.85581 21.9962 6.37355 21.9962 6.91345C21.9962 7.45335 21.7817 7.97122 21.4 8.35303ZM3.68699 21.932L9.88699 19.865L4.13099 14.109L2.06399 20.309C1.98815 20.5354 1.97703 20.7787 2.03189 21.0111C2.08674 21.2436 2.2054 21.4561 2.37449 21.6248C2.54359 21.7934 2.75641 21.9115 2.989 21.9658C3.22158 22.0201 3.4647 22.0084 3.69099 21.932H3.68699Z"
                                                                                            fill="black"></path>
                                                                                        <path
                                                                                            d="M5.574 21.3L3.692 21.928C3.46591 22.0032 3.22334 22.0141 2.99144 21.9594C2.75954 21.9046 2.54744 21.7864 2.3789 21.6179C2.21036 21.4495 2.09202 21.2375 2.03711 21.0056C1.9822 20.7737 1.99289 20.5312 2.06799 20.3051L2.696 18.422L5.574 21.3ZM4.13499 14.105L9.891 19.861L19.245 10.507L13.489 4.75098L4.13499 14.105Z"
                                                                                            fill="black"></path>
                                                                                    </svg>
                                                                                </span>
                                                                            </a> --}}
                                                                            <a href="#" onclick="deleteQuestion({{ $item->id }})" class="btn btn-icon btn-bg-light btn-active-color-danger btn-sm" data-bs-toggle="modal" data-bs-target="#kt_modal_create_campaign">
                                                                                <span class="svg-icon svg-icon-2">
                                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                                        width="24" height="24"
                                                                                        viewBox="0 0 24 24" fill="currentColor"
                                                                                        opacity="0.3">
                                                                                        <path
                                                                                            d="M5 9C5 8.44772 5.44772 8 6 8H18C18.5523 8 19 8.44772 19 9V18C19 19.6569 17.6569 21 16 21H8C6.34315 21 5 19.6569 5 18V9Z">
                                                                                        </path>
                                                                                        <path opacity="0.5"
                                                                                            d="M5 5C5 4.44772 5.44772 4 6 4H18C18.5523 4 19 4.44772 19 5V5C19 5.55228 18.5523 6 18 6H6C5.44772 6 5 5.55228 5 5V5Z">
                                                                                        </path>
                                                                                        <path opacity="0.5"
                                                                                            d="M9 4C9 3.44772 9.44772 3 10 3H14C14.5523 3 15 3.44772 15 4V4H9V4Z">
                                                                                        </path>
                                                                                    </svg>
                                                                                </span>
                                                                            </a>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                @endforeach
                                                                @endif
                                                                
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    {{ $Questions->links() }}
                                            <!--end::Table-->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Content-->

                   <!--begin::Modal - add  hospital-->
        <div class="modal fade" id="kt_modal_offer_a_deal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered mw-400px">
                <div class="modal-content">
                    <div class="modal-header py-3 d-flex justify-content-between">
                        <h2>Add Questions</h2>
                        <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                            <span class="svg-icon svg-icon-1">
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
                    <div class="modal-body py-lg-8 px-lg-10">
                        <form id="questionForm" method="post">
                            @csrf
                            <div class="card-body ps-0 pe-0 py-0 pt-0">
                                <div class="mb-5 fv-row fv-plugins-icon-container">
                                        <label class="required form-label">Questions Title</label>
                                        <div class="mb-5 fv-row possesive_main">
                                            <div class="possesive_wrapper">
                                                <input type="text" name="question" class="form-control mb-2 option_counter" placeholder="Enter question here..." value="" />
                                            </div>
                                        </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end mb-6">
                                <button data-bs-dismiss="modal" type="button" class="btn btn-light me-5">
                                    <span class="indicator-label">Close</span>
                                </button>
                                <button type="submit" id="kt_ecommerce_add_category_submit" class="btn btn-primary">
                                    <span class="indicator-label">Add</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Modal - add  hospital -->
        <!--begin::Modal - edit  hospital-->
        <div class="modal fade" id="kt_modal_create_app" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered mw-400px">
                <div class="modal-content">
                    <div class="modal-header py-3 d-flex justify-content-between">
                        <h2>Edit Questions</h2>
                        <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
                            <span class="svg-icon svg-icon-1">
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
                    <div class="modal-body py-lg-8 px-lg-10">
                        <form id="updateQuestionForm" method="post">
                            @csrf
                            <div class="card-body ps-0 pe-0 py-0 pt-0">
                                <div class="mb-5 fv-row fv-plugins-icon-container">
                                    <label class="required form-label">Question Title</label>
                                    <div class="mb-5 fv-row possesive_main">
                                        <div class="possesive_wrapper">
                                            <input type="hidden" name="id" id="Question_id">
                                            <input type="text" name="question" id="Question_question" class="form-control mb-2 option_counter" placeholder="Enter question here..." value="" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end mb-6">
                                <button data-bs-dismiss="modal" type="button" class="btn btn-light me-5">
                                    <span class="indicator-label">Close</span>
                                </button>
                                <button type="submit" id="kt_ecommerce_add_category_submit" class="btn btn-primary">
                                    <span class="indicator-label">Add</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Modal - edit hospital-->
@endsection

