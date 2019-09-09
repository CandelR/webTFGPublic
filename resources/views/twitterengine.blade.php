{{--@extends('layout')--}}
@extends('layouts.app')
{{--@section('sidebar')--}}
{{--    @include('master.sidebar')--}}
{{--@show--}}
@section('content')

    <div class="container">
        <br>
        <br>

        <h1>TWITTER ENGINE</h1>

        <form class="ui input" method="post" action="{{ route('twitterEngineConf') }}">
            {{csrf_field()}}

            <div class="card-group">

                <div class="card">
                    {{--                    style="width: 25rem;"--}}
                    <div class="card-body">
                        <label for="config_engine">Configuración motor @if(isset($config_ok)) <label
                                style="color: #2d995b;">&nbsp;&nbsp;&nbsp;&nbsp; CONFIG OK</label> @endif
                            <select class="form-control" id="config_engine" name="config_engine"
                                    required
                                    style="width:16rem; margin-top: 10px;">
                                @if(isset($config_ok))
                                    @switch($task)
                                        @case('irony')
                                        <option value="irony" selected>IRONIA</option>
                                        <option value="humor">HUMOR</option>
                                        <option value="gender">GENERO</option>
                                        <option value="sentiment">SENTIMIENTO</option>
                                        @break
                                        @case('humor')
                                        <option value="irony">IRONIA</option>
                                        <option value="humor" selected>HUMOR</option>
                                        <option value="gender">GENERO</option>
                                        <option value="sentiment">SENTIMIENTO</option>
                                        @break
                                        @case('gender')
                                        <option value="irony">IRONIA</option>
                                        <option value="humor">HUMOR</option>
                                        <option value="gender" selected>GENERO</option>
                                        <option value="sentiment">SENTIMIENTO</option>
                                        @break
                                        @case('sentiment')
                                        <option value="irony">IRONIA</option>
                                        <option value="humor">HUMOR</option>
                                        <option value="gender">GENERO</option>
                                        <option value="sentiment" selected>SENTIMIENTO</option>
                                        @break
                                    @endswitch
                                @else
                                    <option value="irony">IRONIA</option>
                                    <option value="humor">HUMOR</option>
                                    <option value="gender">GENERO</option>
                                    <option value="sentiment">SENTIMIENTO</option>
                                @endif
                            </select>
                        </label>
                        <br>
                        <input type="checkbox" name="querieSelected" value="true" @if(isset($config_ok)) @else disabled
                               @endif
                               onclick="muestra_oculta('divReQuerie')"> Reenviar consulta
                        <div id="divReQuerie" style="display: none;">
                            <br>
                            <label for="config_engine">Lista consultas realizadas
                                <select class="form-control" id="queries_done" name="queries_done"
                                        style="margin-top: 10px;">
                                    @foreach($userQueries as $querie)
                                        <option
                                            value="{{$querie->CM_CD_CONSULTA_MOTOR}}">{{"Nº: ".$querie->CM_CD_CONSULTA_MOTOR ." - ".$querie->created_at." - ". $querie->CM_PALABRA_BUSQUEDA }}</option>
                                    @endforeach
                                </select>
                            </label>
                            <br>
                            <button type="submit" id="action" name="action"
                                    value="reSendTweets" class="btn btn-secondary btn-lg btn-block">Enviar consulta
                                seleccionada
                            </button>
                        </div>


                        <br>
                    </div>
                </div>
                <div class="card">
                    {{--                    style="width: 25rem;"--}}


                    <div class="card-body">
                        <div class="form-group">
                            <label for="keyword">Palabra clave</label>
                            <input id="keyword" class="form-control" type="text" name="keyword"
                                   @if(isset($config_ok)) required @else disabled @endif
                                   placeholder="Ej. Investidura">
                        </div>
                        <div class="form-group">
                            <label for="n_tweets">Nº tweets</label>
                            <input id="n_tweets" type="number" class="form-control" name="n_tweets"
                                   @if(isset($config_ok)) required @else disabled @endif
                                   placeholder="Ej. 50">
                        </div>
                    </div>
                </div>
                @if(isset($config_ok))
{{--                    <input type="hidden" name="action" value="getAndSendTweets">--}}
                    <button type="submit" id="action" name="action"
                            value="getAndSendTweets" class="btn btn-secondary btn-lg btn-block">Enviar</button>
                @else
{{--                    <input type="hidden" name="action" value="setEnv">--}}
                    <button type="submit" id="action" name="action"
                            value="setEnv" class="btn btn-secondary btn-lg btn-block">Enviar Configuración</button>
                @endif

            </div>
        </form>


        @if($contents!="")
            <div class="card" style="margin-top: 25px;">

                <div class="ui warning message">
                    <i class="close icon" id="wgClose"></i>
                    <div class="header">

                    </div>
                </div>
                <div id="app" class="card-body" style="width:100%;">
                    {!! $chart->container() !!}
                </div>
                <script src="https://unpkg.com/vue"></script>
                <script>
                    import Vue from 'vue/dist/vue.js';

                    var app = new Vue({
                        el: '#app',
                    });
                </script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/highcharts/6.0.6/highcharts.js"
                        charset="utf-8"></script>
                {!! $chart->script() !!}

            </div>
            <div class="car ui bottom attached segment" style="margin-top: 25px;">
                <table class="table table-striped table-dark">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Tweet</th>
                        <th scope="col">Resultado</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($contents as $key => $tweet)
                        <tr>
                            <th scope="row">{{$key}}</th>
                            <td>{{$tweet}}</td>
                            <td>{{$resultados_analisis[$key-1]}}</td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>

            </div>
        @endif
    </div>
    <script>
        function muestra_oculta(id) {

            if (document.getElementById) { //se obtiene el id

                var el = document.getElementById(id); //se define la variable "el" igual a nuestro div
                el.style.display = (el.style.display == 'none') ? 'inline' : 'none'; //damos un atributo display:none que oculta el div
                var inputKeyword = document.getElementById('keyword'); //se define la variable "el" igual a nuestro div
                var inputNTweets = document.getElementById('n_tweets'); //se define la variable "el" igual a nuestro div
                inputKeyword.disabled = inputKeyword.disabled !== true;
                inputNTweets.disabled = inputNTweets.disabled !== true;

            }
        }

        document.onload = function () {/*hace que se cargue la función lo que predetermina que div estará oculto hasta llamar a la función nuevamente*/
            muestra_oculta('divReQuerie');/* "contenido_a_mostrar" es el nombre que le dimos al DIV */
        }
    </script>
@endsection
