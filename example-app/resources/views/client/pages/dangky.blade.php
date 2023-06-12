@extends('client.layout.master')
@section('content')
@if($errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as  $error)
                <li><span style="color:red">{{$error}}</span></li>
            @endforeach
        </ul>
    </div>
@endif
    <form action="{{ route('nguoidung.dangky') }}" method="POST"
        style="    display: flex;
    flex-direction: column;
    margin: 0 30%;">
        {{-- <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" /> --}}
        @csrf
        <input type="text" name="name" placeholder="Name">
        @error('name')
            <span>{{ $message }}</span>
        @enderror
        <input type="text" name="email" placeholder="Email">
        @error('email')
            <span>{{ $message }}</span>
        @enderror
        <input type="password" name="password" placeholder="Password">
        @error('password')
            <span>{{ $message }}</span>
        @enderror
        <input type="submit" value="Dang Ky">
    </form>
@endsection
