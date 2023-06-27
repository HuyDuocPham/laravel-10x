@extends('client.layout.master')

@section('content')
<div class="container">
    @if($errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <li><span style="color:red;">{{ $error }}</span></li>
            @endforeach
        </ul>
    </div>
    @endif
    <form action="{{ route('nguoidung.dangky') }}" method="POST">
        @csrf
          <!-- Name input -->
          <div class="form-outline mb-4">
            <input type="text" id="name" class="form-control" name="name" value="{{ old('name') }}"/>
            <label class="form-label" for="name">Name</label>
          </div>
          @error('name')
                    <div class="alert-danger">{{ $message }}</div>
            @enderror


        <!-- Email input -->
        <div class="form-outline mb-4">
          <input type="text" id="form2Example1" class="form-control" name="email" value="{{ old('email') }}"/>
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

        <!-- Submit button -->
        <button type="submit" class="btn btn-primary btn-block mb-4">Dang ky</button>
      </form>
</div>
@endsection

