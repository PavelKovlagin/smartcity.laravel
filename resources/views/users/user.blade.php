@extends('layouts/layout')
@section('title')
{{$user->surname}} {{$user->name}} {{$user->subname}}
@endsection
@section('content')
<form action="{{ url('/updateUser') }}" method="POST">
@csrf
<input type="hidden" name="user_id" value="{{$user->id}}">
<p> Фамилия: {{$user->surname}} </p>
<p> Имя: {{$user->name}} </p>
<p> Отчество: {{$user->subname}} </p>
<p> Дата рождения: {{$user->date}} </p>
<p> Роль: {{$user->role}} </p>
<p> Email: {{$user->email}} </p>
@if((Auth::check()) and (Auth::user() -> role == "admin") and ( $user->id <> Auth::user() -> id)) 
<select name="role">
    <option value="user">user</option>
    <option value="admin">admin</option>
   </select>
<button type="submit"> Обновить </button>
</form>
<form action="{{ url('/deleteUser')}}" method="POST">
@csrf
<input type="hidden" name="user_id" value="{{$user->id}}">
<button type="submit"> Удалить пользователя </button>
@endif
</form>
@endsection