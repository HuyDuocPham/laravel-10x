@extends('client.layout.master')
@section('content')
    <form 
    action="{{ route('nguoidung.dangnhap') }}" 
    method="POST" 
    style="display: flex;
    flex-direction: column;
    margin: 0 30%;">
        <input type="text" name="email" placeholder="Email">
        <input type="password" name="password" placeholder="Password">
        <input type="submit" value="Dang Nhap">
    </form>
@endsection