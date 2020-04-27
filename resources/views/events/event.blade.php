@extends('layouts/layout')
@section('title')
{{$event->eventName}}
@endsection
@section('content')
{{session('hey')}}
<p class="error"> {{session('error')}} </p>
@if ($authUser <> false 
    AND (($event->user_id == $authUser -> user_id) AND ($event->status_id == 1)
    OR ($authUser->levelRights > 1) AND (($authUser->levelRights > $user->levelRights) OR ($authUser->user_id == $user->user_id))))
    <p> Пользователь: {{$event->email}} </p>
    <p> Дата создания: {{$event->event_date}} </p>
    <p> Дата последнего обновления: {{$event->dateChange}} </p>

    <form action="/updateEvent" method="POST">
    @csrf
    <input type="hidden" name="event_id" value="{{$event->id}}">    
    <p> Название события: <input type="text" size=50 name="eventName" value="{{$event->eventName}}"> </p>
    <p>Описание события:</p>
    <textarea name="eventDescription" cols="50" rows="10">{{$event->eventDescription}}</textarea>     
    <p> Долгота: <input size=10 type="number" step="any" name="longitude" value="{{$event->longitude}}"> </p>
    <p> Широта: <input size=10 type="number" step="any" name="latitude" value="{{$event->latitude}}"> </p>       
    <p>Категория события: <select name = "category  _id">
        @foreach($categories as $category)
            <option @if($category->id ==  $event->category_id) selected @endif value="{{ $category->id }}">{{ $category->categoryName }}</option>
        @endforeach
        </select></p>
    <button type="submit"> Обновить информацию о событии </button>
    </form>

    <br> 
    @if ($authUser->levelRights > 1)
        <form action="/updateEventStatus" method="POST">
        @csrf
        <input type="hidden" name="event_id" value="{{$event->id}}">
        <input type="hidden" name="user_id" value="{{$event->user_id}}">
        <p>Статус события: <select name = "status_id">
        @foreach($statuses as $status)
            <option @if($status->id ==  $event->status_id) selected @endif value="{{ $status->id }}">{{ $status->statusName }}</option>
        @endforeach
        </select></p>
        <button type="submin">Обновить статус события</button> 
        </form>
    @else
        <p>Статус события: {{$event->statusName}}</p>  
    @endif

    @else    
    <p> Название события: {{$event->eventName}} </p>
    <p> Пользователь: {{$event->email}} </p>
    <p> Статус события: {{$event->statusName}} </p>
    <p> Описание события: {{$event->eventDescription}} </p>
    <p> Дата создания: {{$event->event_date}} </p>
    <p> Дата последнего обновления: {{$event->dateChange}} </p>
    <p> Долгота: {{$event->longitude}} </p>
    <p> Широта: {{$event->latitude}} </p>    
@endif

<script src="http://api-maps.yandex.ru/2.0/?load=package.full&lang=ru-RU" type="text/javascript"></script>
<script type="text/javascript">
var longitude = {{$event->longitude}};
var latitude = {{$event->latitude}};
        // Как только будет загружен API и готов DOM, выполняем инициализацию
        ymaps.ready(init);
 
        function init () {
            // Создание экземпляра карты и его привязка к контейнеру с
            // заданным id ("map")
            var myMap = new ymaps.Map('map', {
                    // При инициализации карты, обязательно нужно указать
                    // ее центр и коэффициент масштабирования
                    center: [latitude, longitude], // Событие
                    zoom: 13
                });
 
			// Создание метки 
			var myPlacemark = new ymaps.Placemark(
                
			// Координаты метки
			[latitude, longitude]        
			);
 
		// Добавление метки на карту
		myMap.geoObjects.add(myPlacemark);
 
 
        }
    </script>
    <div id="map" style="width:600px; height:400px"></div>
    <br>

    @if($authUser <> false)
        <form action="{{ url('/addComment') }}" method="POST">
        @csrf
        <input type="hidden" name="event_id" value="{{$event->id}}">
        <p>Текст комментария:</p>
        <textarea name="comment" cols="50" rows="10"></textarea>
        <button type="submit"> Отправить </button>
        </form>
    @endif

    @foreach ($comments as $comment)
    <p> <a href="/users/user/{{$comment->user_id}}">{{$comment->email}}</a> {{$comment->dateTime}}</p>
    <p>{{$comment->text}}</p>
    @if (($authUser<>false) 
        AND (($authUser->levelRights > $comment->user_levelRights)
        OR ($authUser->user_id == $comment->user_id)))
        <form action="/deleteComment" method="POST">
        @csrf
        <input type='hidden' name='comment_id' value={{$comment->id}}>
        <button type="submit">Удалить</button>
        </form>
    @endif
    @endforeach
@endsection