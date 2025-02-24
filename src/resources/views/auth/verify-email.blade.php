@extends('layouts.default')

@section('title', 'メール認証ページ')

@section('css')
<link rel="stylesheet" href="{{ asset('/css/verify-email.css') }}">
@endsection

@section('content')
@include('components.header')
<div class="message">
    @if(session('message'))
    <p class="message__text">{{session('message')}}</p>
    @endif
</div>
<div class="container">
    <p>登録していただいたメールアドレスに認証メールを送付しました。</p>
    <p>メール認証を完了してください。</p>
    <a class="link" href="https://mailtrap.io/ja/">認証はこちらから</a>
    <form action="/email/verify" method="post">
        @csrf
        <button class="mail__link">認証メールを再送する</button>
    </form>
</div>
@endsection