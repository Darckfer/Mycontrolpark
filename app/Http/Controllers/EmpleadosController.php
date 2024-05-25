<?php

namespace App\Http\Controllers;

use App\Models\tbl_usuarios;
use App\Models\tbl_empresas;
use App\Models\tbl_roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class EmpleadosController extends Controller
{
    public function index(Request $request) {
        $idEmpresa = $request->session()->get('empresa');
        $perPage = $request->query('perPage', 5);
        $search = $request->query('search', '');
        $rol = $request->query('rol', '');
    
        // Filtrar empleados según la búsqueda y el rol
        $query = tbl_usuarios::where('id_empresa', $idEmpresa);
    
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('nombre', 'like', '%'.$search.'%')
                  ->orWhere('apellidos', 'like', '%'.$search.'%');
            });
        }
    
        if (!empty($rol)) {
            $query->where('id_rol', $rol);
        }
    
        // Contar el total de empleados
        $totalEmpleados = $query->count();
    
        // Obtener empleados con paginación
        $empleados = $query->paginate($perPage);
    
        $roles = tbl_roles::all();
    
        if ($request->ajax()) {
            return view('tablas.tbl_empleados', compact('empleados', 'totalEmpleados'))->render();
        }
    
        return view('gestion.gestEmpleados', compact('empleados', 'roles', 'totalEmpleados', 'perPage', 'search', 'rol'));
    }
        
    public function edit($id) {
        $empleado = tbl_usuarios::findOrFail($id);
        return response()->json($empleado);
    }

    public function update(Request $request, $id) {
        $empleado = tbl_usuarios::findOrFail($id);
    
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
        ]);
    
        $empleado->nombre = $request->input('nombre');
        $empleado->apellidos = $request->input('apellidos');
        $empleado->save();
    
        return redirect()->route('gestEmpleados')->with('success', 'Empleado actualizado correctamente.');
    }
    
    public function store(Request $request) {
        $idEmpresa = $request->session()->get('empresa');
        $nombreEmpresa = tbl_empresas::find($idEmpresa)->nombre; // Reemplaza 'Empresa' con el modelo real de tu empresa
    
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
        ]);
    
        $nombreEmpresaSinEspacios = str_replace(' ', '', $nombreEmpresa);
    
        $empleado = new tbl_usuarios();
        $empleado->nombre = $request->input('nombre');
        $empleado->apellidos = $request->input('apellido');
        $empleado->email = strtolower($request->input('nombre').'.'.$request->input('apellido').'@'.$nombreEmpresaSinEspacios).'.com'; // Elimina los espacios en el nombre de la empresa
        $empleado->contrasena = bcrypt('qweQWE123');
        $empleado->id_rol = 3;
        $empleado->id_empresa = $idEmpresa;
        $empleado->save();
    
        return redirect()->route('gestEmpleados')->with('success', 'Usuario registrado correctamente.');
    }
    
    public function destroy($id) {
        $empleado = tbl_usuarios::findOrFail($id);
        if ($empleado->id_rol == 2) { // Usar operador de comparación (==) en lugar de asignación (=)
            return redirect()->route('gestEmpleados')->with('error', 'No puedes eliminar al gestor.');
        } else {
            $empleado->delete();
            return redirect()->route('gestEmpleados')->with('success', 'Usuario eliminado correctamente.');
        }
    }

    public function buscarEmpleado(Request $request) {
        $query = tbl_usuarios::where('id_empresa', $request->session()->get('empresa'));

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nombre', 'like', '%'.$request->search.'%')
                  ->orWhere('apellidos', 'like', '%'.$request->search.'%'); // Ajuste para buscar también por apellidos
            });
        }

        if ($request->filled('rol')) {
            $query->where('id_rol', $request->rol);
        }

        $empleados = $query->get();

        return Response::json(view('tablas.tbl_empleados', compact('empleados'))->render());
    }
}