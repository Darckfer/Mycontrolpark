<?php

namespace App\Http\Controllers;

use App\Models\tbl_chat;
use App\Models\tbl_usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response;


class ChatController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'id' => 'required',
            // Añade más reglas de validación según tus necesidades
        ]);
        $id_user = session('id');
        $usuario = tbl_usuarios::where('id', $id_user)->first();
        $id_empresa = $usuario->id_empresa;

        $mensajes = tbl_chat::join('tbl_usuarios', 'tbl_usuarios.id', '=', 'tbl_chats.emisor')
                // ->where('tbl_usuarios.id_empresa', $id_empresa)
                ->where('tbl_chats.id', '>', $request->id)
                ->select('tbl_chats.*', 'tbl_chats.id as chat_id') // Renombrando el campo "id" de tbl_chat como "chat_id"
                ->get();
        $htmlMensajes = '';
        foreach ($mensajes as $mensaje) {
            // Condición para determinar la clase CSS del mensaje según el emisor
            if ($mensaje->emisor == $id_user) {
                $htmlMensajes .= '<div class="chatEmi">';
                $htmlMensajes .= '<input type="hidden" name="mensaje_id" value="' . $mensaje->chat_id . '">';
                $htmlMensajes .= '<p>' . $mensaje->mensaje . '</p>';
                $htmlMensajes .= '</div>';
                $htmlMensajes .= '<br>';
            } else {
                $usus = tbl_usuarios::where('id', $mensaje->emisor)->get();
                foreach ($usus as $usu) {}
                $htmlMensajes .= '<div class="chatRec">';
                $htmlMensajes .= '<h2>' . $usu->nombre . '</h2>';
                $htmlMensajes .= '<input type="hidden" name="mensaje_id" value="' . $mensaje->chat_id . '">';
                $htmlMensajes .= '<p>' . $mensaje->mensaje . '</p>';
                $htmlMensajes .= '</div>';
                $htmlMensajes .= '<br>';
            }
        }
        if ($htmlMensajes!=''){
            return $htmlMensajes;
        }
    }

    public function chat2()
    {
        $id_user = session('id');
        $mensajes = tbl_chat::all();

        $mensajes = tbl_chat::join('tbl_usuarios', 'tbl_usuarios.id', '=', 'tbl_chats.emisor')
                ->where('tbl_usuarios.id_empresa', $id_user)
                ->select('tbl_chats.*', 'tbl_chats.id as chat_id') // Renombrando el campo "id" de tbl_chat como "chat_id"
                ->get();

        $htmlMensajes = '';
        foreach ($mensajes as $mensaje) {
            // Condición para determinar la clase CSS del mensaje según el emisor
            if ($mensaje->emisor == $id_user) {
                $htmlMensajes .= '<div class="chatEmi">';
                $htmlMensajes .= '<input type="hidden" name="mensaje_id" value="' . $mensaje->chat_id . '">';
                $htmlMensajes .= '<p>' . $mensaje->mensaje . '</p>';
                $htmlMensajes .= '</div>';
                $htmlMensajes .= '<br>';
            } else {
                $usus = tbl_usuarios::where('id', $mensaje->emisor)->get();
                foreach ($usus as $usu) {}
                $htmlMensajes .= '<div class="chatRec">';
                $htmlMensajes .= '<h2>' . $usu->nombre . '</h2>';
                $htmlMensajes .= '<input type="hidden" name="mensaje_id" value="' . $mensaje->chat_id . '">';
                $htmlMensajes .= '<p>' . $mensaje->mensaje . '</p>';
                $htmlMensajes .= '</div>';
                $htmlMensajes .= '<br>';
            }
        }
        if ($htmlMensajes!=''){
            return $htmlMensajes;
        }
    }

    public function enviarMen(Request $request){
        // Obtener el ID del emisor de la sesión
        $emisor = session('id');
        // Obtener el ID del receptor del formulario (supongo que está llegando a través de una solicitud)
        $mensaje = $request->input('mensaje');
    
        // Crear un nuevo mensaje en el modelo TblChat
        $mensajeNuevo = new tbl_chat();
        $mensajeNuevo->emisor = $emisor;
        $mensajeNuevo->mensaje = $mensaje;
        $mensajeNuevo->save();
    }

    public function menCor(Request $request)
    {
        // Verificar si se reciben las coordenadas y el token CSRF
        if ($request->has(['lati', 'longi', '_token'])) {
            // Obtener las coordenadas de la solicitud
            $latitud = $request->lati;
            $longitud = $request->longi;
            
            // Guardar las coordenadas en variables de sesión
            $request->session()->put('latitudC', $latitud);
            $request->session()->put('longitudC', $longitud);

            // Respuesta de éxito (opcional)
            return response()->json(['message' => 'Coordenadas guardadas correctamente'], 200);
        }

        // Si falta alguna de las coordenadas o el token CSRF, responder con un error
        return response()->json(['error' => 'Faltan parámetros'], 400);
    }
    public function menCor2(Request $request)
    {
        $latitudC = $request->session()->get('latitudC');
        $longitudC = $request->session()->get('longitudC');

        // Devolver la variable de sesión como JSON
        return response()->json([
            'latitudC' => $latitudC,
            'longitudC' => $longitudC
        ]);

        // Si falta alguna de las coordenadas o el token CSRF, responder con un error
        return response()->json(['error' => 'Faltan parámetros'], 400);
    }
}