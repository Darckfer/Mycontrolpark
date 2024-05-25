function generarCuadricula(plazas) {
    const gridContainer = document.getElementById("gridContainer");
    gridContainer.innerHTML = ""; // Limpiar cualquier contenido previo

    // Calcular el número de filas y columnas
    const lado = Math.ceil(Math.sqrt(plazas.length));

    // Establecer el tamaño de la cuadrícula en función del número de filas y columnas
    gridContainer.style.gridTemplateRows = `repeat(${lado}, 1fr)`;
    gridContainer.style.gridTemplateColumns = `repeat(${lado}, 1fr)`;

    // Crear los cuadros y agregarlos a la cuadrícula
    plazas.forEach(function (plaza) {
        const cuadro = document.createElement("div");
        cuadro.classList.add("cuadro");

        // Crear cuadrado negro y agregarlo al cuadro
        const cuadradoNegro = document.createElement("div");
        cuadradoNegro.classList.add("cuadrado-negro");
        cuadro.appendChild(cuadradoNegro);

        // Crear imagen y agregarla al cuadro
        const imagen = document.createElement("img");
        imagen.classList.add("imagen");
        if (plaza.id_estado === 1) {
            imagen.src = "../img/car2.svg";
        } else if (plaza.id_estado === 2) {
            imagen.src = "../img/car1.svg";
        } else if (plaza.id_estado === 3) {
            imagen.src = "../img/car3.svg";
        }
        cuadro.appendChild(imagen);

        // Crear texto y agregarlo al cuadro
        const texto = document.createElement("div");
        texto.textContent = plaza.nombre || ''; // Mostrar el nombre si existe
        texto.classList.add("texto");
        cuadro.appendChild(texto);

        // Agregar el evento onclick con la ID correspondiente
        cuadro.onclick = function () {
            reservar(plaza.id, plaza.id_estado, plaza.nombre);
        };
        gridContainer.appendChild(cuadro);

        // Calcular el tamaño del cuadrado y establecer el tamaño del texto
        const cuadroSize = cuadro.offsetWidth;
        const borderSize = cuadroSize * 0.025; // Ajusta el factor según tus preferencias
        const textoSize = cuadroSize * 0.18; // Ajusta el factor según tus preferencias
        texto.style.fontSize = `${textoSize}px`;
        if (plaza.id_estado === 1) {
            texto.style.color = `#f93939`;
        } else if (plaza.id_estado === 2) {
            texto.style.color = `#63e6be`;
        } else if (plaza.id_estado === 3) {
            texto.style.color = `#ffb83d`;
        }

        // Cambiar el color del borde según el valor de id_estado
        if (plaza.id_estado === 1) {
            cuadro.style.border = `${borderSize}px solid #f93939`;
        } else if (plaza.id_estado === 2) {
            cuadro.style.border = `${borderSize}px solid #63e6be`;
        } else if (plaza.id_estado === 3) {
            cuadro.style.border = `${borderSize}px solid #ffb83d`;
        }
    });
}

function cargarPlazas() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', '/aparca', true);

    xhr.onload = function () {
        if (xhr.status == 200) {
            const response = JSON.parse(xhr.responseText);
            const plazas = response.plazas;

            if (plazas.length > 0) {
                generarCuadricula(plazas);
            }
        } else {
            console.log('Error al obtener los datos:', xhr.responseText);
        }
    };

    xhr.onerror = function (error) {
        console.error('Error de red al intentar obtener los datos:', error);
    };

    xhr.send();
}

// Llamar a cargarPlazas al cargar la página
document.addEventListener("DOMContentLoaded", function () {
    cargarPlazas();
});

function setSquareHeight() {
    var element = document.getElementById('gridContainer');
    var width = element.offsetWidth; // Obtener el ancho del elemento
    element.style.height = width + "px"; // Establecer la altura igual al ancho
}

// Llama a la función después de que se cargue el contenido del DOM
document.addEventListener('DOMContentLoaded', setSquareHeight);

// También llamamos a la función cuando la resolución cambia
window.addEventListener('resize', setSquareHeight);

function reservar(id, estado, plaza) {
    if (estado == 2) {
        document.getElementById('modal').style.display = "block";
        console.log(plaza);
        // Llenar los campos idF y estado con los valores proporcionados
        document.getElementById('id_plaza').value = id;
        document.getElementById('estado').value = estado;
    }
    else if (estado == 1) {
        Swal.fire({
            position: 'top-end', // Posición en la esquina superior derecha
            icon: 'error',
            title: 'La plaza ya esta ocupada',
            showConfirmButton: false, // No muestra el botón de confirmación
            timer: 2000 // Tiempo en milisegundos antes de que se cierre automáticamente
        });
    }
    else if (estado == 3) {
        Swal.fire({
            position: 'top-end', // Posición en la esquina superior derecha
            icon: 'warning',
            title: 'La plaza esta en mantenimiento',
            showConfirmButton: false, // No muestra el botón de confirmación
            timer: 2000 // Tiempo en milisegundos antes de que se cierre automáticamente
        });
    }
}

function validar() {
    // Obtener los valores de los campos
    var nom_cliente = document.getElementById("nom_cliente").value;

    // Validar el campo nombre del cliente
    if (nom_cliente.trim() === '') {
        document.getElementById("nom_cliente").classList.add("invalid");
    } else {
        document.getElementById("nom_cliente").classList.remove("invalid");
    }

    // Si todos los campos son válidos, llamar a otra función
    if (nom_cliente.trim() !== '') {
        reservar2();
    }
}

function reservar2() {
    var csrfToken = document.querySelector("meta[name='csrf-token']").getAttribute('content');
    var id_plaza = document.getElementById("id_plaza").value;
    var nom_cliente = document.getElementById("nom_cliente").value;

    // Generar la firma y obtener el objeto Blob
    var firmaBlob = generarFirma();

    // Crear un FormData y agregar los valores
    var formData = new FormData();
    formData.append('_token', csrfToken);
    formData.append('id_plaza', id_plaza);
    formData.append('nom_cliente', nom_cliente);
    formData.append('firma_imagen', firmaBlob);

    // Crear una nueva solicitud XMLHttpRequest
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '/reserva', true);
    xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);

    // Definir el evento onload para manejar la respuesta del servidor
    xhr.onload = function () {
        if (xhr.status == 200) {
            if (xhr.responseText === "Reserva realizada correctamente") {
                document.getElementById('modal').style.display = "none";
                window.location.href = "trabajador";
            }
            else{
                document.getElementById('modal').style.display = "none";
            }
        }
        else{
            console.log(xhr.responseText);
        }
    };

    // Definir el evento onerror para manejar errores de red
    xhr.onerror = function (error) {
        console.error('Error de red al intentar realizar la reserva:', error);
    };

    // Enviar la solicitud con el FormData que contiene los datos del formulario y la imagen de la firma
    xhr.send(formData);
}

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



function cerrar() {
    document.getElementById('modal').style.display = "none";
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

function descargarFirma() {
    if (firma.length === 0) {
        alert('Por favor, firma en el lienzo antes de descargar.');
        return;
    }

    // Convertir la firma en una imagen y descargarla
    const imageDataUrl = canvas.toDataURL('image/png');
    const link = document.createElement('a');
    link.href = imageDataUrl;
    link.download = 'firma.png';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
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
