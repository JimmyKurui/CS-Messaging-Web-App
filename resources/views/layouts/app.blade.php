<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- <title>@yield('title', 'Home')</title> -->
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script> 
</head>

<body class="antialiased">
    <!-- <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand p-4" href="#">Customer Support</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-md-5">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Features</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Pricing</a>
                </li>
            </ul>
        </div>
        @if(Cookie::has('agent_id'))  @include('agents.notification') @endif 
        <div class="ml-auto bg-info">
        @if (!request()->is('/'))
            @if(isset($agent)) {{ $agent->id }} 
            @elseif(isset($user)) {{ $user->id }} 
            @endif
        @endif
        </div>
    </nav> -->

    <main> 
        <!-- @yield('content') -->
        <div id="app">
            <app></app>
        </div>
    </main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.1/popper.min.js" integrity="sha512-eHo1pysFqNmmGhQ8DnYZfBVDlgFSbv3rxS0b/5+Eyvgem/xk0068cceD8GTlJOZsUrtjANIrFhhlwmsL1K3PKg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>    
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="{{ mix('js/app.js') }}"></script> 
    </body>
</html>