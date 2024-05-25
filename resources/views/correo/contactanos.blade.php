<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Contactanos</title>
    {{-- FUENTE --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap" rel="stylesheet">
</head>

<body>
    <h1>MyControlPark</h1>
    <p>Hola Soy: {{ $nom_cliente }} {{ $apellidos }} </p>
    <p>Mi número de telefono es: {{ $prefijo }} {{ $num_telf }}</p>
    <p>Mi email es: {{ $email }}.</p>
    <p>{{ $mensaje }}.</p>
</body>

</html>
