<?php
use Illuminate\Support\Facades\Session;
$variable_de_sesion = session('id') ?? null;
if ($variable_de_sesion === null) {
  echo "<script>
    window.location.href = '/';
  </script>";
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>Document</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 2vh;
            background-color: #003459;
            color: white;
        }
        .canvas {
            margin-left: 2vh;
            transform: scale(1.11);
            border: solid black 2px;
            background-color: white
        }
        label {
            display: block;
            margin-bottom: 10px;
        }
        button, a {
            padding: 10px;
            border: none;
            cursor: pointer;
            border-radius: 5px; /* Hace los botones menos redondos */
            margin-right: 10px; /* Añade espacio entre los botones */
            text-decoration: none;
        }
        button.rojo {
            background-color: #ff0000; /* Cambia el color a rojo */
            color: #fff;
        }
        a.rojo{
            background-color: #ff0000; /* Cambia el color a rojo */
            color: #fff;
        }
        button.naranja {
            background-color: #ffa500; /* Cambia el color a naranja */
            color: #fff;
        }
        button.azul {
            background-color: #0000ff; /* Cambia el color a azul */
            color: #fff;
        }
        button:hover {
            filter: brightness(90%); /* Reduce el brillo al pasar el cursor */
        }
        #datosReserva {
            display: none;
        }
        .inputs {
            padding: 1vh;
            border: none;
            margin-bottom: 2vh;
            width: 93%;
            border-radius: 5px; /* Ajusta el ancho del input */

        }
    </style>
</head>
<body>
    <a href="/trabajador" class="rojo">Volver</a>
    <br>
    <br>
    <br>
    <label for="firma">Codigo del cliente:</label>
    <input id="codigo" class="inputs"></canvas>
    <br>
    <button onclick="codigoSal()" class="azul">Enviar</button>
    <br>
    <br>
    <label for="firma">Firma del cliente:</label>
    <canvas id="canvas" class="canvas"></canvas>
    <br>
    <br>
    <button class="naranja" onclick="limpiarCanvas()">Limpiar Firma</button>
    <button class="azul" onclick="mostrarDatosReserva()">Ver datos de la reserva</button>
    <br>
    <br>
    <br>
    <div id="datosReserva">

    </div>
</body>
</html>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Función para convertir un data URI en un objeto Blob
    function dataURItoBlob(dataURI) {
        var byteString;
        if (dataURI.split(',')[0].indexOf('base64') >= 0) {
            byteString = atob(dataURI.split(',')[1]);
        } else {
            byteString = unescape(dataURI.split(',')[1]);
        }

        var mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];

        var ia = new Uint8Array(byteString.length);
        for (var i = 0; i < byteString.length; i++) {
            ia[i] = byteString.charCodeAt(i);
        }

        return new Blob([ia], { type: mimeString });
    }

    let firma = [];
    const canvas = document.getElementById('canvas');
    const ctx = canvas.getContext('2d');
    let isDrawing = false;

    function limpiarCanvas() {
        firma = [];
        ctx.clearRect(0, 0, canvas.width, canvas.height);
    }

    function guardarCoordenadas(event) {
        if (isDrawing) {
            const rect = canvas.getBoundingClientRect();
            const x = event.clientX - rect.left;
            const y = event.clientY - rect.top;
            firma.push({ x, y });
            dibujar();
        }
    }

    function dibujar() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.beginPath();
        ctx.moveTo(firma[0].x, firma[0].y);
        for (let i = 1; i < firma.length; i++) {
            ctx.lineTo(firma[i].x, firma[i].y);
        }
        ctx.stroke();
    }

    function generarFirma() {
        // Convertir la firma en un objeto Blob
        const imageDataUrl = canvas.toDataURL('image/png');
        const blob = dataURItoBlob(imageDataUrl);

        return blob;
    }

    canvas.addEventListener('mousedown', (event) => {
        isDrawing = true;
        guardarCoordenadas(event);
    });

    canvas.addEventListener('mousemove', guardarCoordenadas);

    canvas.addEventListener('mouseup', () => {
        isDrawing = false;
    });

    canvas.addEventListener('mouseleave', () => {
        isDrawing = false;
    });

    // Para dispositivos táctiles
    canvas.addEventListener('touchstart', (event) => {
        isDrawing = true;
        guardarCoordenadas(event.touches[0]);
    });

    canvas.addEventListener('touchmove', (event) => {
        guardarCoordenadas(event.touches[0]);
        event.preventDefault();
    });

    canvas.addEventListener('touchend', () => {
        isDrawing = false;
    });

    function mostrarDatosReserva() {
        const datosReserva = document.getElementById('datosReserva');
        datosReserva.style.display = 'block';
    }
    function ocultar(){
        const datosReserva = document.getElementById('datosReserva');
        datosReserva.style.display = 'none';
    }

function salsa() {
    var xhr = new XMLHttpRequest();
    var csrfToken = document.querySelector("meta[name='csrf-token']").getAttribute('content');
    xhr.open("POST", "/confirmar-entrega", true);
    xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
    xhr.onreadystatechange = function() {
        if (xhr.status === 200) {
            window.location.href = '/trabajador';
        }
    }
    // Convertir la firma en un objeto Blob
    const imageDataUrl = canvas.toDataURL('image/png');
    const blob = dataURItoBlob(imageDataUrl);

    // Crear un objeto FormData para enviar la imagen al servidor
    var formData = new FormData();
    formData.append("firma", blob);
    formData.append('_token', csrfToken);

    // Enviar una solicitud POST con datos FormData al servidor
    xhr.send(formData);
}



function codigoSal() {
    var csrfToken = document.querySelector("meta[name='csrf-token']").getAttribute('content');
    var codigo = document.getElementById('codigo').value;
    var formData = new FormData();
    formData.append('codigo', codigo);
    formData.append('_token', csrfToken);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/codigoSal', true);
    xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);

    xhr.onload = function() {
        if (xhr.status == 200) {
            if (xhr.responseText=='mal'){
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No existe el codigo'
                });           
            }
            else{
                document.getElementById('datosReserva').innerHTML = xhr.responseText;
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

</script>
