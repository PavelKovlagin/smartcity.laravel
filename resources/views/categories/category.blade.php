@extends('layouts/layout')
@section('title')
{{$category->categoryName}}
@endsection
@section('content')
<div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{$category->categoryName}}</div>

                    <div class="card-body">
                        <div class="col-md-12">
                            @if (($authUser <> false) AND ($authUser->levelRights > 2))
                                <form action = "{{ url('/updateCategory') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{$category->id}}">
                                <p>Название категории: <input class="form-control" type="text" name="categoryName" value="{{$category->categoryName}}"></p>   
                                <p>Описаник категории:</p>
                                <textarea class="form-control" name="categoryDescription">{{$category->categoryDescription}}</textarea>
                                <br>
                                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Обновить категорию</button>
                                </form>    
                                @if ($category->notRemove == 0)        
                                    <form action="{{ url('/deleteCategory') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{$category->id}}">
                                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Удалить категорию</button>
                                    </form>
                                @endif

                            @else
                            <p> Название категории: {{$category->categoryName}}</p>
                            <p> Описание категории: {{$category->categoryDescription}}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection