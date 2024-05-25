<!DOCTYPE html>
<html>
<head>
    <title>Mapa con Leaflet y AJAX</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
    <style>
        body {
            margin: 0;
        }

        .navbar {
            background-color: #333;
            color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1vh; /* 1% de la altura de la ventana */
        }

        #map {
            height: calc(100vh - 10vh); /* Altura del mapa restando 10% de la altura de la ventana */
            width: 100%;
        }

        input[type="search"] {
            padding: 0.8vh; /* 0.8% de la altura de la ventana */
            border: 1px solid #ccc;
            border-radius: 2px;
            width: 30vh;
            height: 4vh;
            margin-right: 1vh; /* 1% de la altura de la ventana */
            font-size: 4vh; /* Cambiado a medida relativa */
        }

        input[type="search"]:focus {
            outline: none;
            border-color: #333;
        }

        .hamburguesa {
            background-color: #fff;
            border: 1px solid #ccc;
            padding-top: 0.7vh; /* 1% de la altura de la ventana */
            padding-bottom: 0.7vh; /* 1% de la altura de la ventana */
            padding-left: 1.4vh; /* 1% de la altura de la ventana */
            padding-right: 1.4vh; /* 1% de la altura de la ventana */
            cursor: pointer;
            transition: background-color 0.3s ease-in-out; /* Efecto smooth para el color de fondo */
            color: black;
        }

        .hamburguesa:hover {
            background-color: #f0f0f0;
        }

        .ubicaciones {
            display: none;
            position: absolute;
            top: 6vh; /* 5% de la altura de la ventana */
            right: 1vh; /* 1% de la altura de la ventana */
            z-index: 1000;
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 1vh; /* 1% de la altura de la ventana */
            transition: opacity 0.3s ease-in-out; /* Efecto smooth para la opacidad */
            font-size: 2.5vh;
        }

        .ubicaciones ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .ubicaciones li {
            margin-bottom: 0.5vh; /* 0.5% de la altura de la ventana */
            cursor: pointer;
        }

        .mostrar {
            color: black;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="/trabajador" class="botonNo"><img src="./img/volver.svg" width="40px"></a>
        <input type="search">
        <div class="hamburguesa" onclick="toggleUbicaciones()">☰</div>
    </div>
    <div id="map"></div>
    <div id="ubicaciones" class="ubicaciones"></div>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
    <script>
        var originalMarkers = []; // Variable para almacenar los marcadores originales
        var map = L.map('map').setView([40.416775, -3.703790], 10); // Coordenadas centradas en España

        // Añadir un mapa base de OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Definir íconos personalizados
        var parkingIcon = L.icon({
            iconUrl: './img/parkinM.svg', // Reemplaza con la ruta real a tu ícono de parking
            iconSize: [38, 38], // tamaño del ícono
            iconAnchor: [19, 38], // punto del ícono que se corresponderá con la ubicación del marcador
            popupAnchor: [0, -38] // punto desde el que se abrirá el popup en relación al iconAnchor
        });

        var currentLocationIcon = L.icon({
            iconUrl: './img/yo.svg', // Reemplaza con la ruta real a tu ícono de ubicación actual
            iconSize: [38, 38], // tamaño del ícono
            iconAnchor: [19, 38], // punto del ícono que se corresponderá con la ubicación del marcador
            popupAnchor: [0, -38] // punto desde el que se abrirá el popup en relación al iconAnchor
        });

        var currentLocationObjetivo = L.icon({
            iconUrl: './img/ubiEn.png', // Reemplaza con la ruta real a tu ícono de ubicación actual
            iconSize: [38, 38], // tamaño del ícono
            iconAnchor: [19, 38], // punto del ícono que se corresponderá con la ubicación del marcador
            popupAnchor: [0, -38] // punto desde el que se abrirá el popup en relación al iconAnchor
        });

        // function showCoordinatesOnMap() {
        //     var xhr = new XMLHttpRequest();
        //     xhr.open('GET', '/get-coordinates');
        //     xhr.onload = function() {
        //         if (xhr.status === 200) {
        //             var coordinates = JSON.parse(xhr.responseText);
        //             var latitude = coordinates.latitude;
        //             var longitude = coordinates.longitude;

        //             // Mostrar las coordenadas en el mapa
        //             var map = L.map('map').setView([latitude, longitude], 12);
        //             L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        //                 attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        //             }).addTo(map);
        //             L.marker([latitude, longitude]).addTo(map)
        //                 .bindPopup('Latitud: ' + latitude + '<br>Longitud: ' + longitude)
        //                 .openPopup();
        //         } else {
        //             console.error('Error al obtener las coordenadas:', xhr.statusText);
        //         }
        //     };
        //     xhr.onerror = function() {
        //         console.error('Error de red al obtener las coordenadas');
        //     };
        //     xhr.send();
        // }

    // // Coordenadas del punto existente
    // var existingPoint = L.latLng(40.7128, -74.0060); // Ejemplo: Nueva York

    var routingControl; // Variable para almacenar la referencia al control de enrutamiento

    function showCurrentLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var lat = position.coords.latitude;
            var lng = position.coords.longitude;

            // Añadir un marcador en la ubicación actual
            var currentLocationMarker = L.marker([lat, lng], { icon: currentLocationIcon }).addTo(map)
                .openPopup();
            
            // Centrar el mapa y hacer zoom solo en la ubicación actual
            map.setView([lat, lng], 10);

            // Llamar a la función com para dibujar la ruta a partir de la ubicación actual
            updateRoute(lat, lng);

        }, function(error) {
            console.error('Error al obtener la ubicación: ' + error.message);
        });
    } else {
        alert('La geolocalización no está soportada por este navegador.');
    }
}


    function updateRoute(lat, lng) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '/menCor2');
        xhr.onload = function() {
            if (xhr.status === 200) {
                var coordinates = JSON.parse(xhr.responseText);
                var destinationLat = coordinates.latitudC;
                var destinationLng = coordinates.longitudC;

                var destinationPoint = L.latLng(destinationLat, destinationLng);

                // Si ya hay un control de enrutamiento, actualizar los puntos de inicio y destino
                if (routingControl) {
                    routingControl.setWaypoints([
                        L.latLng(lat, lng),
                        destinationPoint
                    ]);
                } else {
                    // Si no hay un control de enrutamiento, crear uno nuevo
                    routingControl = L.Routing.control({
                        waypoints: [
                            L.latLng(lat, lng),
                            destinationPoint
                        ],
                        createMarker: function(i, waypoint) {
                            // Crear marcador con la clase currentLocationObjetivo para el destino
                            if (i === 1) {
                                return L.marker(waypoint.latLng, { icon: currentLocationObjetivo });
                            }
                        },
                        routeWhileDragging: false,
                        addWaypoints: false,
                        draggableWaypoints: false,
                        fitSelectedRoutes: true, // Ajustar el mapa para mostrar la ruta completa
                        lineOptions: {
                            styles: [{ color: 'blue', opacity: 0.6, weight: 4 }]
                        }
                    }).addTo(map);
                }

            } else {
                console.error('Error al obtener las coordenadas:', xhr.responseText);
            }
        };
        xhr.onerror = function() {
            console.error('Error de red al obtener las coordenadas');
        };
        xhr.send();
    }

    // Llamar a la función showCurrentLocation() cada 5 segundos
    setInterval(showCurrentLocation, 5000);



        // Realizar una solicitud AJAX para obtener los datos de las plazas de aparcamiento
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '/mapaT');
        xhr.onload = function() {
            if (xhr.status === 200) {
                var plazas = JSON.parse(xhr.responseText);

                var ubicacionesDiv = document.getElementById('ubicaciones');
                var ubicacionesList = document.createElement('ul');

                plazas.forEach(function(plaza) {
                    if (plaza.latitud && plaza.longitud) {
                        var marker = L.marker([plaza.latitud, plaza.longitud], { icon: parkingIcon }).addTo(map)
                            .bindPopup('<b>' + plaza.nombre + '</b>')
                            .on('click', function(e) {
                                map.flyTo([plaza.latitud, plaza.longitud], 16, {
                                    duration: 2 // Duración de la animación en segundos
                                });
                            });

                        originalMarkers.push(marker); // Almacenar el marcador original

                        var ubicacionItem = document.createElement('li');
                        ubicacionItem.textContent = plaza.nombre;
                        ubicacionItem.addEventListener('click', function() {
                            map.flyTo([plaza.latitud, plaza.longitud], 16, {
                                duration: 2 // Duración de la animación en segundos
                            });
                        });
                        ubicacionesList.appendChild(ubicacionItem);
                    }
                });

                ubicacionesDiv.innerHTML = '';
                ubicacionesDiv.appendChild(ubicacionesList);
            } else {
                console.error('Error al cargar los datos de las plazas de aparcamiento');
            }
        };
        xhr.send();

        function toggleUbicaciones() {
            var ubicaciones = document.getElementById('ubicaciones');
            if (ubicaciones.classList.contains("mostrar")) {
                document.getElementById('ubicaciones').style.display = "block";
                ubicaciones.classList.remove("mostrar");
            } else {
                document.getElementById('ubicaciones').style.display = "none";
                ubicaciones.classList.add("mostrar");
            }
        }

        function searchLocation() {
            var query = document.querySelector('input[type="search"]').value.toLowerCase();

            // Mostrar u ocultar los marcadores según la búsqueda
            originalMarkers.forEach(function(marker) {
                var nombre = marker.getPopup().getContent().toLowerCase();

                if (nombre.includes(query)) {
                    marker.addTo(map);
                } else {
                    map.removeLayer(marker);
                }
            });
        }

        // Asignar la función searchLocation al evento input del campo de búsqueda
        document.querySelector('input[type="search"]').addEventListener('input', searchLocation);

        // Llamar a la función para mostrar la ubicación actual
        showCurrentLocation();
        // Llamar a la función showCurrentLocation() cada 5 segundos
        // setInterval(showCurrentLocation, 5000);
    </script>
</body>
</html>
