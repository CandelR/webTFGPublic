{{--@extends('layout')--}}
@extends('layouts.app')

@section('content')


    <div class="container">

        <br>
        <br>
        <h1>Resumen</h1>

        <div class="overflow-auto" style="max-height: 600px;">
            @if($userQueries!=null)
                <table class="table table-striped table-dark">
                    <thead>
                    <tr>
                        <th scope="col"># CONSULTA</th>
                        <th scope="col">CONFIGURACIÓN MOTOR</th>
                        <th scope="col">PALABRA BUSQUEDA</th>
                        <th scope="col">Nº TWEETS CONSULTA</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($userQueries as $querie)
                        <tr>
                            <th scope="row">{{$querie->CM_CD_CONSULTA_MOTOR}}</th>
                            <td>{{($engineTypes->where('TCM_CD_MOTOR',$querie->TCM_CD_MOTOR)->first())->TCM_VALOR}}</td>
                            <td>{{$querie->CM_PALABRA_BUSQUEDA}}</td>
                            <td>{{$querie->CM_NUMERO_TWEETS}}</td>
                        </tr>

                    @endforeach
                    </tbody>
                </table>

            @endif
        </div>
    </div>

@endsection
