<?php

namespace App\Http\Controllers;

use App\Models\tbl_plazas;
use App\Models\tbl_parking;
use App\Models\tbl_estados;
use App\Models\tbl_empresas;

use Illuminate\Http\Request;

class MapaGestorController extends Controller
{
    /* Función para mostrar los parkings en el mapa */
    public function index(Request $request)
    {
        // Obtén la empresa del usuario actual
        $idEmpresa = $request->session()->get('empresa'); // o $request->session()->get('id_empresa')
        
        // Obtén los parkings que pertenecen a esta empresa
        $parkings = tbl_parking::with(['plazas', 'empresa'])
            ->where('id_empresa', $idEmpresa)
            ->get();
            
        $plazas = tbl_plazas::all();
        $estados = tbl_estados::all();
        $empresas = tbl_empresas::all();
    
        return view('gestion.mapa', compact('parkings', 'plazas', 'estados', 'empresas'));
    }
    
    public function store(Request $request) 
    {
        // Validación de entrada
        $validatedData = $request->validate([
            'nombre' => 'required|string|max:255',
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
            'empresa_id' => 'required|exists:tbl_empresas,id',
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'latitud.required' => 'La latitud es obligatoria.',
            'longitud.required' => 'La longitud es obligatoria.',
            'empresa_id.required' => 'La empresa es obligatoria.',
            'empresa_id.exists' => 'La empresa no existe en la base de datos.',
        ]);
    
        // Verificar duplicados basados en el nombre y la empresa
        $nombre = $validatedData['nombre'];
        $empresa_id = $validatedData['empresa_id'];
        
        // Verificar si el parking con el mismo nombre ya existe en la misma empresa
        $parkingExists = tbl_parking::where('nombre', $nombre)
            ->where('id_empresa', $empresa_id)
            ->exists();
        
        if ($parkingExists) {
            // Devuelve un error si ya existe un parking con ese nombre en esa empresa
            return redirect()->back()
                ->withInput()
                ->with('error', 'El nombre del parking ya está registrado para esta empresa.');
        }
    
        // Crear el nuevo parking
        $parking = new tbl_parking();
        $parking->nombre = $nombre;
        $parking->latitud = $validatedData['latitud'];
        $parking->longitud = $validatedData['longitud'];
        $parking->id_empresa = $empresa_id;
    
        // Guardar el nuevo parking
        $parking->save();
    
        return redirect()->route('mapa')->with('success', 'Parking registrado exitosamente.');
    }
        public function destroy($id)
    {
        try {
            // Buscar y eliminar el parking por su ID
            $parking = tbl_parking::findOrFail($id);
            $parking->delete();
            return redirect()->route('mapa')->with('success', 'Parking eliminado exitosamente.');
        } 
        catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el parking: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id) {
        try {
            $parking = tbl_parking::with(['empresa'])->findOrFail($id);  // Ensure it includes related data
            return response()->json($parking);
        } 
        
        catch (\Exception $e) {
            return response()->json(['error' => 'Parking not found'], 404);
        }
    }    

    public function update(Request $request, $id) {
        // Validación de campos
        $request->validate([
            'nombre' => 'required|string|max:255',
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
            'empresa' => 'required|exists:tbl_empresas,id',
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'latitud.required' => 'La latitud es obligatoria.',
            'longitud.required' => 'La longitud es obligatoria.',
            'empresa.required' => 'La empresa es obligatoria.',
        ]);
    
        try {
            // Buscar y actualizar el parking
            $parking = tbl_parking::findOrFail($id);
            $parking->nombre = $request->nombre;
            $parking->latitud = $request->latitud;
            $parking->longitud = $request->longitud;
            $parking->id_empresa = $request->empresa;
    
            // Guardar los cambios
            $parking->save();
    
            return redirect()->route('mapa')->with('success', 'Parking actualizado exitosamente.');
        } 
        
        catch (\Exception $e) {
            return redirect()->route('mapa')->with('error', 'Error al actualizar el parking: ' . $e->getMessage());
        }
    }

    public function filtrarParkings(Request $request) {
        $nombre = $request->get("nombre");
        $empresa = $request->get("empresa");
    
        $query = tbl_parking::query();
    
        if ($nombre) {
            $query->where("nombre", "LIKE", "%" + $nombre + "%");
        }
    
        if ($empresa) {
            $query->where("id_empresa", $empresa);
        }
    
        $parkings = $query->get();
    
        return response()->json([
            "parkings" => $parkings,
        ]);
    }
}
