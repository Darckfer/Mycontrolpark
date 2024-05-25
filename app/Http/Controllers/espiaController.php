<?php

namespace App\Http\Controllers;
use App\Models\modeloRegistros;
use App\Models\modeloReserva;
use App\Models\tbl_parking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class espiaController extends Controller
{
    public function espia(Request $request)
    {
        if (session('acciones')=='entrada'){
            // Validar los datos del formulario (si es necesario)
            $request->validate([
                'latitud' => 'required',
                'longitud' => 'required',
            ]);

            // Obtener las coordenadas del usuario del formulario
            $latitudUsuario = $request->latitud;
            $longitudUsuario = $request->longitud;

            // Calcular la distancia entre las coordenadas del usuario y las del estacionamiento

            // Si la distancia es menor o igual a 500 metros, detener el intervalo
            $coordenada = new modeloRegistros();
            $coordenada->accion = 'Llevar el coche al parkin';
            $coordenada->tipo = 'Ir al parkin';
            $coordenada->id_usuario = session('id'); // Suponiendo que el ID de usuario es 1
            $coordenada->latitud = $latitudUsuario;
            $coordenada->longitud = $longitudUsuario;
            $coordenada->id_reserva = session('codigoReserva');
            $coordenada->fecha_creacion = DB::raw('current_timestamp()');
            $coordenada->save();

            return response()->json(['message' => 'Coordenadas guardadas correctamente'], 200);
        }
        elseif ((session('acciones')=='salida')){
            // Validar los datos del formulario (si es necesario)
            $request->validate([
                'latitud' => 'required',
                'longitud' => 'required',
            ]);

            // Obtener las coordenadas del usuario del formulario
            $latitudUsuario = $request->latitud;
            $longitudUsuario = $request->longitud;

            // Calcular la distancia entre las coordenadas del usuario y las del estacionamiento

            // Si la distancia es menor o igual a 500 metros, detener el intervalo
            $coordenada = new modeloRegistros();
            $coordenada->accion = 'Llevar el coche al clinte';
            $coordenada->tipo = 'Ir hacia el cliente';
            $coordenada->id_usuario = session('id'); // Suponiendo que el ID de usuario es 1
            $coordenada->latitud = $latitudUsuario;
            $coordenada->longitud = $longitudUsuario;
            $coordenada->id_reserva = session('codigoReserva');
            $coordenada->fecha_creacion = DB::raw('current_timestamp()');
            $coordenada->save();

            return response()->json(['message' => 'Coordenadas guardadas correctamente'], 200);
        }
    }
    public function espia2(Request $request)
    {
        if ((session('acciones')=='entrada')){
            // Validar los datos del formulario (si es necesario)
            $request->validate([
                'latitud' => 'required',
                'longitud' => 'required',
            ]);

            // Obtener las coordenadas del usuario del formulario
            $latitudUsuario = $request->latitud;
            $longitudUsuario = $request->longitud;

            // Calcular la distancia entre las coordenadas del usuario y las del estacionamiento

            // Si la distancia es menor o igual a 500 metros, detener el intervalo
            $coordenada = new modeloRegistros();
            $coordenada->accion = 'Llevar el coche al parkin';
            $coordenada->tipo = 'Aparcar coche';
            $coordenada->id_usuario = session('id'); // Suponiendo que el ID de usuario es 1
            $coordenada->latitud = $latitudUsuario;
            $coordenada->longitud = $longitudUsuario;
            $coordenada->id_reserva = session('codigoReserva');
            $coordenada->fecha_creacion = DB::raw('current_timestamp()');
            $coordenada->save();

            return response()->json(['message' => 'Coordenadas guardadas correctamente'], 200);
        }
        elseif ((session('acciones')=='salida')){
            // Validar los datos del formulario (si es necesario)
            $request->validate([
                'latitud' => 'required',
                'longitud' => 'required',
            ]);

            // Obtener las coordenadas del usuario del formulario
            $latitudUsuario = $request->latitud;
            $longitudUsuario = $request->longitud;

            // Calcular la distancia entre las coordenadas del usuario y las del estacionamiento

            // Si la distancia es menor o igual a 500 metros, detener el intervalo
            $coordenada = new modeloRegistros();
            $coordenada->accion = 'Ir hacia el cliente';
            $coordenada->tipo = 'Aparcar coche';
            $coordenada->id_usuario = session('id'); // Suponiendo que el ID de usuario es 1
            $coordenada->latitud = $latitudUsuario;
            $coordenada->longitud = $longitudUsuario;
            $coordenada->id_reserva = session('codigoReserva');
            $coordenada->fecha_creacion = DB::raw('current_timestamp()');
            $coordenada->save();

            return response()->json(['message' => 'Coordenadas guardadas correctamente'], 200);
        }
    }
    public function notaR(Request $request){
        $request->validate([
            'idR' => 'required|exists:tbl_reservas,id',
            'notas' => 'nullable|string',
        ]);

        try {
            // Encuentra la reserva
            $reserva = modeloReserva::findOrFail($request->idR);

            // Actualiza las notas
            $reserva->notas = $request->notas;
            $reserva->save();

            // Devuelve una respuesta exitosa
            return response()->json(['message' => 'Notas actualizadas correctamente'], 200);
        } catch (\Exception $e) {
            // Maneja cualquier error y devuelve una respuesta de error
            return response()->json(['error' => 'Error al actualizar las notas de la reserva'], 500);
        }
    }
}
