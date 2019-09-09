@extends('layouts.app')


@section('content')

    <div class="container">

        <br>
        <br>
        <h1>Home</h1>

        Bienvenido a TwitterEngine! En esta plataforma podrás utilizar el motor de análisis y clasificación del lenguaje
        de les Escuela Técnica Superior de Ingeniería informática de la UPV. A continuación un resumen de lo que
        encontrarás en cada menú.
        <br>
        <br>
        <ol>
            <li><strong>Resumen:</strong> Tabla resumen de las consultas ya realizadas al motor de clasificación por el usuario.</li>
            <li><strong>TwitterEngine:</strong> Herramienta para utilizar el motor de clasificación del lenguaje. </li>
            <li><strong>Estadísticas:</strong> Herramienta para volver a ver los resultados de las consultas realizadas. </li>
        </ol>
    </div>


@endsection
