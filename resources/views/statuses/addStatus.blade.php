@extends('layouts/layout')
@section('title')
Добавить статус
@endsection
@section('content')
<div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Добавить новый статус</div>

                    <div class="card-body">
                        <div class="col-md-12">
                            <form action="{{ url('/addStatus') }}" method="POST">
                                @csrf
                                <p> Название статуса:</p>
                                <input class="form-control" type="text" name="statusName" required>
                                <p> Описание события:</p>
                                <textarea class="form-control" type="text" name="statusDescription" required></textarea>
                            <p>Видимость для пользователя</p>
                            <p><select class="form-control" name = "visibilityForUser">
                                    <option value=0>Невидимый</option>
                                    <option value=1>Видимый</option>
                                    </select></p> 
                                <button class="btn btn-outline-success my-2 my-sm-0" type="submit"> Добавить </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection