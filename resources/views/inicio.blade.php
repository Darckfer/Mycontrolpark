@extends('layouts.plantilla_header')

@section('title', 'Inicio | MyControlPark')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/inicio.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    {{-- FUENTE --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
@endsection

@section('content')

    <div id="cont-general">
        <div class="container">
            <div class="row">
                <div class="col">
                    <a href="/login" class="btn btn-primary btn-lg btn-block">Trabajador</a>
                </div>
                <div class="col">
                    <a href="/reserva" class="btn btn-secondary btn-lg btn-block">Cliente</a>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@endpush
