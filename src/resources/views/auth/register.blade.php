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
        @error('name')
            <p class="error">{{$message}}</p>
        @enderror
        <input type="text" name="name">
        
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
        
        <p class="item">パスワード確認</p>
        <input type="password" name="password_confirmation">
        <button>登録する</button>
        <a class="link" href="/login">ログインはこちら</a>
    </form>
</div>
@endsection