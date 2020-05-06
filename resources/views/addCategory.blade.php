@extends('layouts/layout')
@section('title')
Добавить статус
@endsection
@section('content')
<form action="{{ url('/addCategory') }}" method="POST">
    @csrf
    <p> Название категории: <input type="text" name="categoryName" required></p>
    <p> Описание события:</p>
    <textarea type="text" name="categoryDescription" required></textarea>
    <br>
    <button type="submit"> Добавить </button>
</form>
@endsection