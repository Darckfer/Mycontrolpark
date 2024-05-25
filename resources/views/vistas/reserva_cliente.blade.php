<?php
use App\Models\tbl_usuarios;
use App\Models\tbl_parking;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon; // Asegúrate de importar la clase Carbon si no lo has hecho aún
$variable_de_sesion = session('id') ?? null;
if ($variable_de_sesion === null) {
  echo "<script>
    window.location.href = '/';
  </script>";
}
$fechaEntrada = Carbon::parse($reserva_cliente->fecha_entrada);
$fechaSalida = Carbon::parse($reserva_cliente->fecha_salida);

// Obtener el día actual
$hoy = Carbon::now();
session(['codigoReserva' => $reserva_cliente->id]);
if ($fechaEntrada->format('d') == $hoy->format('d')){
  session(['acciones' => 'entrada']);
}
elseif ($fechaSalida->format('d') == $hoy->format('d')){
  session(['acciones' => 'salida']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de la reserva #{{ $reserva_cliente->id }}</title>
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
    <link rel="stylesheet" href="{{ asset('./css/info_reserva.css') }}">
    {{-- TOKEN --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
      #webcam {
        width: 100%; /* Ajusta el ancho al 100% del contenedor */
        height: auto; /* Altura automática para mantener la proporción del video */
        display: block; /* Para asegurarse de que el video se muestre correctamente */
        margin: 0 auto; /* Centra el video horizontalmente */
      }
    </style>
    <script type="text/javascript" src="https://unpkg.com/webcam-easy/dist/webcam-easy.min.js"></script>
</head>
<body>
  <input type="hidden" id="idR" value="{{ $reserva_cliente->id }}">
    <nav class="navbar navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
          <a href="/trabajador" class="botonNo"><img src="./img/volver.svg" width="40px"></a>
            <button type="button" class="btn btn-dark" data-bs-toggle="modal" onclick="enviarFotos()">
              <span class="material-symbols-outlined">
                outbox
                </span>
            </button>
        </div>
    </nav>
    <div class="reserva">
      <div style="height: 10vh; width: 100%;">
        <div class="info">
            <p class="d-inline-flex gap-1">
                <a class="btn btn-dark" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                    {{ $reserva_cliente->matricula }}
                  </a>
            </p>
            <div class="collapse" id="collapseExample">
                <div class="card card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">{{ $reserva_cliente->nom_cliente }}</li>
                        <li class="list-group-item">{{ $reserva_cliente->marca }} {{ $reserva_cliente->modelo }}</li>
                        <li class="list-group-item">{{ $reserva_cliente->num_telf }}</li>
                        <li class="list-group-item">{{ $reserva_cliente->email }}</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="imagenes">
            <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#staticBackdrop3" onclick="iniciar()">
                <span class="material-symbols-outlined">
                    add_notes
                    </span>
              </button>
            <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#staticBackdrop2">
                <span class="material-symbols-outlined">
                    local_parking
                    </span>
              </button>
          <button type="button" class="btn btn-dark" data-bs-toggle="modal" id="seleccionar-imagen">
            <span class="material-symbols-outlined">
              add_a_photo
              </span>
          </button>
        </div>
      </div>
        <div class="registros">
            <div class="entrada">
                <div class="reg_entr">
                    <p>Registro de entrada</p>
                    <p>{{ $reserva_cliente->fecha_entrada }}</p>
                    <p>{{ $reserva_cliente->ubicacion_entrada }}</p>
                </div>
                <div class="check_entr" id="check_entr">
                    @if($reserva_cliente->firma_entrada === null)
                        <span class="material-symbols-outlined"> 
                        check_circle
                        </span>
                    @else 
                    <span class="material-symbols-outlined">
                        done
                        </span>
                    @endif
                </div>
            </div>
            <div class="salida">
                <div class="reg_sal">
                    <p>Registro de salida</p>
                    <p>{{ $reserva_cliente->fecha_salida }}</p>
                    <p>{{ $reserva_cliente->ubicacion_salida }}</p>
                </div>
                <div class="check_sal" onclick="window.location.href = '/devolucion'">
                    @if($reserva_cliente->firma_salida === null)
                        <span class="material-symbols-outlined">
                            door_open
                        </span>
                    @else 
                        <span class="material-symbols-outlined">
                            door_back
                        </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="desplazamientos">
            <button type="button" class="btn btn-outline-dark" id="ini">
                <div style="width: 20%; float: left;">
                    <span class="material-symbols-outlined"> directions_car </span>
                </div>
                <div style="width: 80%; float: left;">
                    <a id="startButton">Iniciar desplazamiento</a>
                </div>
            </button>
            <button type="button" class="btn btn-outline-dark" style="display: none" id="fin">
              <div style="width: 20%; float: left;">
                  <span class="material-symbols-outlined"> directions_car </span>
              </div>
              <div style="width: 80%; float: left;">
                  <a id="endButton">Finalizar desplazamiento</a>
              </div>
          </button>

        </div>
      </div>
      <div class="modal fade" id="staticBackdrop3" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel3" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="staticBackdropLabel3">Notas</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form action="modificar" method="post" style="padding: 2px;">
                <textarea name="notas" id="notas" cols="40" rows="10">{{ $reserva_cliente->notas }}</textarea>
              </form>
              <button type="button" class="btn btn-outline-dark" onclick="notas()" data-bs-dismiss="modal" aria-label="Close">Modificar</button>
              <button type="button" class="btn btn-outline-danger" onclick="borrarN()" data-bs-dismiss="modal" aria-label="Close">Borrar</button>            </div>
          </div>
          <div class="modal-footer">
            {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Guardar</button>
            <button type="button" class="btn btn-primary" onclick="foto()">Foto</button> --}}
          </div>
        </div>
      </div>
      <div class="modal fade" id="staticBackdrop2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel3" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-body">
              <form method="post">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="float: right"></button>
                <br>
                <label for="">Seleccione el parkin</label>
                <br>
                <br>
                <select name="parking" id="parking">
                  <option value="">escoja una opcion</option>
                  <?php      
                  $id_user = session('id');
                  // Obtener el usuario actual
                  $usuario = tbl_usuarios::where('id', $id_user)->first();
                  
                  if ($usuario) {
                      $id_empresa = $usuario->id_empresa;
                      // Obtener los parkings relacionados con la empresa del usuario
                      $parkings = tbl_parking::where('id_empresa', $id_empresa)->get();
                      
                      foreach ($parkings as $parking) {
                          // Iterar sobre cada parking encontrado y mostrarlo como opción en el select
                          $parking_id = $parking->id;
                          $parking_nombre = $parking->nombre;
                          echo '<option value="' . $parking_id . '">' . $parking_nombre . '</option>';
                      }
                      
                      // Verificar si no se encontraron parkings
                      if ($parkings->isEmpty()) {
                          // Si no se encontró ningún parking para la empresa del usuario, puedes manejarlo aquí
                          echo '<option value="">No se encontró ningún parking para esta empresa.</option>';
                      }
                  } else {
                      // Si no se encontró ningún usuario, puedes manejarlo aquí
                      echo '<option value="">No se encontró ningún usuario con el ID proporcionado.</option>';
                  }
                  ?>      
                </select>              
                <br>
                <br>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" aria-label="Close"  onclick="escogerP()">Confirmar</button>
              </form>
            </div>
          </div>
          <div class="modal-footer">
            {{-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Guardar</button>
            <button type="button" class="btn btn-primary" onclick="foto()">Foto</button> --}}
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.9.0/feather.min.js'></script>
<script src="./js/espia.js"></script>
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', function() {
  nPar();
});
function nPar(){
  Swal.fire({
      title: '¡Nota!',
      text: 'tienes que seleccionar un parking para modificar una reserva',
      icon: 'warning',
      confirmButtonText: 'Aceptar'
  });
}

function notas(){
  var csrfToken = document.querySelector("meta[name='csrf-token']").getAttribute('content');
  var idR = document.getElementById('idR').value;
  var notas = document.getElementById('notas').value;
  var formData = new FormData();
  formData.append('idR', idR);
  formData.append('notas', notas);
  formData.append('_token', csrfToken);

  var xhr = new XMLHttpRequest();
  xhr.open('POST', '/notaR', true);
  xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);

  xhr.onload = function() {
    if (xhr.status == 200) {
      var response = JSON.parse(xhr.responseText);
      if (response.message === 'Notas actualizadas correctamente') {
          console.log('Notas actualizadas correctamente');
      }
    }
  };

  xhr.onerror = function(error) {
      console.error('Error de red al intentar editar incidencia:', error);
  };
  xhr.send(formData);
}


function escogerP(){
  var csrfToken = document.querySelector("meta[name='csrf-token']").getAttribute('content');
  var parking = document.getElementById('parking').value;
  var formData = new FormData();
  formData.append('parking', parking);
  formData.append('_token', csrfToken);

  var xhr = new XMLHttpRequest();
  xhr.open('POST', '/escogerP', true);
  xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);

  xhr.onload = function() {
    if (xhr.status == 200) {
        // Aquí puedes verificar si se ha completado la solicitud y el proceso fue exitoso
        var miDiv = document.getElementById("check_entr");

        // Asegurémonos de que el elemento "check_entr" exista antes de intentar agregar un evento onclick
        if (miDiv) {
            // Añadimos un evento onclick al div solo si se encuentra el elemento
            miDiv.onclick = function() {
                // Define la URL a la que quieres redirigir
                var href = "/cambio";

                // Redirige a la URL cuando se haga clic en el div
                window.location.href = href;
            };
        }
      } else {
          console.log('Error al editar incidencia:', xhr.responseText);
      }
  };

  xhr.onerror = function(error) {
      console.error('Error de red al intentar editar incidencia:', error);
  };
  xhr.send(formData);
}

function borrarN(){
  document.getElementById('notas').textContent = '';
  notas();
}

// Creamos un array para almacenar las imágenes seleccionadas
var imagenesSeleccionadas = [];

// Función para manejar el evento click del botón
document.getElementById('seleccionar-imagen').addEventListener('click', function() {
    // Creamos un input de tipo file dinámicamente
    var input = document.createElement('input');
    input.type = 'file';
    input.accept = 'image/*';
    input.capture = 'environment'; // Esto es específico para dispositivos móviles
    // Escuchamos el evento change del input para manejar la selección de archivos
    input.addEventListener('change', function(event) {
        const file = event.target.files[0]; // Obtenemos el archivo seleccionado
        imagenesSeleccionadas.push(file); // Añadimos el archivo al array de imágenes seleccionadas
    });
    // Disparamos el evento click en el input para abrir el selector de archivos
    input.click();
});

function enviarFotos() {
  var csrfToken = document.querySelector("meta[name='csrf-token']").getAttribute('content');
  var idR = document.getElementById('idR').value;
  var formData = new FormData();

  // Aquí agregamos cada imagen seleccionada al FormData
  imagenesSeleccionadas.forEach(function(imagen, index) {
    formData.append('imagen_' + index, imagen);
  });

  formData.append('_token', csrfToken);

  var xhr = new XMLHttpRequest();
  xhr.open('POST', '/subirImagenes', true); // Reemplaza '/subirImagenes' con tu ruta de destino
  xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);

  xhr.onload = function() {
    if (xhr.status == 200) {
      var response = JSON.parse(xhr.responseText);
      if (response.message === 'Imágenes subidas correctamente') {
          console.log('Imágenes subidas correctamente');
      }
    }
  };

  xhr.onerror = function(error) {
      console.error('Error de red al intentar subir las imágenes:', error);
  };

  xhr.send(formData);
}

</script>
