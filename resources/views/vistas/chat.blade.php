<?php
use App\Models\tbl_chat;
use App\Models\tbl_usuarios;
use Illuminate\Support\Facades\Session;

$id = session('id');

if (!isset($id)) {
    header("Location: /");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>Chat</title>
    <link rel="stylesheet" href="./css/chat.css">
    {{-- FUENTE --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="arriba">
        <a href="/trabajador" class="botonNo"><img src="./img/volver.svg" width="40px"></a>
    </div>
    <div class="">
        <div class="solicitudesTabla" id="ta">
            <@php
                    $id_user =  session('id');
                    // Obtener todos los mensajes
                    $usuario = tbl_usuarios::where('id', $id_user)->first();
                    $id_empresa = $usuario->id_empresa;


                    $mensajes = tbl_chat::join('tbl_usuarios', 'tbl_usuarios.id', '=', 'tbl_chats.emisor')
                            ->where('tbl_usuarios.id_empresa', $id_empresa)
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
                    echo $htmlMensajes;
            @endphp
        </div>
        <div class="div-abajo">
            <input type="text" id="mensaje" placeholder="Escribe tu mensaje...">
            <button onclick="men()" class="envio" id="envio">Send</button>
            <img src="./img/ubiEn.png" onclick="cor()" style="width: 4vh">
        </div>
    </div>
</body>
</html>
<script src="./js/chat.js"></script>

