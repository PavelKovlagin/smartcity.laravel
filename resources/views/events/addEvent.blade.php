@extends('layouts/layout')
@section('title')
Добавить событие
@endsection
@section('content')
@include('errors')
@if($authUser<>false)
    @if($authUser->blocked == false)    
    <form action="{{ url('/addEvent') }}" method="POST">
        {{ csrf_field() }}    
        <p> Название события:</p>
        <input type="text" name="eventName">
        <p> Описание события:</p>
        <textarea type="text" name="eventDescription"></textarea>
        <p> Долгота: </p>
        <input type="number" step="any" name="longitude">
        <p> Широта:</p>
        <input type="number" step="any" name="latitude">
        <br><br><br>
        <button type="submit"> Добавить </button>
    </form>
    
    <form action="/uploadImage" enctype="multipart/form-data" method="POST">
    @csrf
    <input multiple type="file" name="images[]">
    <br><br><br>
    <button type="submit">Загрузить</button>
    
    </form>
    @else
    <p class='error'>Вы не можете добавлять события, Ваш профиль заблокирован до {{$authUser->blockDate}} </p>
    @endif
@else
<p>Вы не авторизованы</p>
@endif
@endsection