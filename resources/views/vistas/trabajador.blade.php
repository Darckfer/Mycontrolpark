@if (session('id'))
    <!DOCTYPE html>
    <html lang="en">

    <head>
        {{-- FUENTE --}}
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
        {{-- ICONOS --}}
        <link rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
        {{-- BOOSTRAPP --}}
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <link rel="stylesheet" href="{{ asset('./css/reservas.css') }}">
        {{-- TOKEN --}}
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title id="texto-nav2"></title>
    </head>

    <body>
        <div class="mostrarenmobile">
            <nav class="navbar navbar-dark bg-dark fixed-top">
                <div class="container-fluid">
                    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar"
                        aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <a class="navbar-brand" href="#" id="texto-nav">Reservas de hoy</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasNavbar2" aria-controls="offcanvasNavbar"
                        aria-label="Toggle navigation">
                        <span class="material-symbols-outlined" style="background-color: transparent;">
                            filter_alt
                        </span>
                    </button>
                    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar"
                        aria-labelledby="offcanvasNavbarLabel">
                        <div class="offcanvas-header">
                            <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Offcanvas</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                                aria-label="Close"></button>
                        </div>
                        <div class="offcanvas-body">
                            <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                                <a class="navbar-brand" href="/chatG" class="dropdown-item"
                                    style="color: green;">Chat</a>
                                <a class="navbar-brand" href="/mapasA" class="dropdown-item"
                                    style="color: blue;">Mapa</a>
                                <a class="navbar-brand" href="logout" class="dropdown-item"
                                    style="color: red;">Cerrar
                                    sesión</a>
                                {{-- <div id="google_translate_element" class="google"></div> --}}
                            </ul>
                        </div>
                    </div>
                    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar2"
                        aria-labelledby="offcanvasNavbarLabel" style="background-color: #00171F;">
                        <div class="offcanvas-header">
                            <h5 class="offcanvas-title btn btn-danger" id="offcanvasNavbarLabel"
                                onclick="borrarFiltro()">
                                Borrar filtros</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"
                                style="background-color: white"></button>
                        </div>
                        <div class="offcanvas-body">
                            <div class="accordion acordion-flush" id="accordionExample">
                                <div class="accordion-item" onclick="Checked()">
                                    <h2 class="accordion-header">
                                        <div class="accordion-button collapsed after" type="button"
                                            data-bs-target="#collapseFive" aria-expanded="true">
                                            <label class="form-check-label" for="asignado" onclick="Checked()">
                                                Asignado a mí </label>
                                            <input type="checkbox" name="asignado" id="asignado"
                                                style="margin: 0.25vh 0 0 1vh;" onclick="Checked()">
                                        </div>

                                    </h2>
                                    <div id="collapseFive" class="accordion-collapse collapse"
                                        data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <div class="accordion-button collapsed after" type="button"
                                            data-bs-target="#collapseSixt" aria-expanded="true"
                                            onclick="CheckedRes()">
                                            <label class="form-check-label" for="pendiente" onclick="CheckedRes()">
                                                Todas las reservas </label>
                                            <input type="checkbox" name="pendiente" id="pendiente"
                                                style="margin: 0.25vh 0 0 1vh;" onclick="CheckedRes()">
                                        </div>

                                    </h2>
                                    <div id="collapseSixt" class="accordion-collapse collapse"
                                        data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseOne"
                                            aria-expanded="true" aria-controls="collapseOne">
                                            Parking
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse"
                                        data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div id="ubicaciones">
                                            </div>
                                            <button type="button" id="deseleccionarUbicaciones"
                                                class="btn btn-dark btn-sm">Deseleccionar Ubicaciones</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseTwo"
                                            aria-expanded="false" aria-controls="collapseTwo">
                                            Fecha
                                        </button>
                                    </h2>
                                    <div id="collapseTwo" class="accordion-collapse collapse"
                                        data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <input type="date" name="filtro_fecha" id="filtro_fecha">
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseThree"
                                            aria-expanded="false" aria-controls="collapseThree">
                                            Ubicación
                                        </button>
                                    </h2>
                                    <div id="collapseThree" class="accordion-collapse collapse"
                                        data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div class="form-check">
                                                <input style="background-color: grey;" class="form-check-input"
                                                    type="radio" name="flexRadioDefault" value=""
                                                    id="empty" checked>
                                                <label class="form-check-label" for="empty">Ninguno</label>
                                            </div>
                                            <div class="form-check">
                                                <input style="background-color: grey;" class="form-check-input"
                                                    type="radio" name="flexRadioDefault" value="Aeropuerto T1"
                                                    id="Aeropuerto T1">
                                                <label class="form-check-label" for="Aeropuerto T1">Aeropuerto
                                                    T1</label>
                                            </div>
                                            <div class="form-check">
                                                <input style="background-color: grey;" class="form-check-input"
                                                    type="radio" name="flexRadioDefault" value="Aeropuerto T2"
                                                    id="T2">
                                                <label class="form-check-label" for="T2">Aeropuerto T2</label>
                                            </div>
                                            <div class="form-check">
                                                <input style="background-color: grey;" class="form-check-input"
                                                    type="radio" name="flexRadioDefault" value="Puerto"
                                                    value="0" id="Puerto">
                                                <label class="form-check-label" for="Puerto">Puerto</label>
                                            </div>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
            <div class="filtro">
                <div class="inputFiltro">
                    <input type="search" name="filtro" id="filtro" class="form-control" style="float: left;">
                    <span class="material-symbols-outlined" onclick="filtrarReservas()">
                        search
                    </span>
                </div>
            </div>
            {{-- <button data-toggle="popover" title="Popover Header" data-content="Some content inside the popover">Toggle popover</button>
            <button data-toggle="popover" title="Popover Header" data-content="Some content inside the popover">Toggle popover</button> --}}
            <div class="reservas" id="reservas">

            </div>
        </div>
        <script type="text/javascript">
            var idParking = ""; // Declarar idParking de forma global
            var checkbox;
            var allres;
            // Obtener referencia al input de búsqueda
            var inputFiltro = document.getElementById('filtro');
            var inputFecha = document.getElementById('filtro_fecha');

            function filtrarReservas() {
                // Obtener el valor del input de búsqueda
                var filtro = inputFiltro.value;
                var filtroFecha = document.getElementById('filtro_fecha').value;
                // Obtener el token CSRF desde una etiqueta meta
                var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                // Configurar la petición Ajax incluyendo el token CSRF
                var xhr = new XMLHttpRequest();
                xhr.open('POST', "{{ route('mostrarR') }}", true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded'); // Establecer el tipo de contenido
                xhr.setRequestHeader('X-CSRF-TOKEN', token); // Configurar el token CSRF en la cabecera de la solicitud

                // Configurar el callback cuando la petición haya sido completada
                xhr.onload = function() {
                    if (xhr.status >= 200 && xhr.status < 400) {
                        var textonav = document.getElementById("texto-nav");
                        var hoy = new Date(); // Obtener la fecha actual
                        var dia = hoy.getDate();
                        var mes2 = hoy.getMonth() + 1; // Los meses en JavaScript se cuentan desde 0
                        if (mes2 < 10) {
                            var mes = "0" + mes2;
                        }
                        var año = hoy.getFullYear();
                        var fechaActual = año + '-' + mes + '-' + dia; // Formatear la fecha actual

                        console.log("filtroFecha:", filtroFecha);
                        console.log("fechaActual:", fechaActual);

                        if (!filtroFecha || filtroFecha === fechaActual) {
                            if (inputFiltro.value == "") {
                                textonav.innerHTML = "Reservas de hoy";
                                document.getElementById("texto-nav2").innerHTML = "Reservas de hoy";
                            } else {
                                textonav.innerHTML = "Reservas";
                                document.getElementById("texto-nav2").innerHTML = "Reservas";
                            }
                        } else {
                            textonav.innerHTML = "Reservas de " + filtroFecha;
                            document.getElementById("texto-nav2").innerHTML = "Reservas del " + filtroFecha;
                        }
                        // La petición fue exitosa, procesar la respuesta
                        var data = JSON.parse(xhr.responseText);
                        var contenidoReserva = '';

                        // Primero, convertimos las fechas de entrada y salida en objetos Date y añadimos la propiedad hora para cada reserva
                        data.reservas.forEach(function(reserva) {
                            reserva.fechaEntrada = new Date(reserva.fecha_entrada);
                            reserva.fechaSalida = new Date(reserva.fecha_salida);
                            // Calculamos la hora basada en la fecha de entrada o salida, dependiendo de cuál sea más temprana
                            reserva.hora = Math.min(reserva.fechaEntrada.getTime(), reserva.fechaSalida.getTime());
                        });

                        // Luego, ordenamos las reservas por la propiedad hora
                        data.reservas.sort(function(a, b) {
                            return a.hora - b.hora;
                        });

                        // Finalmente, recorremos las reservas para crear el contenido
                        data.reservas.forEach(function(reserva) {
                            var esHoyEntrada = comparaFechas(new Date(), reserva.fechaEntrada);
                            var esHoySalida = comparaFechas(new Date(), reserva.fechaSalida);
                            // if (esHoyEntrada && reserva.firma_entrada) {
                            var horaMostrar = '';

                            if (esHoyEntrada) {
                                horaMostrar = formatoHora(reserva.fechaEntrada.getHours()) + ':' + formatoHora(
                                    reserva.fechaEntrada.getMinutes());
                                var tieneFirma = '';
                                if (esHoyEntrada && reserva.firma_entrada) {
                                    tieneFirma = '<span class="material-symbols-outlined"> done </span>';
                                }

                                var claseColor = 'green';


                                contenidoReserva += '<div class="reservaCliente" style="background-color: ' +
                                    claseColor + ';" id="reserva' + reserva.id +
                                    '" onclick="window.location.href = \'/info_res?id_r=' + reserva.id + '\'">';
                                contenidoReserva += '<div class="horasReservas">';
                                contenidoReserva += '<h5 style="float: left;">' + horaMostrar + '</h5>';
                                contenidoReserva += '<p>' + tieneFirma + '</p>';
                                contenidoReserva += '</div>';
                                contenidoReserva += '<h3>' + reserva.matricula + '</h3>';

                                var nombreTrabajador = reserva.trabajador ? reserva.trabajador.nombre :
                                    'No asignado';
                                var desH = reserva.trabajador ? 'disabled' : '';

                                contenidoReserva +=
                                    '<button type="button" class="btn btn-light" data-toggle="popover" title="Popover Header" onmouseover="">' +
                                    nombreTrabajador + '</button>';
                                contenidoReserva += '</div>';
                            }
                            if (esHoySalida) {
                                horaMostrar = formatoHora(reserva.fechaSalida.getHours()) + ':' + formatoHora(
                                    reserva.fechaSalida.getMinutes());
                                var tieneFirma = '';
                                if (esHoySalida && reserva.firma_salida) {
                                    tieneFirma = '<span class="material-symbols-outlined"> done </span>';
                                }

                                var claseColor = 'red';


                                contenidoReserva += '<div class="reservaCliente" style="background-color: ' +
                                    claseColor + ';" id="reserva' + reserva.id +
                                    '" onclick="window.location.href = \'/info_res?id_r=' + reserva.id + '\'">';
                                contenidoReserva += '<div class="horasReservas">';
                                contenidoReserva += '<h5 style="float: left;">' + horaMostrar + '</h5>';
                                contenidoReserva += '<p>' + tieneFirma + '</p>';
                                contenidoReserva += '</div>';
                                contenidoReserva += '<h3>' + reserva.matricula + '</h3>';

                                var nombreTrabajador = reserva.trabajador ? reserva.trabajador.nombre :
                                    'No asignado';
                                var desH = reserva.trabajador ? 'disabled' : '';
                                contenidoReserva +=
                                    '<button type="button" class="btn btn-light" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="' +
                                    nombreTrabajador + '" ' + desH + '>' + nombreTrabajador + '</button>';
                                contenidoReserva += '</div>';
                                console.log(esHoyEntrada);
                            }
                            if ((esHoyEntrada === false && esHoySalida === false)) {
                                horaMostrar = formatoHora(reserva.fechaSalida.getHours()) + ':' + formatoHora(
                                    reserva.fechaSalida.getMinutes());
                                var tieneFirma = '';
                                if (esHoySalida && reserva.firma_salida) {
                                    tieneFirma = '<span class="material-symbols-outlined"> done </span>';
                                }

                                var claseColor = 'white; color: black';


                                contenidoReserva += '<div class="reservaCliente" style="background-color: ' +
                                    claseColor + ';" id="reserva' + reserva.id +
                                    '" onclick="window.location.href = \'/info_res?id_r=' + reserva.id + '\'">';
                                contenidoReserva += '<div class="horasReservas">';
                                // contenidoReserva += '<h5 style="float: left;">' + horaMostrar + '</h5>';
                                contenidoReserva += '<p>' + tieneFirma + '</p>';
                                contenidoReserva += '</div>';
                                contenidoReserva += '<h3>' + reserva.matricula + '</h3>';

                                var nombreTrabajador = reserva.trabajador ? reserva.trabajador.nombre :
                                    'No asignado';
                                var desH = reserva.trabajador ? 'disabled' : '';
                                contenidoReserva +=
                                    '<button type="button" class="btn btn-light" style="z-index: 2;">' +
                                    nombreTrabajador + '</button>';
                                contenidoReserva += '</div>';
                            }
                            // }
                        });

                        console.log('Total de vueltas: ' + data.reservas.length);
                        document.getElementById('texto-nav').innerHTML += " (" + data.reservas.length + ")";

                        // Actualizar el contenido de la sección de reservas con la tabla construida
                        document.getElementById('reservas').innerHTML = contenidoReserva;
                    } else {
                        // Ocurrió un error al hacer la petición
                        console.error('Error al realizar la petición:', xhr.status);
                    }
                };

                // Configurar el callback para manejar errores de red
                xhr.onerror = function() {
                    console.error('Error de red al realizar la petición.');
                };

                // Enviar la petición
                xhr.send("filtro=" + encodeURIComponent(filtro) + "&filtro_fecha=" + encodeURIComponent(filtroFecha) +
                    "&parking=" + encodeURIComponent(idParking) + "&asignado=" + encodeURIComponent(checkbox));
            }
            var asignado = document.getElementById('asignado');
            allres = document.getElementById('pendiente');

            // Agregar un event listener para el evento 'keydown' en el input de búsqueda
            inputFiltro.addEventListener('keydown', function(event) {
                // Verificar si la tecla presionada es "Enter" (código 13)
                if (event.keyCode === 13) {
                    // Ejecutar la función filtrarReservas()
                    filtrarReservas();
                }
            });
            // Agregar un event listener para el evento 'keydown' en el input de búsqueda
            inputFecha.addEventListener('blur', function(event) {
                // Verificar si la tecla presionada es "Enter" (código 13)
                filtrarReservas();
            });

            function checkboxstatus() {
                // Verificar si la tecla presionada es "Enter" (código 13)
                if (asignado.checked) {
                    console.log('Checkbox is checked!');
                    checkbox = true;
                    // Código adicional para cuando el checkbox está activado
                } else {
                    console.log('Checkbox is unchecked!');
                    checkbox = false;
                    // Código adicional para cuando el checkbox está desactivado
                }
                filtrarReservas();
            }

            function checkboxreservas() {
                // Verificar si la tecla presionada es "Enter" (código 13)
                if (allres.checked) {
                    console.log('Checkbox is checked!');
                    allres = true;
                    // Código adicional para cuando el checkbox está activado
                } else {
                    console.log('Checkbox is unchecked!');
                    allres = false;
                    // Código adicional para cuando el checkbox está desactivado
                }
                filtrarReservas();
            }

            asignado.addEventListener('change', function(event) {
                checkboxstatus();
            });
            allres.addEventListener('change', function(event) {
                checkboxreservas();
            });

            function filtroUbi() {
                // Obtener el token CSRF desde una etiqueta meta
                var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                // Configurar la petición Ajax incluyendo el token CSRF
                var xhr = new XMLHttpRequest();
                xhr.open('POST', "{{ route('filtroUbi') }}", true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded'); // Establecer el tipo de contenido
                xhr.setRequestHeader('X-CSRF-TOKEN', token); // Configurar el token CSRF en la cabecera de la solicitud

                // Configurar el callback cuando la petición haya sido completada
                xhr.onload = function() {
                    if (xhr.status >= 200 && xhr.status < 400) {
                        var data = JSON.parse(xhr.responseText);
                        var parkings = data.parkings;

                        // Construir el HTML para los parkings
                        var html = '';
                        parkings.forEach(function(parking) {
                            html += '<div class="form-check">';
                            html +=
                                '<input style="background-color: grey;" class="form-check-input" type="radio" name="flexRadioDefault" value="' +
                                parking.id + '" id="parking' + parking.id + '">';
                            html += '<label class="form-check-label" for="parking' + parking.id + '">' + parking
                                .nombre + '</label>';
                            html += '</div>';
                        });

                        // Actualizar el contenido del elemento 'ubicaciones'
                        document.getElementById('ubicaciones').innerHTML = html;

                        // Agregar event listeners a los checkboxes
                        parkings.forEach(function(parking) {
                            var checkbox = document.getElementById('parking' + parking.id);
                            checkbox.addEventListener('change', function() {
                                idParking = parking.id;
                                filtrarReservas
                                    (); // Llamar a filtrarReservas() con el valor del checkbox como argumento
                            });
                        });
                    }
                };

                // Configurar el callback para manejar errores de red
                xhr.onerror = function() {
                    console.error('Error de red al realizar la petición.');
                };

                // Enviar la petición
                xhr.send(); // Asegúrate de codificar el filtro correctamente
            }

            function Selects() {
                var radiosUbicaciones = document.querySelectorAll('input[name="flexRadioDefault"]');

                // Recorrer los radios y deseleccionarlos
                radiosUbicaciones.forEach(function(radio) {
                    radio.checked = false;
                });
                idParking = "";

                // Llamar a la función de filtrarReservas para refrescar la lista de reservas
                checkboxstatus();
            }
            document.getElementById('deseleccionarUbicaciones').addEventListener('click', function() {
                // Obtener todos los radios de ubicación
                Selects();
            });

            function borrarFiltro() {
                // Obtener todos los radios de ubicación
                var fecha = document.getElementById("filtro_fecha");
                fecha.value = "";
                asignado.checked = false;

                // Llamar a la función de filtrarReservas para refrescar la lista de reservas
                Selects();
            }

            function Checked() {
                if (asignado.checked == true) {
                    asignado.checked = false;
                } else {
                    asignado.checked = true;
                }
                checkboxstatus();
            }

            function CheckedRes() {
                if (allres.checked == true) {
                    allres.checked = false;
                } else {
                    allres.checked = true;
                }
                checkboxreservas();
            }

            filtroUbi();
            filtrarReservas();
            // Función para formatear la hora en dos dígitos
            function formatoHora(hora) {
                return hora.toString().padStart(2, '0');
            }

            // Función para comparar fechas
            function comparaFechas(fecha1, fecha2) {
                return fecha1.toDateString() === fecha2.toDateString();
            }
            $(document).ready(function() {
                $('[data-toggle="popover"]').popover();
            });

            // function googleTranslateElementInit() {
            //     new google.translate.TranslateElement({
            //         pageLanguage: 'es',
            //         includedLanguages: 'ca,eu,en,fr,it,pt',
            //         // layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
            //         // gaTrack: true
            //     }, 'google_translate_element');
            // }
        </script>

        {{-- <script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"> --}}
        </script>
    </body>

    </html>
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
