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
                                <h3 class="card-title">Product</h3> <br>
                                <div>
                                    <form method="GET">
                                        <input type="text" name="keyword" placeholder="Search..."
                                            value="{{ is_null(request()->keyword) ? '' : request()->keyword }}">
                                        <label for="status">Status</label>
                                        <select name="status" id="status"
                                            value="{{ is_null(request()->status) ? '' : request()->status }}">
                                            <option @if (request()->status === '') selected @endif value="">
                                                ---Select All---</option>
                                            <option @if (request()->status === '1') selected @endif value="1">Show
                                            </option>
                                            <option @if (request()->status === '0') selected @endif value="0">Hide
                                            </option>
                                        </select>

                                        <label for="status">Sort</label>
                                        <select name="sort" id="sort"
                                            value="{{ is_null(request()->sort) ? '' : request()->sort }}">
                                            <option @if (request()->sort === '0') selected @endif value="0">
                                                Lastest</option>
                                            <option @if (request()->sort === '1') selected @endif value="1">Price
                                                Low to High
                                            </option>
                                            <option @if (request()->sort === '2') selected @endif value="2">Price
                                                High to Low
                                            </option>
                                        </select>

                                        <button type="submit">Search</button>
                                        <p>
                                            <label for="amount">Price range:</label>
                                            <input type="text" id="amount" readonly
                                                style="border:0; color:#f6931f; font-weight:bold;">
                                            <input type="hidden" name="amount_start" id="amount_start">
                                            <input type="hidden" name="amount_end" id="amount_end">
                                        </p>

                                        <div id="slider-range"></div>



                                    </form>
                                </div>
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
                                                        @method('DELETE')

                                                        <a href="{{ route('admin.product.show', ['product' => $product->id]) }}"
                                                            class="btn btn-primary">Edit</a>

                                                        <button onclick="return confirm('Are you sure?')" type="submit"
                                                            class="btn btn-danger">Delete</button>
                                                        {{-- <form
                                                            action="{{ route('admin.product.restore', ['product' => $product->id]) }}"
                                                            method="POST">
                                                            @csrf
                                                            <button
                                                                style="display: {{ is_null($product->deleted_at) ? 'none' : 'block' }}"
                                                                class="btn btn-primary">Restore</button>
                                                        </form> --}}
                                                        @if ($product->trashed())
                                                            <form
                                                                action="{{ route('admin.product.restore', ['product' => $product->id]) }}"
                                                                method="POST">
                                                                @csrf
                                                                <button class="btn btn-primary">Restore</button>
                                                            </form>
                                                        @endif
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
                                {{ $products->appends(request()->query())->links() }}
                            </div>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
    </div>
@endsection

@section('js-custom')
    <script type="text/javascript">
        $(document).ready(function() {
            $("#slider-range").slider({
                range: true,
                min: {{ $minPrice }},
                max: {{ $maxPrice }},
                values: [{{ request()->amount_start ?? 0 }}, {{ request()->amount_end ?? 1000 }}],
                slide: function(event, ui) {
                    $("#amount").val("$" + ui.values[0] + " - $" + ui.values[1]);
                }
            });
            $("#amount").val("$" + $("#slider-range").slider("values", 0) +
                " - $" + $("#slider-range").slider("values", 1));
            $('#amount_start').val($("#slider-range").slider("values", 0));
            $('#amount_end').val($("#slider-range").slider("values", 1));
        });
    </script>
@endsection
