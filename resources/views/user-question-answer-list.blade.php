@extends ('admin.layouts.app')
@section ('title') Welcome to {{ config('app.name') }} @endsection
@section ('content')
  <!--begin::Content-->
  <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="toolbar" id="kt_toolbar">
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <div class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="d-flex align-items-center text-dark fw-bolder fs-3 my-1"> @if($userName->name == null) {{ 'Known' }} @else {{ $users[0]->name }} @endif Question Answer List</h1>
                <span class="h-20px border-gray-300 border-start mx-4"></span>
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-separatorless fw-bold fs-7 my-1">
                    <li class="breadcrumb-item text-muted">
                        <a href="{{ route('index') }}" class="text-muted text-hover-primary">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <span class="bullet bg-gray-300 w-5px h-2px"></span>
                    </li>
                    <li class="breadcrumb-item text-dark">Questions Answer List</li>
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
                            <!--start::Table-->
                            <div class="table-responsive prdct-tbl">
                                <table class="table table-row-dashed align-middle gs-0 gy-4 my-0">
                                    <thead>
                                        <tr class="fs-7 fw-bolder text-gray-500 ">
                                            <th class="ps-0 w-50px">#</th>
                                            <th class="min-w-140px">Question-Answer</th>
                                        </tr>
                                    </thead>
                                    <tbody>
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
                                                        <div class="d-flex justify-content-start flex-column qst">
                                                            <span class="text-dark fw-bold fs-6"><em>Que.</em>{{ $item->question }}</span>
                                                            <span class="text-dark fs-6"><em>Ans.</em>{{ $item->answer }}</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <!--end::Table-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end::Content-->
@endsection

