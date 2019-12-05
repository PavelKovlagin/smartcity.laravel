@extends('layouts/layout')
@section('title')
{{$event->eventName}}
@endsection
@section('content')
<form action="{{ url('/updateEvent') }}" method="POST">
@csrf
<p> Идентификатор события: {{$event->event_id}} </p>

    @if(Auth::check() and Auth::user() -> role == 'admin')
    <p> Статус события: </p>
       <input type="hidden" name="event_id" value="{{$event->event_id}}">
        <p><select name = "status_id">
     @foreach($statuses as $status)
        <option @if($status->id ==  $event->status_id) selected @endif value="{{ $status->id }}">{{ $status->statusName }}</option>
    @endforeach
        </select></p>   
        <button type="submit"> Обновить событие </button>
    @else
        <p> Статус события: {{$event->statusName}} </p>
     @endif
<p> Название события: {{$event->eventName}} </p>
<p> Описание события: {{$event->eventDescription}} </p>
<p> Дата создания: {{$event->event_date}} </p>
<p> Дата последнего обновления: {{$event->dateChange}} </p>
<p> Долгота: {{$event->longitude}} </p>
<p> Широта: {{$event->latitude}} </p>
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
                    center: [longitude, latitude], // Событие
                    zoom: 13
                });
 
			// Создание метки 
			var myPlacemark = new ymaps.Placemark(
                
			// Координаты метки
			[longitude, latitude]        
			);
 
		// Добавление метки на карту
		myMap.geoObjects.add(myPlacemark);
 
 
        }
    </script>
    <div id="map" style="width:600px; height:400px"></div>
    <br><br>
       
    </form>
    @if(Auth::check()) {
        <form action="{{ url('/addComment') }}" method="POST">
        @csrf
        <input type="hidden" name="event_id" value="{{$event->event_id}}">
        <p>Текст комментария:</p>
        <textarea name="comment" cols="50" rows="10">
        </textarea>
        <button type="submit"> Отправить </button>
        </form>
    }
    @endif

    @foreach ($comments as $someComment)
    <p> <font color="black">{{$someComment->email}} {{$someComment->dateTime}}</font></p>
    <p>{{$someComment->text}}</p>
    @endforeach
@endsection