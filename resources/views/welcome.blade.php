<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="Aleix Leon">

        <title>Artesapp by Aleon</title>

        <!-- favicon -->
        <link rel="icon" href="{{asset('img/TOMATO.png')}}" type="image/gif" sizes="16x16">

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- icones awesome v5 -->
        <link rel="stylesheet" href="{{asset('fontawesome-free/css/all.min.css')}}">

        <!--estils dins vue app.css-->
        <link rel="stylesheet" href="{{asset('css/app.css')}}">
        
    </head>
    <body>
        
        <!--crida vue app.js-->
        <div id="app"></div>
        <script src="{{asset('js/app.js')}}"></script>
    
    </body>
</html>
