
$(document).ready(function() {
    $(document).ready(function() {
        // Manejar el evento de cambio en el select de perPage
        $('#perPage').on('change', function() {
            $('#filterForm').submit(); // Enviar el formulario cuando se cambie el número de elementos por página
        });
    
        // Manejar el evento de cambio en los filtros de búsqueda y rol
        $('#search, #rol').on('change', function() {
            $('#filterForm').submit(); // Enviar el formulario cuando cambie algún filtro
        });
    
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
    
    // Mostrar el modal de registro al hacer clic en
    $('#abrirModal').click(function() {
        $('#registerModal').modal('show');
    });

    $(document).ready(function() {
        // Manejar el evento keyup en el campo de búsqueda
        $('#search').on('keyup', function() {
            $('#filterForm').submit(); // Enviar el formulario cuando se suelta una tecla en el campo de búsqueda
        });
    
        // Manejar el evento change en el select de rol
        $('#rol').on('change', function() {
            $('#filterForm').submit(); // Enviar el formulario cuando se cambia el valor del select de rol
        });
    });    
});
