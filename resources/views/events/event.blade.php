@extends('layouts/layout')
@section('title')
{{$event->nameEvent}}
@endsection
@section('content')
<p> Название события: {{$event->nameEvent}} </p>
<p> Описание события: {{$event->eventDescription}} </p>
<p> Долгота: {{$event->longitude}} </p>
<p> Широта: {{$event->latitude}} </p>
@endsection