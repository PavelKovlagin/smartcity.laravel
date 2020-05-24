@extends('layouts.layout')
@section('content')
<form method="POST" action="{{ route('register') }}">
    @csrf
<div class="container">
    <div class="container">
        <label for="surname">Фамилия</label>
        <input id="surname" type="text" class="form-control @error('surname') is-invalid @enderror" name="surname"
            value="{{ old('surname') }}" autocomplete="surname" autofocus>
    </div>
    <br>
    <div class="container">
        <label for="name">Имя</label>
        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name"
            value="{{ old('name') }}" required autocomplete="name" autofocus>

        @error('name')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <br>
    <div class="container">
        <label for="subname">Отчество</label>
        <br>
        <input id="subname" type="text" class="form-control @error('subname') is-invalid @enderror" name="subname"
            value="{{ old('subname') }}" autocomplete="subname" autofocus>
    </div>
    <br>
    <div class="container">
        <label for="date" value="1999-01-01">Дата рождения</label>
        <br>
        <input id="date" type="date" class="form-control @error('date') is-invalid @enderror" name="date"
            value="{{ old('date') }}" autocomplete="date" autofocus>
    </div>
    <br>
    <div class="container">
        <label for="email">E-Mail Address</label>
        <br>
        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
            value="{{ old('email') }}" required autocomplete="email">

        @error('email')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <br>
    <div class="container">
        <label for="password">Пароль</label>
        <br>
        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
            name="password" required autocomplete="new-password">

        @error('password')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>
    <br>
    <div class="container">
    <label for="password-confirm">Подтверждение пароля</label>
    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required
        autocomplete="new-password">
        <br><br>
    <button type="submit" class="btn btn-primary">
        Зарегистрироваться
    </button>
    </div>
</form>
@endsection