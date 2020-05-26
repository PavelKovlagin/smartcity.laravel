@extends('layouts/layout')
@section('title')
Smart City
@endsection
@section('content')
<script>
</script>
<div class="row justify-content-center" style="text-align: center">
    <h1>Добро пожаловать в Умный Город, приложение для мониторинга городских проблем</h1>
    <div id="map_pl" style="width:1000px; height:500px"></div>
</div>

<script src="//api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
<script type="text/javascript">
ymaps.ready(function () {
    var myMap = new ymaps.Map('map_pl', {
            center: [55.751574, 37.573856],
            zoom: 9,
            controls: ['zoomControl', 'typeSelector', 'trafficControl']
        }),
        clusterer = new ymaps.Clusterer({
            preset: 'islands#invertedVioletClusterIcons',
            clusterHideIconOnBalloonOpen: false,
            geoObjectHideIconOnBalloonOpen: false
        });

    /**
     * Кластеризатор расширяет коллекцию, что позволяет использовать один обработчик
     * для обработки событий всех геообъектов.
     * Будем менять цвет иконок и кластеров при наведении.
     */
    clusterer.events
        // Можно слушать сразу несколько событий, указывая их имена в массиве.
        .add(['mouseenter', 'mouseleave'], function (e) {
            var target = e.get('target'),
                type = e.get('type');
            if (typeof target.getGeoObjects != 'undefined') {
                // Событие произошло на кластере.
                if (type == 'mouseenter') {
                    target.options.set('preset', 'islands#invertedPinkClusterIcons');
                } else {
                    target.options.set('preset', 'islands#invertedVioletClusterIcons');
                }
            } else {
                // Событие произошло на геообъекте.
                if (type == 'mouseenter') {
                    target.options.set('preset', 'islands#pinkIcon');
                } else {
                    target.options.set('preset', 'islands#violetIcon');
                }
            }
        });

    var getPointData = function (eventName, eventDescription, index, eventId) {
            return {
                balloonContent: '<strong>' + eventName + '</strong><br/>' + eventDescription + "<br><a href='/events/" + eventId +"'>Подробнее</a>",
                clusterCaption: 'метка <strong>' + index + '</strong>'
            };
        },
        getPointOptions = function () {
            return {
                preset: 'islands#violetIcon'
            };
        },
        geoObjects = [];

        points = [];
        @foreach ($events as $key => $event)
            points[{{$key}}] = ["{{$event->eventName}}", "{{$event->eventDescription}}", "{{$event->latitude}}", "{{$event->longitude}}", "{{$event->id}}"];            
        @endforeach

        for(var i = 0, len = points.length; i < len; i++) {
            geoObjects[i] = new ymaps.Placemark([points[i][2], points[i][3]], getPointData(points[i][0], points[i][1], i, points[i][4]), getPointOptions());
    }
   
   clusterer.options.set({
       gridSize:1000
   });

    clusterer.add(geoObjects);
    myMap.geoObjects.add(clusterer);

    myMap.setBounds(clusterer.getBounds(), {
        checkZoomRange: true
    });
});
</script>
</div>
@endsection