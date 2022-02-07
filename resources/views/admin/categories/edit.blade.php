@extends('layouts.admin.master')

@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2 mt-4">
                    <div class="col-12">
                        <h2 class="m-0 text-dark">
                            <a class="nav-link drawer" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
                            دسته بندی ها / بروزرسانی <span class="text-danger">{{ $categories->title }}</span>
                            <a class="btn btn-primary float-left text-white py-2 px-4"
                                href="{{ route('admin.categories.all') }}">بازگشت به صفحه
                                دسته بندی ها</a>
                        </h2>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">

            @include('errors.message')

            <div class="row mt-5">
                <div class="col-md-12">
                    <div class="card card-defualt">
                        <!-- form start -->

                        <form action="{{ route('admin.categories.update', $categories->id) }}" method='post'>
                            @csrf
                            @method('put')
                            <input type="hidden" name="category_id" value="{{$categories->id}}">

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>نامک</label>
                                            <input type="text" class="form-control" name="slug"
                                                value="{{ $categories->slug }}" placeholder="نامک را وارد کنید">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>عنوان</label>
                                            <input type="text" class="form-control" name="title"
                                                value="{{ $categories->title }}" placeholder="عنوان را وارد کنید">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary float-left"> ذخیره تغییرات</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

@endsection
