@extends('admin.layout.master')

@section('content')
<div class="content-wrapper">
    <section class="content">
        <a class="btn btn-primary"
        href="{{ route('admin.product.create') }}">
        Create Product</a>
    </section>
</div>
@endsection
