@extends('layouts/layout')
@section('title')
{{$title}}
@endsection
@section('content')
    <h1>Hello, <?= $name; ?>, your task today</h1>
    <table border="1">
        <tr>
            <th> Название события </th>
            <th> Описание события </th>
            <th> Долгота </th>
            <th> Широта </th>
        </tr>
        @foreach ($events as $event)
        <tr>
            <th> {{$event->nameEvent}} </th>
            <th> {{$event->eventDescription}} </th>
            <th> {{$event->longitude}} </th>
            <th> {{$event->latitude}} </th>
            <th> <a href="{{$event->id}}"> Подробно </a></th>
        </tr>
        @endforeach
    </table>
@endsection