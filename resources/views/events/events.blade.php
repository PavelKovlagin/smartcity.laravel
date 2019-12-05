@extends('layouts/layout')
@section('title')
{{$title}}
@endsection
@section('content')

@if (Auth::check())
<button type="submit" onclick="location.href='/events/addEvent'">Добавить событие</button>
@endif

@if(count($events)>0)
<h1>{{$title}}</h1>
    
    <table border="1">
        <tr>
            <th> Название события </th>
            <th> Долгота </th>
            <th> Широта </th>
            <th> Статус </th>
            <th> Пользователь </th>
        </tr>
        @foreach ($events as $event)
        <tr>
            <th> {{$event->eventName}} </th>            
            <th> {{$event->longitude}} </th>
            <th> {{$event->latitude}} </th>
            <th> {{$event->statusName}} </th>
            <th> {{$event->email}}</th>
            <th> <a href="/events/{{$event->event_id}}"> Подробно </a></th>
            @if((Auth::check()) and (Auth::user() -> role == "admin"))
            <th> <a href="/deleteEvent/{{$event->event_id}}"> Удалить </a></th> 
            @endif
        </tr>
        @endforeach
    </table>
    {{$events->links('pagination.pagination')}}
    @else
    <h1>Событий нет</h1>
    @endif
@endsection