@extends('layouts/layout')
@section('title')
{{$category->categoryName}}
@endsection
@section('content')
@if (($authUser <> false) AND ($authUser->levelRights > 2))
    <form action = "{{ url('/updateCategory') }}" method="POST">
    @csrf
    <input type="hidden" name="id" value="{{$category->id}}">
    <p>Название категории: <input type="text" name="categoryName" value="{{$category->categoryName}}"></p>   
    <p>Описаник категории:</p>
    <textarea name="categoryDescription">{{$category->categoryDescription}}</textarea>
    <br>
    <button type="submit">Обновить категорию</button>
    </form>    
    @if ($category->notRemove == 0)        
        <form action="{{ url('/deleteCategory') }}" method="POST">
        @csrf
        <input type="hidden" name="id" value="{{$category->id}}">
        <button type="submit">Удалить категорию</button>
        </form>
    @endif

@else
<p> Название категории: {{$category->categoryName}}</p>
<p> Описание категории: {{$category->categoryDescription}}</p>
@endif
@endsection