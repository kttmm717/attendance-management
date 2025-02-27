@extends('layouts.default')

@section('title', '勤怠一覧ページ')

@section('css')
<link rel="stylesheet" href="{{asset('css/attendance-index.css')}}">
@endsection

@section('content')
@include('components.header')
<div class="container">
    <div class="attendance">
        <h2 class="attendance__title">勤怠一覧</h2>
        <div class="page">
            <p>↼前月</p>
            <p class="date">2025/02</p>
            <p>翌月⇀</p>
        </div>
        <table>
            <tr>
                <th>日付</th>
                <th>出勤</th>
                <th>退勤</th>
                <th>休憩</th>
                <th>合計</th>
                <th>詳細</th>
            </tr>
            @foreach($attendances as $attendance)
            <tr>
                <td>{{$attendance->date->translatedformat('m/d(D)')}}</td>
                <td>{{$attendance->clock_in->format('H:i')}}</td>
                @if($attendance->clock_out)
                <td>{{$attendance->clock_out->format('H:i')}}</td>
                @else
                <td>出勤中</td>
                @endif
                <td>{{$attendance->formatedTotalBreakTime()}}</td>
                <td>{{$attendance->totalAttendanceTime()}}</td>
                <td><a class="detail__link" href="/attendance/{{$attendance->id}}">詳細</a></td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection