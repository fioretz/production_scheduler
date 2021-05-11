<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Schedulatore Produzione - @yield('title')</title>

        <script src="{{ asset('js/app.js') }}"></script>
        <script src="{{ asset('js/scripts.js') }}"></script>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">
        <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">

    </head>
    <body>
        <div class="container-fluid">
            <div class="content">
                <main>
                    @yield('content')
                </main>
            </div>
        </div>
    </body>
</html>
