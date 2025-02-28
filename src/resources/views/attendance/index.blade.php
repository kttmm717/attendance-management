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
            <span onclick="changeMonth(-1)">↼ 前月</span>
            <p class="date">{{ $currentMonth->format('Y/m') }}</p>
            <span onclick="changeMonth(1)">翌月 ⇀</span>
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
<script>
    function changeMonth(offset) {
        let currentMonth = "{{ $currentMonth->format('Y-m') }}"; // 現在の年月
        let date = new Date(currentMonth + "-01");
        date.setMonth(date.getMonth() + offset);
        let newMonth = date.toISOString().slice(0, 7);

        window.location.href = "{{ route('attendance.index') }}?month=" + newMonth;
    }
</script>
@endsection