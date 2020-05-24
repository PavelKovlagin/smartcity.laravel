@extends('layouts/layout')
@section('title')
Восстановаить пароль
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Введите e-mail на который будет отправлен код сброса пароля</div>

                <div class="card-body">
                    <div class="col-md-12">
                        <p class='error'>{{session('error')}}</p>
                        <form action='/sendCode' method="POST">
                        @csrf
                        <p>E-mail address: <input class="form-control" type="text" name="email"></p>
                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Отправть код</button>
                        </form>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>   
@endsection