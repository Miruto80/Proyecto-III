// Inicializar formulario cuando el documento esté listo
$(document).ready(function() {
    // Inicializar datepicker en fecha de reserva
    $('#fecha_apartado').val(obtenerFechaActual());

    // Evento para productos
    actualizarEventosProducto();

    // Botón para agregar producto
    $('#agregar_producto').click(function() {
        agregarFilaProducto();
    });

    // Botón para registrar
    $('#registrar').click(function() {
        registrarReserva();
    });

    // Botón para modificar
    $('#btnModificar').click(function() {
        modificarReserva();
    });
    
});

// Función para obtener la fecha actual en formato YYYY-MM-DD
function obtenerFechaActual() {
    const fecha = new Date();
    const año = fecha.getFullYear();
    let mes = fecha.getMonth() + 1;
    let dia = fecha.getDate();

    // Agregar cero si el mes o día es menor a 10
    mes = mes < 10 ? '0' + mes : mes;
    dia = dia < 10 ? '0' + dia : dia;

    return `${año}-${mes}-${dia}`;
}

// Contador para IDs únicos de filas de productos
let contadorFilas = 1;

// Función para agregar una nueva fila de producto
function agregarFilaProducto() {
    const filaId = `fila_producto_${contadorFilas}`;
    const nuevaFila = `
        <tr id="${filaId}">
            <td>
                <select class="form-control producto-select" name="productos[]" required>
                    <option value="">Seleccione un producto</option>
                    ${$('#fila_producto_0 .producto-select').html()}
                </select>
            </td>
            <td>
                <input type="number" class="form-control cantidad-input" name="cantidades[]" value="1" min="1" required>
            </td>
            <td>
                <input type="number" step="0.01" class="form-control precio-input" name="precios_unit[]" value="0.00" min="0" required>
            </td>
            <td>
                <input type="text" class="form-control precio-total" value="0.00" readonly>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm eliminar-fila">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </td>
        </tr>
    `;
    $('#productos_body').append(nuevaFila);
    contadorFilas++;
    actualizarEventosProducto();
}

// Función para actualizar eventos en productos
function actualizarEventosProducto() {
    // Evento para selección de producto
    $('.producto-select').off('change').on('change', function() {
        const productoId = $(this).val();
        if (productoId) {
            // Obtener el precio del atributo data-precio
            const precio = $(this).find('option:selected').data('precio') || 0;
            const fila = $(this).closest('tr');
            fila.find('.precio-input').val(precio);
            calcularTotalFila(fila);
        } else {
            const fila = $(this).closest('tr');
            fila.find('.precio-input').val('0.00');
            calcularTotalFila(fila);
        }
    });

    // Evento para cambio de cantidad
    $('.cantidad-input, .precio-input').off('input').on('input', function() {
        const fila = $(this).closest('tr');
        calcularTotalFila(fila);
    });

    // Evento para eliminar fila
    $('.eliminar-fila').off('click').on('click', function() {
        const fila = $(this).closest('tr');
        // No eliminar si es la única fila
        if ($('#productos_body tr').length > 1) {
            fila.remove();
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Atención',
                text: 'Debe haber al menos un producto en la reserva'
            });
        }
    });
}

// Función para calcular el total de una fila
function calcularTotalFila(fila) {
    const cantidad = parseFloat(fila.find('.cantidad-input').val()) || 0;
    const precioUnitario = parseFloat(fila.find('.precio-input').val()) || 0;
    const total = cantidad * precioUnitario;
    fila.find('.precio-total').val(total.toFixed(2));
}

// Función para registrar una reserva
function registrarReserva() {
    // Validar que haya al menos un producto seleccionado
    let productosValidos = true;
    $('.producto-select').each(function() {
        if (!$(this).val()) {
            productosValidos = false;
            return false; // Romper el bucle
        }
    });

    if (!productosValidos) {
        Swal.fire({
            icon: 'warning',
            title: 'Atención',
            text: 'Debe seleccionar todos los productos'
        });
        return;
    }

    // Validar fecha y usuario
    if (!$('#fecha_apartado').val() || !$('#id_persona').val()) {
        Swal.fire({
            icon: 'warning',
            title: 'Atención',
            text: 'Debe completar todos los campos requeridos'
        });
        return;
    }

    // Recopilar datos del formulario
    const formData = $('#formRegistrar').serialize();

    // Enviar datos al servidor
    $.ajax({
        url: '?pagina=reserva',
        type: 'POST',
        data: formData + '&registrar=true',
        dataType: 'json',
        success: function(response) {
            if (response.respuesta === 1) {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: 'Reserva registrada correctamente',
                    confirmButtonText: 'Ok'
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.mensaje || 'No se pudo registrar la reserva'
                });
            }
        },
        error: function(xhr, status, error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un error al procesar la solicitud'
            });
            console.error(xhr.responseText);
        }
    });
}

// Función para abrir modal de modificación
function abrirModalModificar(idReserva) {
    // Consultar datos de la reserva
    $.ajax({
        url: '?pagina=reserva',
        type: 'POST',
        data: {
            consultar_reserva: true,
            id_reserva: idReserva
        },
        dataType: 'json',
        success: function(response) {
            if (response) {
                $('#id_reserva_modificar').val(response.id_reserva);
                $('#fecha_apartado_modificar').val(response.fecha_apartado);
                $('#id_persona_modificar').val(response.id_persona);
                $('#estatus_modificar').val(response.estatus);
                
                // Mostrar modal
                $('#modificar').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se encontró la reserva'
                });
            }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al consultar la reserva'
            });
        }
    });
}

// Función para modificar una reserva
function modificarReserva() {
    const formData = $('#formModificar').serialize();
    
    $.ajax({
        url: '?pagina=reserva',
        type: 'POST',
        data: formData + '&modificar=true',
        dataType: 'json',
        success: function(response) {
            if (response.respuesta === 1) {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: 'Reserva modificada correctamente',
                    confirmButtonText: 'Ok'
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload();
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.mensaje || 'No se pudo modificar la reserva'
                });
            }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al procesar la solicitud'
            });
        }
    });
}

// Función para eliminar una reserva
function eliminarReserva(idReserva) {
    Swal.fire({
        title: '¿Está seguro?',
        text: "Esta acción eliminará la reserva y todos sus detalles",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '?pagina=reserva',
                type: 'POST',
                data: {
                    eliminar: true,
                    id_reserva: idReserva
                },
                dataType: 'json',
                success: function(response) {
                    if (response.respuesta === 1) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Eliminado',
                            text: 'La reserva ha sido eliminada correctamente',
                            confirmButtonText: 'Ok'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.mensaje || 'No se pudo eliminar la reserva'
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al procesar la solicitud'
                    });
                }
            });
        }
    });
}

// Función para ver detalles de una reserva
function verDetalles(idReserva) {
    // Consultar datos de la reserva
    $.ajax({
        url: '?pagina=reserva',
        type: 'POST',
        data: {
            consultar_reserva: true,
            id_reserva: idReserva
        },
        dataType: 'json',
        success: function(response) {
            if (response) {
                // Llenar datos del encabezado
                $('#detalle_id_reserva').text(response.id_reserva);
                $('#detalle_fecha_apartado').text(formatearFecha(response.fecha_apartado));
                $('#detalle_persona').text(response.nombre_completo);
                $('#detalle_estatus').text(response.estatus == 1 ? 'Activo' : 'Inactivo');
                
                // Consultar detalles de la reserva
                consultarDetallesReserva(idReserva);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se encontró la reserva'
                });
            }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al consultar la reserva'
            });
        }
    });
}

// Función para consultar detalles de una reserva
function consultarDetallesReserva(idReserva) {
    $.ajax({
        url: '?pagina=reserva',
        type: 'POST',
        data: {
            consultar_detalle: true,
            id_reserva: idReserva
        },
        dataType: 'json',
        success: function(response) {
            // Limpiar tabla de detalles
            $('#detalles_body').empty();
            
            let totalReserva = 0;
            
            // Agregar detalles a la tabla
            if (response && response.length > 0) {
                response.forEach(function(detalle) {
                    const precioTotal = parseFloat(detalle.cantidad) * parseFloat(detalle.precio);
                    totalReserva += precioTotal;
                    
                    $('#detalles_body').append(`
                        <tr>
                            <td>${detalle.nombre_producto}</td>
                            <td>${detalle.cantidad}</td>
                            <td>${formatearMoneda(detalle.precio)}</td>
                            <td>${formatearMoneda(precioTotal)}</td>
                        </tr>
                    `);
                });
            } else {
                $('#detalles_body').append(`
                    <tr>
                        <td colspan="4" class="text-center">No hay detalles disponibles</td>
                    </tr>
                `);
            }
            
            // Actualizar total
            $('#total_reserva').text(formatearMoneda(totalReserva));
            
            // Mostrar modal
            $('#verDetalles').modal('show');
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al consultar los detalles de la reserva'
            });
        }
    });
}

// Función para formatear moneda
function formatearMoneda(valor) {
    return parseFloat(valor).toFixed(2);
}

// Función para formatear fecha DD/MM/YYYY
function formatearFecha(fechaStr) {
    const fecha = new Date(fechaStr);
    const dia = fecha.getDate().toString().padStart(2, '0');
    const mes = (fecha.getMonth() + 1).toString().padStart(2, '0');
    const año = fecha.getFullYear();
    
    return `${dia}/${mes}/${año}`;
}