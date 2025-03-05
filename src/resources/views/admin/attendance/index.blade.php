@extends('layouts.default')

@section('title', '勤怠一覧ページ(管理者)')

@section('css')
<link rel="stylesheet" href="{{asset('css/admin-attendance-index.css')}}">
@endsection

@section('content')
@include('components.header')
<div class="container">
    <div class="attendance">
        <h2 class="attendance__title" id="attendanceTitle">
            {{ \Carbon\Carbon::parse(request('date', now()))->format('Y年n月j日') }}の勤怠
        </h2>
        <div class="page">
            <span id="prevDay" class="date-btn">↼前日</span>
            <div class="date-picker">                
            <input type="date" id="dateInput" class="date" value="{{ request('date', now()->format('Y-m-d')) }}">
                <i class="fas fa-calendar-alt"></i>              
            </div>
            <span id="nextDay" class="date-btn">翌日⇀</span>
        </div>
        <table>
            <tr>
                <th>名前</th>
                <th>出勤</th>
                <th>退勤</th>
                <th>休憩</th>
                <th>合計</th>
                <th>詳細</th>
            </tr>
            @foreach($attendances as $attendance)
            <tr>
                <td>{{$attendance->user->name}}</td>
                <td>{{$attendance->clock_in->format('H:i')}}</td>
                @if($attendance->clock_out)
                    <td>{{$attendance->clock_out->format('H:i')}}</td>
                @else    
                    <td></td>
                @endif  
                <td>{{$attendance->formatedTotalBreakTime()}}</td>
                <td>{{$attendance->totalAttendanceTime()}}</td>
                <td class="detail__link">
                    <a href="/admin/attendance/{{$attendance->id}}">詳細</a>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
<script>
function updateDate(newDate) {
    let url = new URL(window.location.href);
    url.searchParams.set('date', newDate);
    window.location.href = url.toString();
}

document.getElementById('dateInput').addEventListener('change', function() {
    updateDate(this.value);
});

// 前日ボタン
document.getElementById("prevDay").addEventListener("click", function() {
    let dateInput = document.getElementById("dateInput");
    let date = new Date(dateInput.value);
    date.setDate(date.getDate() - 1);

    let newDate = date.toISOString().split('T')[0];

    updateDate(newDate);
});

// 翌日ボタン
document.getElementById("nextDay").addEventListener("click", function() {
    let dateInput = document.getElementById("dateInput");
    let date = new Date(dateInput.value);
    date.setDate(date.getDate() + 1);

    let newDate = date.toISOString().split('T')[0];

    updateDate(newDate);
});

</script>
@endsection