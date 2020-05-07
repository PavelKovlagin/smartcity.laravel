@extends('layouts/layout')
@section('title')
Восстановаить пароль
@endsection
@section('content')
<p class='error'>{{session('error')}}</p>
<form action='/sendCode' method="POST">
@csrf
<p>E-mail address: <input type="text" name="email"></p>
<button type="submit">Отправть код</button>
</form>
   
@endsection