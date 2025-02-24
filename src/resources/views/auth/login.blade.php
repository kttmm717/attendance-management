@extends('layouts.default')

@section('title', 'スタッフログインページ')

@section('css')
<link rel="stylesheet" href="{{asset('css/auth.css')}}">
@endsection

@section('content')
@include('components.header')
<div class="container">
    <form class="form" action="/login" method="post">
        @csrf
        <h2 class="title">ログイン</h2>
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
        <button>ログインする</button>
        <a class="link" href="/register">会員登録はこちら</a>
    </form>
</div>
@endsection