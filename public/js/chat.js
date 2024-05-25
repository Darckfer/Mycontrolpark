window.onload = function() {
    var taDiv = document.getElementById("ta");
    taDiv.scrollTop = taDiv.scrollHeight;
}
function cor() {
    // Función para manejar la obtención de la ubicación
    function obtenerUbicacion(posicion) {
        var latitud = posicion.coords.latitude // Latitud obtenida de la posición actual
        var longitud = posicion.coords.longitude // Longitud obtenida de la posición actual
        // Actualizar el contenido del elemento con las coordenadas en el formato requerido
        document.getElementById("mensaje").value = latitud + ", " + longitud;
        // Llamar a la función chat2() u otro código que necesites ejecutar después de obtener las coordenadas
        men();
    }

    // Función para manejar errores al obtener la ubicación
    function errorUbicacion(error) {
        document.getElementById("mensaje").textContent = "No se pudo obtener la ubicación: " + error.message;
    }

    // Verificar si el navegador soporta geolocalización
    if (navigator.geolocation) {
        // Obtener la ubicación actual
        navigator.geolocation.getCurrentPosition(obtenerUbicacion, errorUbicacion);
    } else {
        document.getElementById("mensaje").textContent = "Geolocalización no soportada por este navegador.";
    }
}


function chat() {
    var csrfToken = document.querySelector("meta[name='csrf-token']").getAttribute('content');
    var hiddenInputs = document.querySelectorAll('input[name="mensaje_id"]');
    if (hiddenInputs.length === 0) {
        var id = '';
    }
    else{
        var id = hiddenInputs[hiddenInputs.length - 1].value; // Asegúrate de obtener el valor del input
    }
    if (id!=''){
        var formData = new FormData();
        formData.append('id', id);
        formData.append('_token', csrfToken);

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/chat', true);
        xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);

        xhr.onload = function() {
            if (xhr.status == 200) {
                // Agrega la respuesta del servidor al div con id "ta"
                if (xhr.responseText!=''){
                    document.getElementById("ta").innerHTML += xhr.responseText;
                    var taDiv = document.getElementById("ta");
                    taDiv.scrollTop = taDiv.scrollHeight;
                    // console.log(xhr.responseText);
                    convertirCoordenadasAMapa();
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
}
function chat2() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', '/chat2', true);

    xhr.onload = function() {
        if (xhr.status == 200) {
            // Agrega la respuesta del servidor al div con id "ta"
            if (xhr.responseText!=''){
                document.getElementById("ta").innerHTML = xhr.responseText;
                var taDiv = document.getElementById("ta");
                taDiv.scrollTop = taDiv.scrollHeight;
                convertirCoordenadasAMapa();
            }
        } else {
            console.log('Error al editar incidencia:', xhr.responseText);
        }
    };

    xhr.onerror = function(error) {
        console.error('Error de red al intentar editar incidencia:', error);
    };
    xhr.send();
}

// Ejecutar la función chat cada 5 segundos
setInterval(chat, 5000);


function men() {
    if (document.getElementById("mensaje").value!=''){
        var mensaje = document.getElementById("mensaje").value;
        var csrfToken = document.querySelector("meta[name='csrf-token']").getAttribute('content');
        var formData = new FormData();
        formData.append('mensaje', mensaje);
        formData.append('_token', csrfToken);
        if (mensaje!=''){
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '/enviarMen', true);
            xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
            
            xhr.onload = function() {
                if (xhr.status == 200) {
                    limpiar();
                    if (document.getElementById("ta").children.length === 0){
                        chat2();
                    }
                    else{
                        chat();
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
    }
}
function limpiar(){
    document.getElementById("mensaje").value = "";
}

function menCor(lati,longi) {
    var csrfToken = document.querySelector("meta[name='csrf-token']").getAttribute('content');
    var formData = new FormData();
    formData.append('lati', lati);
    formData.append('longi', longi);
    formData.append('_token', csrfToken);
    if (mensaje!=''){
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/menCor', true);
        xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
        
        xhr.onload = function() {
            window.location.href = '/mapasA';
        };
        
        xhr.send(formData);
    }
}

function convertirCoordenadasAMapa() {
    var mensajes = document.querySelectorAll('.chatRec p:not(.coordenadas-convertidas), .chatEmi p:not(.coordenadas-convertidas)');

    mensajes.forEach(function(mensaje) {
        var textoMensaje = mensaje.textContent;

        // Expresión regular para encontrar coordenadas
        var regexCoordenadas = /(-?\d+(\.\d+)?),\s*(-?\d+(\.\d+)?)/g;

        // Reemplazar coordenadas por imágenes que redirigen a Google Maps
        var mensajeConImagenes = textoMensaje.replace(regexCoordenadas, function(match) {
            var coordenadas = match.split(', ');
            // console.log(coordenadas[1]);
            return '<br><img src="./img/ubiEn.png" style="width: 6vh" onclick="menCor(' + coordenadas[0] + ',' + coordenadas[1] + ')">';
        });

        // Reemplazar el contenido del mensaje con el nuevo mensaje con imágenes
        mensaje.innerHTML = mensajeConImagenes;

        // Agregar una clase para indicar que estas coordenadas ya han sido convertidas
        mensaje.classList.add('coordenadas-convertidas');
    });
}

// Llama a la función convertirCoordenadasAMapa al cargar la página
window.onload = function() {
    // Ejecuta la función por primera vez al cargar la página
    convertirCoordenadasAMapa();

    // Establece la ejecución de la función cada 3 segundos
    setInterval(convertirCoordenadasAMapa, 2400);
};