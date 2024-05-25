@if (session('id'))
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

    @section('content')
        <header>
            <nav>
                <ul class="nav-left">
                    <li><img src="{{ asset('img/logo.png') }}" alt="Logo"></li>
                </ul>
                <ul class="nav-right">
                    <!-- Mostrar el nombre del usuario -->
                    <li>{{ session('nombre') }}</li>
                    <!-- Mostrar el nombre de la empresa, si está disponible -->
                    @if (session('nombre_empresa'))
                        <li>{{ session('nombre_empresa') }}</li>
                    @else
                        <li>Empresa no asignada</li> <!-- Mensaje alternativo si no hay empresa -->
                    @endif
                    <!-- Enlace para cerrar sesión -->
                    <li><a href="{{ route('logout') }}">Cerrar sesión</a></li>
                </ul>
            </nav>
        </header>

        <button id="menu" class="btnregister">Registrar <i class="fas fa-user-circle"></i></button>
        <div class="col-lg-12 ml-auto" style="border:1px solid">
            <form action="" method="post" id="frmbusqueda">
                <div class="form-group">
                    <label for="nombre">nombre:</label>
                    <input type="text" name="nombre" id="nombre" placeholder="Buscar...">
                </div>
            </form>
        </div>

        <div id="rolfiltros">
            <form action="" method="post" id="frmfiltroRol">
                <label for="rol">Rol:</label><br>
                <select name="filtroRol" id="filtroRol">
                </select>
            </form>
            <div>
                <button onclick="selectmuldel()">Eliminar seleccion</button>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Empresa</th>
                    <th>seleccionar</th>
                    <th>acciones</th>
                </tr>
            </thead>
            <tbody id="resultado"></tbody>
        </table>

        <div class="sub-menu1-container" id="submenu" style="display: none;">
            <form action="" method="post" id="formnewuser">
                <p>Nombre</p>
                <input type="text" name="nombreuser" id="nombreuser">
                <p>Apellido</p>
                <input type="text" name="apellido" id="apellido">
                <p>Email</p>
                <input type="text" name="email" id="email">
                <p>pwd</p>
                <input type="password" name="pwd" id="pwd">
                <p>Rol</p>
                <p><select name="SelecRoles" id="SelecRoles">
                    </select></p>
                <p>empresa</p>
                <p><select name="SelecEmpresa" id="SelecEmpresa">
                    </select></p>
                <p><input type="button" value="registrar" id="registrar"></p>
            </form>
        </div>

    @endsection

    @push('scripts')
        <script src="{{ asset('/js/admin.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    @endpush
@else
    {{-- Establecer el mensaje de error --}}
    @php
        session()->flash('error', 'Debes iniciar sesión para acceder a esta página');
    @endphp

    {{-- Redireccionar al usuario a la página de inicio de sesión --}}
    <script>
        window.location = "{{ route('login') }}";
    </script>

    @csrf
    <script>
        var csrfToken = "{{ csrf_token() }}";
    </script>
@endif
