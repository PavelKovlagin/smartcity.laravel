@extends('layouts/layout')
@section('title')
Добавить событие
@endsection
@section('content')
<script>
    function MyPosition(){

        var options = {
            enableHighAccuracy: true,
            timeout: 5000,
            maximumAge: 0
        }; 

        function success(pos) {
            var crd = pos.coords;
            document.getElementById('longitude').value = crd.longitude ;
            document.getElementById('latitude').value = crd.latitude;
        };

        function error(err) {
            alert("Ошибка геолокации. Код ошибки: " + err.code + ". Сообщение: " + err.message);
        };

        navigator.geolocation.getCurrentPosition(success, error, {enableHighAccuracy: true, timeout: 5000, maximumAge: 0});
    }
</script>

@if($authUser<>false)
    @if($authUser->blocked == false) 
    <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Добавить новое событие</div>                
                <div class="card-body">
                    <div class="col-md-12">
                        <p class="error">{{session("message")}}</p>
                        <form enctype="multipart/form-data" action="{{ url('/addEvent') }}" method="POST">
                        {{ csrf_field() }} 
                        <p> Название события:</p>
                        <input required class="form-control" type="text" name="eventName">
                        <p> Описание события:</p>
                        <textarea required class="form-control" type="text" name="eventDescription"></textarea>
                        <p>Категория события: <select class="form-control" name = "category_id">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->categoryName }}</option>
                        @endforeach
                        </select></p>
                        <p> Долгота: </p>
                        <input class="form-control" required type="number" step="any" id="longitude" name="longitude">
                        <p> Широта:</p>
                        <input class="form-control" required type="number" step="any" id="latitude" name="latitude">
                        <br>
                        <input class="btn btn-outline-success my-2 my-sm-0" type="button" value="Мое местоположение" onClick="MyPosition()">

                        <div class="row justify-content-center" style="text-align: center">
                            <div id="map" style="width:640px; height:400px"></div>
                        </div>
                        <script src="//api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
                        <script type="text/javascript">
                            ymaps.ready(init);
                            var myMap;

                            function init () {
                                myMap = new ymaps.Map("map", {
                                    center: [56.129057, 40.406635], // Владимир
                                    zoom: 11
                                }, {
                                    balloonMaxWidth: 200,
                                    searchControlProvider: 'yandex#search'
                                });

                                // Обработка события, возникающего при щелчке
                                // левой кнопкой мыши в любой точке карты.
                                
                                myMap.events.add('click', function (e) {
                                    if (!myMap.balloon.isOpen()) {
                                        var coords = e.get('coords');
                                        document.getElementById('longitude').value =  coords[1].toPrecision(6);
                                        document.getElementById('latitude').value = coords[0].toPrecision(6);
                                        myMap.balloon.open(coords, {
                                            contentHeader:'Координаты события',
                                            contentBody:'<p>Координаты собития сохранены в текстовые поля.</p>' + [
                                                coords[0].toPrecision(6),
                                                coords[1].toPrecision(6)
                                                ].join(', ') + '</p>',
                                            contentFooter:'<sup>Щелкните еще раз</sup>'
                                        });
                                    }
                                    else {
                                        myMap.balloon.close();
                                    }
                                });
                                
                                // Скрываем хинт при открытии балуна.
                                myMap.events.add('balloonopen', function (e) {
                                    myMap.hint.close();
                                });
                            }
                            </script>

                        <p>Изображения</p>
                        <input class="btn btn-outline-success my-2 my-sm-0" multiple type="file" name="images[]" accept="image/*">
                        <br><br><br>
                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit"> Добавить </button>  
                        </form>    
                        @else
                        <p class='error'>Вы не можете добавлять события, Ваш профиль заблокирован до {{$authUser->blockDate}} </p>
                        @endif
                    @else
                    <p>Вы не авторизованы</p>
                    @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection