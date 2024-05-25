<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\tbl_usuarios;
use App\Models\tbl_empresas; // Asegúrate de tener este modelo

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        // Validar las credenciales
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6|max:20',
        ], [
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'El correo electrónico debe ser una dirección válida',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres',
            'password.max' => 'La contraseña no debe tener más de 20 caracteres',
        ]);

        // Buscar usuario por correo electrónico
        $user = tbl_usuarios::where('email', $credentials['email'])->first();

        if ($user && password_verify($credentials['password'], $user->contrasena)) {
            // Obtener el nombre de la empresa
            $empresa = tbl_empresas::find($user->id_empresa);

            // Iniciar sesión con variables de sesión
            $request->session()->put('id', $user->id);
            $request->session()->put('nombre', $user->nombre);
            $request->session()->put('apellidos', $user->apellidos);
            $request->session()->put('email', $user->email);
            $request->session()->put('rol', $user->id_rol);
            $request->session()->put('empresa', $user->id_empresa);
            $request->session()->put('nombre_empresa', $empresa->nombre);

            switch ($user->id_rol) {
                case 1:
                    // Código a ejecutar cuando $user->id_rol es igual a 1
                    return redirect()->route('admin');
                    break;
                case 2:
                    return redirect()->route('gestEmpleados');
                    break;
                case 3:
                    return view('vistas.trabajador');
                    break;
            }
        } else {
            // Si las credenciales son incorrectas, redirigir con mensaje de error
            return redirect()->route('login')->with('error', 'Credenciales incorrectas')->withInput();
        }
    }

    public function logout(Request $request)
    {
        // Limpiar variables de sesión y redirigir a la página de login
        $request->session()->flush();

        return redirect()->route('login')->with('success', 'Sesión cerrada correctamente');
    }
}
