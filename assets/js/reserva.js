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

    // Botón para guardar cambios de estado
    $('#btnGuardarEstado').click(function() {
        guardarCambioEstado();
    });
    
    // Función de prueba para verificar que el modal existe
    console.log('Modal editarEstado existe:', $('#editarEstado').length > 0);
    
    // Evento para el botón de ayuda
    const btnAyuda = document.getElementById('btnAyuda');
    if (btnAyuda) {
        btnAyuda.addEventListener('click', function() {
            // Verificar que Driver.js esté disponible
            if (typeof window.driver !== 'undefined' && window.driver.js && window.driver.js.driver) {
                const driver = window.driver.js.driver;
                const driverObj = new driver({
                nextBtnText: 'Siguiente',
                prevBtnText: 'Anterior',
                doneBtnText: 'Listo',
                popoverClass: 'driverjs-theme',
                closeBtn: true,
                steps: [
                    {
                        element: '.table-color th:nth-child(1)',
                        popover: {
                            title: 'ID',
                            description: 'Identificador único de la reserva en el sistema.',
                            side: "bottom"
                        }
                    },
                    {
                        element: '.table-color th:nth-child(2)',
                        popover: {
                            title: 'Fecha Reserva',
                            description: 'Fecha en la que se realizó la reserva de productos.',
                            side: "bottom"
                        }
                    },
                    {
                        element: '.table-color th:nth-child(3)',
                        popover: {
                            title: 'Usuario',
                            description: 'Nombre del cliente que realizó la reserva.',
                            side: "bottom"
                        }
                    },
                    {
                        element: '.table-color th:nth-child(4)',
                        popover: {
                            title: 'Estado',
                            description: 'Estado actual de la reserva: Activo, Inactivo o Entregado.',
                            side: "bottom"
                        }
                    },
                    {
                        element: '.table-color th:nth-child(5)',
                        popover: {
                            title: 'Acciones',
                            description: 'Botones para ver detalles y editar el estado de la reserva.',
                            side: "bottom"
                        }
                    },
                    { 
                        element: '#btnAyuda', 
                        popover: { 
                            title: 'Botón de ayuda', 
                            description: 'Haz clic aquí para ver esta guía interactiva del módulo de reservas.', 
                            side: "bottom", 
                            align: 'start' 
                        }
                    },
                    { 
                        element: '.btn-success[data-bs-target="#registro"]', 
                        popover: { 
                            title: 'Registrar reserva', 
                            description: 'Este botón abre el formulario para registrar una nueva reserva de productos.', 
                            side: "bottom", 
                            align: 'start' 
                        }
                    },
                    { 
                        element: '.btn-info', 
                        popover: { 
                            title: 'Ver detalles', 
                            description: 'Haz clic aquí para ver los detalles completos de una reserva específica.', 
                            side: "left", 
                            align: 'start' 
                        }
                    },
                    { 
                        element: '.btn-primary.editar-estado', 
                        popover: { 
                            title: 'Editar estado', 
                            description: 'Permite cambiar el estado de la reserva (solo disponible para reservas activas).', 
                            side: "left", 
                            align: 'start' 
                        }
                    },
                    { 
                        popover: { 
                            title: 'Eso es todo', 
                            description: 'Este es el fin de la guía del módulo de reservas. ¡Gracias por usar el sistema!' 
                        } 
                    }
                ]
            });
            driverObj.drive();
            } else {
                console.error('Driver.js no está disponible');
                alert('La funcionalidad de ayuda no está disponible en este momento.');
            }
        });
    }
    
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
    const productos = [];
    $('.producto-select').each(function(i) {
        productos.push($(this).val());
    });
    const cantidades = [];
    $('.cantidad-input').each(function(i) {
        cantidades.push($(this).val());
    });
    const precios_unit = [];
    $('.precio-input').each(function(i) {
        precios_unit.push($(this).val());
    });

    // Enviar datos al servidor con el nuevo flujo
    $.ajax({
        url: '?pagina=reserva',
        type: 'POST',
        data: {
            registrar_json: true,
            fecha_apartado: $('#fecha_apartado').val(),
            id_persona: $('#id_persona').val(),
            productos: productos,
            cantidades: cantidades,
            precios_unit: precios_unit
        },
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

// Función para abrir modal de edición de estado
function abrirModalEditarEstado(idReserva) {
    console.log('Abriendo modal para reserva:', idReserva);
    
    // Primero probar si el modal se abre sin datos
    try {
        const modalElement = document.getElementById('editarEstado');
        if (modalElement) {
            console.log('Modal encontrado, intentando abrir...');
            
            // Intentar con jQuery primero
            try {
                $('#editarEstado').modal('show');
                console.log('Modal abierto con jQuery');
            } catch (jqError) {
                console.log('jQuery falló, intentando con Bootstrap 5...');
                const modal = new bootstrap.Modal(modalElement);
                modal.show();
                console.log('Modal abierto con Bootstrap 5');
            }
            
            // Llenar datos básicos
            $('#info_id_reserva').text(idReserva);
            
            // Consultar datos de la reserva después de abrir el modal
            $.ajax({
                url: '?pagina=reserva',
                type: 'POST',
                data: {
                    operacion: 'consultar_reserva',
                    datos: { id_reserva: idReserva }
                },
                dataType: 'json',
                success: function(response) {
                    console.log('Respuesta del servidor:', response);
                    
                    if (response) {
                        // Llenar información de la reserva
                        $('#info_fecha_reserva').text(formatearFecha(response.fecha_apartado));
                        $('#info_cliente_reserva').text(response.nombre_completo);
                        
                        // Mostrar estado actual
                        let estadoTexto = '';
                        switch(response.estatus) {
                            case 1:
                                estadoTexto = 'Activo';
                                break;
                            case 0:
                                estadoTexto = 'Inactivo';
                                break;
                            case 2:
                                estadoTexto = 'Entregado';
                                break;
                            default:
                                estadoTexto = 'Desconocido';
                        }
                        $('#info_estado_actual').text(estadoTexto);
                        
                        // Configurar el select para que muestre "Seleccione un estado" por defecto
                        $('#nuevo_estado').val('');
                        
                        // Deshabilitar opciones si ya está inactiva o entregada
                        if (response.estatus == 0 || response.estatus == 2) {
                            $('#nuevo_estado').prop('disabled', true);
                            $('#btnGuardarEstado').prop('disabled', true);
                        } else {
                            $('#nuevo_estado').prop('disabled', false);
                            $('#btnGuardarEstado').prop('disabled', false);
                        }
                        
                        console.log('Datos cargados correctamente');
                    } else {
                        console.log('No se encontraron datos de la reserva');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error en AJAX:', xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Error al consultar la reserva: ' + error
                    });
                }
            });
        } else {
            console.error('Modal no encontrado');
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Modal no encontrado'
            });
        }
    } catch (error) {
        console.error('Error al abrir modal:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error al abrir el modal: ' + error.message
        });
    }
}

// Función para guardar el cambio de estado
function guardarCambioEstado() {
    const nuevoEstado = $('#nuevo_estado').val();
    const idReserva = $('#info_id_reserva').text();
    if (!nuevoEstado) {
        Swal.fire({
            icon: 'warning',
            title: 'Atención',
            text: 'Debe seleccionar un nuevo estado'
        });
        return;
    }
    let confirmacion = '';
    let icono = '';
    if (nuevoEstado == 0) {
        confirmacion = '¿Está seguro de que desea marcar esta reserva como inactiva?';
        icono = 'warning';
    } else if (nuevoEstado == 2) {
        confirmacion = '¿Está seguro de que desea marcar esta reserva como entregada?';
        icono = 'question';
    } else {
        confirmacion = '¿Está seguro de que desea cambiar el estado de esta reserva?';
        icono = 'info';
    }
    Swal.fire({
        title: 'Confirmar cambio de estado',
        text: confirmacion,
        icon: icono,
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, cambiar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '?pagina=reserva',
                type: 'POST',
                data: {
                    cambiar_estado_json: true,
                    id_reserva: idReserva,
                    nuevo_estatus: nuevoEstado
                },
                dataType: 'json',
                success: function(response) {
                    if (response.respuesta === 1) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Estado cambiado',
                            text: response.mensaje,
                            confirmButtonText: 'Ok'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $('#editarEstado').modal('hide');
                                location.reload();
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.mensaje || 'No se pudo cambiar el estado de la reserva'
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
            operacion: 'consultar_reserva',
            datos: { id_reserva: idReserva }
        },
        dataType: 'json',
        success: function(response) {
            if (response) {
                // Llenar datos del encabezado
                $('#detalle_id_reserva').text(response.id_reserva);
                $('#detalle_fecha_apartado').text(formatearFecha(response.fecha_apartado));
                $('#detalle_persona').text(response.nombre_completo);
                
                // Mostrar estado
                let estadoTexto = '';
                switch(response.estatus) {
                    case 1:
                        estadoTexto = 'Activo';
                        break;
                    case 0:
                        estadoTexto = 'Inactivo';
                        break;
                    case 2:
                        estadoTexto = 'Entregado';
                        break;
                    default:
                        estadoTexto = 'Desconocido';
                }
                $('#detalle_estatus').text(estadoTexto);
                
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
            operacion: 'consultar_detalle',
            datos: { id_reserva: idReserva }
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