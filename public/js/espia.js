var botonHabilitado = true;

document.getElementById('startButton').addEventListener('click', function() {
    document.getElementById('ini').style.display="none";
    document.getElementById('fin').style.display="block";
    var csrfToken = document.querySelector("meta[name='csrf-token']").getAttribute('content');
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var coordenadasUsuario = {
                latitud: position.coords.latitude,
                longitud: position.coords.longitude
            };

            // Enviar las coordenadas al servidor
            var formData = new FormData();
            formData.append('_token', csrfToken);
            formData.append('latitud', coordenadasUsuario.latitud);
            formData.append('longitud', coordenadasUsuario.longitud);
            
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '/espia', true);
            xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);

            xhr.onload = function() {
                if (xhr.status == 200) {
                    console.log('Coordenadas enviadas correctamente.');
                    Swal.fire({
                        title: '!La ruta ha empezado!',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                } else {
                    console.log('Error al enviar las coordenadas:', xhr.responseText);
                }
            };

            xhr.onerror = function(error) {
                console.error('Error de red al intentar enviar las coordenadas:', error);
            };

            xhr.send(formData);
        });
    } else {
        console.log('La geolocalización no es compatible con este navegador.');
    }
});
document.getElementById('endButton').addEventListener('click', function() {
    var csrfToken = document.querySelector("meta[name='csrf-token']").getAttribute('content');
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var coordenadasUsuario = {
                latitud: position.coords.latitude,
                longitud: position.coords.longitude
            };

            // Enviar las coordenadas al servidor
            var formData = new FormData();
            formData.append('_token', csrfToken);
            formData.append('latitud', coordenadasUsuario.latitud);
            formData.append('longitud', coordenadasUsuario.longitud);
            
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '/espia2', true);
            xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);

            xhr.onload = function() {
                if (xhr.status == 200) {
                    console.log('Coordenadas enviadas correctamente.');
                    Swal.fire({
                        title: '!La ruta ha acabado!',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                    document.getElementById('fin').disabled = true;
                } else {
                    console.log('Error al enviar las coordenadas:', xhr.responseText);
                }
            };

            xhr.onerror = function(error) {
                console.error('Error de red al intentar enviar las coordenadas:', error);
            };

            xhr.send(formData);
        });
    } else {
        console.log('La geolocalización no es compatible con este navegador.');
    }
});

