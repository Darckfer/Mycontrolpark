<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\modeloPlazas; // Importar el modelo TblParking
use App\Models\modeloReserva; // Importar el modelo TblParking
use App\Models\tbl_parking; // Importar el modelo TblParking
use App\Models\tbl_usuarios;
use Illuminate\Support\Facades\DB;



class AparcaController extends Controller
{
    // Función para realizar una consulta de conteo de aparcamientos
    public function aparca()
    {
        $id_user =  session('id');
        $parking = session('parking');
        // Obtener todos los mensajes
        $usuario = tbl_usuarios::where('id', $id_user)->first();
        $id_empresa = $usuario->id_empresa;
        $plazas = modeloPlazas::join('tbl_parkings AS parkings', 'parkings.id', '=', 'tbl_plazas.id_parking')
        ->where('parkings.id_empresa', $id_empresa)
        ->where('parkings.id', $parking)
        // ->orderBy('tbl_plazas.id_parking')
        ->select('tbl_plazas.id AS tnt', 'tbl_plazas.nombre AS dina', 'tbl_plazas.id_estado AS mike')
        ->get();


        $plazasData = [];

        foreach ($plazas as $plaza) {
            $plazaData = [
                'id' => $plaza->tnt,
                'nombre' => $plaza->dina,
                'id_estado' => $plaza->mike,
                'parkin' => $plaza->id,
            ];

            $plazasData[] = $plazaData;
        }

        $data = [
            'count' => count($plazasData),
            'plazas' => $plazasData,
        ];

        return response()->json($data);

    }
    public function reserva(Request $request)
    {
        // Validamos los datos recibidos del formulario
        $request->validate([
            'id_plaza' => 'required|exists:tbl_plazas,id',
            'nom_cliente' => 'required|exists:tbl_reservas,id|max:45',
            'firma_imagen' => 'required|image', // Aseguramos que se haya enviado una imagen
        ]);

        // Guardamos la imagen de la firma en la carpeta public/img/firmas
        try {
            $firmaImagen = $request->file('firma_imagen');
            $firmaPath = $firmaImagen->store('img/firmas', 'public');
        } catch (\Exception $e) {
            // Error al guardar la imagen
            return response()->json(['error' => 'Error al guardar la imagen: ' . $e->getMessage()], 500);
        }
        $firmaNombre = pathinfo($firmaPath, PATHINFO_BASENAME);

        $modelo = modeloPlazas::find($request->id_plaza);
        $modelo->update(['id_estado' => 1]);

        $modeloP = DB::table('tbl_reservas')
        ->select('id_plaza')
        ->where('id', $request->nom_cliente)
        ->first();
            
        $modeloP->id_plaza;
        $modelo = modeloPlazas::find($modeloP->id_plaza);
        $modelo->update(['id_estado' => 2]);
        
        $idtrabajador = session('id');
        // Creamos una nueva instancia de TblReserva con los datos del formulario
        $reserva = modeloReserva::where('id', $request->nom_cliente)->first();
        $reserva->id_trabajador = $idtrabajador;
        $reserva->id_plaza = $request->id_plaza;
        $reserva->firma_entrada = $firmaNombre;
        $reserva->save();
        // Guardamos la reserva en la base de datos
        try {
            $reserva->save();
        } catch (\Exception $e) {
            // Error al guardar la reserva en la base de datos
            // Puedes manejar este error según tus necesidades, por ejemplo, eliminando la imagen guardada anteriormente
            return response()->json(['error' => 'Error al guardar la reserva: ' . $e->getMessage()], 500);
        }

        // Retornamos una respuesta adecuada, por ejemplo, un mensaje de éxito
        // return response()->json(['message' => 'Reserva realizada correctamente'], 200);
        echo "Reserva realizada correctamente";
    }
    public function mapaT(Request $request) {

        $id_user = session('id');
        // Obtener todos los mensajes
        $usuario = tbl_usuarios::where('id', $id_user)->first();

        $id_empresa = $usuario->id_empresa;
        $plazas = tbl_parking::where('id_empresa', $id_empresa)->get();

        // Devuelve los datos como JSON
        header('Content-Type: application/json');
        echo json_encode($plazas);
    }
}
