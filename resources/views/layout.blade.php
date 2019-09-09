<!DOCTYPE html>

<html>
<head>
    <title>@yield('title','TFG')</title>
    <style>
        .dropdown-menu .sub-menu {
            left: 100%;
            position: absolute;
            top: 0;
            visibility: hidden;
            margin-top: -1px;
        }

        .dropdown-menu li:hover .sub-menu {
            visibility: visible;
        }

        .dropdown:hover .dropdown-menu {
            display: block;
        }
    </style>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="css/public.css">
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="{{route('home')}}"><img class="css-class" alt="alt text"
                                                          src="/resources/img/marca_UPV_principal_negro150.png"/></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">

            <li class="nav-item">
                <a class="nav-link" href="{{route('home')}}">Home</a>
            </li>
            @if ( auth()->check())
                <li class="nav-item">
                    <a class="nav-link" href="{{route('summary')}}">Resumen</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{route('twitterEngine')}}">TwitterEngine</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{route('logout')}}">Cerrar sesión de {{auth()->user()->name}}</a>
                </li>
            @endif
            @if (auth()->guest())
                <li class="nav-item">
                    <a class="nav-link" href="/">Login</a>
                </li>
            @endif

            {{--            <li class="nav-item active">--}}
            {{--                <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>--}}
            {{--            </li>--}}
            {{--            <li class="nav-item">--}}
            {{--                <a class="nav-link" href="#">Link</a>--}}
            {{--            </li>--}}
            {{--            <li class="nav-item dropdown">--}}
            {{--                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
            {{--                    Dropdown--}}
            {{--                </a>--}}
            {{--                <div class="dropdown-menu" aria-labelledby="navbarDropdown">--}}
            {{--                    <a class="dropdown-item" href="#">Action</a>--}}
            {{--                    <a class="dropdown-item" href="#">Another action</a>--}}
            {{--                    <div class="dropdown-divider"></div>--}}
            {{--                    <a class="dropdown-item" href="#">Something else here</a>--}}
            {{--                </div>--}}
            {{--            </li>--}}
            {{--            <li class="nav-item">--}}
            {{--                <a class="nav-link disabled" href="#">Disabled</a>--}}
            {{--            </li>--}}
        </ul>
        {{--        <form class="form-inline my-2 my-lg-0">--}}
        {{--            <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">--}}
        {{--            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>--}}
        {{--        </form>--}}
    </div>
</nav>

@yield('content')
</body>
</html>
