@extends('layouts/layout')
@section('title')
{{$event->eventName}}
@endsection
@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{$event->eventName}}</div>                      
                    <div class="card-body">
                        <div class="col-md-12">
                        <p class="error">{{session("message")}}</p> 
                            <p class="error"> {{session('error')}} </p>
                            <p> Пользователь: {{$event->email}} </p>
                            <p> Дата создания: {{$event->event_date}} </p>
                            <p> Дата последнего обновления: {{$event->dateChange}} </p>
                            @if ($authUser <> false 
                                AND (($event->user_id == $authUser -> user_id) AND ($event->status_id == 1)
                                OR ($authUser->levelRights > 1) AND (($authUser->levelRights > $user->levelRights) OR ($authUser->user_id == $user->user_id))))
                                    
                                    @if (count($event->eventImages) > 0)
                                        <form action="/deleteEventImages" method="POST">
                                        @csrf                 
                                        <input type="hidden" name="event_id" value="{{$event->id}}">
                                        @foreach($event->eventImages as $eventImage)
                                            <img src="{{asset('storage')}}/{{$eventImage->image_name}}" height=150px> 
                                            @if ($authUser->levelRights >= $eventImage->user_levelRights)
                                                <input type="checkbox" name="event_images_id[]" value="{{$eventImage->event_image_id}}">
                                            @endif
                                        @endforeach
                                        <br>
                                        <input class="btn btn-outline-success my-2 my-sm-0" type="submit" value="Удалить  изображения">
                                        </form>
                                    @endif                

                                <form enctype="multipart/form-data" action="/updateEvent" method="POST">
                                @csrf
                                <input  type="hidden" name="event_id" value="{{$event->id}}">    
                                <p> Название события: <input class="form-control" type="text" size=50 name="eventName" value="{{$event->eventName}}" required> </p>
                                <p>Описание события:</p>
                                <textarea required class="form-control" name="eventDescription" cols="50" rows="10">{{$event->eventDescription}}</textarea> <br>   
                                <p> Долгота: <input required class="form-control" size=10 type="number" step="any" name="longitude" value="{{$event->longitude}}"> </p>
                                <p> Широта: <input required class="form-control" size=10 type="number" step="any" name="latitude" value="{{$event->latitude}}"> </p>       
                                <p>Категория события: <select class="form-control" name = "category_id">
                                    @foreach($categories as $category)
                                        <option @if($category->id ==  $event->category_id) selected @endif value="{{ $category->id }}">{{ $category->categoryName }}</option>
                                    @endforeach
                                    </select></p>
                                    <p>Изображения</p>
                                    <input class="btn btn-outline-success my-2 my-sm-0" multiple type="file" name="images[]" accept="image/*">                                    
                                <button class="btn btn-outline-success my-2 my-sm-0" type="submit"> Обновить информацию о событии </button>
                                </form>
                                <br> 
                                @if ($authUser->levelRights > 1)
                                    <form action="/updateEventStatus" method="POST">
                                    @csrf
                                    <input type="hidden" name="event_id" value="{{$event->id}}">
                                    <input type="hidden" name="user_id" value="{{$event->user_id}}">
                                    <p>Статус события: <select class="form-control" name = "status_id">
                                    @foreach($statuses as $status)
                                        <option @if($status->id ==  $event->status_id) selected @endif value="{{ $status->id }}">{{ $status->statusName }}</option>
                                    @endforeach
                                    </select></p>
                                    <button class="btn btn-outline-success my-2 my-sm-0" type="submin">Обновить статус события</button> 
                                    </form>
                                @else
                                    <p>Статус события: {{$event->statusName}}</p>  
                                @endif
                            @else    
                                <p> Название события: {{$event->eventName}} </p>
                                <p> Статус события: {{$event->statusName}} </p>
                                <p> Описание события: {{$event->eventDescription}} </p>
                                    @foreach($event->eventImages as $image)   
                                        <img src="{{asset('storage')}}/{{$image->image_name}}" height=150px>
                                    @endforeach  
                                <p> Долгота: {{$event->longitude}} </p>
                                <p> Широта: {{$event->latitude}} </p>    
                            @endif

                            <script src="//api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
                            <script type="text/javascript">
                            ymaps.ready(init);

                            function init() {
                            var myMap = new ymaps.Map('map_pl', {
                                        center: [{{$event->latitude}}, {{$event->longitude}}],
                                        zoom: 11,
                                        controls: ['zoomControl', 'typeSelector', 'trafficControl']
                                    }, {
                                        searchControlProvider: 'yandex#search'
                                    })  

                                    myMap.geoObjects.add(new ymaps.Placemark([{{$event->latitude}}, {{$event->longitude}}], {
                                        balloonContent: '<strong>{{$event->eventName}}</strong><br/>{{$event->eventDescription}}',
                                    }, {
                                    preset: 'islands#redDotIconWithCaption'
                                    }));
                            }
                            </script>
                            <div class="row justify-content-center" style="text-align: center">
                                <div id="map_pl" style="width:640px; height:400px"></div>
                            </div>

                                @if($authUser <> false)
                                    <form enctype="multipart/form-data" action="{{ url('/addComment') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="event_id" value="{{$event->id}}">
                                    <p>Текст комментария:</p>
                                    <textarea class="form-control" name="comment" cols="50" rows="10"></textarea>
                                    <input class="btn btn-outline-success my-2 my-sm-0"class="btn btn-outline-success my-2 my-sm-0" multiple type="file" name="images[]" accept="image/*">
                                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit"> Отправить </button>
                                    </form>
                                @endif
                                @foreach ($comments as $comment)
                                    <p> <a href="/users/user/{{$comment->user_id}}">{{$comment->email}}</a> {{$comment->dateTime}}</p>
                                    <p>{{$comment->text}}</p>
                                    @if (($authUser<>false) 
                                        AND (($authUser->levelRights > $comment->user_levelRights)
                                        OR ($authUser->user_id == $comment->user_id)))
                                        @if (count($comment->commentImages) > 0)
                                            <form action="/deleteCommentImages" method="POST">
                                            @csrf
                                            @foreach ($comment->commentImages as $commentImage)             
                                                <input type="hidden" name="comment_id" value="{{$comment->id}}">
                                                <img src="{{asset('storage')}}/{{$commentImage->image_name}}" height=60px> 
                                                <input type="checkbox" name="comment_images_id[]" value="{{$commentImage->comment_image_id}}">                                     
                                            @endforeach
                                            <br><br>
                                            <input class="btn btn-outline-success my-2 my-sm-0" type="submit" value="Удалить  изображения">
                                            </form> 
                                        @endif 
                                        <form action="/deleteComment" method="POST">
                                        @csrf
                                        <input type='hidden' name='comment_id' value={{$comment->id}}>
                                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Удалить</button>
                                        </form>
                                    @else
                                        @foreach ($comment->commentImages as $commentImage)
                                            <img src="{{asset('storage')}}/{{$commentImage->image_name}}" height=60px>       
                                        @endforeach
                                    @endif
                                @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection