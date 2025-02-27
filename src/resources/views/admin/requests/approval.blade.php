@extends('layouts.default')

@section('title', '申請一覧ページ')

@section('css')
<link rel="stylesheet" href="{{asset('css/approval.css')}}">
@endsection

@section('content')
@include('components.header')
<div class="container">
    <form class="form" action="">
        @csrf
        <h2 class="attendance__title">勤怠詳細</h2>
        <table>
            <tr>
                <th>名前</th>
                <td>{{$user->name}}</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <th>日付</th>
                <td>{{$correction_request->date->format('Y年')}}</td>
                <td></td>
                <td>{{$correction_request->date->format('n月j日')}}</td>
                <td></td>
            </tr>
            <tr>
                <th>出勤・退勤</th>
                <td>{{$correction_request->new_clock_in->format('H:i')}}</td>
                <td>～</td>
                <td>{{$correction_request->new_clock_out->format('H:i')}}</td>
                <td></td>
            </tr>
            @foreach($correction_breaks as $correction_break)
            <tr>
                <th>休憩</th>
                <td>{{$correction_break->new_break_start->format('H:i')}}</td>
                <td>～</td>
                <td>{{$correction_break->new_break_end->format('H:i')}}</td>
                <td></td>
            </tr>
            @endforeach
            <tr>
                <th>備考</th>
                <td colspan="3">{{$correction_request->reason}}</td>
                <td></td>
            </tr>
        </table>
        <div class="btn">
            <button>承認</button>
        </div>
    </form>
</div>
@endsection