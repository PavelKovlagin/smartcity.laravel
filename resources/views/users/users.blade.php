@extends('layouts/layout')
@section('title')
{{$title}}
@endsection
@section('content')
@if((Auth::check()) and (Auth::user() -> role == "admin"))
@if(count($users)>0)
<h1>{{$title}}</h1>
    
    <table border="1">
        <tr>
            <th> ID </th>
            <th> Фамилия </th>
            <th> Имя </th>
            <th> Отчество </th>
            <th> Дата рождения </th>
            <th> Email </th>
            <th> Роль </th>
        </tr>
        @foreach ($users as $user)
        <tr>
            <th> {{$user->id}} </th>
            <th> {{$user->surname}} </th>
            <th> {{$user->name}} </th>
            <th> {{$user->subname}} </th>
            <th> {{$user->date}} </th>
            <th> {{$user->email}} </th>
            <th> {{$user->role}} </th>
            @if((Auth::check()) and (Auth::user() -> role == "admin")) 
            <th> <a href="/users/user/{{$user->id}}" > Подробно </a><th>
            @endif
        </tr>
        @endforeach
    </table>
    {{$users->links('pagination.pagination')}}
    @else
    <h1>Пользователей нет</h1>
    @endif
@else
<h1>У Вас недостаточно прав</h1>
@endif
@endsection