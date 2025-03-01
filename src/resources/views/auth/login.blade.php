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
        @error('email')
            <p class="error">{{$message}}</p>
        @enderror
        <input type="text" name="email">
        
        <p class="item">パスワード</p>
        @error('password')
            <p class="error">{{$message}}</p>
        @enderror
        <input type="password" name="password">
        
        <button>ログインする</button>
        <a class="link" href="/register">会員登録はこちら</a>
    </form>
</div>
@endsection