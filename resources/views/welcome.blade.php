@extends('layouts/layout')
@section('content')
<div class="container">
<h1>HelloLaravel</h1>
@foreach($images as $image)
<img src="storage/{{$image->image_name}}" height=150px>
@endforeach
</div>
@endsection