@extends('layouts.default')

@section('title', 'スタッフ一覧ページ')

@section('css')
<link rel="stylesheet" href="{{asset('css/admin-staff-index.css')}}">
@endsection

@section('content')
@include('components.header')
<div class="container">
    <div class="staffs">
        <h2 class="staffs__title">スタッフ一覧</h2>
        <table>
            <tr>
                <th>名前</th>
                <th>メールアドレス</th>
                <th>月次勤怠</th>
            </tr>
            @foreach($users as $user)
            <tr>
                <td>{{$user->name}}</td>
                <td>{{$user->email}}</td>
                <td class="link"><a href="/admin/attendance/staff/{{$user->id}}">詳細</a></td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection