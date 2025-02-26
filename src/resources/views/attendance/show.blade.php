@extends('layouts.default')

@section('title', '勤怠詳細ページ')

@section('css')
<link rel="stylesheet" href="{{asset('css/attendance-show.css')}}">
@endsection

@section('content')
@include('components.header')
<div class="container">
    <form class="form" action="/request/{{$attendance->id}}" method="post">
        @csrf
        <h2 class="attendance__title">勤怠詳細</h2>
        <table>
            <tr>
                <th>名前</th>
                <td>{{$attendance->user->name}}</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <th>日付</th>
                <td>{{$attendance->date->format('Y年')}}</td>
                <td></td>
                <td>{{$attendance->date->format('n月j日')}}</td>
                <td></td>
            </tr>
            <tr>
                <th>出勤・退勤</th>
                <td><input type="text" name="clock_in" value="{{$attendance->clock_in->format('H:i')}}"></td>
                <td>～</td>
                <td><input type="text" name="clock_out" value="{{$attendance->clock_out->format('H:i')}}"></td>
                <td></td>
            </tr>
            @foreach($attendance->break_times as $index => $break_time)
            <tr>
                <th>休憩</th>
                <td>
                    <input type="text" name="break_times[{{$index}}][break_start]" value="{{$break_time->break_start->format('H:i')}}">
                    <input type="hidden" name="break_times[{{$index}}][original_break_start]" value="{{$break_time->break_start->format('H:i')}}">
                </td>
                <td>～</td>
                <td>
                    <input type="text" name="break_times[{{$index}}][break_end]"  value="{{$break_time->break_end->format('H:i')}}">
                    <input type="hidden" name="break_times[{{$index}}][original_break_end]"  value="{{$break_time->break_end->format('H:i')}}">
                </td>
                <td></td>
            </tr>
            @endforeach
            <tr>
                <th>備考</th>
                <td colspan="3">
                    <textarea name="reason"></textarea>
                </td>
                <td></td>
            </tr>
        </table>
        @if(optional($attendance->correction_request)->status === 'pending')
            <p class="text">＊承認待ちのため修正はできません</p>
        @else
            <div class="btn">
                <button>修正</button>
            </div>
        @endif
    </form>
</div>
@endsection