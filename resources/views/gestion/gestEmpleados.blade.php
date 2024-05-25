@extends('layouts.plantilla_header')

@section('title', 'Empleados | MyControlPark')

@section('token')
    <meta name="csrf_token" content="{{ csrf_token() }}">
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/empleados.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection

@if (session('id'))
    @section('content')
        {{-- NAVBAR --}}
        <header>
            <nav>
                <ul class="nav-left">
                    <li><img src="{{ asset('img/logo.png') }}" alt="Logo"></li>
                    <li class="active">Empleados</li>
                    <li><a href="{{ 'reservas' }}">Reservas</a></li>
                    <li><a href="{{ 'mapa' }}">Mapa</a></li>
                </ul>

                <ul class="nav-right">
                    <li>{{ session('nombre') }}</li>

                    @if (session('nombre_empresa'))
                        <li>{{ session('nombre_empresa') }}</li>
                    @else
                        <li>Empresa no asignada</li>
                    @endif

                    <li><a href="{{ route('logout') }}">Cerrar sesión</a></li>
                </ul>
            </nav>
        </header>

        {{-- MENSAJE ERROR --}}
        @if (session('error'))
            <div class="alert alert-danger" style="padding-top: 10px">{{ session('error') }}</div>
        @endif

        {{-- MENSAJE ÉXITO --}}
        @if (session('success'))
            <div class="alert alert-success" style="padding-top: 10px">{{ session('success') }}</div>
        @endif

        {{-- CANTIDAD DE USUARIOS --}}
        <h3>Total de usuarios: ({{ $totalEmpleados }})</h3>

        {{-- REGISTRAR USUARIO --}}
        <button type="button" class="btn btn-primary" id="abrirModal">Añadir usuario</button>

        {{-- BOTÓN PARA VOLVER A LA PÁGINA PRINCIPAL POR DEFECTO --}}
        <button class="btn btn-danger" style="border-radius: 5px;"><a href="{{ 'gestEmpleados' }}" style="text-decoration: none; color: white;">Reset</a></button>

        {{-- FORMULARIO FILTRO Y PÁGINA --}}
        <form id="filterForm">
            <div class="form-group">
                <label for="search">Buscar:</label>
                <input type="text" name="search" id="search" value="{{ $search }}"
                    placeholder="Buscar empleado" class="form-control">
            </div>

            <div class="form-group">
                <label for="rol">Rol:</label>
                <select name="rol" id="rol" class="form-control">
                    <option value="">Todos</option>
                    @foreach ($roles as $role)
                        @if ($role->id != 1)
                            <option value="{{ $role->id }}" {{ $rol == $role->id ? 'selected' : '' }}>
                                {{ $role->nombre }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </form>

        {{-- TABLA --}}
        <div id="tabla">
            @include('tablas.tbl_empleados')
        </div>
        
        {{-- MODAL AÑADIR USUARIO --}}
        <div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="modal-register"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-register">Registrar nuevo empleado</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('empleado.store') }}" method="post" id="frmRegistro">
                            @csrf

                            <input type="hidden" name="currentUrl" id="currentUrl">

                            <div class="form-group">
                                <label for="nombre">Nombre:</label>
                                <input type="text" name="nombre" id="nombre" placeholder="Introduce el nombre"
                                    class="form-control" value="{{ isset($empleado) ? $empleado->nombre : '' }}">
                            </div>

                            <div class="form-group">
                                <label for="apellido">Apellidos:</label>
                                <input type="text" name="apellido" id="apellido" placeholder="Introduce el apellido"
                                    class="form-control" value="{{ isset($empleado) ? $empleado->apellidos : '' }}">
                            </div>

                            <button type="submit" class="btn btn-primary">Registrar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection
@else
    @php
        header('Location: ' . route('login'));
        exit();
    @endphp
@endif

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl7+Yj7/6/gqH1D00iW6c+zo5FJ3w7QaXK/z6ZC9Yg" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.10.2/umd/popper.min.js"
        integrity="sha384-B4tt8/DBP0LbRULaFO15QwEReKo0+kTPrUN6RfFzAD5SMoFfO+Xt5Jx5W2c6Xg7L" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-QJHtvGhmr9b4Is3NZoJ6wTrFjjGmkjFw8LLAPk2vRT0TctW7NO3S1Zef6j5oaJXp" crossorigin="anonymous">
    </script>
    <script src="{{ asset('js/empleados.js') }}"></script>
    <script>
        $(document).ready(function() {
            function fetchData(page) {
                $.ajax({
                    url: "?page=" + page,
                    method: "GET",
                    data: $('#filterForm').serialize(),
                    success: function(data) {
                        $('#tabla').html(data);
                    }
                });
            }

            $(document).on('submit', '#filterForm', function(e) {
                e.preventDefault();
                fetchData(1);
            });

            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                fetchData(page);
            });
        });
    </script>
@endpush
