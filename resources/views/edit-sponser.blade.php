@extends ('admin.layouts.app')
@section ('title') Welcome to {{ config('app.name') }} @endsection
@section ('content')

 <!--begin::Content-->
 <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1">Edit Sponser</h1>
                <span class="h-20px border-gray-300 border-start mx-4"></span>
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="index.html" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-300 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-muted">Sponser</li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-300 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-dark">Edit Sponser</li>
                </ul>
                <!--end::Breadcrumb-->
            </div>
        </div>
    </div>
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
            <div class="row gy-5 g-xl-10">
                <div class="col-xl-12 mb-xl-10">
                    <div class="card h-md-100">
                        <div class="card-body pt-6">
                            @error('Success')
                                <div class="alert alert-success">{{ $message }}</div>
                            @enderror
                            <form action="{{ route('edit-sponser-form',$Sponser->id) }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="advndr-flex">
                                            <div class="vndr-inerflx-100">
                                                <div class="mb-5 fv-row fv-plugins-icon-container">
                                                    <label class="required form-label">Title</label>
                                                    <input type="text" name="title" value="{{ $Sponser->title }}" class="form-control mb-2" placeholder="Enter sponser title here" value="">
                                                    @error('title')
                                                        <span class="text text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="vndr-inerflx-100">
                                                <div class="mb-5 fv-row fv-plugins-icon-container">
                                                    <label class="required form-label">Url</label>
                                                    <input type="text" name="url" value="{{ $Sponser->url }}" class="form-control mb-2" placeholder="Enter url here" value="">
                                                    @error('url')
                                                        <span class="text text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="vndr-inerflx-100">
                                                <div class="mb-5 fv-row fv-plugins-icon-container">
                                                    <label class="required form-label">Description</label>
                                                    <textarea class="form-control form-control" rows="3" name="description"  placeholder="Describe here..">{{ $Sponser->description }}</textarea>
                                                    @error('description')
                                                        <span class="text text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="uplod-ctegry">
                                            <label class="form-label">Image Upload</label>
                                            <div class="editimage-userdoc">
                                                <div class="img-uplder">
                                                    <div class="imguplod-editsign">
                                                        <em><i class="bi bi-pencil"></i></em>
                                                    </div>
                                                    <input id="file" name="file" value="{{ $Sponser->image }}" type="file">
                                                    <div class="filupl-rsltimg">
                                                        <span style="background-image: url(https://dev.codemeg.com/caly-backend/public/no_image.png);"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <img style="max-height: 100% ; max-width: 100%;" src="{{ asset('uploads/sponsers/'.$Sponser->image) }}" alt="">
                                            </div>
                                            <div class="text-muted mt-3 fs-7">Set the Sponser thumbnail image. Only *.png, *.jpg and *.jpeg image files are accepted
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end brdr-tp mt-5 pt-8 px-lg-10">
                                    <button data-bs-dismiss="modal" type="button" class="btn btn-light me-5">
                                        <span class="indicator-label">Close</span>
                                    </button>
                                    <button type="submit" id="kt_ecommerce_add_category_submit" class="btn btn-primary">
                                        <span class="indicator-label">Save</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end::Content-->
@endsection