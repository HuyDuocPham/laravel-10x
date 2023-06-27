@extends('client.layout.master')

@section('content')
<div class="container">
    <form action="{{ route('nguoidung.dangnhap') }}" method="POST">
        @csrf
        <!-- Email input -->
        <div class="form-outline mb-4">
          <input type="text" id="form2Example1" class="form-control" name="email" />
          <label class="form-label" for="form2Example1">Email address</label>
        </div>
           @error('email')
                    <div class="alert-danger">{{ $message }}</div>
            @enderror

        <!-- Password input -->
        <div class="form-outline mb-4">
          <input type="password" id="form2Example2" class="form-control" name="password" />
          <label class="form-label" for="form2Example2">Password</label>
        </div>
        @error('password')
            <div class="alert-danger">{{ $message }}</div>
        @enderror

        @if ($message = Session::get('error'))
            <div class="alert-danger">{{ $message }}</div>
        @endif

        <!-- Submit button -->
        <button type="submit" class="btn btn-primary btn-block mb-4">Dang nhap</button>

        <!-- Register buttons -->
        <div class="text-center">
          <p>Ban chua co tai khoan? <a href="#!">Dang ky</a></p>
        </div>
      </form>
</div>
@endsection

