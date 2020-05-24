@extends('layouts/layout')
@section('title')
{{$user->surname}} {{$user->user_name}} {{$user->subname}}
@endsection
@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{$user->surname}} {{$user->user_name}} {{$user->surname}}</div>
                        
                    <div class="card-body">
                        <div class="col-md-12">

                            @if ($authUser <> false
                                AND (($user->user_id == $authUser->user_id)
                                OR ($authUser->levelRights > 1 AND $authUser->levelRights > $user->levelRights))) 
                                    @if ($user->blocked <> false)
                                        <p class="error"> {{$user->blocked}} </p>
                                    @endif      
                                <form action="{{ url('/updateUser') }}" method="POST">
                                @csrf
                                <input type="hidden" name="user_id" value="{{$user->user_id}}">
                                <input type="hidden" name="user_role" value="{{$user->role_id}}">  
                                <p > Фамилия: <input class="form-control" type="text" name="surname" value="{{$user->surname}}"></p>
                                <p> Имя: <input class="form-control" type="text" name="name" value="{{$user->user_name}}"> </p>
                                <p> Отчество: <input class="form-control" type="text" name="subname" value="{{$user->subname}}"></p>
                                <p> Дата рождения: <input class="form-control" type="date" name="date" value="{{$user->date}}">  </p>
                                <button type="submit" class="btn btn-outline-success my-2 my-sm-0"> Редактировать профиль </button>       
                                </form>
                                <p> Роль: {{$user->role_name}} </p>
                                <p> Email: {{$user->email}} </p>
                                    @if(($user->user_id <> $authUser->user_id))
                                        @if($authUser->levelRights == 3)
                                        <form action="{{ url('/updateRole') }}" method="POST">
                                            @csrf
                                            <input  type="hidden" name="user_id" value="{{$user->user_id}}">
                                            <select class="form-control" name="role_id">
                                                @foreach($roles as $role)
                                                <option @if($user->role_id == $role->role_id) selected @endif value="{{$role->role_id}}">{{$role->role_name}}</option>
                                                @endforeach
                                            </select>
                                            <button type="submit"> Обновить </button>
                                            </form>
                                        @endif
                                @if ($authUser->levelRights > $user->levelRights)             
                                    <form action="{{ url('/blockedUser')}}" method="POST">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{$user->user_id}}">
                                    Заблокировать пользователя до
                                    <br>
                                    <input class="form-control" type="date" id="start" name="blockDate" value="{{$user->blockDate}}">
                                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit"> Заблокировать </button>
                                    </form>
                                    <br>
                                    <form action="{{ url('/blockedUser')}}" method="POST">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{$user->user_id}}">
                                    <input type="hidden" id="start" name="blockDate" value="0001-01-01">
                                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit"> Разблокировать </button>
                                    </form>
                                @endif
                            @endif
                        @else
                        <p> Фамилия: {{$user->surname}} </p>
                        <p> Имя: {{$user->user_name}} </p>
                        <p> Отчество: {{$user->subname}} </p>
                        <p> Дата рождения: {{$user->date}} </p>
                        <p> Роль: {{$user->role_name}} </p>
                        <p> Email: {{$user->email}} </p>
                        @endif
                        <br>
                        <a href="/events?user_id={{$user->user_id}}">Показать события пользователя</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 
@endsection