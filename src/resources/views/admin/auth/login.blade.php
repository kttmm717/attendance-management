@extends('layouts.default')

@section('title', '管理者ログインページ')

@section('css')
<link rel="stylesheet" href="{{asset('css/auth.css')}}">
@endsection

@section('content')
@include('components.header')
<div class="container">
    <form class="form" action="/admin/login" method="post">
        @csrf
        <h2 class="title">管理者ログイン</h2>
        <p class="item">メールアドレス</p>
        <input type="text" name="email">
        @error('email')
            <p class="error">{{$message}}</p>
        @enderror
        <p class="item">パスワード</p>
        <input type="password" name="password">
        @error('password')
            <p class="error">{{$message}}</p>
        @enderror
        <button>管理者ログインする</button>
    </form>
</div>
@endsection