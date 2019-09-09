@extends('layout')

@section('title')
    Login
@stop
@section('content')




    <section id="offer-form">
        <div class="container clearfix">
            <br>
            <br>
            <h1 >LOGIN</h1>
            <div class="col-md-1">
            </div>
            <div class="col-md-6">
                <div id="offer-form-div">
                    <form method="POST" action="/login">
                        {{ csrf_field() }}

                        <div id="formDiv">

                            <div class="form-group">
                                <label for="email">Email</label>
                                <input id="email"  class="form-control" type="email" name="email" placeholder="Email">
                            </div>
                            <div class="form-group">
                                <label for="password">Contraseña</label>
                                <input id="password"  type="password" class="form-control" name="password" placeholder="Password" >
                                <a href="/register" >Registrate</a>
                            </div>


                            <input type="submit" class="btn-ppal" value="Entrar">
                        </div>
                        <br>
                        <br>
{{--                        <div class="form-group">--}}
{{--                            <label for="exampleInputEmail1">Email address</label>--}}
{{--                            <input type="email" class="form-control" id="exampleInputEmail1"--}}
{{--                                   aria-describedby="emailHelp" placeholder="Enter email">--}}
{{--                            <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone--}}
{{--                                else.--}}
{{--                            </small>--}}
{{--                        </div>--}}
{{--                        <div class="form-group">--}}
{{--                            <label for="exampleInputPassword1">Password</label>--}}
{{--                            <input type="password" class="form-control" id="exampleInputPassword1"--}}
{{--                                   placeholder="Password">--}}
{{--                        </div>--}}
{{--                        <div class="form-check">--}}
{{--                            <input type="checkbox" class="form-check-input" id="exampleCheck1">--}}
{{--                            <label class="form-check-label" for="exampleCheck1">Check me out</label>--}}
{{--                        </div>--}}
{{--                        <button type="submit" class="btn btn-primary">Submit</button>--}}

                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection


