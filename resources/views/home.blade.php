<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
    <div id="app">
        <div class="container-fluid">
            <header class="row bg-white p-1 mb-3 position-fixed top-bar" style="top: 0;left:0;right:0;">
                <div class="col-xl-3 col-md-2 d-flex left-side">
                    <img src="{{asset('images/logo.png')}}" class="img-fluid">
                    <div class="w-100 search-container">
                        <div class="search-icon"><i class="fas fa-search"></i></div>
                        <input type="search" placeholder="Search Facebook" class="form-control">
                    </div>
                </div>
                <nav class="col-xl-6 col-md-6 my-auto middle">
                    <div class="row justify-content-between">
                        <router-link class="flex-grow-1 text-center" to="/" exact><i class="fas fa-home"></i>
                        </router-link>
                        <router-link class="flex-grow-1 text-center" to="/x"><i class="far fa-play-circle"></i>
                        </router-link>
                        <router-link class="flex-grow-1 text-center" to="/y"><i class="fas fa-store-alt"></i>
                        </router-link>
                        <router-link class="flex-grow-1 text-center" to="/z"><i class="fas fa-users"></i>
                        </router-link>
                        <router-link class="flex-grow-1 text-center" to="/m"><i class="fas fa-gamepad"></i>
                        </router-link>
                    </div>
                </nav>
                <div class="col-xl-3 col-md-4 my-auto right-side">
                    <div class="d-flex justify-content-end">
                        <div class="profile">
                            <img src="{{auth()->user()->image_url}}" class="img-fluid rounded-circle">
                            {{strtok(auth()->user()->name,' ')}}
                        </div>
                        <div class="icon"><i class="fas fa-plus"></i></div>
                        <div class="icon"><i class="fab fa-facebook-messenger"></i></div>
                        <div class="icon"><i class="fas fa-bell"></i></div>
                        <div class="icon"><i class="fas fa-sort-down"></i></div>
                    </div>
                </div>
            </header>
            <div style="position: relative;top:100px;">
                <form action="{{route('logout')}}" method="post">
                    @csrf
                    <button class="btn btn-primary">Logout</button>
                </form>
                <router-view></router-view>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>