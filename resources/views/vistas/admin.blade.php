@if (session('id') && session('nombre') && session('apellidos') && session('email') && session('rol'))

@extends('layouts.plantilla_header') 

@section('title', 'Admin | MyControlPark') 

@section('css') <link rel="stylesheet" href="{{ asset('css/admin.css') }}">@endsection

@section('content')

<h1>Página de admin</h1>

@endsection

@else
    @php
        session()->flash('error', 'Debes iniciar sesión para acceder a esta página');
    @endphp

    <script>
        window.location = "{{ route('login') }}"; <!-- Redireccionar a la página de inicio de sesión -->
    </script>
@endif

