@extends('layouts/layout')
@section('title')
{{$title}}
@endsection
@section('content')

@if (($authUser <> false) AND ($authUser->levelRights > 2))
<button type="submit" onclick="location.href='/categories/addCategory'">Добавить категорию</button>
@endif

@if(count($categories)>0)
<h1>{{$title}}</h1>
    
    <table border="1">
        <tr>
            <th> Название категории </th>
            <th> Описание категории </th>
        </tr>
        @foreach ($categories as $category)
        <tr>
            <th> {{$category->categoryName}} </th>
            <th> {{$category->categoryDescription}} </th>
            <th> <a href="/categories/{{$category->id}}"> Подробно </a></th>
        </tr>
        @endforeach
    </table>
    {{$categories->links('pagination.pagination')}}
    @else
    <h1>Категорий нет</h1>
    @endif
@endsection