@extends('layouts/layout')
@section('title')
Добавить событие
@endsection
@section('content')
@include('errors')
<form action="{{ url('/addEvent') }}" method="POST">
    {{ csrf_field() }}
    <p> Название события:</p>
    <input type="text" name="eventName">
    <p> Описание события:</p>
    <textarea type="text" name="eventDescription">
</textarea>
    <p> Долгота: </p>
    <input type="number" step="any" name="longitude">
    <p> Широта:</p>
    <input type="number" step="any" name="latitude">
    <br><br><br>
    <button type="submit"> Добавить </button>
</form>
@endsection