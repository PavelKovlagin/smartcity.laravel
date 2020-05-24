@extends('layouts/layout')
@section('title')
Smart City
@endsection
@section('content')
<div class="container">
<h1>Добро пожаловать в Умный Город, приложение для мониторинга городских проблем</h1>
</div>
<div id="map_pl" style="width:640px; height:400px">
<script src="//api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
<script type="text/javascript">
ymaps.ready(init);

function init() {
   var myMap = new ymaps.Map('map_pl', {
            center: [{{$events[0]->latitude}}, {{$events[0]->longitude}}],
            zoom: 11,
            controls: ['zoomControl', 'typeSelector', 'trafficControl']
        }, {
            searchControlProvider: 'yandex#search'
        })  
    
    @foreach ($events as $event)
        myMap.geoObjects.add(new ymaps.Placemark([{{$event->latitude}}, {{$event->longitude}}], {
            balloonContent: '<strong>{{$event->eventName}}</strong><br/>{{$event->eventDescription}}',
        }, {
           preset: 'islands#redDotIconWithCaption'
        }));
    @endforeach

}
</script>
</div>



@endsection