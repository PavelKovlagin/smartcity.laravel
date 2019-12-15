@extends('layouts/layout')
@section('title')
{{$status->statusName}}
@endsection
@section('content')
<form action="{{ url('/updateVisibility') }}" method="POST">
@csrf
    <p> Идентификатор статуса: {{$status->id}} </p>
    <input type="hidden" name="id" value="{{$status->id}}">
    <p> Название статуса: {{$status->statusName}}</p>
    <p> Описание события: {{$status->statusDescription}}</p>
<p>Видимость для пользователя</p>
<p><select name = "visibilityForUser">
        <option value=0>Невидимый</option>
        <option value=1>Видимый</option>
        </select></p> 
    <br><br><br>
    <button type="submit"> Обновить </button>
</form>
@endsection