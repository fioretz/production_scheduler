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
    <nav class="navbar navbar-expand-lg fixed-top navbar-inverse" style="padding: 0">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="{{ route('dashboard.index') }}" style="color: #fff">
                    <img alt="Production Scheduler" src="{{ asset('images/logo_bianco.svg') }}" style="opacity: 0.7; height: 50px">
                </a>
            </div>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('prodotto.show') }}" style="padding: 18px 10px">Prodotti</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('macchina.show') }}" style="padding: 18px 10px">Macchine</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('tipomacchina.show') }}" style="padding: 18px 10px">Tipo Macchina</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding: 18px 10px">
                            Ordini di Produzione
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item item-menu" href="{{ route('ordineproduzione.show') }}">Ordini di Produzione</a>
                            <a class="dropdown-item item-menu" href="{{ route('macchina.show') }}">Chiudi Ordine in Produzione</a>
                            <a class="dropdown-item item-menu" href="{{ route('tipomacchina.show') }}">Ordini di Produzione chiusi</a>
                        </div>
                    </li>
                </ul>
                <ul class="navbar-nav my-2 my-lg-0">
                    <li class="nav-item text-nowrap">
                        <a class="nav-link" href="#" style="padding: 18px 10px">Sign out</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container-fluid">
        <div class="content">
            <main>
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
