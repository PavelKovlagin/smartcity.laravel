@extends('layouts/layout')
@section('title')
Добавить статус
@endsection
@section('content')
@include('errors')
<form action="{{ url('/addStatus') }}" method="POST">
    @csrf
    <p> Название статуса:</p>
    <input type="text" name="statusName">
    <p> Описание события:</p>
    <textarea type="text" name="statusDescription">
</textarea>
<p>Видимость для пользователя</p>
<p><select name = "visibilityForUser">
        <option value=0>Невидимый</option>
        <option value=1>Видимый</option>
        </select></p> 
    <br><br><br>
    <button type="submit"> Добавить </button>
</form>
@endsection