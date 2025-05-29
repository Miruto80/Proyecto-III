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

// Función para mostrar mensajes con SweetAlert
function mostrarMensaje(icono, titulo, mensaje, tiempo = 3000) {
    Swal.fire({
        icon: icono,
        title: titulo,
        text: mensaje,
        timer: tiempo,
        showConfirmButton: false
    });
}

// Validaciones individuales
function validarReferenciaBancaria(referencia) {
    const patron = /^[0-9]{4,6}$/;
    return {
        esValido: patron.test(referencia),
        mensaje: "La referencia bancaria debe contener solo números y tener entre 4 y 6 dígitos"
    };
}

function validarTelefonoEmisor(telefono) {
    const patron = /^(0414|0424|0412|0416|0426)[0-9]{11}$/;
    return {
        esValido: patron.test(telefono),
        mensaje: "El teléfono debe contener solo números y tener 11 dígitos"
    };
}

function validarDireccion(direccion) {
    const direccionLimpia = direccion.trim();
    return {
        esValido: direccionLimpia.length >= 10,
        mensaje: "La dirección debe tener al menos 10 caracteres"
    };
}

function validarMetodoPago(metodoPago) {
    return {
        esValido: metodoPago !== "" && metodoPago !== "Seleccione un Metodo de pago",
        mensaje: "• Debe seleccionar un método de pago válido"
    };
}

function validarMetodoEntrega(metodoEntrega) {
    return {
        esValido: metodoEntrega !== "" && metodoEntrega !== "Seleccione un Metodo de Entrega",
        mensaje: "• Debe seleccionar un método de entrega válido"
    };
}

function validarBancos(bancoOrigen, bancoDestino) {
    if (bancoOrigen === "") {
        return {
            esValido: false,
            mensaje: "Debe seleccionar el banco de origen"
        };
    }
    if (bancoDestino === "") {
        return {
            esValido: false,
            mensaje: "Debe seleccionar el banco de destino"
        };
    }
    return {
        esValido: true,
        mensaje: ""
    };
}

// Función para validar que un texto no contenga números
function validarTextoSinNumeros(texto, nombreCampo) {
    const patron = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]*$/;
    return {
        esValido: patron.test(texto),
        mensaje:" ${nombreCampo} no debe contener números ni caracteres especiales"
    };
}

// Event Listeners para validaciones en tiempo real
document.addEventListener("DOMContentLoaded", function() {
    obtenerTasaDolarApi();

    // Validación de referencia bancaria
    const refBancaria = document.getElementById('referencia_bancaria');
    
    // Prevenir entrada de letras y caracteres especiales
    refBancaria.addEventListener('input', function() {
        // Eliminar cualquier carácter que no sea número
        this.value = this.value.replace(/\D/g, '');
        
        // Limitar a 6 dígitos
        if (this.value.length > 6) {
            this.value = this.value.slice(0, 6);
        }
        
        const validacion = validarReferenciaBancaria(this.value);
        if (!validacion.esValido) {
            Swal.fire({
                icon: 'warning',
                title: 'Formato Incorrecto',
                text: 'La referencia bancaria debe tener entre 4 y 6 números',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
        }
    });

    // Prevenir el pegado de texto con letras
    refBancaria.addEventListener('paste', function(e) {
        e.preventDefault();
        const texto = (e.clipboardData || window.clipboardData).getData('text');
        const numeros = texto.replace(/\D/g, '');
        this.value = numeros.slice(0, 6);
    });

    // Prevenir entrada de letras en keypress
    refBancaria.addEventListener('keypress', function(e) {
        if (!/^\d*$/.test(e.key)) {
            e.preventDefault();
        }
    });

    // Validación de teléfono emisor
    document.getElementById('telefono_emisor').addEventListener('input', function() {
        this.value = this.value.replace(/\D/g, '').slice(0, 11);
        const validacion = validarTelefonoEmisor(this.value);
        if (!validacion.esValido) {
            Swal.fire({
                icon: 'warning',
                title: 'Formato Incorrecto',
                text: validacion.mensaje,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
        }
    });

    // Validación de dirección
    document.getElementById('direccion').addEventListener('input', function() {
        const validacion = validarDireccion(this.value);
        if (!validacion.esValido) {
            Swal.fire({
                icon: 'warning',
                title: 'Formato Incorrecto',
                text: validacion.mensaje,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
        }
    });

    // Validación combinada de método de pago y entrega
    ['metodopago', 'metodoentrega'].forEach(id => {
        document.getElementById(id).addEventListener('change', function() {
            validarMetodos();
        });
    });

    // Validación de bancos
    ['banco', 'banco_destino'].forEach(id => {
        document.getElementById(id).addEventListener('change', function() {
            const validacion = validarBancos(
                document.getElementById('banco').value,
                document.getElementById('banco_destino').value
            );
            if (!validacion.esValido) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Selección Requerida',
                    text: validacion.mensaje,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                });
                this.classList.add('is-invalid');
            } else {
                document.getElementById('banco').classList.remove('is-invalid');
                document.getElementById('banco_destino').classList.remove('is-invalid');
            }
        });
    });

    // Evento para el botón de realizar pedido
    document.getElementById('btn-guardar-pedido').addEventListener('click', async function(e) {
        e.preventDefault();

        if (!validarFormularioPedido()) {
            return;
        }

        try {
            const form = document.getElementById('formPedido');
            const formData = new FormData(form);

            Swal.fire({
                title: 'Procesando pedido',
                text: 'Por favor espere...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const resPedido = await fetch('controlador/verpedidoweb.php', {
                method: 'POST',
                body: formData
            });

            const dataPedido = await resPedido.json();

            if (!dataPedido.success) {
                throw new Error(dataPedido.message || 'Error al procesar el pedido');
            }

            Swal.fire({
                icon: 'success',
                title: '¡Pedido realizado con éxito!',
                text: 'Será redirigido al catálogo...',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                window.location.href = '?pagina=catalogo_pedido';
            });

        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message || 'Ocurrió un error al procesar el pedido',
                confirmButtonText: 'Entendido'
            });
        }
    });
});