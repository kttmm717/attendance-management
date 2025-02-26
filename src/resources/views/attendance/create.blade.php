@extends('layouts.default')

@section('title', '勤怠登録ページ')

@section('css')
<link rel="stylesheet" href="{{asset('css/attendance-create.css')}}">
@endsection

@section('content')
@include('components.header')
<div class="container">
    @csrf
    @if($user->todayAttendance)
        @switch($user->todayAttendance->status)
            @case('working')
            @case('finished')
                <p class="status">出勤中</p>
                @break
            @case('break')
                <p class="status">休憩中</p>
                @break
            @default
                <p class="status">退勤済</p>
        @endswitch
    @else
        <p class="status">勤務外</p>
    @endif

    <p class="data"></p>
    <p class="time"></p>

    @if($user->todayAttendance)
        @switch($user->todayAttendance->status)
            @case('working')
            @case('finished')
                <form action="/clock/out" method="post">
                @csrf
                    <button class="btn">退勤</button>
                </form>
                <form action="/break/start" method="post">
                @csrf
                    <button class="btn break">休憩入</button>
                </form>
                @break
            @case('break')
                <form action="/break/end" method="post">
                @csrf
                    <button class="btn break">休憩戻</button>
                </form>
                @break
            @default
                <p class="text">お疲れ様でした。</p>
        @endswitch
    @else
        <form action="/clock/in" method="post">
            @csrf
            <button class="btn">出勤</button>
        </form>
    @endif
</div>

<script>
function updateDateTime() {
    const now = new Date();
    const weekdays = ["日", "月", "火", "水", "木", "金", "土"];
    const dateStr = `${now.getFullYear()}年${now.getMonth() + 1}月${now.getDate()}日(${weekdays[now.getDay()]})`;
    const timeStr = now.toLocaleTimeString("ja-JP", { hour: "2-digit", minute: "2-digit" });

    document.querySelector(".data").textContent = dateStr;
    document.querySelector(".time").textContent = timeStr;
}
updateDateTime();
setInterval(updateDateTime, 60000);
</script>
@endsection