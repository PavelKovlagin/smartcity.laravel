@extends('layouts/layout')
@section('title')
{{$event->nameEvent}}
@endsection
@section('content')
<p> Название события: {{$event->nameEvent}} </p>
<p> Описание события: {{$event->eventDescription}} </p>
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
    <form action="{{ url('/addComment') }}" method="POST">
    @csrf
    <input type="hidden" name="event_id" value="{{$event->id}}">
    <p>Текст комментария:</p>
    <textarea name="comment" cols="50" rows="10">
    </textarea>
    <button type="submit"> Отправть </button>
    </form>

    @foreach ($comments as $someComment)
    <p> <font color="black">{{$someComment->email}} {{$someComment->dateTime}}</font></p>
    <p>{{$someComment->text}}</p>
    @endforeach
@endsection