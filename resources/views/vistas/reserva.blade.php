@extends('layouts.plantilla_header')

@section('title', 'Reserva Cliente | MyControlPark')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/cliente.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection


@section('content')
    <!-- Header -->
    <header id="header">
        <h2>Bienvenido a <br> MyControlPark</h2>
    </header>

    <nav class="navbar navbar-dark bg-dark fixed-top top-nav" id="navbar">
        <div class="container-fluid d-flex justify-content-between">
            <div class="d-flex align-items-center">
                <img class="navbar-brand" src="{{ asset('img/logo.png') }}" alt="Logo">
            </div>

            <a href="/"><i class="fa-solid fa-arrow-left"></i></a>
        </div>
    </nav>

    <div id="cont-principal" class="row">
        <div class="column-2">
            <img src="{{ asset('img/logo.png') }}" alt="">
        </div>

        <div id="formulario" class="column-2">
            <div id="cont-form">
                <h1>Haga su reserva:</h1>
                <div>
                    <label for="nom_cliente" class="form-label">Nombre del cliente:</label>
                    <input type="text" class="form-control" id="nom_cliente" name="nom_cliente">
                </div>
                <div>
                    <label for="matricula" class="form-label">Matrícula:</label>
                    <input type="text" class="form-control" id="matricula" name="matricula">
                </div>
                <div>
                    <label for="marca" class="form-label">Marca:</label>
                    <input type="text" class="form-control" id="marca" name="marca">
                </div>
                <div>
                    <label for="modelo" class="form-label">Modelo:</label>
                    <input type="text" class="form-control" id="modelo" name="modelo">
                </div>
                <div>
                    <label for="color" class="form-label">Color:</label>
                    <input type="text" class="form-control" id="color" name="color">
                </div>
                <div>
                    <label for="num_telf" class="form-label">Teléfono:</label>
                    <input type="text" class="form-control" id="num_telf" name="num_telf">
                </div>
                <div>
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" id="email" name="email">
                </div>
                <div>
                    <label for="ubicacion_entrada" class="form-label">Ubicación de Entrada:</label>
                    <input type="text" class="form-control" id="ubicacion_entrada" name="ubicacion_entrada">
                </div>
                <div>
                    <label for="ubicacion_salida" class="form-label">Ubicación de Salida:</label>
                    <input type="text" class="form-control" id="ubicacion_salida" name="ubicacion_salida">
                </div>
                <div>
                    <label for="fecha_entrada" class="form-label">Fecha de Entrada:</label>
                    <input type="datetime-local" class="form-control" id="fecha_entrada" name="fecha_entrada">
                </div>
                <div>
                    <label for="fecha_salida" class="form-label">Fecha de Salida:</label>
                    <input type="datetime-local" class="form-control" id="fecha_salida" name="fecha_salida">
                </div>
                <div>
                    <button type="button" onclick="reservarNuevo()">Enviar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <footer class="custom-footer">
        <div class="footer-content">
            <div class="footer-column">
                <h4>Contacto</h4>
                <p><i class="bi bi-person-fill"></i> +123456789</p>
                <p><i class="bi bi-envelope-fill"></i> info@mycontrolpark.com</p>
            </div>
            <div class="footer-column">
                <h4>Síguenos</h4>
                <p><i class="bi bi-facebook"></i> <i class="bi bi-whatsapp"></i> <i class="bi bi-instagram"></i></p>
            </div>
            <div class="footer-column">
                <h4>Encuentranos En</h4>
                <p><i class="bi bi-geo-alt"></i>Jesuitas Bellvitge - Joan XXIII</p>
            </div>
        </div>
    </footer>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        function reservarNuevo() {
            var csrfToken = document.querySelector("meta[name='csrf-token']").getAttribute('content');
            var nom_cliente = document.getElementById("nom_cliente").value;
            var matricula = document.getElementById("matricula").value;
            var marca = document.getElementById("marca").value;
            var modelo = document.getElementById("modelo").value;
            var color = document.getElementById("color").value;
            var num_telf = document.getElementById("num_telf").value;
            var email = document.getElementById("email").value;
            var ubicacion_entrada = document.getElementById("ubicacion_entrada").value;
            var ubicacion_salida = document.getElementById("ubicacion_salida").value;
            var fecha_entrada = document.getElementById("fecha_entrada").value;
            var fecha_salida = document.getElementById("fecha_salida").value;

        // Definir el evento onload para manejar la respuesta del servidor
        xhr.onload = function() {
            if (xhr.status == 200) {
                // Aquí puedes manejar la respuesta del servidor si es necesario
                if (xhr.responseText === "ok") {
                    Swal.fire(
                        'Reservado!',
                        '¡El vehiculo ha sido reservado!',
                        'success'
                    );
                    form.reset();
                } else {
                    Swal.fire(
                        'Error!',
                        '¡Rellena los campos!',
                        'error'
                    );
                }
            } else {
                console.log('Error al realizar la reserva:', xhr.responseText);
            }
        };

            // Crear una nueva solicitud XMLHttpRequest
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '/reservaO', true);
            xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);

            // Definir el evento onload para manejar la respuesta del servidor
            xhr.onload = function() {
                if (xhr.status == 200) {
                    // Aquí puedes manejar la respuesta del servidor si es necesario
                    console.log(xhr.responseText);
                    Swal.fire(
                        'Reservado!',
                        '¡El vehiculo ha sido reservado!',
                        'success'
                    );
                } else {
                    console.log('Error al realizar la reserva:', xhr.responseText);
                }
            };

            // Definir el evento onerror para manejar errores de red
            xhr.onerror = function(error) {
                console.error('Error de red al intentar realizar la reserva:', error);
            };

            // Enviar la solicitud con el FormData que contiene los datos del formulario y la imagen de la firma
            xhr.send(formData);
        }
    </script>

    <!-- JS NAVBAR -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Referencias al navbar y al header
            var navbar = document.getElementById('navbar');
            var header = document.getElementById('header');

            // Función para controlar el evento de desplazamiento
            window.addEventListener('scroll', function() {
                var headerHeight = header.offsetHeight; // Altura del header
                var scrollPos = window.scrollY; // Posición de desplazamiento

                // Fijar el navbar si se ha desplazado más allá de la altura del header
                if (scrollPos >= headerHeight) {
                    navbar.classList.add('navbar-fixed');
                } else {
                    navbar.classList.remove('navbar-fixed');
                }
            });
        });
    </script>
@endpush
