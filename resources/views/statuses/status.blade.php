@extends('layouts/layout')
@section('title')
{{$status->statusName}}
@endsection
@section('content')
@if (($authUser<>false) AND ($authUser->levelRights > 2))
    <form action="{{ url('/updateStatus') }}" method="POST">
    @csrf
    <p> Идентификатор статуса: {{$status->id}} </p>
    <input type="hidden" name="status_id" value="{{$status->id}}">
    <p><input type="text" name="status_name" value="{{$status->statusName}}"></p>
    <p>Описание статуса</p>
    <textarea name="status_description">{{$status->statusDescription}}</textarea>
    <p>Видимость для пользователя: {{$status->visibilityForUser}}</p>
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
    <button type="submit"> Удалить </button>
    </form>
    @endif
@else
<p>Название статуса: {{$status->statusName}}</p>
<p>Описание статуса: {{$status->statusDescription}}</p>
<p>Видимость для пользователя: {{$status->visibilityForUser}}</p>
@endif
@endsection