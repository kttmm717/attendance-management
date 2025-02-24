@extends('layouts.default')

@section('title', '会員登録ページ')

@section('css')
<link rel="stylesheet" href="{{asset('css/auth.css')}}">
@endsection

@section('content')
@include('components.header')
<div class="container">
    <form class="form" action="/register" method="post">
        @csrf
        <h2 class="title">会員登録</h2>
        <p class="item">名前</p>
        <input type="text" name="name">
        @error('name')
            <p class="error">{{$message}}</p>
        @enderror
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
        <p class="item">パスワード確認</p>
        <input type="password" name="password_confirmation">
        <button>登録する</button>
        <a class="link" href="/login">ログインはこちら</a>
    </form>
</div>
@endsection