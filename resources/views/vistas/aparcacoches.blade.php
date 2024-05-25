<?php
use App\Models\modeloPlazas;
use App\Models\tbl_usuarios;
use App\Models\tbl_parking;
use Illuminate\Support\Facades\Session;
$variable_de_sesion = session('id') ?? null;
if ($variable_de_sesion === null) {
  echo "<script>
    window.location.href = '/';
  </script>";
} 
$id_user =  session('id');
$parking = session('parking');
// Obtener todos los mensajes
$usuario = tbl_usuarios::where('id', $id_user)->first();
$id_empresa = $usuario->id_empresa;
$plazas = modeloPlazas::join('tbl_parkings AS parkings', 'parkings.id', '=', 'tbl_plazas.id_parking')
->where('parkings.id_empresa', $id_empresa)
// ->orderBy('tbl_plazas.id_parking')
->select('tbl_plazas.id AS tnt', 'tbl_plazas.nombre AS dina', 'tbl_plazas.id_estado AS mike')
->get();
use Illuminate\Support\Facades\DB;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
    <link rel="stylesheet" href="./css/aparca.css">
    {{-- FUENTE --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
</head>

<body>

    <div class="modal" id="modal">
        <div class="modal-content">
            <input type="hidden" id="estado">
            <div class="form-group">
                <label for="id_plaza">Plaza:</label>
                <select id="id_plaza" name="id_plaza" disabled>
                    <option value="">Selecciona una plaza</option>
                    <?php foreach ($plazas as $plaza): ?>
                    <option value="<?php echo $plaza->tnt; ?>"><?php echo $plaza->dina; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="nom_cliente">Codigo:</label>
                <input type="text" id="nom_cliente" name="nom_cliente">
            </div>
            <div class="form-group">
                <label for="firma">Firma:</label>
                <canvas id="canvas"></canvas>
            </div>
            <div class="form-group">
                <button type="button" onclick="validar()">Reservar</button>
                <button onclick="limpiarCanvas()" style="background-color: orange" type="button">Limpiar</button>
                <button onclick="cerrar()" style="background-color: red" type="button">Cerrar</button>
            </div>
            <br>
            <br>
        </div>
    </div>
    <div id="container">
        <br>
        <a href="/trabajador"><button style="background-color: red">Volver</button></a>
        <br>
        <div>
            <div id="item">
                <?php $parking = tbl_parking::find($parking); ?>
                <h1>{{ $parking->nombre }}</h1>
                <div id="pagination" class="pagination"></div> <!-- Aquí se agregará la paginación -->
            </div>
            <div id="gridContainer"></div>
        </div>
    </div>
</body>

</html>
<script src="./js/aparca.js"></script>
