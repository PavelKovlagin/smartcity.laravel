@extends('layouts/layout')
@section('title')
Добавить статус
@endsection
@section('content')
<div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Добавить категорию</div>

                    <div class="card-body">
                        <div class="col-md-12">
                            <form action="{{ url('/addCategory') }}" method="POST">
                                @csrf
                                <p> Название категории: <input class="form-control" type="text" name="categoryName" required></p>
                                <p> Описание события:</p>
                                <textarea class="form-control" type="text" name="categoryDescription" required></textarea>
                                <br>
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