@extends('layouts/layout')
@section('title')
{{$city->city_name}}
@endsection
@section('content')
<div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{$city->city_name}}</div>
                    <div class="card-body">
                        <div class="col-md-12">
                            @if (($authUser<>false) AND ($authUser->levelRights > 2))
                                <form action="{{ url('/updateCity') }}" method="POST">
                                @csrf
                                <p> Идентификатор города: {{$city->city_id}} </p>
                                <input type="hidden" name="city_id" value="{{$city->city_id}}">
                                <p>Название города: <input class="form-control" type="text" name="city_name" value="{{$city->city_name}}"></p>
                                <p>Долгота: <input class="form-control" type="text" name="city_longitude" value="{{$city->city_longitude}}"></p>
                                <p>Широта: <input class="form-control" type="text" name="city_latitude" value="{{$city->city_latitude}}"></p> 
                                <button class="btn btn-outline-success my-2 my-sm-0" type="submit"> Обновить </button>
                                </form>
                                <form action="{{ url('/deleteCity') }}" method="POST">
                                @csrf
                                <input type="hidden" name="city_id" value="{{$city->city_id}}">
                                <button class="btn btn-outline-success my-2 my-sm-0" type="submit"> Удалить </button>
                                </form>
                            @else
                            <p>Название города: {{$city->city_name}}</p>
                            <p>Долгота: {{$city->city_longitude}}</p>
                            <p>Широта: {{$city->city_latitude}}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection