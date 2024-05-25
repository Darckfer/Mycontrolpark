<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Gestión de Empleados</title>
    <!-- Estilos CSS -->
    <link rel="stylesheet" href="{{ asset('css/empleados.css') }}">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <div>
        <!-- Tabla de empleados -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre completo</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @if ($empleados->isEmpty())
                    <tr>
                        <td colspan="5" class="text-center">No se encontraron resultados.</td>
                    </tr>
                @else
                    @foreach ($empleados as $empleado)
                        <tr>
                            <td>{{ $empleado->id }}</td>
                            <td>{{ $empleado->nombre }} {{ $empleado->apellidos }}</td>
                            <td>{{ $empleado->email }}</td>
                            <td>
                                @if ($empleado->id_rol)
                                    {{ \App\Models\tbl_roles::find($empleado->id_rol)->nombre }}
                                @else
                                    Sin rol asignado
                                @endif
                            </td>
                            <td>
                                @if ($empleado->id_rol == 3)
                                    <!-- Botón para editar empleado -->
                                    <button class="btn btn-primary btn-sm btn-edit"
                                        data-product-id="{{ $empleado->id }}"><i class="fas fa-edit"></i>
                                        Editar</button>
                                    <!-- Formulario para eliminar el usuario -->
                                    <form id="frmEliminar{{ $empleado->id }}"
                                        action="{{ route('empleado.destroy', ['id' => $empleado->id]) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="eliminarUsuario({{ $empleado->id }})"
                                            class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i>
                                            Eliminar</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>

        <!-- Paginación -->
        {{ $empleados->links() }}
    </div>

    <!-- Modal de edición de empleado -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Editar empleado</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editForm" action="{{ route('empleado.update', ['id' => $empleado->id]) }}" method="POST">                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="edit_nombre">Nombre:</label>
                            <input type="text" name="nombre" id="edit_nombre" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="edit_apellidos">Apellidos:</label>
                            <input type="text" name="apellidos" id="edit_apellidos" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('.btn-edit').click(function(e) {
                e.preventDefault();
                var empleadoId = $(this).data('product-id');
                $.ajax({
                    url: '/empleado/' + empleadoId + '/edit',
                    type: 'GET',
                    success: function(response) {
                        // Llenar el modal de edición con los datos del empleado
                        $('#edit_nombre').val(response.nombre);
                        $('#edit_apellidos').val(response.apellidos);
                        $('#edit_email').val(response.email);
                        // Mostrar el modal de edición
                        $('#editModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        alert('Error al cargar los datos del usuario.');
                    }
                });
            });
        });

        function eliminarUsuario(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                position: 'top-end',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminarlo'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('frmEliminar' + id).submit();
                }
            });
        }
    </script>
</body>

</html>
