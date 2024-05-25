<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\modeloReserva;
use App\Models\modeloParking;
use App\Models\tbl_parking;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ReservasController extends Controller
{
    public function mostrarR(Request $request) {
        // Obtener el filtro enviado desde la solicitud Ajax
        $filtro = "%" . $request->input('filtro') . "%";
        $filtroFecha = $request->input('filtro_fecha');
        // $parking = "%" . intval($request->input('parking')) . "%";
        $fechaActual = Carbon::now()->toDateTimeString();
    
        // Filtrar las reservas según el filtro
        $fechaHoy = date('Y-m-d');
        $fechaHoyInicio = $fechaHoy . ' 00:00:00';
        $fechaHoyFin = $fechaHoy . ' 23:59:59';

        $reservas = modeloReserva::with('trabajador')
        ->selectRaw('*, DATE_FORMAT(fecha_entrada, "%H:%i") as hora_entrada, DATE_FORMAT(fecha_salida, "%H:%i") as hora_salida')
        ->where(function($query) use ($filtro) {
            $query->where('tbl_reservas.id', 'LIKE', $filtro)
                ->orWhere('nom_cliente', 'LIKE', $filtro)
                ->orWhere('matricula', 'LIKE', $filtro)
                ->orWhere('num_telf', 'LIKE', $filtro)
                ->orWhere('tbl_reservas.email', 'LIKE', $filtro);
        });
        if ($request->input('asignado') == "true") {
            $reservas->where('id_trabajador', '=', session('id'));
        }
    
    if (!empty($request->input('parking'))) {
        $parking = intval($request->input('parking'));
        $reservas->whereHas('plaza', function($query) use ($parking) {
            $query->whereHas('parking', function($query) use ($parking) {
                $query->where('id', $parking);
            });
        });
    }


        // Verificar si el filtro está vacío para aplicar el filtro por fecha
        // Verificar si se proporcionó una fecha de filtro
    if (!empty($filtroFecha)) {
        $reservas->where(function($query) use ($filtroFecha) {
            $query->whereDate('fecha_entrada', $filtroFecha)
                  ->orWhereDate('fecha_salida', $filtroFecha);
        });
    } else {
        // Si no se proporcionó una fecha de filtro, aplicar el filtro por fecha de hoy
        if (empty($request->input('filtro'))) {
            $reservas->where(function($query) use ($fechaHoyInicio, $fechaHoyFin) {
                $query->whereBetween('fecha_entrada', [$fechaHoyInicio, $fechaHoyFin])
                      ->orWhereBetween('fecha_salida', [$fechaHoyInicio, $fechaHoyFin]);
            });
        }
    }

        $reservas = $reservas->orderBy('fecha_entrada', 'asc')
            ->get();
    
        // Devolver la vista con las reservas filtradas
        return response()->json(['reservas' => $reservas]);
    
    }
    public function info(Request $request) {
        $id_res = $request->input('id_r');
        $reserva_cliente = modeloReserva::findOrFail($id_res); // Usamos findOrFail para obtener la reserva o lanzar una excepción si no se encuentra
        return view('vistas.reserva_cliente', compact('reserva_cliente')); // Pasamos los datos a la vista
    }
    public function filtroUbi(Request $request) {
        $parkings = tbl_parking::where('id_empresa', function ($query) {
            $query->select('id_empresa')
                ->from('tbl_usuarios')
                ->where('id', session('id'));
        })->get();
    
        return response()->json(['parkings' => $parkings]);
    }

    public function escogerP(Request $request)
    {
        $request->validate([
            'parking' => 'required|string',
        ]);

        $parking = $request->input('parking');
        
        // Guardar el valor en una variable de sesión correctamente
        session(['parking' => $parking]);

        return response()->json(['message' => 'Parking seleccionado exitosamente.']);
    }
    
    
}