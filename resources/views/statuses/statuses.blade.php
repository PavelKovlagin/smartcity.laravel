@extends('layouts/layout')
@section('title')
{{$title}}
@endsection
@section('content')

@if (Auth::check())
<button type="submit" onclick="location.href='/statuses/addStatus'">Добавить статус</button>
@endif

@if(count($statuses)>0)
<h1>{{$title}}</h1>
    
    <table border="1">
        <tr>
            <th> Название статуса </th>
            <th> Описание статуса </th>
        </tr>
        @foreach ($statuses as $status)
        <tr>
            <th> {{$status->statusName}} </th>
            <th> {{$status->statusDescription}} </th>
            <th> <a href="/statuses/{{$status->id}}"> Подробно </a></th>
            @if((Auth::check()) and (Auth::user() -> role == "admin"))
            <th> <a href="/deleteStatus/{{$status->id}}"> Удалить </a></th> 
            @endif
        </tr>
        @endforeach
    </table>
    {{$statuses->links('pagination.pagination')}}
    @else
    <h1>Статусов нет</h1>
    @endif
@endsection