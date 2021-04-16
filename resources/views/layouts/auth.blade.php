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

    <style>
        .recent-login:hover {
            box-shadow: 0 0.5rem 1rem rgb(0 0 0 / 15%) !important;
            cursor: pointer;
        }

        .recent-login:hover .circle{
            opacity: 1 !important;
            background: white !important;
            width: 25px !important;
            height: 25px !important;
            top: -4px !important;
            left: -4px !important;
        }
        .recent-login:hover .icon{
            top: -2px !important;
            left: 3px !important;
        }
        .recent-login:hover .icon i{
            color:rgba(0, 0, 0, 0.4) !important;
            font-size: 1rem !important;
        }
    </style>
</head>

<body>
    <div id="app">
        <div class="container-fluid p-4">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div style="padding-top:4rem;zoom:1.1">
                        <div class="row justify-content-between">
                            <div class="col-md-7">
                                <img src="https://static.xx.fbcdn.net/rsrc.php/y8/r/dF5SId3UHWd.svg"
                                    style="height: 70px;margin-left:-20px;" alt="">
                                <h3>Recent logins</h3>
                                <p>Click your picture or add an account.</p>
                                <div class="row recent-container no-gutters">
                                    @foreach($recent_logins as $recent)
                                    <div class="col-md-4">
                                        <form method="POST" action="" class="bg-white rounded recent-login mx-1" data-id="{{$recent['id']}}">
                                            <input type="hidden" name="token" value="{{$recent['token']}}">
                                            <div class="delete-account">
                                                <div class="circle"
                                                    style="position: absolute;top:1px;left:1px;width:15px;height:15px;border-radius:50%;background:black;opacity:0.6;text-align:center;z-index:2;">
                                                </div>
                                                <div data-toggle="tooltip" data-placement="top"
                                                    title="Remove Account From Recent Logins"
                                                    class="icon"
                                                    style="position: absolute;top:-4px;left:4px;z-index:4">
                                                    <i class="fas fa-times" style="color:white;font-size:11px;"></i>
                                                </div>
                                            </div>
                                            <img src="{{$recent['image_url']}}" class="img-fluid rounded-top">
                                            <p class="mb-0 p-2 text-center">{{$recent['name']}}</p>
                                        </form>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-5">
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    
    <script>
        $(".delete-account").on('click',function(){
            let recent_id_clicked = $(event.target).parents('.recent-login').data('id');
            axios.post(`/api/recent-logins-cookie/${recent_id_clicked}/delete`,{withCredentials: true}).then(function(response){
                if(response.data.success){
                    location.reload();
                }
            })
        })
    </script>

    @yield('js')

</body>

</html>