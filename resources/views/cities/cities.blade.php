@extends('layouts/layout')
@section('title')
{{$title}}
@endsection
@section('content')
@if (Auth::check())
<button type="submit" onclick="location.href='/cities/addCity'">Добавить город</button>
@endif
@if(count($cities)>0)
<h1>{{$title}}</h1>    
    <table border="1">
        <tr>
            <th> Название города </th>
            <th> Долгота </th>
            <th> Широта </th>
        </tr>
        @foreach ($cities as $city)
        <tr>
            <th> {{$city->city_name}} </th>
            <th> {{$city->city_longitude}} </th>
            <th> {{$city->city_latitude}} </th>
            <th> <a href="/cities/{{$city->city_id}}"> Подробно </a></th>
        </tr>
        @endforeach
    </table>
    {{$cities->links('pagination.pagination')}}
    @else
    <h1>Городов нет</h1>
    @endif
@endsection