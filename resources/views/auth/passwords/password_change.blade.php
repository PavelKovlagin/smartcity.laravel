@extends('layouts/layout')
@section('title')
Восстановаить пароль
@endsection
@section('content')
<div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Добавить категорию</div>

                    <div class="card-body">
                        <div class="col-md-12">
                            <p class='error'>{{session('message')}}</p>
                            <form action='/passwordChange' method="POST">
                            @csrf
                            <p>E-mail address: <input class="form-control" type="text" name="email" required ></p>
                            <p>Код сброса пароля: <input class="form-control" type="text" name="code_reset_password" required ></p>
                            <p>Новый пароль: <input class="form-control" type="password" name="password" required minlength="8"></p>
                            <p>Подтверждение пароля: <input class="form-control" type="password" name="password_confirm" required minlength="8"></p>
                            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Отправить</button>
                            </form>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection