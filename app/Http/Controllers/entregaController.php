<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str; // Importar la clase Str para generar UUID
use App\Models\modeloReserva;
use App\Models\tbl_plazas;

class entregaController extends Controller
{
    public function confirmarEntrega(Request $request)
    {
        $id = session('codigo');
        // Validar y guardar la foto en el servidor
        if ($request->hasFile('firma')) {
            $foto = $request->file('firma');
            $nombreFoto = Str::uuid()->toString() . '.' . $foto->getClientOriginalExtension(); // Generar un UUID único y agregar la extensión original
            $ruta = $foto->storeAs('public/img/firmas', $nombreFoto); // Guardar la foto con el nombre único
            // Actualizar el nombre de la foto en la base de datos
            $idPlazaAnterior = modeloReserva::where('id', $id)->value('id_plaza');
            tbl_plazas::where('id', $idPlazaAnterior)->update(['id_estado' => 2]);

            modeloReserva::where('id', $id)->update([
                'firma_salida' => $nombreFoto // Guardar el nombre único en la base de datos
            ]);
            return response()->json(['bien']);
        }
    }
    public function codigoSal(Request $request)
    {
        $html='';
        // Obtenemos el ID enviado por el formulario
        $id = $request->input('codigo');
        if (!$id) {
            // Si no se encuentra la reserva, devolvemos una respuesta vacía
            return response('mal');
        }
        
        $reserva = modeloReserva::find($id);
        if (!$reserva) {
            // Si no se encuentra la reserva, devolvemos una respuesta vacía
            return response('mal');
        }

        $urlImagen = '';
        if (property_exists($reserva, 'firma_entrada')) {
            $urlImagen = asset("storage/img/firmas/{$reserva->firma_entrada}.png");
        }
        // $html = '<label>Codigo: ' . $reserva->id . '</label>';
        $html .= '<img src="' . $urlImagen . '" alt="Firma de entrada" class="canvas">';
        $html .= '<br><br>';
        $html .= '<button class="azul" onclick="salsa()">Confirmar entrega</button>';
        $html .= '<button class="rojo" onclick="ocultar()">ocultar</button>';

        session(['codigo' => $id]);
        // Devolvemos la respuesta con el HTML
        return response($html);
    }
}


