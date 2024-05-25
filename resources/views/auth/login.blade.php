<!-- resources/views/auth/login.blade.php -->
@extends('layouts.plantilla_header') <!-- Extiende la plantilla base -->

@section('title', 'Login | MyControlPark') <!-- Título personalizado -->
@section('css') <!-- CSS personalizado -->
    <link rel="stylesheet" href="{{ asset('css/login-register.css') }}">
@endsection

@section('content')
    <header>
        <nav>
            <ul class="nav-left">
                <li><img src="{{ asset('img/logo.png') }}" alt="Logo"></li>
            </ul>

            <ul class="nav-right">
                <li><a href="{{ route('inicio') }}">Volver</a></li>
            </ul>
        </nav>
    </header>

    <div class="row">
        <div id="cont-form">
            <form class="login-form" method="POST" action="{{ route('login.post') }}">
                @csrf

                <h1 class="mb-4 text-center">Inicio de sesión</h1>

                <!-- Manejo de errores y éxito -->
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif


                <!-- Campo de correo electrónico -->
                <div class="form-group">
                    <div class="form-field">
                        <label for="email" class="form-label">Correo electrónico:</label>
                        <input type="email" name="email" id="email"
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder="Ingresa tu correo electrónico" value="{{ old('email') }}">
                    </div>
                </div>

                <!-- Campo de contraseña -->
                <div class="form-group">
                    <div class="form-field">
                        <label for="password" class="form-label">Contraseña:</label>
                        <div class="input-group">
                            <input type="password" name="password" id="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Ingresa tu contraseña" value="{{ old('password') }}">
                            <button type="button" id="password-toggle-btn" class="btn btn-outline-secondary"
                                onclick="togglePasswordVisibility()">
                                <i class="far fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <!-- Botón de enviar -->
                    <button id="btn-enviar">Iniciar sesión</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script para alternar la visibilidad de la contraseña -->
    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById("password");
            const passwordToggleBtn = document.getElementById("password-toggle-btn");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                passwordToggleBtn.innerHTML = '<i class="far fa-eye-slash"></i>'; // Cambiar ícono a ojo tachado
            } else {
                passwordInput.type = "password";
                passwordToggleBtn.innerHTML = '<i class="far fa-eye"></i>'; // Cambiar ícono a ojo abierto
            }
        }
    </script>
@endsection
