@extends('layouts/layout')
@section('title')
{{$title}}
@endsection
@section('content')
<h1>{{$title}}</h1>

@if (Auth::check())
<button type="submit" onclick="location.href='/events/addEvent'">Добавить событие</button>
@endif

<form action="/events" method="GET">
    
    <input type='hidden' name='user_id' value={{$user_id}}>
    <p>Статус событий 
    <select name = "status_id">
    <option value="0">Все статусы</option>
    @foreach($statuses as $status)
        <option @if ($status_id == $status->id) selected @endif value="{{ $status->id }}">{{ $status->statusName }}</option>
    @endforeach
    </select> 

    Категория событий 
    <select name = "category_id">
    <option value="0">Все категории</option>
    @foreach($categories as $category)
        <option @if ($category_id == $category->id) selected @endif value="{{ $category->id }}">{{ $category->categoryName }}</option>
    @endforeach
    </select>
    <button type="submit">Применить фильтр</button>
    </p> 
    
</form>

@if(count($events)>0)

    
    <table border="1">
        <tr>
            <th> Название события </th>
            <th> Долгота </th>
            <th> Широта </th>
            <th> Статус </th>
            <th> Категория </th>
            <th> Пользователь </th>
        </tr>
        @foreach ($events as $event)
        <tr>
            <th> {{$event->eventName}} </th>            
            <th> {{$event->longitude}} </th>
            <th> {{$event->latitude}} </th>
            <th> {{$event->statusName}} </th>
            <th> {{$event->categoryName}} </th>
            <th> <a href="/users/user/{{$event->user_id}}"> {{$event->email}} </a> </th>
            <th> <a href="/events/{{$event->id}}"> Подробно </a></th>
        </tr>
        @endforeach
    </table>
    {{$events->links('pagination.pagination')}}
    @else
    <h1>Событий нет</h1>
    @endif
@endsection