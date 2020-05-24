<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <link href="{{ asset('css/default.css') }}" rel="stylesheet">

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" >
</head>

<body>
    @include('layouts/header')
    
         
    <div class="jumbotron">
      <div class="container">
        @yield('content')
      </div>
    </div>

    <div class="container">
      <div class="row">
        <div class="col-md-4">
            @include('layouts/footer')
        </div>
      </div>
</body>

</html>