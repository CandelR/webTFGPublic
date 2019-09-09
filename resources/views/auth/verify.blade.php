@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Verify Your Email Address') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('Se ha enviado un link de verificación a tu email') }}
                        </div>
                    @endif

                    {{ __('Por favor, comprueba el link de verificación en la bandeja de entrada de tu email.') }}
                    {{ __('Si no has recibido el email') }}, <a href="{{ route('verification.resend') }}">{{ __('aprieta aqui para recibir otro.') }}</a>.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
