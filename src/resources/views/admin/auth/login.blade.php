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
        @error('email')
            <p class="error">{{$message}}</p>
        @enderror
        <input type="text" name="email">
        
        <p class="item">パスワード</p>
        @error('password')
            <p class="error">{{$message}}</p>
        @enderror
        <input type="password" name="password">
        
        <button>管理者ログインする</button>
    </form>
</div>
@endsection