{{--@extends('layout')--}}
@extends('layouts.app')

@section('content')


    <div class="container">

        <br>
        <br>
        <h1>Estadísticas</h1>
        <form class="ui input" method="post" action="{{ route('statisticsDone') }}">
            {{csrf_field()}}
            <div class="card">
                <div class="card-body">
                    <label for="config_engine">Lista consultas realizadas
                        <select class="form-control" id="queries_done" name="queries_done" required
                                style=" margin-top: 10px;">
                            @foreach($userQueries as $querie)
                                <option
                                    value="{{$querie->CM_CD_CONSULTA_MOTOR}}">{{"Nº: ".$querie->CM_CD_CONSULTA_MOTOR ." - ".$querie->created_at." - ". $querie->CM_PALABRA_BUSQUEDA }}</option>
                            @endforeach
                        </select>
                    </label>
                    <br>
                    <input type="checkbox" name="secondQuerie" value="true" onclick="muestra_oculta('divSecondQuerie')">
                    Añadir
                    segunda consulta
                    <br>
                    <div id="divSecondQuerie" style="display: none;">
                        <br>
                        <label for="config_engine">Elegir segunda consulta:
                            <select class="form-control" id="queries_done2" name="queries_done2" required
                                    style=" margin-top: 10px;">
                                @foreach($userQueries as $querie)
                                    <option
                                        value="{{$querie->CM_CD_CONSULTA_MOTOR}}">{{"Nº: ".$querie->CM_CD_CONSULTA_MOTOR ." - ".$querie->created_at." - ". $querie->CM_PALABRA_BUSQUEDA }}</option>
                                @endforeach
                            </select>
                        </label>
                    </div>
                    <br>
                    <button type="submit" class="btn btn-secondary " style="margin-top: 10px;">Ver estadísticas</button>
                </div>
            </div>
        </form>
        @if($chartsExec)
            @if($noResults)
                <div class="card" style="margin-top: 25px;">
                    <div class="ui warning message" style="padding-left: 5%;">
                        <i class="close icon" id="wgClose"></i>
                        No existen resultados para esta consulta

                    </div>
                </div>
            @else
                <div class="card" style="margin-top: 25px;">
                    <div class="ui warning message">
                        <i class="close icon" id="wgClose"></i>
                        <div class="header">

                        </div>
                    </div>
                    <div id="app" class="card-body" style="width:100%;">
                        <h3 style="margin-left: 5%;">Consulta {{$nConsulta}} @if($nConsulta2) &
                            Consulta {{$nConsulta2}} @endif </h3>
                        <br>
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

                    <div class="card-footer" style="padding-left: 15px;">

                        <div style="float:left; margin-right: 20px">
                            <label><strong> <u>Consulta</u> </strong> {{$nConsulta}}</label>
                            <br>
                            <label><strong>Palabra clave:</strong> {{$keyword}}</label>
                            <br>
                            <label><strong>Nº tweets: </strong>{{$ntweets}}</label>
                        </div>
                        @if($nConsulta2)
                            @if($noResults2)
                                <label><strong> <u>Consulta</u> </strong> {{$nConsulta2}}</label>
                                <div class="card">
                                    <div class="ui warning message" style="padding-left: 5%;">
                                        <i class="close icon" id="wgClose"></i>
                                        No existen resultados para esta consulta

                                    </div>
                                </div>
                            @else
                                <div style="float:right;">
                                    <label><strong> <u>Consulta</u> </strong> {{$nConsulta2}}</label>
                                    <br>
                                    <label><strong>Palabra clave:</strong> {{$keyword2}}</label>
                                    <br>
                                    <label><strong>Nº tweets: </strong>{{$ntweets2}}</label>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                <div class="overflow-auto" style="margin-top: 25px; max-height: 300px;">
                    <div class="car ui bottom attached segment">
                        <ul class="nav nav-tabs" role="tablist">

                            <li class="nav-item">
                                <a class="nav-link active" id="consulta1-tab" data-toggle="tab" href="#consulta1"
                                   role="tab"
                                   aria-selected="true">Consulta {{$nConsulta}}</a>
                            </li>
                            @if($nConsulta2)
                                @if(!$noResults2)
                                    <li class="nav-item">
                                        <a class="nav-link" id="consulta2-tab" data-toggle="tab" href="#consulta2"
                                           role="tab"
                                           aria-selected="false">Consulta {{$nConsulta2}}</a>
                                    </li>
                                @endif
                            @endif
                        </ul>
                        <div class="tab-content" id="myTabContent">

                            <div class="tab-pane fade show active" id="consulta1" role="tabpanel"
                                 aria-labelledby="home-tab">

                                <table class="table table-striped table-dark">
                                    <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Tweet</th>
                                        <th scope="col">Resultado</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($tweets as $key => $tweet)
                                        <tr>
                                            <th scope="row">{{$key}}</th>
                                            <td>{{$tweet}}</td>
                                            <td>{{$resultados_analisis[$key-1]}}</td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>

                            </div>
                            @if($nConsulta2)
                                @if(!$noResults2)
                                    <div class="tab-pane fade show active" id="consulta2" role="tabpanel"
                                         aria-labelledby="home-tab">
                                        <table class="table table-striped table-dark">
                                            <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Tweet</th>
                                                <th scope="col">Resultado</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($tweets2 as $key => $tweet)
                                                <tr>
                                                    <th scope="row">{{$key}}</th>
                                                    <td>{{$tweet}}</td>
                                                    <td>{{$resultados_analisis2[$key-1]}}</td>
                                                </tr>
                                            @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            @endif
                        </div>


                    </div>
                </div>
            @endif
        @endif
    </div>
    <script>
        function muestra_oculta(id) {

            if (document.getElementById) { //se obtiene el id

                var el = document.getElementById(id); //se define la variable "el" igual a nuestro div
                el.style.display = (el.style.display == 'none') ? 'inline' : 'none'; //damos un atributo display:none que oculta el div
            }
        }

        document.onload = function () {/*hace que se cargue la función lo que predetermina que div estará oculto hasta llamar a la función nuevamente*/
            muestra_oculta('divSecondQuerie');/* "contenido_a_mostrar" es el nombre que le dimos al DIV */
        }
    </script>
@endsection
