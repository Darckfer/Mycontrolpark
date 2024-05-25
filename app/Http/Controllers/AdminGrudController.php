<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\tbl_usuarios;
use App\Models\tbl_empresas;
use App\Models\tbl_roles;

class AdminGrudController extends Controller
{
    public function listarempresarios(Request $request)
    {
        $roles = tbl_roles::all();
        $empresas = tbl_empresas::all();
        $usuarios = tbl_usuarios::join('tbl_empresas as e', 'tbl_usuarios.id_empresa', '=', 'e.id')
            ->join('tbl_roles as r', 'tbl_usuarios.id_rol', '=', 'r.id')
            ->select('tbl_usuarios.*', 'e.nombre as nom_empresa', 'r.nombre as nom_rol')
            ->where(function ($query) {
                $query->where('tbl_usuarios.id_rol', 1)
                    ->orWhere('tbl_usuarios.id_rol', 2);
            })
            ->orderBy('tbl_usuarios.id_rol', 'asc');
        if ($request->input('nombre')) {
            $nombre = $request->input('nombre');
            $usuarios->where('tbl_usuarios.nombre', 'like', "%$nombre%");
        }
        if ($request->input('rol')) {
            if ($request->input('rol') != "[object KeyboardEvent]") {
                $rol = $request->input('rol');
                $usuarios->where('tbl_usuarios.id_rol', $rol);
            }
        }
        $usuarios = $usuarios->get();
        return response()->json(['usuarios' => $usuarios, 'empresas' => $empresas, 'roles' => $roles]);
    }


    public function admineditar(Request $request)
    {
        $id = $request->input('idp');
        $nombre = $request->input('nombre');
        $apellidos = $request->input('apellidos');
        $email = $request->input('email');
        $rol = $request->input('rol');
        $empresa = $request->input('empresa');

        $resultado = tbl_usuarios::find($id);
        $resultado->nombre = $nombre;
        $resultado->apellidos = $apellidos;
        $resultado->email = $email;
        $resultado->id_rol = $rol;
        $resultado->id_empresa = $empresa;
        $resultado->save();
        echo "ok";
    }


    public function admineliminar(Request $request)
    {
        $ids = $request->input('id');
        if (is_array($ids)) {
            foreach ($ids as $id) {
                $resultado = tbl_usuarios::find($id);
                if ($resultado) {
                    $resultado->delete();
                }
            }
            echo "ok";
        } else {
            $resultado = tbl_usuarios::find($ids);
            $resultado->delete();
            echo "ok";
        }
    }


    public function adminregistrar(Request $request)
    {
        $empresa = session('id_empresa');

        $nombre = $request->input('nombreuser');
        $apellidos = $request->input('apellido');
        $email = $request->input('email');
        $pwdencrip = bcrypt($request->input('pwd'));
        // $pwd = $request->input('pwd');
        $SelecRoles = $request->input('SelecRoles');
        $SelecEmpresa = $request->input('SelecEmpresa');


        $resultado = new tbl_usuarios();
        $resultado->nombre = $nombre;
        $resultado->apellidos = $apellidos;
        $resultado->email = $email;
        $resultado->contrasena = $pwdencrip;
        $resultado->id_rol = $SelecRoles;
        $resultado->id_empresa = $SelecEmpresa;
        $resultado->save();
        echo "ok";
    }
}
