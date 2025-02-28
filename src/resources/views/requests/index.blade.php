@extends('layouts.default')

@section('title', '申請一覧ページ')

@section('css')
<link rel="stylesheet" href="{{asset('css/request-index.css')}}">
@endsection

@section('content')
@include('components.header')
<div class="container">
    <div class="requests">
        <h2 class="request__title">申請一覧</h2>
        <div class="border">
            <a href="{{route('request', ['tab'=>'pending'])}}">承認待ち</a>
            <a href="{{route('request', ['tab'=>'approved'])}}">承認済み</a>
        </div>
        <table>
            <tr>
                <th>状態</th>
                <th>名前</th>
                <th>対象日時</th>
                <th>申請理由</th>
                <th>申請日時</th>
                <th>詳細</th>
            </tr>
            @foreach($correction_requests as $correction_request)
            <tr>                
                @if($correction_request->status === 'pending')
                <td>承認待ち</td>
                @else
                <td>承認済み</td>
                @endif
                <td>{{$correction_request->user->name}}</td>
                <td>{{$correction_request->date->format('Y/m/d')}}</td>
                <td>{{$correction_request->reason}}</td>
                <td>{{$correction_request->requested_at->format('Y/m/d')}}</td>
                @if($user->role === 'admin')
                <td class="detail"><a href="/stamp_correction_request/approve/{{$correction_request->id}}">詳細</a></td>
                @else
                <td class="detail"><a href="/attendance/{{$correction_request->attendance_id}}">詳細</a></td>                
                @endif
            </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection