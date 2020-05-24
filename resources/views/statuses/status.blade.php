@extends('layouts/layout')
@section('title')
{{$status->statusName}}
@endsection
@section('content')
<div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{$status->statusName}}</div>

                    <div class="card-body">
                        <div class="col-md-12">
                            @if (($authUser<>false) AND ($authUser->levelRights > 2))
                                <form action="{{ url('/updateStatus') }}" method="POST">
                                @csrf
                                <p> Идентификатор статуса: {{$status->id}} </p>
                                <input type="hidden" name="status_id" value="{{$status->id}}">
                                <p><input class="form-control" type="text" name="status_name" value="{{$status->statusName}}"></p>
                                <p>Описание статуса</p>
                                <textarea class="form-control" name="status_description">{{$status->statusDescription}}</textarea>
                                <p>Видимость для пользователя: {{$status->visibilityForUser}}</p>
                                <p><select class="form-control" name = "visibilityForUser">
                                    <option value=0>Невидимый</option>
                                    <option value=1>Видимый</option>
                                    </select></p> 
                                <button class="btn btn-outline-success my-2 my-sm-0" type="submit"> Обновить </button>
                                </form>

                                @if($status->notRemove == 0)
                                <form action="{{ url('/deleteStatus') }}" method="POST">
                                @csrf
                                <input type="hidden" name="status_id" value="{{$status->id}}">
                                <button class="btn btn-outline-success my-2 my-sm-0" type="submit"> Удалить </button>
                                </form>
                                @endif
                            @else
                            <p>Название статуса: {{$status->statusName}}</p>
                            <p>Описание статуса: {{$status->statusDescription}}</p>
                            <p>Видимость для пользователя: {{$status->visibilityForUser}}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection