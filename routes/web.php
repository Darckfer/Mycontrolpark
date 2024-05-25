<?php

use App\Http\Controllers\AparcaController;
use App\Http\Controllers\chatController;
use App\Http\Controllers\espiaController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\ReservasController;
use App\Http\Controllers\MapaAdminController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\entregaController;
use App\Http\Controllers\ParkingController;
use App\Http\Controllers\MapaGestorController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\ReservasGrudController;
use App\Http\Controllers\EmpleadosController;
use App\Http\Controllers\AdminGrudController;
use App\Http\Controllers\enviarFotos;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Ruta para mostrar la página de inicio de sesión
Route::get('/login', [LoginController::class, 'index'])->name('login');

// Ruta para autenticar al usuario
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.post');

// Ruta para cerrar sesión
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Ruta para la página de admin
Route::get('/mapa', [MapaGestorController::class, 'index'])->name('mapa');

// Rutas para operaciones CRUD de parkings
Route::post('/parking', [MapaGestorController::class, 'store'])->name('parking.post');
Route::get('/parking/{id}', [MapaGestorController::class, 'show'])->name('parking.show');
Route::put('/parking/{id}', [MapaGestorController::class, 'update'])->name('parking.update');
Route::delete('/parking/{id}', [MapaGestorController::class, 'destroy'])->name('parking.destroy');

// Ruta mover parking
Route::post('/parking/update/{id}', [ParkingController::class, 'updateLocation']);

// Ruta página de inicio
Route::get('/', [InicioController::class, 'index'])->name('inicio');


// Rutas Julio
Route::get('/empleados', function () {
    return view('gestion.empleados');
})->name('empleados');

Route::post('/Listarempleados', [EmpresaController::class, 'Listarempleados'])->name('Listarempleados');
Route::post('/registrar', [EmpresaController::class,  'registrar'])->name('registrar');
Route::post('/estado', [EmpresaController::class,  'estado'])->name('estado');
Route::post('/eliminar', [EmpresaController::class, 'eliminar'])->name('eliminar');


// Reservas
Route::get('/reservas', function () {
    return view('gestion.reservas');
})->name('reservas');

Route::post('/listarreservas', [ReservasGrudController::class, 'listarreservas'])->name('listarreservas');




// Formulario reserva
Route::post('/reservaO', [ReservaController::class, 'reservaO'])->name('reservaO');
// Formulario Contactacnos

Route::get('/contactanos', function () {
    return view('vistas.contactanos');
})->name('contactanos');

Route::post('/Contactanos', [ReservaController::class, 'Contactanos'])->name('Contactanos');

/* Trabajador */

// Route::get('/trabajador', [LoginController::class, 'trabajador'])->name('trabajador');
Route::get('/trabajador', function () {
    return view('vistas.trabajador');
});

Route::get('/mapasA', function () {
    return view('vistas.mapaAparca');
});


/* Cliente */
Route::get('/reserva', function () {
    return view('vistas.reserva');
});

Route::get('/devolucion', function () {
    return view('vistas.devolucion');
});

Route::post('/confirmar-entrega', [entregaController::class, 'confirmarEntrega'])->name('reservaO');
Route::post('/codigoSal', [entregaController::class, 'codigoSal'])->name('codigoSal');

Route::post('/reservaO', [ReservaController::class, 'reservaO'])->name('reservaO');

Route::post('/espia', [espiaController::class, 'espia'])->name('espia');
Route::post('/espia2', [espiaController::class, 'espia2'])->name('espia2');
Route::post('/notaR', [espiaController::class, 'notaR'])->name('notaR');

Route::post('/reserva', [AparcaController::class, 'reserva'])->name('reserva');
Route::get('/aparca', [AparcaController::class, 'aparca'])->name('aparca');
Route::get('/mapaT', [AparcaController::class, 'mapaT'])->name('mapaT');

Route::post('/chat', [chatController::class, 'chat'])->name('chat');
Route::get('/chat2', [chatController::class, 'chat2'])->name('chat2');
Route::post('/menCor', [chatController::class, 'menCor'])->name('menCor');
Route::get('/menCor2', [chatController::class, 'menCor2'])->name('menCor2');
Route::post('/enviarMen', [chatController::class, 'enviarMen'])->name('enviarMen');

Route::post('/subirImagenes', [enviarFotos::class, 'subirImagenes'])->name('subirImagenes');

// Route::get('/', function () {return view('reservas');});


Route::post('/mostrar_reservas', [ReservasController::class, 'mostrarR'])->name('mostrarR');
Route::post('/filtroubi', [ReservasController::class, 'filtroUbi'])->name('filtroUbi');
Route::post('/mostrar_reservas_filtro', [ReservasController::class, 'mostrarRFiltro'])->name('mostrarRFiltro');

Route::get('/cambio', function () {
    return view('vistas.aparcacoches');
});

Route::get('/info_res', [ReservasController::class, 'info']);

Route::post('/escogerP', [ReservasController::class, 'escogerP'])->name('escogerP');

Route::post('/confirmar-entrega', [entregaController::class, 'confirmarEntrega'])->name('reservaO');





/* Rutas Iker */
/////// Login
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.post');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// Ruta para la página de admin
/////// Mapa
Route::get('/mapa_admin', [MapaAdminController::class, 'index'])->name('mapa_admin');
Route::post('/parking', [MapaAdminController::class, 'store'])->name('parking.post');
Route::get('/parking/{id}', [MapaAdminController::class, 'show'])->name('parking.show');
Route::put('/parking/{id}', [MapaAdminController::class, 'update'])->name('parking.update');
Route::delete('/parking/{id}', [MapaAdminController::class, 'destroy'])->name('parking.destroy');
// Route::put('/parking/update-location', [MapaAdminController::class, 'updateLocation'])->name('parking.updateLocation');

// Ruta para actualizar la ubicación de un parking
Route::put('/parking/{id}/update-location', [ParkingController::class, 'updateLocation']);


// Empresa 
Route::get('/empresa', function () {
    return view('empresa.empresa');
})->name('empresa');


Route::post('/Listarempleados', [EmpresaController::class, 'Listarempleados'])->name('Listarempleados');
Route::post('/registrar', [EmpresaController::class,  'registrar'])->name('registrar');
Route::post('/estado', [EmpresaController::class,  'estado'])->name('estado');
Route::post('/eliminar', [EmpresaController::class, 'eliminar'])->name('eliminar');


// Admin
Route::get('/admin', function () {
    return view('admin.admin');
})->name('admin');


Route::post('/listarempresarios', [AdminGrudController::class, 'listarempresarios'])->name('listarempresarios');
Route::post('/adminregistrar', [AdminGrudController::class,  'adminregistrar'])->name('adminregistrar');
Route::post('/admineditar', [AdminGrudController::class,  'admineditar'])->name('adminadmineditar');
Route::post('/admineliminar', [AdminGrudController::class, 'admineliminar'])->name('admineliminar');



// Reservas

Route::get('/gestionreservas', function () {
    return view('empresa.gestionreservas');
})->name('gestionreservas');
Route::post('/listarreservas', [ReservasGrudController::class, 'listarreservas'])->name('listarreservas');
Route::post('/CancelarReserva', [ReservasGrudController::class,  'CancelarReserva'])->name('CancelarReserva');




// Chat

Route::get('/chatG', function () {
    return view('vistas.chat');
});

// Info de los registros

/* Gestión empleados prueba Iker */
Route::get('/gestEmpleados', [EmpleadosController::class, 'index'])->name('gestEmpleados'); // Mostrar todos los empleados
Route::post('/empleado/store', [EmpleadosController::class, 'store'])->name('empleado.store'); // Registrar
Route::get('/empleado/{id}/edit', [EmpleadosController::class, 'edit'])->name('empleado.edit'); // Editar un empleado
Route::put('/empleado/update/{id}', [EmpleadosController::class, 'update'])->name('empleado.update'); // Actualizar datos de un empleado
Route::delete('/empleado/destroy/{id}', [EmpleadosController::class, 'destroy'])->name('empleado.destroy'); // Eliminar un empleado
// Route::get('/empleado/buscar', [EmpleadosController::class, 'buscarEmpleado'])->name('empleado.buscar');
