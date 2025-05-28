function muestraMensaje(icono, tiempo, titulo, mensaje) {
    Swal.fire({
        icon: icono,
        timer: tiempo,
        title: titulo,
        html: mensaje,
        showConfirmButton: false,
    });
}

document.getElementById('btn-guardar-pedido').addEventListener('click', async () => {
    try {
        const form = document.getElementById('formPedido');
        const formData = new FormData(form);

        // 1. Registrar el pedido
        const resPedido = await fetch('controlador/verpedidoweb.php', {
            method: 'POST',
            body: formData
        });

        const text = await resPedido.text();
        let idPedidoGenerado;

        try {
            const dataPedido = JSON.parse(text);
            if (!dataPedido.success) throw new Error(dataPedido.message);

            idPedidoGenerado = dataPedido.id_pedido;
            console.log("ID pedido generado:", idPedidoGenerado);
        } catch (e) {
            console.error('Error al parsear JSON del pedido:', text);
            return;
        }

        // 2. Registrar detalles + preliminar
        const items = document.querySelectorAll('.row.item');

        for (const item of items) {
            const detalleData = new FormData();
            detalleData.append('id_pedido', idPedidoGenerado);
            detalleData.append('id_producto', item.dataset.idProducto);
            detalleData.append('cantidad', item.dataset.cantidad);
            detalleData.append('precio_unitario', item.dataset.precioUnitario);
            detalleData.append('subtotal', item.dataset.subtotal);
        
            const resDetalle = await fetch('controlador/verpedidoweb.php', {
                method: 'POST',
                body: detalleData
            });
        
            const textDetalle = await resDetalle.text();
            let dataDetalle;
        
            try {
                dataDetalle = JSON.parse(textDetalle);
                if (!dataDetalle.success) throw new Error("Error al registrar detalle");
        
                const idDetalleGenerado = dataDetalle.id_detalle;
        
                // Llamar a registrar_preliminar con ese ID
                const respuesta = await fetch('controlador/verpedidoweb.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({
                        id_detalle: idDetalleGenerado,
                        condicion: 'pedido'
                    })
                });
        
                const resultado = await respuesta.json();
                if (!resultado.success) {
                    console.warn('Error al registrar preliminar:', resultado.message);
                } else {
                    console.log('Preliminar insertado con ID:', resultado.id_preliminar);
                }
        
            } catch (e) {
             
            }
        }

        // 4. Mostrar mensaje de éxito final
        muestraMensaje("success", 2000, "Su Pedido se ha registrado con éxito");

        setTimeout(() => {
            window.location.href = '?pagina=catalogo_pedido';
        }, 1000);

    } catch (err) {
        console.error('Error general en el proceso:', err.message);
    }
});

function muestraMensaje(icono, tiempo, titulo, mensaje) {
    Swal.fire({
        icon: icono,
        timer: tiempo,
        title: titulo,
        html: mensaje,
        showConfirmButton: false,
    });
}

// Función para validar referencia bancaria (solo números, entre 4 y 6 dígitos)
function validarReferencia(referencia) {
    const patron = /^[0-9]{4,6}$/;
    return patron.test(referencia);
}

// Función para validar teléfono (formato venezolano)
function validarTelefono(telefono) {
    const patron = /^(0414|0424|0412|0416|0426)[0-9]{7}$/;
    return patron.test(telefono);
}

// Función para validar dirección (mínimo 10 caracteres)
function validarDireccion(direccion) {
    const longitudMinima = 10;
    const direccionLimpia = direccion.trim();
    return direccionLimpia.length >= longitudMinima;
}

// Función para validar que se hayan seleccionado los métodos
function validarMetodos(metodoPago, metodoEntrega) {
    return metodoPago !== "Seleccione un Metodo de pago" && 
           metodoEntrega !== "Seleccione un Metodo de Entrega";
}

// Función principal de validación
function validarFormularioPedido() {
    const referencia = document.getElementById('referencia_bancaria').value;
    const telefono = document.getElementById('telefono_emisor').value;
    const direccion = document.getElementById('direccion').value;
    const metodoPago = document.getElementById('metodopago').value;
    const metodoEntrega = document.getElementById('metodoentrega').value;

    let mensajesError = [];

    // Validar referencia bancaria
    if (!validarReferencia(referencia)) {
        mensajesError.push("La referencia bancaria debe contener entre 4 y 6 números");
    }

    // Validar teléfono
    if (!validarTelefono(telefono)) {
        mensajesError.push("El teléfono debe tener un formato válido (ej: 0414xxxxxxx)");
    }

    // Validar dirección
    if (!validarDireccion(direccion)) {
        mensajesError.push("La dirección debe tener al menos 10 caracteres");
    }

    // Validar métodos seleccionados
    if (!validarMetodos(metodoPago, metodoEntrega)) {
        mensajesError.push("Debe seleccionar un método de pago y un método de entrega");
    }

    return {
        esValido: mensajesError.length === 0,
        mensajes: mensajesError
    };
}

// Función para mostrar errores
function mostrarErrores(mensajes) {
    Swal.fire({
        icon: 'error',
        title: 'Por favor, corrija los siguientes errores:',
        html: mensajes.join('<br>'),
        confirmButtonText: 'Entendido'
    });
}

// Función para mostrar mensaje de éxito
function mostrarExito(mensaje) {
    Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: mensaje,
        timer: 2000,
        showConfirmButton: false
    });
}

// Evento click para procesar el pedido
document.getElementById('btn-guardar-pedido').addEventListener('click', async () => {
    try {
        // Validar el formulario antes de procesar
        const validacion = validarFormularioPedido();
        if (!validacion.esValido) {
            mostrarErrores(validacion.mensajes);
            return;
        }

        const form = document.getElementById('formPedido');
        const formData = new FormData(form);
        formData.append('accion', 'registrar_pedido'); // Agregamos la acción para el backend

        // 1. Registrar el pedido
        const resPedido = await fetch('../controlador/verpedidoweb.php', {
            method: 'POST',
            body: formData
        });

        const text = await resPedido.text();
        let idPedidoGenerado;

        try {
            const dataPedido = JSON.parse(text);
            if (!dataPedido.success) throw new Error(dataPedido.message);

            idPedidoGenerado = dataPedido.id_pedido;
            console.log("ID pedido generado:", idPedidoGenerado);
        } catch (e) {
            console.error('Error al parsear JSON del pedido:', text);
            muestraMensaje("error", 3000, "Error", "Hubo un problema al procesar su pedido");
            return;
        }

        // 2. Registrar detalles + preliminar
        const items = document.querySelectorAll('.row.item');

        for (const item of items) {
            const detalleData = new FormData();
            detalleData.append('accion', 'registrar_detalle'); // Agregamos la acción para el backend
            detalleData.append('id_pedido', idPedidoGenerado);
            detalleData.append('id_producto', item.dataset.idProducto);
            detalleData.append('cantidad', item.dataset.cantidad);
            detalleData.append('precio_unitario', item.dataset.precioUnitario);
            detalleData.append('subtotal', item.dataset.subtotal);
        
            const resDetalle = await fetch('../controlador/verpedidoweb.php', {
                method: 'POST',
                body: detalleData
            });
        
            const textDetalle = await resDetalle.text();
            let dataDetalle;
        
            try {
                dataDetalle = JSON.parse(textDetalle);
                if (!dataDetalle.success) {
                    throw new Error("Error al registrar detalle");
                }
        
                const idDetalleGenerado = dataDetalle.id_detalle;
        
                // Registrar preliminar
                const preliminarData = new FormData();
                preliminarData.append('accion', 'registrar_preliminar');
                preliminarData.append('id_detalle', idDetalleGenerado);
                preliminarData.append('condicion', 'pedido');

                const respuesta = await fetch('../controlador/verpedidoweb.php', {
                    method: 'POST',
                    body: preliminarData
                });
        
                const resultado = await respuesta.json();
                if (!resultado.success) {
                    console.warn('Error al registrar preliminar:', resultado.message);
                    muestraMensaje("error", 3000, "Error", "Hubo un problema al registrar los detalles del pedido");
                    return;
                }
        
            } catch (e) {
                muestraMensaje("error", 3000, "Error", "Hubo un problema al procesar los detalles del pedido");
                return;
            }
        }

        // 4. Mostrar mensaje de éxito final
        mostrarExito("Su Pedido se ha registrado con éxito");

        // 5. Limpiar el carrito después de procesar el pedido
        localStorage.removeItem('carrito');

        setTimeout(() => {
            window.location.href = '?pagina=tienda'; // Redirigir a la tienda
        }, 2000);

    } catch (err) {
        console.error('Error general en el proceso:', err.message);
        muestraMensaje("error", 3000, "Error", "Ocurrió un error inesperado al procesar su pedido");
    }
});
