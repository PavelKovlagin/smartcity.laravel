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
    <button type="submit"> Обновить </button>
</form>
@if($status->notRemove == 0)
    <form action="{{ url('/deleteStatus') }}" method="POST">
    @csrf
    <input type="hidden" name="status_id" value="{{$status->id}}">
    <input type="hidden" name="notRemove" value="{{$status->notRemove">
    <button type="submit"> Удалить </button>
    </form>
@endif
@endsection