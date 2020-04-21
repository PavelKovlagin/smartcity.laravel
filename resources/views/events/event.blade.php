@extends('layouts/layout')
@section('title')
{{$event->eventName}}
@endsection
@section('content')
<p class="error"> {{session('error')}} </p>
@if ($authUser <> false 
    AND (($event->user_id == $authUser -> user_id) AND ($event->status_id == 1)
    OR ($authUser->levelRights > 1) AND (($authUser->levelRights > $user->levelRights) OR ($authUser->user_id == $user->user_id))))
    <form action="{{ url('/updateEvent') }}" method="POST">
    @csrf
    <p> Идентификатор события: {{$event->event_id}} </p>
    <input type="hidden" name="event_id" value="{{$event->event_id}}">
    <p> Пользователь: {{$event->email}} </p>
    <p> Дата создания: {{$event->event_date}} </p>
    <p> Дата последнего обновления: {{$event->dateChange}} </p>
    <p> Название события: <input type="text" size=50 name="eventName" value="{{$event->eventName}}"> </p>
    <p> Статус события: 

        @if($authUser->levelRights > 1)
        <p><select name = "status_id">
        @foreach($statuses as $status)
            <option @if($status->id ==  $event->status_id) selected @endif value="{{ $status->id }}">{{ $status->statusName }}</option>
        @endforeach
            </select></p> 
        @else
        {{$event->statusName}}  
        <input type="hidden" name="status_id" value="{{$event->status_id}}">  
        @endif


    <p>Описание события:<br>
    <textarea name="eventDescription" cols="50" rows="10"> 
    {{$event->eventDescription}} 
    </textarea></p>     
    <p> Долгота: <input size=10 type="number" step="any" name="longitude" value="{{$event->longitude}}"> </p>
    <p> Широта: <input size=10 type="number" step="any" name="latitude" value="{{$event->latitude}}"> </p>       
    <button type="submit"> Обновить событие </button>
    </form>
    <br>

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
        <input type="hidden" name="event_id" value="{{$event->event_id}}">
        <p>Текст комментария:</p>
        <textarea name="comment" cols="50" rows="10">
        </textarea>
        <button type="submit"> Отправить </button>
        </form>
    @endif

    @foreach ($comments as $someComment)
    <p> <a href="/users/user/{{$someComment->user_id}}">{{$someComment->email}}</a> {{$someComment->dateTime}}</p>
    <p>{{$someComment->text}}</p>
    @endforeach
@endsection