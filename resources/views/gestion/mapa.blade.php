@extends('layouts.plantilla_header')

@section('title', 'Mapa | MyControlPark')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/mapa_gestor.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection

@section('content')
    <header>
        <nav>
            <ul class="nav-left">
                <li><img src="{{ asset('img/logo.png') }}" alt="Logo"></li>
                <li><a href="{{ route('gestEmpleados') }}">Empleados</a></li>
                <li><a href="{{ 'reservas' }}">Reservas</a></li>
                <li class="active">Mapa</li>
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

    <div id="cont-principal">
        <div id="cont-crud" class="collapsed">
            <div class="menu-toggle" id="menuToggle">
                <span class="hamburger"></span>
                <span class="hamburger"></span>
                <span class="hamburger"></span>
            </div>

            <div class="menu-content">
                <h1>Listado de los Parkings: ({{ count($parkings) }})</h1>

                <!-- Manejo de errores y éxito -->
                @if (session('error'))
                    <div class="alert alert-danger" style="padding-top: 10px">{{ session('error') }}</div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success" style="padding-top: 10px">{{ session('success') }}</div>
                @endif

                <!-- Lista de parkings -->
                <div id="lista-parkings">
                    @foreach ($parkings as $parking)
                        <div class="parking-item" id="parking-{{ $parking->id }}">
                            <h3>{{ $parking->nombre }}</h3>
                            <p>Empresa: {{ $parking->empresa->nombre }}</p>
                            <p>{{ $parking->direccion }}</p>
                            {{-- <p>Latitud: {{ $parking->latitud }}</p>
                            <p>Longitud: {{ $parking->longitud }}</p> --}}
                            <div>
                                <button class="btn btn-primary" onclick="verParking({{ $parking->id }})">Ver</button>
                                <button class="btn btn-warning" onclick="editarParking({{ $parking->id }})"
                                    data-bs-toggle="modal" data-bs-target="#modal-editar">Editar</button>

                                <form action="{{ route('parking.destroy', ['id' => $parking->id]) }}" method="POST"
                                    onsubmit="return confirmDeletion()">
                                    @csrf
                                    @method('DELETE')
                                    <input type="submit" class="btn btn-danger" value="Eliminar">
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div id="cont-mapa">
            <!-- Mapa con marcadores -->
            <div id="map" style="flex: 1; height: 100%;"></div>
        </div>
    </div>

    <!-- Modal para añadir parking -->
    <div class="modal fade" id="modal-crear" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal">Añadir Parking</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-crear-parking" action="{{ route('parking.post') }}" method="POST">
                        @csrf
                        <div>
                            <label for="nombre">Nombre:</label>
                            <input type="text" name="nombre" id="nombre" placeholder="Nombre...">
                        </div>

                        <!-- Campos ocultos para enviar latitud y longitud -->
                        <input type="hidden" name="latitud" id="latitud">
                        <input type="hidden" name="longitud" id="longitud">

                        <div>
                            <label for="direccion">Dirección:</label>
                            <input type="text" name="direccion" id="direccion" placeholder="Dirección..." readonly>
                        </div>

                        <div>
                            <label for="empresa">Empresa:</label>
                            @php
                                $empresa_id_sesion = session('empresa'); // Obtener el ID de la empresa desde la sesión
                            @endphp

                            @foreach ($empresas as $empresa)
                                @if ($empresa->id == $empresa_id_sesion)
                                    <!-- Compara el ID de la empresa con el de la sesión -->
                                    <input type="text" value="{{ $empresa->nombre }}" readonly>
                                    <!-- Marca el input como de solo lectura -->
                                    <input type="hidden" name="empresa_id" value="{{ $empresa->id }}">
                                    <!-- Oculta el input del ID de la empresa -->
                                @endif
                            @endforeach
                        </div>

                        <div>
                            <input type="submit" name="btn-enviar" id="btn-enviar" value="Añadir">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para editar parking -->
    <div class="modal fade" id="modal-editar" tabindex="-1" role="dialog" aria-labelledby="modal-editar"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Parking</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formulario-editar-parking" action="" method="POST">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="id" id="editar-id">

                        <div>
                            <label for="nombre">Nombre:</label>
                            <input type="text" name="nombre" id="editar-nombre">
                        </div>

                        {{-- <div>
                            <label for="direccion">Dirección:</label>
                            <input type="text" name="direccion" id="editar-direccion" readonly>
                        </div> --}}

                        <div>
                            <label for="empresa">Empresa:</label>
                            <!-- Campo no editable que muestra el nombre de la empresa -->
                            <input type="text" id="editar-empresa-nombre" readonly>

                            <!-- Campo oculto para el ID de la empresa -->
                            <input type="hidden" name="empresa" id="editar-empresa-id">
                        </div>

                        <!-- Campos ocultos para enviar latitud y longitud -->
                        <input type="hidden" name="latitud" id="editar-latitud">
                        <input type="hidden" name="longitud" id="editar-longitud">

                        <div>
                            <input type="submit" name="btn-editar" value="Guardar Cambios">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://unpkg.com/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Configuración del mapa con un nivel de zoom adecuado
        var map = L.map('map', {
            center: [41.3497528271445, 2.1080974175773473], // Coordenadas iniciales
            zoom: 18, // Nivel de zoom inicial
            zoomControl: false, // Deshabilitar los controles de zoom
        });
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Definir el icono para el parking
        var parkingIcon = L.divIcon({
            className: 'custom-parking-icon',
            html: '<i class="fas fa-parking" style="font-size: 1.5rem; color: blue;"></i>',
            iconSize: [30, 42],
            iconAnchor: [15, 42],
        });

        var parkingMarkers = {};

        // Función para actualizar la ubicación del parking en la base de datos
        function actualizarUbicacion(parkingId, lat, lng) {
            $.ajax({
                url: `/parking/update/${parkingId}`,
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    latitud: lat,
                    longitud: lng,
                },
                success: function() {
                    alert("Ubicación actualizada con éxito");
                },
                error: function() {
                    alert("Error al actualizar la ubicación.");
                }
            });
        }

        // Función para obtener la dirección a partir de las coordenadas
        function obtenerDireccion(lat, lng, callback) {
            $.getJSON(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lng}&format=json`, function(data) {
                if (data && data.display_name) {
                    callback(data.display_name);
                } else {
                    callback("Dirección no encontrada");
                }
            });
        }

        // Crear los marcadores para los parkings
        @foreach ($parkings as $parking)
            var marker = L.marker([{{ $parking->latitud }}, {{ $parking->longitud }}], {
                    icon: parkingIcon,
                    draggable: true // Marcador arrastrable
                })
                .addTo(map);

            // Almacenar los marcadores para referencia futura
            parkingMarkers[{{ $parking->id }}] = marker;

            // Obtener la dirección y actualizar el contenido del pop-up
            obtenerDireccion({{ $parking->latitud }}, {{ $parking->longitud }}, function(direccion) {
                marker.bindPopup(`<b>{{ $parking->nombre }}</b><br>Dirección: ${direccion}`);
                document.querySelector(`#parking-{{ $parking->id }} p:nth-child(3)`).textContent =
                    `Dirección: ${direccion}`;
            });

            // Evento para cuando se termina de arrastrar
            marker.on('dragend', function(e) {
                var newLatLng = e.target.getLatLng();
                var newLat = newLatLng.lat;
                var newLng = newLatLng.lng;

                // Actualizar ubicación en la base de datos
                actualizarUbicacion({{ $parking->id }}, newLat, newLng);

                // Obtener la dirección correspondiente a las nuevas coordenadas
                obtenerDireccion(newLat, newLng, function(direccion) {
                    // Actualizar el contenido del pop-up
                    marker.setPopupContent(`<b>{{ $parking->nombre }}</b><br>Dirección: ${direccion}`);

                    // Actualizar la dirección mostrada en la lista de parkings
                    document.querySelector(`#parking-{{ $parking->id }} p:nth-child(3)`).textContent =
                        `Dirección: ${direccion}`;
                });
            });
        @endforeach

        // Evento para agregar un nuevo parking con clic en el mapa
        map.on('click', function(e) {
            var latlng = e.latlng; // Coordenadas donde se hace clic

            // Asignar coordenadas a los campos en el formulario del modal
            $("#latitud").val(latlng.lat);
            $("#longitud").val(latlng.lng);

            // Obtener la dirección correspondiente a las coordenadas
            obtenerDireccion(latlng.lat, latlng.lng, function(direccion) {
                // Mostrar la dirección en el campo de dirección del formulario
                $("#direccion").val(direccion);
            });

            // Mostrar el modal para agregar un nuevo parking
            $("#modal-crear").modal("show");
        });

        // Alternar el panel lateral
        document.getElementById('menuToggle').addEventListener('click', function() {
            var contCrud = document.getElementById('cont-crud');
            contCrud.classList.toggle('expanded');
        });

        // Confirmación para eliminación de un parking
        function confirmDeletion() {
            return confirm("¿Estás seguro de que quieres eliminar este parking?");
        }

        // Función para cargar y mostrar el modal de edición de parking
        function editarParking(id) {
            $.ajax({
                url: `/parking/${id}`,
                type: 'GET',
                success: function(parking) {
                    // Rellenar el formulario de edición
                    $("#editar-id").val(parking.id);
                    $("#editar-nombre").val(parking.nombre);
                    // $("#editar-direccion").val(parking.direccion);

                    // Campo oculto para el ID de la empresa
                    $("#editar-empresa-id").val(parking.empresa.id);

                    // Campo de texto para el nombre de la empresa (no editable)
                    $("#editar-empresa-nombre").val(parking.empresa.nombre);

                    // Campos ocultos para enviar latitud y longitud
                    $("#editar-latitud").val(parking.latitud);
                    $("#editar-longitud").val(parking.longitud);

                    // Establecer la acción del formulario para la edición
                    $("#formulario-editar-parking").attr("action", `/parking/${parking.id}`);

                    // Mostrar el modal de edición
                    $("#modal-editar").modal("show");
                },
                error: function() {
                    console.error("Error al obtener los datos del parking con ID: " + id);
                }
            });
        }

        // Función para centrarse en un parking específico por ID y resaltar su marcador
        function verParking(parkingId) {
            if (parkingMarkers[parkingId]) {
                // Desactivar cualquier marcador resaltado previamente
                for (var id in parkingMarkers) {
                    parkingMarkers[id].setIcon(parkingIcon);
                }

                var marker = parkingMarkers[parkingId];
                var latLng = marker.getLatLng();

                // Centrar la vista en el marcador y hacer zoom
                map.setView(latLng, 35); // Ajusta el nivel de zoom según tus necesidades

                // Resaltar el marcador seleccionado cambiando su icono
                marker.setIcon(L.divIcon({
                    className: 'custom-parking-icon selected-marker', // Agrega la clase CSS adicional
                    html: '<i class="fas fa-parking" style="font-size: 1.5rem; color: orange;"></i>',
                    iconSize: [30, 42],
                    iconAnchor: [15, 42],
                }));

                // Asegurar que el marcador está en el centro de la pantalla
                map.panTo(latLng);
            } else {
                console.error("No se encontró el parking con ID " + parkingId);
            }
        }
    </script>
@endpush
