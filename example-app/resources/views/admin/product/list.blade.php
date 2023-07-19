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
                    <a class="btn btn-primary"href="{{ route('admin.product.create') }}">Create</a>
                </div>
                </div>
                <div>
                    <form method="GET">
                        <input type="text" placeholder="Search..." name="keyword" value="{{ is_null(request()->keyword) ? '' : request()->keyword }}">
                        <label for="status">Status</label>
                        <select name="status" id="status">
                            <option @if (request()->status === '') selected @endif value="">---Select All---</option>
                            <option @if (request()->status === '1') selected @endif value="1">Show</option>
                            <option @if (request()->status === '0') selected @endif value="0">Hide</option>
                        </select>
                        <!-- Sort -->
                        <label for="sort">Sort</label>
                        <select name="sort" id="sort">
                            <option @if (request()->sort === '0') selected @endif value="0">Lastest</option>
                            <option @if (request()->sort === '1') selected @endif value="1">Price Low to High</option>
                            <option @if (request()->sort === '2') selected @endif value="2">Price High to Low</option>
                        </select>
                        <!-- Price -->
                        <p>
                            <label for="amount">Price range:</label>
                            <input type="text" id="amount" readonly style="border:0; color:#f6931f; font-weight:bold;">
                            <input type="hidden" id="amount_start" name="amount_start">
                            <input type="hidden" id="amount_end" name="amount_end">
                        </p>
                        <div id="slider-range"></div>

                        <button type="submit">Search</button>
                    </form>
                  </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th style="width: 10px">#</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Price</th>
                        <th>Description</th>
                        <th>Image</th>
                        <th>Product Category Name</th>
                        <th style="width: 40px">Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->slug }}</td>
                                <td>{{ number_format($product->price, 2) }}</td>
                                <td>{!! $product->description !!}</td>
                                <td>
                                    @php
                                        $imageLink = (is_null($product->image_url) || !file_exists("images/" . $product->image_url)) ? 'default-product-image.png' : $product->image_url;
                                    @endphp
                                    <img src="{{ asset('images/'.$imageLink) }}" alt="{{ $product->name }}" width="150" height="150">
                                </td>
                                {{-- <td>{{ $product->category->name }}</td>--}}
                                <td>{{ $product->product_category_name }}</td>
                                <td>
                                    <a class="btn btn-{{ $product->status ? 'success' : 'danger' }}">
                                        {{ $product->status ? 'Show' : 'Hide' }}
                                    </a>
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('admin.product.destroy', ['product' => $product->id]) }}">
                                        @csrf
                                        @method('delete')
                                        <a href="{{ route('admin.product.show', ['product' => $product->id]) }}" class="btn btn-primary">Edit</a>
                                        <button onclick="return confirm('Are you sure?')" type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                    {{-- @if($product->trashed()) --}}
                                        <form action="{{ route('admin.product.restore', ['product' => $product->id]) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-success">Restore</button>
                                        </form>
                                    {{-- @endif --}}
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

              <h1>DataTable </h1>
              <table id="product-datatable" class="table table-bordered">
                    <thead>
                      <tr>
                        <th style="width: 10px">#</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Price</th>
                        <th>Description</th>
                        <th>Image</th>
                        <th>Product Category Name</th>
                        <th style="width: 40px">Status</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->slug }}</td>
                                <td>{{ number_format($product->price, 2) }}</td>
                                <td>{!! $product->description !!}</td>
                                <td>
                                    @php
                                        $imageLink = (is_null($product->image_url) || !file_exists("images/" . $product->image_url)) ? 'default-product-image.png' : $product->image_url;
                                    @endphp
                                    <img src="{{ asset('images/'.$imageLink) }}" alt="{{ $product->name }}" width="150" height="150">
                                </td>
                                {{-- <td>{{ $product->category->name }}</td>--}}
                                <td>{{ $product->product_category_name }}</td>
                                <td>
                                    <a class="btn btn-{{ $product->status ? 'success' : 'danger' }}">
                                        {{ $product->status ? 'Show' : 'Hide' }}
                                    </a>
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('admin.product.destroy', ['product' => $product->id]) }}">
                                        @csrf
                                        @method('delete')
                                        <a href="{{ route('admin.product.show', ['product' => $product->id]) }}" class="btn btn-primary">Edit</a>
                                        <button onclick="return confirm('Are you sure?')" type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                    {{-- @if($product->trashed()) --}}
                                        <form action="{{ route('admin.product.restore', ['product' => $product->id]) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-success">Restore</button>
                                        </form>
                                    {{-- @endif --}}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">No Product</td>
                            </tr>
                        @endforelse
                    </tbody>
                  </table>
              <!-- /.card -->
            </div>
          </div>
        </div><!-- /.container-fluid -->
      </section>
</div>
@endsection

@section('js-custom')
    <script type="text/javascript">
        $(document).ready(function(){

            $( "#slider-range" ).slider({
                range: true,
                min: {{ $minPrice }},
                max: {{ $maxPrice }},
                values: [ {{  request()->amount_start ?? 0 }}, {{  request()->amount_end ?? $maxPrice }} ],
                slide: function( event, ui ) {
                    $( "#amount" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
                    $('#amount_start').val(ui.values[0]);
                    $('#amount_end').val(ui.values[1]);
                }
            });

            $( "#amount" ).val( "$" + $( "#slider-range" ).slider( "values", 0 ) +
            " - $" + $( "#slider-range" ).slider( "values", 1 ) );
            $('#amount_start').val($( "#slider-range" ).slider( "values", 0 ));
            $('#amount_end').val($( "#slider-range" ).slider( "values", 1 ));
        });
    </script>
@endsection
