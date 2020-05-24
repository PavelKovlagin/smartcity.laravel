@extends('layouts/layout')
@section('title')
Добавить событие
@endsection
@section('content')
@include('errors')
@if($authUser<>false)
    @if($authUser->blocked == false)    
    <form enctype="multipart/form-data" action="{{ url('/addEvent') }}" method="POST">
        {{ csrf_field() }}    
        <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Добавить новое событие</div>

                    <div class="card-body">
                        <div class="col-md-12">
                            <p> Название события:</p>
                            <input required class="form-control" type="text" name="eventName">
                            <p> Описание события:</p>
                            <textarea required class="form-control" type="text" name="eventDescription"></textarea>
                            <p>Категория события: <select class="form-control" name = "category_id">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->categoryName }}</option>
                            @endforeach
                            </select></p>
                            <p> Долгота: </p>
                            <input class="form-control" required type="number" step="any" name="longitude">
                            <p> Широта:</p>
                            <input class="form-control" required type="number" step="any" name="latitude">
                            <p>Изображения</p>
                            <input class="form-control" multiple type="file" name="images[]" accept="image/*">
                            <br><br><br>
                            <button class="btn btn-outline-success my-2 my-sm-0" type="submit"> Добавить </button>  
                            </form>    
                            @else
                            <p class='error'>Вы не можете добавлять события, Ваш профиль заблокирован до {{$authUser->blockDate}} </p>
                            @endif
                        @else
                        <p>Вы не авторизованы</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection