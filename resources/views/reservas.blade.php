d<!DOCTYPE html>
<html lang="en">
<head>
    {{-- FUENTE --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
    {{-- ICONOS --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    {{-- BOOSTRAPP --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="{{ asset('./css/reservas.css') }}">
    {{-- TOKEN --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reservas de hoy</title>
</head>
<body>
    <nav class="navbar navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
          <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <a class="navbar-brand" href="#">Reservas de hoy</a>
          <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header">
              <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Offcanvas</h5>
              <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
              <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                <li class="nav-item">
                  <a class="nav-link active" aria-current="page" href="#">Home</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#">Link</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </nav>
      <div class="filtro">
        <div class="iconoFiltro">
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                <span class="material-symbols-outlined">
                  filter_alt
                  </span>
              </button>
            </h2>
          </div>
        </div>
        <div class="inputFiltro">
            <input type="search" name="filtro" id="filtro" class="form-control" style="float: left;">
            <span class="material-symbols-outlined" onclick="filtrarReservas()">
              search
              </span>
        </div>
      </div>
      <div id="flush-collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFlushExample">
        <div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate the <code>.accordion-flush</code> class. This is the first item's accordion body.</div>
      </div>
      <div class="reservas" id="reservas">

      </div>
  <script type="text/javascript">
    // Obtener referencia al input de búsqueda
    var inputFiltro = document.getElementById('filtro');

    function filtrarReservas() {
      document.getElementById('reservas').innerHTML = "";
        // Obtener el valor del input de búsqueda
        var filtro = inputFiltro.value;
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
                    // La petición fue exitosa, procesar la respuesta
                    var data = JSON.parse(xhr.responseText);

                    // Construir la tabla con los datos recibidos
                    // var tabla = '<table class="table"><thead><tr><th>ID</th><th>Nombre Cliente</th><th>Teléfono</th><th>Email</th></tr></thead><tbody>';
                      var contenidoReserva;
                    // Iterar sobre los datos de las reservas y agregar filas a la tabla
                    // data.reservas.forEach(function(reserva) {
                    //     tabla += '<tr><td>' + reserva.id + '</td><td>' + reserva.nom_cliente + '</td><td>' + reserva.num_telf + '</td><td>' + reserva.email + '</td></tr>';
                    // });
                    var contadorVueltas = 0;
                    data.reservas.forEach(function(reserva) {
                      contadorVueltas++;

                      contenidoReserva += '<div class="reservaCliente">';
                      contenidoReserva += '<div class="horasReservas">';
                      // contenidoReserva += '<h5 style="float: left;">' + reserva.hora_entrada + '</h5>';
                      var fechaActual = new Date();
                      var fechaEntrada = new Date(reserva.fecha_entrada);
                      if (fechaEntrada.toDateString() === fechaActual.toDateString()) {
                          contenidoReserva += '<h5 style="float: left;">' + reserva.hora_entrada + '</h5>';
                      } else {
                          // Verificar si la fecha de salida coincide con el día actual
                          var fechaSalida = new Date(reserva.fecha_salida);
                          if (fechaSalida.toDateString() === fechaActual.toDateString()) {
                              contenidoReserva += '<h5 style="float: left;">' + reserva.hora_salida + '</h5>';
                          } else {
                              contenidoReserva += '<h5 style="float: left;">Otra hora</h5>'; // Cambia "Otra hora" según tu necesidad
                          }
                      }
                      var tieneFirma = reserva.firma ? '<span class="material-symbols-outlined"> done </span>' : '';
                      contenidoReserva += '<p>' + tieneFirma + '</p>';
                      contenidoReserva += '</div>';
                      contenidoReserva += '<h3>' + reserva.matricula + '</h3>';
                      var nombreTrabajador = reserva.trabajador ? reserva.trabajador.nombre : 'No asignado';
                      var desH = reserva.trabajador ? 'disabled' : '';
                      contenidoReserva += '<button type="button" class="btn btn-dark" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="left" data-bs-content="' + nombreTrabajador + '" ' + desH + '>' + nombreTrabajador + '</button>';
                      contenidoReserva += '</div>';
                    });
                    console.log('Total de vueltas: ' + contadorVueltas);

                    // Cerrar la tabla

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
            xhr.send("filtro=" + encodeURIComponent(filtro)); // Asegúrate de codificar el filtro correctamente
    }

  //   // Agregar un event listener para el evento 'input'
  //   // inputFiltro.addEventListener('input', function() {
  //   //   filtrarReservas();
  //   // });
  //   // Agregar un event listener para el evento 'keydown' en el input de búsqueda
  inputFiltro.addEventListener('keydown', function(event) {
      // Verificar si la tecla presionada es "Enter" (código 13)
      if (event.keyCode === 13) {
          // Ejecutar la función filtrarReservas()
          filtrarReservas();
      }
  });
  filtrarReservas();
</script>
</body>
</html>
