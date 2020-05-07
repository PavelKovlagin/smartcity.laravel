@extends('layouts/layout')
@section('title')
Восстановаить пароль
@endsection
@section('content')
<p class='error'>{{session('error')}}</p>
<form action='/passwordChange' method="POST">
@csrf
<p>E-mail address: <input type="text" name="email" required ></p>
<p>Код сброса пароля: <input type="text" name="code_reset_password" required ></p>
<p>Новый пароль: <input type="password" name="password" required minlength="8"></p>
<p>Подтверждение пароля: <input type="password" name="password_confirm" required minlength="8"></p>
<button type="submit">Отправить</button>
</form>
   
@endsection