<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tbl_fotos;
class enviarFotos extends Controller
{
    public function subirImagenes(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'id_reserva' => 'required|exists:tbl_reservas,id',
            'ruta' => 'required|string',
        ]);

        // Crear una nueva foto
        $foto = tbl_fotos::create([
            'id_reserva' => $request->id_reserva,
            'ruta' => $request->ruta,
        ]);

        // Devolver una respuesta
        return response()->json(['message' => 'Foto creada con éxito', 'foto' => $foto], 201);
    }
}
