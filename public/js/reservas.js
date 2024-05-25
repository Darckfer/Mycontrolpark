
ListarEmpresas('');

function ListarEmpresas(nombre) {
    var expirados = document.getElementById('expirados');
    var activos = document.getElementById('activos');
    var nuevos = document.getElementById('nuevos');

    var formdata = new FormData();
    var csrfToken = document.querySelector('meta[name="csrf_token"]').getAttribute('content');
    formdata.append('_token', csrfToken);
    formdata.append('nombre', nombre);

    var ajax = new XMLHttpRequest();
    ajax.open('POST', '/listarreservas');
    ajax.onload = function () {
        if (ajax.status == 200) {
            var json = JSON.parse(ajax.responseText);
            var reservas = json.reservas;
            var usuarios = json.usuarios;
            var parkings = json.parkings;
            var plazas = json.plazas;




            // console.log(parkings)

            // Almacenar la info
            let DatosAnteriores = '';
            let DatosActuales = '';
            let DatosPosteriores = '';

            var fechaActualIni = new Date();
            fechaActualIni.setHours(0);
            fechaActualIni.setMinutes(0);
            fechaActualIni.setSeconds(0);

            var fechaActualFin = new Date();
            fechaActualFin.setHours(23);
            fechaActualFin.setMinutes(60);
            fechaActualFin.setSeconds(0);


            reservas.forEach(function (reserva) {
                var strexpirados = "";
                var stractivos = "";
                var strnuevos = "";

                var plaza = reserva.plaza ? reserva.plaza : 'No escogido';
                var parking = reserva.parking ? reserva.parking : 'No escogido';
                var firma_entrada = reserva.firma_entrada ? reserva.firma_entrada : 'No firmado';
                var firma_salida = reserva.firma_salida ? reserva.firma_salida : 'No firmado';

                let fechaEntrada = new Date(reserva.fecha_entrada);
                let fechaSalida = new Date(reserva.fecha_salida);
                if (fechaEntrada < fechaActualIni && fechaSalida < fechaActualFin) {
                    strexpirados += '<div style="border: 1px solid #ccc; padding: 3%; margin-bottom: 20px; background-color: #f9f9f9;">';
                    strexpirados += "<h5 style='margin: 0; text-align:center;'><input style='border:none; background-color: transparent'  type='text' id='cliente_" + reserva.id + "' name='cliente' value='" + reserva.nom_cliente + "' readonly ondblclick='quitarReadOnly(this, \"" + reserva.nom_cliente + "\")' onchange='activarEdicion(this, \"" + reserva.id + "\")'></h5>";
                    strexpirados += "<td><strong>Trabajador: </strong><select name='rol' id='rol_" + reserva.id + "' class='rol' onchange='activarEdicion(this, \"" + reserva.id + "\")'>";
                    strexpirados += "<option value='0'>Sin asignar</option>";
                    usuarios.forEach(function (usuario) {
                        strexpirados += "<option value='" + usuario.id + "'";
                        if (reserva.id_trabajador == usuario.id) {
                            strexpirados += " selected";
                        }
                        strexpirados += ">" + usuario.nombre + "</option>";

                    });
                    strexpirados += "</select></td>";

                    strexpirados += "<br><strong>Parking: </strong><select>";
                    // console.log(reserva)
                    parkings.forEach(function (parking) {
                        strexpirados += "<option value='" + parking.id + "'";
                        if (reserva.parking == parking.nombre) {
                            strexpirados += " selected";
                        }
                        strexpirados += ">" + parking.nombre + "</option>";
                    });
                    strexpirados += "</select>";

                    strexpirados += "<br><strong>Plazas: </strong><select>";
                    for (const parkingId in plazas) {
                        parkings.forEach(function (parking) {
                            if (parking.id == parkingId) {
                                const parkingsPlaza = plazas[parkingId];
                                parkingsPlaza.forEach(function (parkingSpot) {
                                    if (parking.id === parkingSpot.id_parking) {
                                        console.log(parking.id);
                                        strexpirados += "<option value='" + parkingSpot.id + "'";
                                        if (reserva.plaza === parkingSpot.nombre) {
                                            strexpirados += " selected";
                                        }
                                        strexpirados += ">" + parkingSpot.nombre + "</option>";
                                    }
                                });
                            }
                        });
                    }
                    strexpirados += "</select>";

                    strexpirados += "<p style='margin: 0;'><strong>Plaza: </strong> <input style='border:none; background-color: transparent'  type='text' id='plaza_" + reserva.id + "' name='plaza' value='" + plaza + "' readonly ondblclick='quitarReadOnly(this, \"" + reserva.plaza + "\")' onchange='activarEdicion(this, \"" + reserva.id + "\")'></p>";
                    strexpirados += "<p style='margin: 0;'><strong>Matrícula: </strong> <input style='border:none; background-color: transparent'  type='text' id='matricula_" + reserva.id + "' name='matricula' value='" + reserva.matricula + "' readonly ondblclick='quitarReadOnly(this, \"" + reserva.matricula + "\")' onchange='activarEdicion(this, \"" + reserva.id + "\")'></p>";
                    strexpirados += "<p style='margin: 0;'><strong>Marca: </strong> <input style='border:none; background-color: transparent'  type='text' id='marca_" + reserva.id + "' name='marca' value='" + reserva.marca + "' readonly ondblclick='quitarReadOnly(this, \"" + reserva.marca + "\")' onchange='activarEdicion(this, \"" + reserva.id + "\")'></p>";
                    strexpirados += "<p style='margin: 0;'><strong>Modelo: </strong> <input style='border:none; background-color: transparent'  type='text' id='modelo_" + reserva.id + "' name='modelo' value='" + reserva.modelo + "' readonly ondblclick='quitarReadOnly(this, \"" + reserva.modelo + "\")' onchange='activarEdicion(this, \"" + reserva.id + "\")'></p>";
                    strexpirados += "<p style='margin: 0;'><strong>Color: </strong> <input style='border:none; background-color: transparent'  type='text' id='color_" + reserva.id + "' name='color' value='" + reserva.color + "' readonly ondblclick='quitarReadOnly(this, \"" + reserva.color + "\")' onchange='activarEdicion(this, \"" + reserva.id + "\")'></p>";

                    strexpirados += '<p style="margin: 0;"><strong>prefijo:</strong><select id="prefijo">';
                    strexpirados += '</select></p>';

                    strexpirados += "<p style='margin: 0;'><strong>Contacto: </strong> <input style='border:none; background-color: transparent'  type='text' id='num_telf_" + reserva.id + "' name='num_telf' value='" + reserva.num_telf + "' readonly ondblclick='quitarReadOnly(this, \"" + reserva.num_telf + "\")' onchange='activarEdicion(this, \"" + reserva.id + "\")'></p>";
                    strexpirados += "<p style='margin: 0;'><strong>Email: </strong> <input style='border:none; background-color: transparent'  type='text' id='email_" + reserva.id + "' name='email' value='" + reserva.email + "' readonly ondblclick='quitarReadOnly(this, \"" + reserva.email + "\")' onchange='activarEdicion(this, \"" + reserva.id + "\")'></p>";
                    strexpirados += "<p style='margin: 0;'><strong>Punto recogida: </strong> <input style='border:none; background-color: transparent'  type='text' id='ubi_entrada_" + reserva.id + "' name='ubi_entrada' value='" + reserva.ubicacion_entrada + "' readonly ondblclick='quitarReadOnly(this, \"" + reserva.ubicacion_entrada + "\")' onchange='activarEdicion(this, \"" + reserva.id + "\")'></p>";
                    strexpirados += "<p style='margin: 0;'><strong>Punto entrega: </strong> <input style='border:none; background-color: transparent'  type='text' id='ubi_salida_" + reserva.id + "' name='ubi_salida' value='" + reserva.ubicacion_salida + "' readonly ondblclick='quitarReadOnly(this, \"" + reserva.ubicacion_salida + "\")' onchange='activarEdicion(this, \"" + reserva.id + "\")'></p>";
                    strexpirados += "<p style='margin: 0;'><strong>Fecha entrada: </strong> <input style='border:none; background-color: transparent'  type='text' id='fecha_entrada_" + reserva.id + "' name='fecha_entrada' value='" + reserva.fecha_entrada + "' readonly ondblclick='quitarReadOnly(this, \"" + reserva.fecha_entrada + "\")' onchange='activarEdicion(this, \"" + reserva.id + "\")'></p>";
                    strexpirados += '<p style="margin: 0;"><strong>Firma entrada: </strong>' + firma_entrada + '</p>';
                    strexpirados += '<p style="margin: 0;"><strong>Fecha entrega: </strong> <input style="border:none; background-color: transparent"  type="text" id="fecha_salida_' + reserva.id + '" name="fecha_salida" value="' + reserva.fecha_salida + '"></p>';
                    strexpirados += '<p style="margin: 0;"><strong>Firma salida: </strong>' + firma_salida + '</p>';
                    strexpirados += '<button><input type="button" id="registrar_' + reserva.id + '" class="btn btn-danger" onclick="CancelarReserva(' + reserva.id + ')" value="Cancelar"></button>';
                    strexpirados += '</div>';
                }
                else if (fechaEntrada > fechaActualFin && fechaSalida >= fechaActualIni) {
                    // fechaActualIni  Fri May 24 2024 00:00:00 GMT+0200
                    // fechaEntrada    Fri May 24 2024 01:14:00 GMT+0200

                    // fechaActualFin  Sat May 25 2024 00:00:00 GMT+0200
                    // fechaSalida     Sat May 25 2024 18:14:00 GMT+0200

                    strnuevos += '<div style="border: 1px solid #ccc; padding: 3%; margin-bottom: 20px; background-color: #f9f9f9;">';
                    strnuevos += '<h5 style="margin: 0; text-align:center;"><input style="border:none; background-color: transparent"  type="text" id="cliente_' + reserva.id + '" name="cliente" value="' + reserva.nom_cliente + '"></h5>';
                    strnuevos += "<td><strong>Trabajador:asdas </strong><select name='rol' id='rol_" + reserva.id + "' class='rol' onchange='activarEdicion(this, \"" + reserva.id + "\")'>";
                    strnuevos += "<option value='0'>Sin asignar</option>";
                    usuarios.forEach(function (usuario) {
                        strnuevos += "<option value='" + usuario.id + "'";
                        if (reserva.id_trabajador == usuario.id) {
                            strnuevos += " selected";
                        }
                        strnuevos += ">" + usuario.nombre + "</option>";

                    });
                    strnuevos += "</select></td>";
                    strnuevos += '<p style="margin: 0;"><strong>Plaza: </strong> <input style="border:none; background-color: transparent"  type="text" id="plaza_' + reserva.id + '" name="plaza" value="' + plaza + '"></p>';
                    strnuevos += '<p style="margin: 0;"><strong>Parking: </strong> <input style="border:none; background-color: transparent"  type="text" id="parking_' + reserva.id + '" name="parking" value="' + parking + '"></p>';
                    strnuevos += '<p style="margin: 0;"><strong>Matrícula: </strong> <input style="border:none; background-color: transparent"  type="text" id="matricula_' + reserva.id + '" name="matricula" value="' + reserva.matricula + '"></p>';
                    strnuevos += '<p style="margin: 0;"><strong>Marca: </strong> <input style="border:none; background-color: transparent"  type="text" id="marca_' + reserva.id + '" name="marca" value="' + reserva.marca + '"></p>';
                    strnuevos += '<p style="margin: 0;"><strong>Modelo: </strong> <input style="border:none; background-color: transparent"  type="text" id="modelo_' + reserva.id + '" name="modelo" value="' + reserva.modelo + '"></p>';
                    strnuevos += '<p style="margin: 0;"><strong>Color: </strong> <input style="border:none; background-color: transparent"  type="text" id="color_' + reserva.id + '" name="color" value="' + reserva.color + '"></p>';
                    strnuevos += '<p style="margin: 0;"><strong>Contacto: </strong> <input style="border:none; background-color: transparent"  type="text" id="num_telf_' + reserva.id + '" name="num_telf" value="' + reserva.num_telf + '"></p>';
                    strnuevos += '<p style="margin: 0;"><strong>Email: </strong> <input style="border:none; background-color: transparent"  type="text" id="email_' + reserva.id + '" name="email" value="' + reserva.email + '"></p>';
                    strnuevos += '<p style="margin: 0;"><strong>Punto recogida: </strong> <input style="border:none; background-color: transparent"  type="text" id="ubi_entrada_' + reserva.id + '" name="ubi_entrada" value="' + reserva.ubicacion_entrada + '"></p>';
                    strnuevos += '<p style="margin: 0;"><strong>Punto entrega: </strong> <input style="border:none; background-color: transparent"  type="text" id="ubi_salida_' + reserva.id + '" name="ubi_salida" value="' + reserva.ubicacion_salida + '"></p>';
                    strnuevos += '<p style="margin: 0;"><strong>Fecha entrada: </strong> <input style="border:none; background-color: transparent"  type="text" id="fecha_entrada_' + reserva.id + '" name="fecha_entrada" value="' + reserva.fecha_entrada + '"></p>';
                    strnuevos += '<p style="margin: 0;"><strong>Firma entrada: </strong>' + firma_entrada + '</p>';
                    strnuevos += '<p style="margin: 0;"><strong>Fecha entrega: </strong> <input style="border:none; background-color: transparent"  type="text" id="fecha_salida_' + reserva.id + '" name="fecha_salida" value="' + reserva.fecha_salida + '"></p>';
                    strnuevos += '<p style="margin: 0;"><strong>Firma salida: </strong>' + firma_salida + '</p>';
                    strnuevos += '<button><input type="button" id="registrar_' + reserva.id + '" class="btn btn-danger" onclick="CancelarReserva(' + reserva.id + ')" value="Cancelar"></button>';
                    strnuevos += '</div>';
                }
                else {

                    stractivos += '<div style="border: 1px solid #ccc; padding: 3%; margin-bottom: 20px; background-color: #f9f9f9;">';
                    stractivos += '<h5 style="margin: 0; text-align:center;"><input style="border:none; background-color: transparent"  type="text" id="cliente_' + reserva.id + '" name="cliente" value="' + reserva.nom_cliente + '' + reserva.id + '"></h5>';
                    stractivos += "<td><strong>Trabajador: </strong><select name='rol' id='rol_" + reserva.id + "' class='rol' onchange='activarEdicion(this, \"" + reserva.id + "\")'>";
                    stractivos += "<option value='0'>Sin asignar</option>";
                    usuarios.forEach(function (usuario) {
                        stractivos += "<option value='" + usuario.id + "'";
                        if (reserva.id_trabajador == usuario.id) {
                            stractivos += " selected";
                        }
                        stractivos += ">" + usuario.nombre + "</option>";

                    });
                    stractivos += "</select></td>";

                    stractivos += '<p style="margin: 0;"><strong>Plaza: </strong> <input style="border:none; background-color: transparent"  type="text" id="plaza_' + reserva.id + '" name="plaza" value="' + plaza + '"></p>';
                    stractivos += '<p style="margin: 0;"><strong>Parking: </strong> <input style="border:none; background-color: transparent"  type="text" id="parking_' + reserva.id + '" name="parking" value="' + parking + '"></p>';
                    stractivos += '<p style="margin: 0;"><strong>Matrícula: </strong> <input style="border:none; background-color: transparent"  type="text" id="matricula_' + reserva.id + '" name="matricula" value="' + reserva.matricula + '"></p>';
                    stractivos += '<p style="margin: 0;"><strong>Marca: </strong> <input style="border:none; background-color: transparent"  type="text" id="marca_' + reserva.id + '" name="marca" value="' + reserva.marca + '"></p>';
                    stractivos += '<p style="margin: 0;"><strong>Modelo: </strong> <input style="border:none; background-color: transparent"  type="text" id="modelo_' + reserva.id + '" name="modelo" value="' + reserva.modelo + '"></p>';
                    stractivos += '<p style="margin: 0;"><strong>Color: </strong> <input style="border:none; background-color: transparent"  type="text" id="color_' + reserva.id + '" name="color" value="' + reserva.color + '"></p>';

                    stractivos += '<p style="margin: 0;"><strong>prefijo:</strong><select id="prefijo">';
                    stractivos += '</select></p>';

                    stractivos += '<p style="margin: 0;"><strong>Contacto: </strong> <input style="border:none; background-color: transparent"  type="text" id="num_telf_' + reserva.id + '" name="num_telf" value="' + reserva.num_telf + '"></p>';
                    stractivos += '<p style="margin: 0;"><strong>Email: </strong> <input style="border:none; background-color: transparent"  type="text" id="email_' + reserva.id + '" name="email" value="' + reserva.email + '"></p>';
                    stractivos += '<p style="margin: 0;"><strong>Punto recogida: </strong> <input style="border:none; background-color: transparent"  type="text" id="ubi_entrada_' + reserva.id + '" name="ubi_entrada" value="' + reserva.ubicacion_entrada + '"></p>';
                    stractivos += '<p style="margin: 0;"><strong>Punto entrega: </strong> <input style="border:none; background-color: transparent"  type="text" id="ubi_salida_' + reserva.id + '" name="ubi_salida" value="' + reserva.ubicacion_salida + '"></p>';
                    stractivos += '<p style="margin: 0;"><strong>Fecha entrada: </strong> <input style="border:none; background-color: transparent"  type="text" id="fecha_entrada_' + reserva.id + '" name="fecha_entrada" value="' + reserva.fecha_entrada + '"></p>';
                    stractivos += '<p style="margin: 0;"><strong>Firma entrada: </strong>' + firma_entrada + '</p>';
                    stractivos += '<p style="margin: 0;"><strong>Fecha entrega: </strong> <input style="border:none; background-color: transparent"  type="text" id="fecha_salida_' + reserva.id + '" name="fecha_salida" value="' + reserva.fecha_salida + '"></p>';
                    stractivos += '<p style="margin: 0;"><strong>Firma salida: </strong>' + firma_salida + '</p>';
                    stractivos += '<button><input type="button" id="registrar_' + reserva.id + '" class="btn btn-danger" onclick="CancelarReserva(' + reserva.id + ')" value="Cancelar"></button>';
                    stractivos += '</div>';
                }
                DatosAnteriores += strexpirados;
                DatosActuales += stractivos;
                DatosPosteriores += strnuevos;

            });

            expirados.innerHTML = DatosAnteriores;
            activos.innerHTML = DatosActuales;
            nuevos.innerHTML = DatosPosteriores;
            prefijo();

        } else {
            expirados.innerText = 'Error';
        }
    }
    ajax.send(formdata);
}

function prefijo() {
    // Prefijo
    var prefijo = document.getElementById("prefijo");
    // Crear una nueva solicitud XMLHttpRequest
    var xhr2 = new XMLHttpRequest();
    xhr2.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            // Parsear la respuesta JSON
            var data = JSON.parse(this.responseText);
            // Iterar sobre los datos y agregar opciones al select
            data.forEach(prefijos => {
                var option = document.createElement("option");
                option.value = prefijos.prefijo;
                option.textContent = prefijos.pais + " (" + prefijos.prefijo + ")";
                prefijo.appendChild(option);

            });
        }
    };
    xhr2.open("GET", "https://644158e3fadc69b8e081cd34.mockapi.io/api/mycontrolpark/prefijo", true);
    xhr2.send();
}

function CancelarReserva(id) {
    var Trabajador = document.getElementById('cliente_' + id).value;
    Swal.fire({
        title: '¿Cancelar la reserva de <br>  ' + Trabajador + '?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí',
        cancelButtonText: 'No'
    }).then((result) => {
        if (result.isConfirmed) {
            var formdata = new FormData();
            formdata.append('id', id);
            var csrfToken = document.querySelector('meta[name="csrf_token"]').getAttribute('content');
            formdata.append('_token', csrfToken);
            var ajax = new XMLHttpRequest();
            ajax.open('POST', '/CancelarReserva');
            ajax.onload = function () {
                if (ajax.status === 200) {
                    if (ajax.responseText == "ok") {
                        ListarEmpresas('');
                        Swal.fire({
                            icon: 'success',
                            title: 'Eliminado',
                            showConfirmButton: false,
                            timer: 1500
                        })
                    }
                }
            }
            ajax.send(formdata);
        }
    })
}