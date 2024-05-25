<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\tbl_parking;
use Illuminate\Support\Facades\Response;

class ParkingController extends Controller
{
    public function updateLocation(Request $request, $id)
    {
        // Encuentra el parking por ID
        $parking = tbl_parking::find($id);

        // Verifica si el parking existe
        if (!$parking) {
            return Response::json([
                'success' => false,
                'message' => 'Parking no encontrado.',
            ], 404);
        }

        // Valida los datos de entrada
        $validated = $request->validate([
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
        ]);

        // Actualiza las coordenadas del parking
        $parking->latitud = $validated['latitud'];
        $parking->longitud = $validated['longitud'];

        try {
            // Guarda los cambios en la base de datos
            $parking->save();

            return Response::json([
                'success' => true,
                'message' => 'Ubicación actualizada con éxito.',
            ]);
        } catch (Exception $e) {
            return Response::json([
                'success' => false,
                'message' => 'Error al guardar los cambios.',
            ], 500);
        }
    }
}
