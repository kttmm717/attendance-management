@extends('layouts.default')

@section('title', '勤怠登録ページ')

@section('css')
<link rel="stylesheet" href="{{asset('css/attendance-create.css')}}">
@endsection

@section('content')
@include('components.header')
<form class="form" action="" method="">
    @csrf
    <p class="status">勤務外</p>
    <p class="data">2025年2月24日(日)</p>
    <p class="time">10:00</p>
    <button class="work__btn">出勤</button>
</form>
@endsection