<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="csrf-token" content="{{csrf_token()}}">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Enviar Coordenadas</title>
{{-- FUENTE --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
</head>
<body>
    <label for="id_usuario">ID de Usuario:</label>
    <input type="text" id="id_usuario" name="id_usuario">
    <br>
    <button id="startButton">Obtener Coordenadas y Enviar</button>

<script src="./js/espia.js">
</script>
</body>
</html>
