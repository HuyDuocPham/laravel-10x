@extends('admin.layout.master')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Create Product Category</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Product Category</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Create Product Category</h3>
                </div>
                <form role="form" method="POST" action="{{ route('admin.product_category.save') }}">
                    @csrf
                  <div class="card-body">
                    <div class="form-group">
                      <label for="exampleInputEmail1">Name</label>
                      <input type="text" name="name" class="form-control {{ $errors->any('name') ? 'is-invalid' : '' }}" id="name" placeholder="Name">
                    </div>
                    <div>
                        @error('name')
                            <small style="color: red">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                      <label for="slug">Slug</label>
                      <input type="text" name="slug" class="form-control {{ $errors->any('name') ? 'is-invalid' : '' }}" id="slug" placeholder="Slug">
                    </div>
                    <div>
                        @error('slug')
                            <small style="color: red">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" class="form-select form-control {{ $errors->any('name') ? 'is-invalid' : '' }}" id="status">
                            <option value="">---Please Select---</option>
                            <option value="1">Show</option>
                            <option value="0">Hide</option>
                        </select>
                    </div>
                    <div>
                        @error('status')
                            <small style="color: red">{{ $message }}</small>
                        @enderror
                    </div>

                  <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Create</button>
                  </div>
                </form>
              </div>
              <!-- /.card -->
            </div>
          </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
@endsection


@section('js-custom')
    <script type="text/javascript">
        $(document).ready(function (){
            $('#name').on('keyup',function(){
                let name = $(this).val();
                $.ajax({
                    method: 'POST', //method of form
                    url: "{{ route('admin.product_category.slug') }}", // action of form
                    data: {
                        name: name,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(res) {
                        $('#slug').val(res.slug);
                    },
                    error: function(res) {

                    }
                });
            });
        });
    </script>
@endsection
