@extends('admin.layout.master')

@section('content')
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    @if (session('message'))
                        <div class="alert-success">
                            {{ session('message') }}
                        </div>
                    @endif
                </div>
            </div>
            <div class="row">
                <a class="btn btn-primary"
                href="{{ route('admin.product.create') }}">
                Create Product</a>
            </div>
        </div>
    </section>
</div>
@endsection
