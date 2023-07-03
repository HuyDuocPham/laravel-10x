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
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Product</h3>
                                <div class="d-flex justify-content-end">
                                    <a class="btn btn-primary"href="{{ route('admin.product.create') }}">Create Product</a>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width: 10px">#</th>
                                            <th>name</th>
                                            <th>Product Category Name</th>
                                            <th>slug</th>
                                            <th>price</th>
                                            <th>discount_price</th>
                                            <th>description</th>
                                            <th>short_description</th>
                                            <th>information</th>
                                            <th>qty</th>
                                            <th>weight</th>
                                            <th>image_url</th>
                                            <th style="width: 40px">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($products as $product)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $product->name }}</td>
                                                {{-- <td>{{ $product->product_category_name }}</td> Query Builder --}}
                                                <td>{{ $product->category->name }}</td>
                                                <td>{{ $product->slug }}</td>
                                                <td>{{ number_format($product->price, 2) }}</td>
                                                <td>{{ $product->discount_price }}</td>
                                                <td>{!! $product->description !!}</td>
                                                {{-- Note: {!! abc !!} --}}
                                                <td>{{ $product->short_description }}</td>
                                                <td>{{ $product->information }}</td>
                                                <td>{{ $product->qty }}</td>
                                                <td>{{ $product->weight }}</td>
                                                <td>
                                                    @php
                                                        $imageLink = is_null($product->image_url) || !file_exists('images/' . $product->image_url) ? 'default-product-image.png' : $product->image_url;
                                                    @endphp
                                                    <img src="{{ asset('images/' . $imageLink) }}"
                                                        alt="{{ $product->name }}" width="100px" height="100px">
                                                </td>
                                                <td>
                                                    <a class="btn btn-{{ $product->status ? 'success' : 'danger' }}">
                                                        {{ $product->status ? 'Show' : 'Hide' }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <form method="post"
                                                        action="{{ route('admin.product.destroy', ['product' => $product->id]) }}">
                                                        @csrf
                                                        <a href="{{ route('admin.product.show', ['product' => $product->id]) }}"
                                                            class="btn btn-primary">Edit</a>
                                                        <button onclick="return confirm('Are you sure?')" type="submit"
                                                            class="btn btn-danger">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4">No Product</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer clearfix">
                                {{ $products->links() }}
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
    </div>
@endsection
