<?php
use App\Models\modeloReserva;
$reserva = modeloReserva::where('id', 2141561966191692)->get();
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
        button {
            padding: 10px;
            border: none;
            cursor: pointer;
            border-radius: 5px; /* Hace los botones menos redondos */
            margin-right: 10px; /* Añade espacio entre los botones */
        }
        button.rojo {
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
    </style>
</head>
<body>
    <a href="/trabajador">Volver</a>
    <br>
    <br>
    <label for="firma">Firma del cliente:</label>
    <canvas id="canvas" class="canvas"></canvas>
    <br>
    <br>
    <button class="naranja" onclick="limpiarCanvas()">Limpiar Firma</button>
    <button class="azul" onclick="mostrarDatosReserva()">Ver datos de la reserva</button>
    <div id="datosReserva">
        <br>
        <br>
        @foreach ($reserva as $r)
            <label>Codigo: {{ $r->id }}</label>
            <?php
            // Construimos la URL completa a la imagen
            $urlImagen = asset("storage/img/firmas/{$r->firma_entrada}.png");
            ?>
            <!-- Mostramos la imagen -->
            <img src="{{ $urlImagen }}" alt="Firma de entrada" class="canvas">
        @endforeach   
        <br>
        <br> 
        <button class="azul" id="confirmarBtn">Confirmar entrega</button>
        <button class="rojo" onclick="ocultar()">ocultar</button>
    </div>
</body>
</html>
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

    document.getElementById("confirmarBtn").addEventListener("click", function() {
    var xhr = new XMLHttpRequest();
    var csrfToken = document.querySelector("meta[name='csrf-token']").getAttribute('content');
    xhr.open("POST", "/confirmar-entrega", true);
    xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                console.log(xhr.responseText); // Manejar la respuesta del servidor si es necesario
            } else {
                console.error('Error en la solicitud:', xhr.status);
                console.error(xhr.responseText);
            }
        }
    };

    // Convertir la firma en un objeto Blob
    const imageDataUrl = canvas.toDataURL('image/png');
    const blob = dataURItoBlob(imageDataUrl);

    // Crear un objeto FormData para enviar la imagen al servidor
    var formData = new FormData();
    formData.append("firma", blob);
    formData.append('_token', csrfToken);

    // Enviar una solicitud POST con datos FormData al servidor
    xhr.send(formData);
});

</script>
