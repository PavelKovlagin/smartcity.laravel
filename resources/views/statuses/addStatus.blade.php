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
    <br><br><br>
    <button type="submit"> Добавить </button>
</form>
@endsection