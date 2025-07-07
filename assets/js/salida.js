document.addEventListener('DOMContentLoaded', function() {
    // Referencias a elementos del formulario
    const formVenta = document.getElementById('formRegistroVenta');
    const cedulaInput = document.getElementById('cedula_cliente');
    const nombreInput = document.getElementById('nombre_cliente');
    const apellidoInput = document.getElementById('apellido_cliente');
    const telefonoInput = document.getElementById('telefono_cliente');
    const correoInput = document.getElementById('correo_cliente');
    
    // Referencias para el registro de cliente
    const camposCliente = document.getElementById('campos-cliente');
    const contenedorProductos = document.getElementById('productos-container-venta');
    const idClienteHidden = document.getElementById('id_cliente_hidden');

    // Referencias a las secciones de venta y productos
    const seccionVenta = document.querySelector('.seccion-venta');
    const seccionProductos = document.querySelector('.seccion-productos');

    // Verificar que los elementos críticos existan
    if (!cedulaInput || !nombreInput || !apellidoInput || !telefonoInput || !correoInput || !idClienteHidden) {
        return;
    }

    // Variable para almacenar el ID del cliente
    let clienteActualId = null;

    // Expresiones regulares para validaciones
    const regexSoloNumeros = /^[0-9]+$/;
    const regexCedula = /^[0-9]{7,8}$/;
    const regexSoloLetras = /^[A-Za-zÁáÉéÍíÓóÚúÑñ\s]+$/;
    const regexCorreo = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    const regexTelefono = /^0[0-9]{10}$/;

    // Variable para el paso actual
    let pasoActual = 1;
    const totalPasos = 4;

    // Funciones de utilidad
    function mostrarError(elemento, mensaje) {
        limpiarError(elemento);
        const errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback';
        errorDiv.textContent = mensaje;
        elemento.classList.add('is-invalid');
        elemento.parentNode.appendChild(errorDiv);
    }

    function limpiarError(elemento) {
        if (!elemento) return;
        
        elemento.classList.remove('is-invalid');
        elemento.classList.remove('is-valid');
        
        if (elemento.parentNode) {
            const errorDiv = elemento.parentNode.querySelector('.invalid-feedback');
            if (errorDiv && errorDiv.parentNode) {
                errorDiv.remove();
            }
        }
    }

    function mostrarExito(elemento) {
        elemento.classList.remove('is-invalid');
        elemento.classList.add('is-valid');
    }

    function resetearFormulario() {
        // Verificar que los elementos existan antes de usarlos
        if (!cedulaInput || !nombreInput || !apellidoInput || !telefonoInput || !correoInput || !idClienteHidden) {
            return;
        }
        
        // Limpiar campos
        cedulaInput.value = '';
        nombreInput.value = '';
        apellidoInput.value = '';
        telefonoInput.value = '';
        correoInput.value = '';
        idClienteHidden.value = '';
        
        // Limpiar errores
        [cedulaInput, nombreInput, apellidoInput, telefonoInput, correoInput].forEach(limpiarError);
        
        // Restablecer estados
        nombreInput.readOnly = false;
        apellidoInput.readOnly = false;
        telefonoInput.readOnly = false;
        correoInput.readOnly = false;
        
        // Ocultar campos de cliente por defecto
        if (camposCliente) {
            camposCliente.style.display = 'none';
        }
        
        // Reiniciar sistema de pasos
        pasoActual = 1;
        mostrarPaso(1);
        
        // Actualizar validación del botón siguiente
        actualizarBotonesNavegacion();
        
        clienteActualId = null;
    }

    // Evento para cuando se abre el modal de registro
    const registroModal = document.getElementById('registroModal');
    if (registroModal) {
        registroModal.addEventListener('show.bs.modal', function () {
            resetearFormulario();
            // Los campos del cliente se mantienen ocultos hasta consultar la cédula
            if (camposCliente) {
                camposCliente.style.display = 'none';
            }
        });
    }

    // Los campos del cliente se mantienen ocultos por defecto
    if (camposCliente) {
        camposCliente.style.display = 'none';
    }

    // Eventos para actualizar validación cuando se escriben en campos del cliente
    [cedulaInput, nombreInput, apellidoInput, telefonoInput, correoInput].forEach(input => {
        if (input) {
            input.addEventListener('input', function() {
                actualizarBotonesNavegacion();
            });
        }
    });

    // Forzar actualización de botones al cargar
    setTimeout(() => {
                    actualizarBotonesNavegacion();
    }, 200);

    // Evento adicional para el botón siguiente usando delegación
    document.addEventListener('click', function(e) {
        if (e.target && e.target.id === 'btnSiguiente') {
            e.preventDefault();
            siguientePaso();
        }
    });

    // Función para validar que todos los campos requeridos estén completos
    function validarCamposRequeridos() {
        // Validar cliente
        if (!clienteActualId) {
            Swal.fire('Error', 'Debe seleccionar o registrar un cliente primero', 'error');
            return false;
        }
        
        // Validar productos
        const productos = document.querySelectorAll('#productos-container-venta tr.producto-fila');
        if (productos.length === 0) {
            Swal.fire('Error', 'Debe agregar al menos un producto', 'error');
            return false;
        }
        
        // Validar que todos los productos tengan datos válidos
        let productosValidos = 0;
        let productosConError = [];
        
        productos.forEach((fila, index) => {
            const select = fila.querySelector('.producto-select-venta');
            const cantidad = fila.querySelector('.cantidad-input-venta');
            
            if (!select.value) {
                productosConError.push(`Fila ${index + 1}: Seleccione un producto`);
            } else if (!cantidad.value || cantidad.value <= 0) {
                productosConError.push(`Fila ${index + 1}: Ingrese una cantidad válida`);
            } else {
                productosValidos++;
            }
        });
        
        if (productosValidos === 0) {
            Swal.fire('Error', 'Debe seleccionar al menos un producto válido', 'error');
            return false;
        }
        
        if (productosConError.length > 0) {
            Swal.fire('Error', 'Corrija los siguientes errores:\n' + productosConError.join('\n'), 'error');
            return false;
        }
        
        // Validar métodos de pago
        const metodosPago = document.querySelectorAll('.metodo-pago-select');
        let metodosSeleccionados = 0;
        
        metodosPago.forEach(select => {
            if (select.value) {
                metodosSeleccionados++;
            }
        });
        
        if (metodosSeleccionados === 0) {
            Swal.fire('Error', 'Debe seleccionar al menos un método de pago', 'error');
            return false;
        }
        
        // Validar campos específicos de métodos de pago
        if (!validarCamposMetodoPago()) {
            return false;
        }
        
        // Validar que la suma de métodos de pago coincida con el total
        if (!validarTotalMetodosPago()) {
            return false;
        }
        
        return true;
    }

    // Función para mostrar resumen de la venta antes de confirmar
    function mostrarResumenVenta() {
        const cliente = {
            nombre: nombreInput.value,
            apellido: apellidoInput.value,
            cedula: cedulaInput.value
        };
        
        const productos = [];
        document.querySelectorAll('#productos-container-venta tr.producto-fila').forEach(fila => {
            const select = fila.querySelector('.producto-select-venta');
            const cantidad = fila.querySelector('.cantidad-input-venta');
            const precio = fila.querySelector('.precio-input-venta');
            const subtotal = fila.querySelector('.subtotal-venta');
            
            if (select.value && cantidad.value > 0) {
                productos.push({
                    nombre: select.options[select.selectedIndex].text,
                    cantidad: cantidad.value,
                    precio: precio.value,
                    subtotal: subtotal.textContent
                });
            }
        });
        
        const totalVenta = document.getElementById('total-general-venta').textContent;
        
        // Obtener información de métodos de pago
        const metodosPago = [];
        document.querySelectorAll('.metodo-pago-fila').forEach(fila => {
            const select = fila.querySelector('.metodo-pago-select');
            if (select.value) {
                const option = select.options[select.selectedIndex];
                const nombreMetodo = option.getAttribute('data-nombre');
                let detallesMetodo = '';
                
                // Obtener detalles específicos según el método
                if (nombreMetodo === 'Divisas $') {
                    const montoPrincipal = fila.querySelector('input[name="monto_metodopago[]"]')?.value || '0.00';
                    detallesMetodo = `Monto: $${montoPrincipal}`;
                } else if (nombreMetodo === 'Efectivo Bs') {
                    const montoPrincipal = fila.querySelector('input[name="monto_metodopago[]"]')?.value || '0.00';
                    const montoBsEfectivo = document.querySelector('input[name="monto_efectivo_bs"]')?.value || '0.00';
                    detallesMetodo = `USD: $${montoPrincipal} | Bs: ${montoBsEfectivo}`;
                } else if (nombreMetodo === 'Pago Movil') {
                    const bancoEmisor = document.querySelector('select[name="banco_emisor_pm"]')?.value || 'No especificado';
                    const bancoReceptor = document.querySelector('select[name="banco_receptor_pm"]')?.value || 'No especificado';
                    const referencia = document.querySelector('input[name="referencia_pm"]')?.value || 'No especificada';
                    const telefono = document.querySelector('input[name="telefono_emisor_pm"]')?.value || 'No especificado';
                    const montoBsPagoMovil = document.querySelector('input[name="monto_pm_bs"]')?.value || '0.00';
                    detallesMetodo = `Emisor: ${bancoEmisor} | Receptor: ${bancoReceptor} | Ref: ${referencia} | Tel: ${telefono} | Monto: Bs ${montoBsPagoMovil}`;
                } else if (nombreMetodo === 'Punto de Venta') {
                    const referencia = document.querySelector('input[name="referencia_pv"]')?.value || 'No especificada';
                    const montoBsPuntoVenta = document.querySelector('input[name="monto_pv_bs"]')?.value || '0.00';
                    detallesMetodo = `Referencia: ${referencia} | Monto: Bs ${montoBsPuntoVenta}`;
                } else if (nombreMetodo === 'Transferencia Bancaria') {
                    const referencia = document.querySelector('input[name="referencia_tb"]')?.value || 'No especificada';
                    const montoBsTransferencia = document.querySelector('input[name="monto_tb_bs"]')?.value || '0.00';
                    detallesMetodo = `Referencia: ${referencia} | Monto: Bs ${montoBsTransferencia}`;
                }
                
                metodosPago.push({
                    nombre: nombreMetodo,
                    detalles: detallesMetodo
                });
            }
        });
        
        let resumenHTML = `
            <div class="resumen-venta-completo">
                <!-- Información del Cliente -->
                <div class="resumen-seccion">
                    <h6 class="resumen-titulo">
                        <i class="fas fa-user text-primary"></i> Información del Cliente
                    </h6>
                    <div class="resumen-contenido">
                        <p><strong>Nombre:</strong> ${cliente.nombre} ${cliente.apellido}</p>
                        <p><strong>Cédula:</strong> ${cliente.cedula}</p>
                        <p><strong>Teléfono:</strong> ${telefonoInput.value}</p>
                        <p><strong>Correo:</strong> ${correoInput.value}</p>
                    </div>
                </div>
                
                <!-- Información de Productos -->
                <div class="resumen-seccion">
                    <h6 class="resumen-titulo">
                        <i class="fas fa-shopping-cart text-success"></i> Productos Seleccionados
                    </h6>
                    <div class="resumen-contenido">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Producto</th>
                                        <th class="text-center">Cantidad</th>
                                        <th class="text-center">Precio Unit.</th>
                                        <th class="text-center">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
        `;
        
        productos.forEach(producto => {
            resumenHTML += `
                                    <tr>
                                        <td>${producto.nombre}</td>
                                        <td class="text-center">${producto.cantidad}</td>
                                        <td class="text-center">$${producto.precio}</td>
                                        <td class="text-center">$${producto.subtotal}</td>
                                    </tr>
            `;
        });
        
        resumenHTML += `
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th colspan="3" class="text-end">Total USD:</th>
                                        <th class="text-center">${totalVenta}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Información de Métodos de Pago -->
                <div class="resumen-seccion">
                    <h6 class="resumen-titulo">
                        <i class="fas fa-credit-card text-info"></i> Métodos de Pago
                    </h6>
                    <div class="resumen-contenido">
        `;
        
        if (metodosPago.length > 0) {
            metodosPago.forEach((metodo, index) => {
                resumenHTML += `
                        <div class="metodo-pago-item">
                            <p><strong>${index + 1}. ${metodo.nombre}</strong></p>
                            <p class="text-muted small">${metodo.detalles}</p>
                        </div>
                `;
            });
        } else {
            resumenHTML += `<p class="text-muted">No se han seleccionado métodos de pago</p>`;
        }
        
        resumenHTML += `
                    </div>
                </div>
                
                <!-- Resumen Final -->
                <div class="resumen-seccion resumen-final">
                    <h6 class="resumen-titulo">
                        <i class="fas fa-calculator text-warning"></i> Resumen Final
                    </h6>
                    <div class="resumen-contenido">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Total de Productos:</strong> ${productos.length}</p>
                                <p><strong>Métodos de Pago:</strong> ${metodosPago.length}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Total USD:</strong> ${totalVenta}</p>
                                <p><strong>Fecha:</strong> ${new Date().toLocaleDateString('es-ES')}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Advertencias y Validaciones -->
                <div class="resumen-seccion resumen-validaciones">
                    <h6 class="resumen-titulo">
                        <i class="fas fa-exclamation-triangle text-warning"></i> Validaciones
                    </h6>
                    <div class="resumen-contenido">
        `;
        
        // Verificar validaciones
        const validaciones = [];
        
        if (!clienteActualId) {
            validaciones.push('<span class="text-danger"><i class="fas fa-times"></i> Cliente no seleccionado</span>');
        } else {
            validaciones.push('<span class="text-success"><i class="fas fa-check"></i> Cliente válido</span>');
        }
        
        if (productos.length === 0) {
            validaciones.push('<span class="text-danger"><i class="fas fa-times"></i> No hay productos seleccionados</span>');
        } else {
            validaciones.push('<span class="text-success"><i class="fas fa-check"></i> Productos válidos</span>');
        }
        
        if (metodosPago.length === 0) {
            validaciones.push('<span class="text-danger"><i class="fas fa-times"></i> No hay métodos de pago seleccionados</span>');
        } else {
            validaciones.push('<span class="text-success"><i class="fas fa-check"></i> Métodos de pago válidos</span>');
        }
        
        // Verificar suma de métodos de pago
        const totalVentaNum = parseFloat(totalVenta.replace('$', '')) || 0;
        let sumaMetodos = 0;
        document.querySelectorAll('.metodo-pago-fila').forEach(fila => {
            const montoInput = fila.querySelector('input[name="monto_metodopago[]"]');
            if (montoInput && montoInput.value) {
                sumaMetodos += parseFloat(montoInput.value) || 0;
            }
        });
        
        if (Math.abs(sumaMetodos - totalVentaNum) <= 0.01) {
            validaciones.push('<span class="text-success"><i class="fas fa-check"></i> Suma de métodos coincide con total</span>');
        } else {
            validaciones.push('<span class="text-warning"><i class="fas fa-exclamation-triangle"></i> Verificar suma de métodos de pago</span>');
        }
        
        validaciones.forEach(validacion => {
            resumenHTML += `<p>${validacion}</p>`;
        });
        
        resumenHTML += `
                    </div>
                </div>
            </div>
        `;
        
        return resumenHTML;
    }

    // Evento para el formulario de venta
    if (formVenta) {
        formVenta.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Eliminar filas de métodos de pago vacías o con monto 0
            document.querySelectorAll('.metodo-pago-fila').forEach(fila => {
                const select = fila.querySelector('.metodo-pago-select');
                const montoInput = fila.querySelector('input[name="monto_metodopago[]"]');
                if (!select.value || !montoInput.value || parseFloat(montoInput.value) <= 0) {
                    fila.remove();
                }
            });
            
            // Solo validar si estamos en el paso 4 (confirmación)
            if (pasoActual === 4) {
                // Validar que la suma de métodos de pago coincida con el total
                if (!validarTotalMetodosPago()) {
                    return;
                }
                
                // Validar campos específicos de métodos de pago
                if (!validarCamposMetodoPago()) {
                    return;
                }
                
                // Mostrar confirmación final
                Swal.fire({
                    title: '¿Confirmar venta?',
                    text: '¿Está seguro de que desea registrar esta venta?',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, registrar venta',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        registrarVenta();
                    }
                });
            }
        });
    }

    function registrarVenta() {
        // Mostrar loading
        const submitBtn = formVenta.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Registrando...';

        // Función para registrar la venta (con o sin cliente)
        const registrarVentaFinal = async () => {
            try {
                // Preparar datos del formulario
                const formData = new FormData(formVenta);
                formData.append('registrar', '1');

                // Si no hay cliente registrado, agregar los datos del cliente al formulario
                if (!clienteActualId) {
                    formData.append('registrar_cliente_con_venta', '1');
                    formData.append('cedula_cliente', cedulaInput.value.trim());
                    formData.append('nombre_cliente', nombreInput.value.trim());
                    formData.append('apellido_cliente', apellidoInput.value.trim());
                    formData.append('telefono_cliente', telefonoInput.value.trim());
                    formData.append('correo_cliente', correoInput.value.trim());
                }

                // Agregar datos de métodos de pago al formulario
                agregarDatosMetodosPago(formData);

                const response = await fetch('?pagina=salida', {
                    method: 'POST',
                    body: formData
                });

                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }

                const data = await response.json();

                if (data.respuesta === 1) {
                    Swal.fire({
                        title: '¡Venta registrada exitosamente!',
                        text: `La venta ha sido registrada con ID: ${data.id_pedido}`,
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        // Cerrar modal y recargar página
                        const modal = bootstrap.Modal.getInstance(document.getElementById('registroModal'));
                        if (modal) {
                            modal.hide();
                        }
                        window.location.reload();
                    });
                } else {
                    Swal.fire('Error', data.error || data.mensaje || 'Error al registrar la venta', 'error');
                }
            } catch (error) {
                console.error('Error al registrar venta:', error);
                Swal.fire('Error', 'Error al registrar la venta. Intente nuevamente.', 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        };

        // Función para agregar datos de métodos de pago al formulario
        function agregarDatosMetodosPago(formData) {
            document.querySelectorAll('.metodo-pago-fila').forEach((fila, index) => {
                const select = fila.querySelector('.metodo-pago-select');
                const montoInput = fila.querySelector('input[name="monto_metodopago[]"]');
                
                if (select && select.value && montoInput && montoInput.value > 0) {
                    const option = select.options[select.selectedIndex];
                    const nombreMetodo = option.getAttribute('data-nombre') || option.textContent;
                    
                    // Agregar datos básicos del método
                    formData.append('id_metodopago[]', select.value);
                    formData.append('monto_metodopago[]', montoInput.value);
                    
                    // Agregar detalles específicos según el método
                    switch(nombreMetodo) {
                        case 'Divisas $':
                            // No necesitamos campo específico, ya usamos monto_metodopago[]
                            break;
                            
                        case 'Efectivo Bs':
                            const montoEfectivoBs = document.querySelector('input[name="monto_efectivo_bs"]')?.value || '0.00';
                            formData.append('monto_efectivo_bs', montoEfectivoBs);
                            break;
                            
                        case 'Pago Movil':
                            const bancoEmisor = document.querySelector('select[name="banco_emisor_pm"]')?.value || '';
                            const bancoReceptor = document.querySelector('select[name="banco_receptor_pm"]')?.value || '';
                            const referenciaPm = document.querySelector('input[name="referencia_pm"]')?.value || '';
                            const telefonoEmisor = document.querySelector('input[name="telefono_emisor_pm"]')?.value || '';
                            const montoPmBs = document.querySelector('input[name="monto_pm_bs"]')?.value || '0.00';
                            
                            formData.append('banco_emisor_pm', bancoEmisor);
                            formData.append('banco_receptor_pm', bancoReceptor);
                            formData.append('referencia_pm', referenciaPm);
                            formData.append('telefono_emisor_pm', telefonoEmisor);
                            formData.append('monto_pm_bs', montoPmBs);
                            break;
                            
                        case 'Punto de Venta':
                            const referenciaPv = document.querySelector('input[name="referencia_pv"]')?.value || '';
                            const montoPvBs = document.querySelector('input[name="monto_pv_bs"]')?.value || '0.00';
                            
                            formData.append('referencia_pv', referenciaPv);
                            formData.append('monto_pv_bs', montoPvBs);
                            break;
                            
                        case 'Transferencia Bancaria':
                            const referenciaTb = document.querySelector('input[name="referencia_tb"]')?.value || '';
                            const montoTbBs = document.querySelector('input[name="monto_tb_bs"]')?.value || '0.00';
                            
                            formData.append('referencia_tb', referenciaTb);
                            formData.append('monto_tb_bs', montoTbBs);
                            break;
                    }
                }
            });
        }

        // Validar que todos los campos del cliente estén completos si no hay cliente registrado
        if (!clienteActualId) {
            const cedula = cedulaInput.value.trim();
            const nombre = nombreInput.value.trim();
            const apellido = apellidoInput.value.trim();
            const telefono = telefonoInput.value.trim();
            const correo = correoInput.value.trim();

            if (!cedula || !nombre || !apellido || !telefono || !correo) {
                Swal.fire('Error', 'Complete todos los datos del cliente antes de continuar', 'error');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                return;
            }

            // Validar formato de cédula
            if (!regexCedula.test(cedula)) {
                Swal.fire('Error', 'Formato de cédula inválido', 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
                return;
            }

            // Validar formato de teléfono
            if (!regexTelefono.test(telefono)) {
                Swal.fire('Error', 'Formato de teléfono inválido', 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
                return;
            }

            // Validar formato de correo
            if (!regexCorreo.test(correo)) {
                Swal.fire('Error', 'Formato de correo inválido', 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
                return;
            }
        }

        // Proceder con el registro de la venta
        registrarVentaFinal();
    }

    // Eventos para productos
    function inicializarEventosProducto() {
        // Evento para agregar producto
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('agregar-producto-venta')) {
                const fila = e.target.closest('tr');
                const nuevaFila = fila.cloneNode(true);
                
                // Limpiar valores de la nueva fila
                nuevaFila.querySelector('.producto-select-venta').value = '';
                nuevaFila.querySelector('.cantidad-input-venta').value = '1';
                nuevaFila.querySelector('.precio-input-venta').value = '0.00';
                nuevaFila.querySelector('.subtotal-venta').textContent = '0.00';
                nuevaFila.querySelector('.stock-info').textContent = '';
                
                // Obtener el contenedor de botones
                const contenedorBotones = nuevaFila.querySelector('td:last-child');
                
                // Limpiar todos los botones existentes
                contenedorBotones.innerHTML = '';
                
                // Agregar solo el botón de eliminar
                const btnEliminar = document.createElement('button');
                btnEliminar.type = 'button';
                btnEliminar.className = 'btn btn-danger btn-sm remover-producto-venta';
                btnEliminar.innerHTML = '<i class="fas fa-trash-alt"></i>';
                contenedorBotones.appendChild(btnEliminar);
                
                contenedorProductos.appendChild(nuevaFila);
                inicializarEventosFila(nuevaFila);
            }
            
            if (e.target.classList.contains('remover-producto-venta')) {
                const filas = contenedorProductos.querySelectorAll('tr.producto-fila');
                const filaActual = e.target.closest('tr');
                const esPrimeraFila = filaActual === filas[0];
                
                if (esPrimeraFila) {
                    Swal.fire('Error', 'No se puede eliminar el primer producto', 'error');
                    return;
                }
                
                if (filas.length > 1) {
                    filaActual.remove();
                    actualizarTotalVenta();
                } else {
                    Swal.fire('Error', 'Debe mantener al menos un producto', 'error');
                }
            }
        });

        // Eventos para cambio de producto
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('producto-select-venta')) {
                const fila = e.target.closest('tr');
                const option = e.target.options[e.target.selectedIndex];
                
                if (option.value) {
                    const precio = option.getAttribute('data-precio');
                    const stock = option.getAttribute('data-stock');
                    
                    fila.querySelector('.precio-input-venta').value = precio;
                    fila.querySelector('.cantidad-input-venta').setAttribute('data-stock', stock);
                    fila.querySelector('.stock-info').textContent = `Stock: ${stock}`;
                    
                    calcularSubtotalVenta(fila);
                } else {
                    fila.querySelector('.precio-input-venta').value = '0.00';
                    fila.querySelector('.cantidad-input-venta').removeAttribute('data-stock');
                    fila.querySelector('.stock-info').textContent = '';
                    fila.querySelector('.subtotal-venta').textContent = '0.00';
                    actualizarTotalVenta();
                }
            }
            
            if (e.target.classList.contains('cantidad-input-venta')) {
                const fila = e.target.closest('tr');
                const cantidad = parseInt(e.target.value);
                const stock = parseInt(e.target.getAttribute('data-stock') || 0);
                
                if (stock > 0 && cantidad > stock) {
                    Swal.fire('Error', `Stock disponible: ${stock}`, 'error');
                    e.target.value = stock;
                }
                
                if (cantidad < 1) {
                    e.target.value = 1;
                }
                
                calcularSubtotalVenta(fila);
            }
        });

        // Inicializar eventos en filas existentes
        document.querySelectorAll('tr.producto-fila').forEach(fila => {
            inicializarEventosFila(fila);
        });
    }

    function inicializarEventosFila(fila) {
        const select = fila.querySelector('.producto-select-venta');
        const cantidad = fila.querySelector('.cantidad-input-venta');
        
        if (select.value) {
            const option = select.options[select.selectedIndex];
            const stock = option.getAttribute('data-stock');
            cantidad.setAttribute('data-stock', stock);
            fila.querySelector('.stock-info').textContent = `Stock: ${stock}`;
        }
    }

    function calcularSubtotalVenta(fila) {
        const cantidad = parseFloat(fila.querySelector('.cantidad-input-venta').value) || 0;
        const precio = parseFloat(fila.querySelector('.precio-input-venta').value) || 0;
        const subtotal = cantidad * precio;
        fila.querySelector('.subtotal-venta').textContent = subtotal.toFixed(2);
        actualizarTotalVenta();
        
        // Actualizar estado del botón siguiente
        actualizarBotonesNavegacion();
    }

    function actualizarTotalVenta() {
        let total = 0;
        document.querySelectorAll('.subtotal-venta').forEach(subtotal => {
            total += parseFloat(subtotal.textContent) || 0;
        });
        document.getElementById('total-general-venta').textContent = `$${total.toFixed(2)}`;
        
        // Actualizar campo oculto para el total
        const totalInput = document.querySelector('input[name="precio_total"]');
        if (totalInput) {
            totalInput.value = total.toFixed(2);
        }
        
        // Actualizar el display del total de venta en el paso 3
        const totalVentaDisplay = document.getElementById('total-venta-display');
        if (totalVentaDisplay) {
            totalVentaDisplay.textContent = `$${total.toFixed(2)}`;
        }
        
        // Actualizar el cálculo de saldo restante
        actualizarSaldoRestante();
        
        // Actualizar montos en los métodos de pago cuando cambie el total
        // Solo si hay un método de pago seleccionado, obtener la tasa y actualizar
        const metodoSeleccionado = document.querySelector('.metodo-pago-select');
        if (metodoSeleccionado && metodoSeleccionado.value) {
            obtenerTasaCambio();
        }
    }

    // Función centralizada para calcular el total de métodos de pago
    function calcularTotalMetodosPago() {
        let totalPagado = 0;
        let metodosDetalle = [];
        
        document.querySelectorAll('.metodo-pago-fila').forEach((fila, index) => {
            const select = fila.querySelector('.metodo-pago-select');
            const montoInput = fila.querySelector('input[name="monto_metodopago[]"]');
            
            if (select.value && montoInput && montoInput.value) {
                const monto = parseFloat(montoInput.value) || 0;
                const option = select.options[select.selectedIndex];
                const nombreMetodo = option ? option.getAttribute('data-nombre') : '';
                
                totalPagado += monto;
                metodosDetalle.push({
                    index: index,
                    nombre: nombreMetodo,
                    monto: monto
                });
            }
        });
        
        return { total: totalPagado, metodos: metodosDetalle };
    }

    function actualizarSaldoRestante() {
        const totalVenta = parseFloat(document.getElementById('total-general-venta').textContent.replace('$', '')) || 0;
        const resultado = calcularTotalMetodosPago();
        const totalPagado = resultado.total;
        const metodosAplicados = resultado.metodos;
        
        const saldoRestante = totalVenta - totalPagado;
        
        // Actualizar displays
        const totalPagadoDisplay = document.getElementById('total-pagado-display');
        const saldoRestanteDisplay = document.getElementById('saldo-restante-display');
        
        if (totalPagadoDisplay) {
            totalPagadoDisplay.textContent = `$${totalPagado.toFixed(2)}`;
        }
        
        if (saldoRestanteDisplay) {
            saldoRestanteDisplay.textContent = `$${saldoRestante.toFixed(2)}`;
            
            // Cambiar color según el saldo
            if (Math.abs(saldoRestante) <= 0.01) {
                saldoRestanteDisplay.className = 'text-success';
                saldoRestanteDisplay.innerHTML = `$${saldoRestante.toFixed(2)} <i class="fas fa-check-circle"></i>`;
                // Mostrar mensaje de pago completo
                // (Eliminado: no mostrar mensaje de pago completo)
            } else if (saldoRestante < 0) {
                saldoRestanteDisplay.className = 'text-danger';
                saldoRestanteDisplay.innerHTML = `$${saldoRestante.toFixed(2)} <i class="fas fa-exclamation-triangle"></i>`;
                // Mostrar advertencia de sobrepago
                mostrarAdvertenciaSobrepago(saldoRestante);
            } else {
                saldoRestanteDisplay.className = 'text-warning';
                saldoRestanteDisplay.innerHTML = `$${saldoRestante.toFixed(2)} <i class="fas fa-clock"></i>`;
            }
        }
        

        
        // Actualizar lista de métodos aplicados
        actualizarListaMetodosAplicados();
        
        // Validar si se puede proceder al siguiente paso
        validarPagoCompleto();
    }

    // Función para mostrar mensaje cuando el pago está completo
    function mostrarMensajePagoCompleto() {
        // Solo mostrar una vez para evitar spam
        if (!document.getElementById('pago-completo-mensaje')) {
            const mensaje = document.createElement('div');
            mensaje.id = 'pago-completo-mensaje';
            mensaje.className = 'alert alert-success alert-dismissible fade show mt-2';
            mensaje.innerHTML = `
                <i class="fas fa-check-circle"></i>
                <strong>¡Pago completo!</strong> El total de la venta ha sido cubierto.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            const container = document.querySelector('#step-3-content .mb-4');
            if (container) {
                container.appendChild(mensaje);
            }
        }
    }

    // Función para mostrar advertencia de sobrepago
    function mostrarAdvertenciaSobrepago(saldoNegativo) {
        const montoSobrepago = Math.abs(saldoNegativo);
        
        // Solo mostrar una vez para evitar spam
        if (!document.getElementById('sobrepago-mensaje')) {
            const mensaje = document.createElement('div');
            mensaje.id = 'sobrepago-mensaje';
            mensaje.className = 'alert alert-warning alert-dismissible fade show mt-2';
            mensaje.innerHTML = `
                <i class="fas fa-exclamation-triangle"></i>
                <strong>¡Atención!</strong> El monto ingresado excede el total por $${montoSobrepago.toFixed(2)}.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            const container = document.querySelector('#step-3-content .mb-4');
            if (container) {
                container.appendChild(mensaje);
            }
        }
    }

    // Función para validar si el pago está completo
    function validarPagoCompleto() {
        const totalVenta = parseFloat(document.getElementById('total-general-venta').textContent.replace('$', '')) || 0;
        const resultado = calcularTotalMetodosPago();
        const totalPagado = resultado.total;
        
        const saldoRestante = totalVenta - totalPagado;
        const pagoCompleto = saldoRestante <= 0.01; // Tolerancia de 1 centavo
        
        // Actualizar estado del botón siguiente
        const btnSiguiente = document.getElementById('btnSiguiente');
        if (btnSiguiente && pasoActual === 3) {
            btnSiguiente.disabled = !pagoCompleto;
            
            // Cambiar texto del botón según el estado
            if (pagoCompleto) {
                btnSiguiente.innerHTML = '<i class="fas fa-check"></i> Continuar';
                btnSiguiente.className = 'btn btn-success btn-navigation';
            } else {
                btnSiguiente.innerHTML = '<i class="fas fa-clock"></i> Completar Pago';
                btnSiguiente.className = 'btn btn-warning btn-navigation';
            }
        }
        
        return pagoCompleto;
    }

    // Función para actualizar la lista de métodos de pago aplicados
    function actualizarListaMetodosAplicados() {
        const container = document.getElementById('metodos-aplicados-container');
        const listaContainer = document.getElementById('lista-metodos-aplicados');
        
        if (!container || !listaContainer) return;
        
        container.innerHTML = '';
        let hayMetodos = false;
        let contador = 1;
        
        document.querySelectorAll('.metodo-pago-fila').forEach((fila, index) => {
            const select = fila.querySelector('.metodo-pago-select');
            const montoInput = fila.querySelector('input[name="monto_metodopago[]"]');
            
            if (select && select.value && montoInput && montoInput.value > 0) {
                hayMetodos = true;
                const option = select.options[select.selectedIndex];
                const nombreMetodo = option.getAttribute('data-nombre') || option.textContent;
                const monto = parseFloat(montoInput.value) || 0;
                
                // Obtener detalles específicos del método
                let detallesMetodo = obtenerDetallesMetodoPago(nombreMetodo, fila);
                
                const div = document.createElement('div');
                div.className = 'metodo-pago-aplicado mb-2';
                div.innerHTML = `
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <strong>${contador}. ${nombreMetodo}</strong>
                        <span class="badge badge-monto-metodo">$${monto.toFixed(2)}</span>
                        </div>
                        <small class="text-muted">
                            <i class="fas fa-clock"></i> ${new Date().toLocaleTimeString('es-ES')}
                        </small>
                        ${detallesMetodo ? `<br><small class="text-muted">${detallesMetodo}</small>` : ''}
                    <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="eliminarMetodoPago(${index})">
                        <i class="fas fa-times"></i>
                    </button>
                `;
                container.appendChild(div);
                contador++;
            }
        });
        
        // Mostrar u ocultar la lista según si hay métodos
        listaContainer.style.display = hayMetodos ? 'block' : 'none';
        
        // Limpiar mensajes de pago completo/sobrepago si no hay métodos
        if (!hayMetodos) {
            const mensajeCompleto = document.getElementById('pago-completo-mensaje');
            const mensajeSobrepago = document.getElementById('sobrepago-mensaje');
            if (mensajeCompleto) mensajeCompleto.remove();
            if (mensajeSobrepago) mensajeSobrepago.remove();
        }
    }

    // Función para obtener detalles específicos de cada método de pago
    function obtenerDetallesMetodoPago(nombreMetodo, fila) {
        let detalles = '';
        
        // Obtener el monto del campo principal
        const montoPrincipal = fila.querySelector('input[name="monto_metodopago[]"]')?.value || '0.00';
        
        switch(nombreMetodo) {
            case 'Divisas $':
                detalles = `Monto en USD: $${montoPrincipal}`;
                break;
                
            case 'Efectivo Bs':
                const montoBsEfectivo = document.querySelector('input[name="monto_efectivo_bs"]')?.value || '0.00';
                detalles = `USD: $${montoPrincipal} | Bs: ${montoBsEfectivo}`;
                break;
                
            case 'Pago Movil':
                const bancoEmisor = document.querySelector('select[name="banco_emisor_pm"]')?.value || 'No especificado';
                const bancoReceptor = document.querySelector('select[name="banco_receptor_pm"]')?.value || 'No especificado';
                const referencia = document.querySelector('input[name="referencia_pm"]')?.value || 'No especificada';
                const telefono = document.querySelector('input[name="telefono_emisor_pm"]')?.value || 'No especificado';
                const montoBsPagoMovil = document.querySelector('input[name="monto_pm_bs"]')?.value || '0.00';
                detalles = `Emisor: ${bancoEmisor} | Receptor: ${bancoReceptor} | Ref: ${referencia} | Tel: ${telefono} | Monto: Bs ${montoBsPagoMovil}`;
                break;
                
            case 'Punto de Venta':
                const referenciaPv = document.querySelector('input[name="referencia_pv"]')?.value || 'No especificada';
                const montoBsPuntoVenta = document.querySelector('input[name="monto_pv_bs"]')?.value || '0.00';
                detalles = `Referencia: ${referenciaPv} | Monto: Bs ${montoBsPuntoVenta}`;
                break;
                
            case 'Transferencia Bancaria':
                const referenciaTb = document.querySelector('input[name="referencia_tb"]')?.value || 'No especificada';
                const montoBsTransferencia = document.querySelector('input[name="monto_tb_bs"]')?.value || '0.00';
                detalles = `Referencia: ${referenciaTb} | Monto: Bs ${montoBsTransferencia}`;
                break;
                
            default:
                detalles = 'Sin detalles adicionales';
        }
        
        return detalles;
    }

    // Función global para eliminar método de pago desde la lista
    window.eliminarMetodoPago = function(index) {
        const filas = document.querySelectorAll('.metodo-pago-fila');
        if (filas[index]) {
            // Limpiar los campos de la fila
            const select = filas[index].querySelector('.metodo-pago-select');
            const montoInput = filas[index].querySelector('input[name="monto_metodopago[]"]');
            
            if (select) select.value = '';
            if (montoInput) montoInput.value = '';
            
            // Ocultar campos dinámicos
            ocultarTodosLosCamposMetodoPago();
            
            // Actualizar saldo restante y validaciones
            actualizarSaldoRestante();
            validarTotalMetodosPago();
            actualizarBotonesNavegacion();
            
            // console.log eliminado
        }
    };

    // Validación de cédula en tiempo real
    cedulaInput.addEventListener('keypress', function(e) {
        // Prevenir entrada de letras y caracteres especiales
        if (!regexSoloNumeros.test(e.key) && e.key !== 'Backspace' && e.key !== 'Tab') {
            e.preventDefault();
        }
    });

    cedulaInput.addEventListener('input', function() {
        // Limpiar cualquier caracter que no sea número
        this.value = this.value.replace(/[^0-9]/g, '');
        
        if (this.value.length > 0) {
            if (!regexCedula.test(this.value)) {
                mostrarError(this, 'La cédula debe tener entre 7 y 8 dígitos');
        } else {
                mostrarExito(this);
                
                // Si la cédula es válida y tiene 7-8 dígitos, consultar si existe
                if (this.value.length >= 7) {
                    // Agregar un pequeño delay para evitar consultas innecesarias
                    clearTimeout(this.consultaTimeout);
                    this.consultaTimeout = setTimeout(() => {
                        consultarClientePorCedula(this.value);
                    }, 500);
                    
                    // Mostrar campos de cliente si no están visibles
                    if (camposCliente && camposCliente.style.display === 'none') {
                        camposCliente.style.display = 'block';
                        
                        // Hacer campos editables
                        nombreInput.readOnly = false;
                        apellidoInput.readOnly = false;
                        telefonoInput.readOnly = false;
                        correoInput.readOnly = false;
                    }
                }
            }
        } else {
            limpiarError(this);
            // Limpiar campos del cliente si se borra la cédula
            limpiarCamposCliente();
            // Ocultar campos del cliente cuando se borra la cédula
            if (camposCliente) {
                camposCliente.style.display = 'none';
            }
        }
        
        // Actualizar estado del botón siguiente
        setTimeout(() => {
            actualizarBotonesNavegacion();
        }, 100);
    });

    // Función para consultar cliente por cédula
    async function consultarClientePorCedula(cedula) {
        try {
            const formData = new FormData();
            formData.append('buscar_cliente', '1');
            formData.append('cedula', cedula);
            formData.append('csrf_token', document.querySelector('input[name="csrf_token"]').value);

            const response = await fetch('?pagina=salida', {
                method: 'POST',
                body: formData
            });

            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }

            const data = await response.json();

            if (data.respuesta === 1 && data.cliente) {
                // Cliente existe, llenar campos automáticamente
                llenarCamposCliente(data.cliente);
                clienteActualId = data.cliente.id_persona;
                idClienteHidden.value = data.cliente.id_persona;
                
                // Hacer campos de solo lectura
                nombreInput.readOnly = true;
                apellidoInput.readOnly = true;
                telefonoInput.readOnly = true;
                correoInput.readOnly = true;
                
                // Mostrar campos del cliente
                if (camposCliente) {
                    camposCliente.style.display = 'block';
                }
                
                // Mostrar mensaje de éxito
                Swal.fire({
                    title: 'Cliente encontrado',
                    text: 'Los datos del cliente han sido cargados automáticamente',
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
                
                // Actualizar estado del botón siguiente después de cargar los datos
                setTimeout(() => {
                    actualizarBotonesNavegacion();
                }, 100);
            } else {
                // Cliente no existe, limpiar campos para nuevo registro
                limpiarCamposCliente();
                clienteActualId = null;
                idClienteHidden.value = '';
                
                // Hacer campos editables para nuevo cliente
                nombreInput.readOnly = false;
                apellidoInput.readOnly = false;
                telefonoInput.readOnly = false;
                correoInput.readOnly = false;
                
                // Mostrar campos del cliente para nuevo registro
                if (camposCliente) {
                    camposCliente.style.display = 'block';
                }
                
                // Mostrar mensaje informativo para nuevo cliente
                Swal.fire({
                    title: 'Cliente no registrado',
                    text: 'Esta cédula no está registrada. Por favor, complete los campos para registrar un nuevo cliente.',
                    icon: 'info',
                    confirmButtonText: 'Entendido',
                    confirmButtonColor: '#3085d6'
                });
                
                // Actualizar estado del botón siguiente después de limpiar los campos
                setTimeout(() => {
                    actualizarBotonesNavegacion();
                }, 100);
            }
        } catch (error) {
            console.error('Error al consultar cliente:', error);
            // En caso de error, permitir edición manual
            limpiarCamposCliente();
            clienteActualId = null;
            idClienteHidden.value = '';
        }
    }

    // Función para llenar campos del cliente
    function llenarCamposCliente(cliente) {
        nombreInput.value = cliente.nombre || '';
        apellidoInput.value = cliente.apellido || '';
        telefonoInput.value = cliente.telefono || '';
        correoInput.value = cliente.correo || '';
        
        // Marcar campos como válidos
        [nombreInput, apellidoInput, telefonoInput, correoInput].forEach(mostrarExito);
    }

    // Función para limpiar campos del cliente
    function limpiarCamposCliente() {
        nombreInput.value = '';
        apellidoInput.value = '';
        telefonoInput.value = '';
        correoInput.value = '';
        
        // Limpiar validaciones
        [nombreInput, apellidoInput, telefonoInput, correoInput].forEach(limpiarError);
        
        // Ocultar campos del cliente cuando se limpian
        if (camposCliente) {
            camposCliente.style.display = 'none';
        }
    }

    // Validación de nombre y apellido
    [nombreInput, apellidoInput].forEach(input => {
        input.addEventListener('keypress', function(e) {
            if (!regexSoloLetras.test(e.key) && e.key !== 'Backspace' && e.key !== 'Tab' && e.key !== ' ') {
                e.preventDefault();
            }
        });

        input.addEventListener('input', function() {
            if (this.value.length > 0) {
                if (!regexSoloLetras.test(this.value)) {
                mostrarError(this, 'Solo se permiten letras');
                } else if (this.value.length < 2) {
                    mostrarError(this, 'Debe contener al menos 2 caracteres');
                } else {
                    mostrarExito(this);
                }
            } else {
                limpiarError(this);
            }
            
            // Actualizar estado del botón siguiente cada vez que cambie un campo
            setTimeout(() => {
                actualizarBotonesNavegacion();
            }, 100);
        });
    });

    // Validación de teléfono
    telefonoInput.addEventListener('keypress', function(e) {
        if (!regexSoloNumeros.test(e.key) && e.key !== 'Backspace' && e.key !== 'Tab') {
            e.preventDefault();
        }
    });

    telefonoInput.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
        
        if (this.value.length > 0) {
            if (!this.value.startsWith('0')) {
                mostrarError(this, 'El número debe comenzar con 0');
            } else if (this.value.length !== 11) {
                mostrarError(this, 'El teléfono debe tener 11 dígitos');
            } else if (!regexTelefono.test(this.value)) {
                mostrarError(this, 'Formato de teléfono inválido');
            } else {
                mostrarExito(this);
            }
            } else {
                limpiarError(this);
        }
        
        // Actualizar estado del botón siguiente
        setTimeout(() => {
            actualizarBotonesNavegacion();
        }, 100);
    });

    // Validación de correo
    correoInput.addEventListener('input', function() {
        if (this.value.length > 0) {
        if (!regexCorreo.test(this.value)) {
            mostrarError(this, 'Ingrese un correo electrónico válido');
            } else {
                mostrarExito(this);
            }
        } else {
            limpiarError(this);
        }
        
        // Actualizar estado del botón siguiente
        setTimeout(() => {
            actualizarBotonesNavegacion();
        }, 100);
    });

    // Eventos para validar campos del cliente en tiempo real
    [cedulaInput, nombreInput, apellidoInput, telefonoInput, correoInput].forEach(input => {
        input.addEventListener('input', function() {
            // Actualizar estado del botón siguiente cada vez que cambie un campo
            setTimeout(() => {
                actualizarBotonesNavegacion();
            }, 100);
        });
    });

    // Inicializar eventos de productos
    inicializarEventosProducto();

    // Eventos para métodos de pago
    function inicializarEventosMetodoPago() {
        // Evento para agregar método de pago
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('agregar-metodo-pago')) {
                const fila = e.target.closest('.metodo-pago-fila');
                if (!fila) return;
                
                const nuevaFila = fila.cloneNode(true);
                
                // Limpiar valores de la nueva fila
                const selectNuevo = nuevaFila.querySelector('.metodo-pago-select');
                const montoInputNuevo = nuevaFila.querySelector('input[name="monto_metodopago[]"]');
                
                if (selectNuevo) selectNuevo.value = '';
                if (montoInputNuevo) montoInputNuevo.value = '';
                
                // Obtener el contenedor de botones
                const contenedorBotones = nuevaFila.querySelector('.col-md-2:last-child');
                
                // Limpiar todos los botones existentes
                if (contenedorBotones) {
                    contenedorBotones.innerHTML = '';
                    
                    // Agregar solo el botón de eliminar
                    const btnEliminar = document.createElement('button');
                    btnEliminar.type = 'button';
                    btnEliminar.className = 'btn btn-danger btn-sm remover-metodo-pago';
                    btnEliminar.innerHTML = '<i class="fas fa-trash-alt"></i>';
                    contenedorBotones.appendChild(btnEliminar);
                }
                
                const container = document.getElementById('metodos-pago-container');
                if (container) {
                    container.appendChild(nuevaFila);
                    inicializarEventosFilaMetodoPago(nuevaFila);
                }
            }
            
            if (e.target.classList.contains('remover-metodo-pago')) {
                const filas = document.querySelectorAll('.metodo-pago-fila');
                const filaActual = e.target.closest('.metodo-pago-fila');
                const esPrimeraFila = filaActual === filas[0];
                
                if (esPrimeraFila) {
                    Swal.fire('Error', 'No se puede eliminar el primer método de pago', 'error');
                    return;
                }
                
                if (filas.length > 1) {
                    filaActual.remove();
                    validarTotalMetodosPago();
                } else {
                    Swal.fire('Error', 'Debe mantener al menos un método de pago', 'error');
                }
            }
        });

        // Inicializar eventos en filas existentes
        document.querySelectorAll('.metodo-pago-fila').forEach(fila => {
            inicializarEventosFilaMetodoPago(fila);
        });
    }

    function inicializarEventosFilaMetodoPago(fila) {
        if (!fila) return;
        
        // Eventos para cambio de método de pago
        const select = fila.querySelector('.metodo-pago-select');
        const montoInput = fila.querySelector('input[name="monto_metodopago[]"]');
        
        // Verificar que los elementos existan antes de agregar event listeners
        if (!select) {
            return;
        }
        
        select.addEventListener('change', function() {
            if (this.value) {
                const option = this.options[this.selectedIndex];
                const nombreMetodo = option.getAttribute('data-nombre');
                
                mostrarCamposMetodoPago(nombreMetodo);
                validarTotalMetodosPago();
                
                // Actualizar montos cuando se seleccione un método
                setTimeout(() => {
                    actualizarMontosEnBs();
                }, 100);
                
                // Actualizar estado del botón siguiente
                actualizarBotonesNavegacion();
            } else {
                ocultarTodosLosCamposMetodoPago();
                actualizarBotonesNavegacion();
            }
        });
        
        // Evento para el campo de monto
        if (montoInput) {
            montoInput.addEventListener('input', function() {
                const montoActual = parseFloat(this.value) || 0;
                const totalVenta = parseFloat(document.getElementById('total-general-venta').textContent.replace('$', '')) || 0;
                
                // Obtener el método de pago seleccionado
                const selectMetodo = fila.querySelector('.metodo-pago-select');
                const option = selectMetodo.options[selectMetodo.selectedIndex];
                const nombreMetodo = option ? option.getAttribute('data-nombre') : '';
                
                // Validar que no exceda el saldo restante
                let totalPagado = 0;
                document.querySelectorAll('input[name="monto_metodopago[]"]').forEach(input => {
                    if (input !== this) {
                        totalPagado += parseFloat(input.value) || 0;
                    }
                });
                
                const saldoDisponible = totalVenta - totalPagado;
                
                if (montoActual > saldoDisponible) {
                    Swal.fire('Advertencia', `El monto excede el saldo disponible ($${saldoDisponible.toFixed(2)})`, 'warning');
                    this.value = saldoDisponible.toFixed(2);
                }
                
                // Si el método requiere conversión (no es "Divisas $"), actualizar conversión
                if (nombreMetodo && nombreMetodo !== 'Divisas $' && montoActual > 0) {
                    // Obtener tasa de cambio y actualizar conversión
                    obtenerTasaCambio().then(tasaCambio => {
                        if (tasaCambio) {
                            const montoBs = montoActual * tasaCambio;
                            
                            // Actualizar campos específicos según el método
                            if (nombreMetodo === 'Efectivo Bs') {
                                // Solo calcular el equivalente en Bs
                                document.querySelectorAll('input[name="monto_efectivo_bs"]').forEach(input => {
                                    input.value = montoBs.toFixed(2);
                                });
                            } else if (nombreMetodo === 'Pago Movil') {
                                document.querySelectorAll('input[name="monto_pm_bs"]').forEach(input => {
                                    input.value = montoBs.toFixed(2);
                                });
                            } else if (nombreMetodo === 'Punto de Venta') {
                                document.querySelectorAll('input[name="monto_pv_bs"]').forEach(input => {
                                    input.value = montoBs.toFixed(2);
                                });
                            } else if (nombreMetodo === 'Transferencia Bancaria') {
                                document.querySelectorAll('input[name="monto_tb_bs"]').forEach(input => {
                                    input.value = montoBs.toFixed(2);
                                });
                            }
                            
                            // Mostrar información de conversión
                            mostrarInformacionConversion(montoActual, montoBs, tasaCambio);
                        }
                    });
                } else if (nombreMetodo === 'Divisas $') {
                    // Para divisas, no necesitamos actualizar campos específicos
                    // Remover información de conversión si existe
                    const infoConversion = document.getElementById('info-conversion');
                    if (infoConversion) {
                        infoConversion.remove();
                    }
                }
                
                // Actualizar saldo restante
                actualizarSaldoRestante();
                validarTotalMetodosPago();
            });
        }
    }

    function mostrarCamposMetodoPago(nombreMetodo) {
        // Ocultar todos los campos primero
        ocultarTodosLosCamposMetodoPago();
        
        // Remover información de conversión anterior
        const infoConversion = document.getElementById('info-conversion');
        if (infoConversion) {
            infoConversion.remove();
        }
        
        // Mostrar campos según el método seleccionado (nombres exactos de la BD)
        if (nombreMetodo === 'Divisas $') {
            const camposDivisa = document.getElementById('campos-divisa');
            if (camposDivisa) camposDivisa.style.display = 'block';
            
            // Para divisas, no necesitamos conversión automática
            // console.log eliminado
        } else if (nombreMetodo === 'Efectivo Bs') {
            const camposEfectivo = document.getElementById('campos-efectivo');
            if (camposEfectivo) camposEfectivo.style.display = 'block';
            
            // Obtener tasa de cambio del API
            // console.log eliminado
            obtenerTasaCambio();
        } else if (nombreMetodo === 'Pago Movil') {
            const camposPagoMovil = document.getElementById('campos-pago-movil');
            if (camposPagoMovil) camposPagoMovil.style.display = 'block';
            
            // console.log eliminado
            obtenerTasaCambio();
        } else if (nombreMetodo === 'Punto de Venta') {
            const camposPuntoVenta = document.getElementById('campos-punto-venta');
            if (camposPuntoVenta) camposPuntoVenta.style.display = 'block';
            
            // console.log eliminado
            obtenerTasaCambio();
        } else if (nombreMetodo === 'Transferencia Bancaria') {
            const camposTransferencia = document.getElementById('campos-transferencia');
            if (camposTransferencia) camposTransferencia.style.display = 'block';
            
            // console.log eliminado
            obtenerTasaCambio();
        }
        
        const camposDinamicos = document.getElementById('campos-metodo-pago-dinamicos');
        if (camposDinamicos) camposDinamicos.style.display = 'block';
    }

    function ocultarTodosLosCamposMetodoPago() {
        const campos = document.querySelectorAll('.campos-metodo');
        campos.forEach(campo => {
            if (campo) campo.style.display = 'none';
        });
        
        const camposDinamicos = document.getElementById('campos-metodo-pago-dinamicos');
        if (camposDinamicos) camposDinamicos.style.display = 'none';
        
        // Limpiar información de conversión
        const infoConversion = document.getElementById('info-conversion');
        if (infoConversion) {
            infoConversion.remove();
        }
    }

    async function obtenerTasaCambio() {
        try {
            // console.log eliminado
            
            // Llamada al API de dólar oficial del BCV
            const response = await fetch('https://ve.dolarapi.com/v1/dolares/oficial');
            
            if (!response.ok) {
                throw new Error('Error al obtener la tasa de cambio');
            }
            
            const data = await response.json();
            
            if (!data.promedio || data.promedio <= 0) {
                throw new Error('No se pudo obtener una tasa de cambio válida');
            }
            
            const tasaCambio = parseFloat(data.promedio);
            // console.log eliminado
            
            // Calcular montos en Bs basado en el total de productos
            actualizarMontosEnBs(tasaCambio);
            
            return tasaCambio;
            
        } catch (error) {
            console.error('Error al obtener tasa de cambio:', error);
            Swal.fire('Error', 'No se pudo obtener la tasa de cambio del dólar. Intente nuevamente.', 'error');
            // No actualizar montos si falla el API
            return null;
        }
    }

    function actualizarMontosEnBs(tasaCambio) {
        // Verificar que se proporcione una tasa válida
        if (!tasaCambio || tasaCambio <= 0) {
            // console.log eliminado
            return;
        }
        
        // console.log eliminado
        
        // Obtener el total de productos (total de la venta)
        const totalVenta = parseFloat(document.getElementById('total-general-venta').textContent.replace('$', '')) || 0;
        
        // console.log eliminado
        
        // Obtener el monto ingresado en el campo principal de método de pago
        const montoMetodoPago = document.querySelector('input[name="monto_metodopago[]"]');
        const montoIngresado = parseFloat(montoMetodoPago?.value) || 0;
        
        // console.log eliminado
        
        // Si no hay monto ingresado, usar el total de la venta
        const montoAConvertir = montoIngresado > 0 ? montoIngresado : totalVenta;
        
        // Calcular el monto en bolívares
        const montosBs = montoAConvertir * tasaCambio;
        
        // console.log eliminado
        
        // Actualizar montos en Bs para cada método que lo requiera
        document.querySelectorAll('input[name="monto_efectivo_bs"]').forEach(input => {
            input.value = montosBs.toFixed(2);
        });
        
        document.querySelectorAll('input[name="monto_pm_bs"]').forEach(input => {
            input.value = montosBs.toFixed(2);
        });
        
        document.querySelectorAll('input[name="monto_pv_bs"]').forEach(input => {
            input.value = montosBs.toFixed(2);
        });
        
        document.querySelectorAll('input[name="monto_tb_bs"]').forEach(input => {
            input.value = montosBs.toFixed(2);
        });
        
        // Actualizar montos en USD para efectivo (monto ingresado o total)
        document.querySelectorAll('input[name="monto_efectivo_usd"]').forEach(input => {
            input.value = montoAConvertir.toFixed(2);
        });
        
        // Actualizar monto en USD para divisa (monto ingresado o total)
        document.querySelectorAll('input[name="monto_divisa"]').forEach(input => {
            input.value = montoAConvertir.toFixed(2);
        });
        
        // Mostrar información de conversión
        mostrarInformacionConversion(montoAConvertir, montosBs, tasaCambio);
    }

    // Función para mostrar información de conversión de monedas
    function mostrarInformacionConversion(montoUsd, montoBs, tasaCambio) {
        // Remover información de conversión anterior si existe
        const infoAnterior = document.getElementById('info-conversion');
        if (infoAnterior) {
            infoAnterior.remove();
        }
        
        // Crear elemento de información de conversión
        const infoConversion = document.createElement('div');
        infoConversion.id = 'info-conversion';
        infoConversion.className = 'alert alert-info alert-dismissible fade show mt-2';
        infoConversion.innerHTML = `
            <div class="row">
                <div class="col-md-4">
                    <strong><i class="fas fa-dollar-sign text-success"></i> Monto USD:</strong><br>
                    $${montoUsd.toFixed(2)}
                </div>
                <div class="col-md-4">
                    <strong><i class="fas fa-exchange-alt text-primary"></i> Tasa de Cambio:</strong><br>
                    Bs ${tasaCambio.toFixed(2)} / USD
                </div>
                <div class="col-md-4">
                    <strong><i class="fas fa-bolivar-sign text-warning"></i> Monto Bs:</strong><br>
                    Bs ${montoBs.toFixed(2)}
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        // Insertar después del contenedor de métodos de pago
        const container = document.getElementById('metodos-pago-container');
        if (container) {
            container.parentNode.insertBefore(infoConversion, container.nextSibling);
        }
    }

    function validarTotalMetodosPago() {
        // Obtener el total de la venta
        const totalVenta = parseFloat(document.getElementById('total-general-venta').textContent.replace('$', '')) || 0;
        
        // Usar la función centralizada para calcular el total de métodos de pago
        const resultado = calcularTotalMetodosPago();
        const sumaMetodos = resultado.total;
        const metodosValidos = resultado.metodos;
        const metodosConMonto = metodosValidos.length;
        
        console.log(`Validación: Total venta $${totalVenta.toFixed(2)}, Suma métodos $${sumaMetodos.toFixed(2)}, Diferencia $${(sumaMetodos - totalVenta).toFixed(2)}`);
        
        // Si no hay métodos con monto, no validar
        if (metodosConMonto === 0) {
            return true;
        }
        
        // Verificar que la suma no exceda el total (con tolerancia de 0.01 para errores de redondeo)
        const diferencia = sumaMetodos - totalVenta;
        
        // Solo mostrar error si realmente excede el total
        if (diferencia > 0.01) {
            Swal.fire('Error', `La suma de los métodos de pago ($${sumaMetodos.toFixed(2)}) excede el total de la venta ($${totalVenta.toFixed(2)}) por $${diferencia.toFixed(2)}`, 'error');
            return false;
        }
        
        // Si la suma es menor al total, es válido (puede ser pago parcial)
        // Si la suma es igual al total, es perfecto
        return true;
    }

    // Validaciones específicas para cada método de pago
    function validarCamposMetodoPago() {
        // Obtener todas las filas de métodos de pago
        const filasMetodosPago = document.querySelectorAll('.metodo-pago-fila');
        
        for (let i = 0; i < filasMetodosPago.length; i++) {
            const fila = filasMetodosPago[i];
            const select = fila.querySelector('.metodo-pago-select');
            const montoInput = fila.querySelector('input[name="monto_metodopago[]"]');
            const option = select ? select.options[select.selectedIndex] : null;
            const nombreMetodo = option ? option.getAttribute('data-nombre') : '';
            const monto = montoInput ? parseFloat(montoInput.value) : 0;
            
            // Solo validar si hay un método seleccionado Y un monto válido en esta fila
            if (!nombreMetodo || monto <= 0) continue;
            
            // Validaciones según el método específico de esta fila
            if (nombreMetodo.toLowerCase().includes('pago móvil') || nombreMetodo.toLowerCase().includes('movil')) {
                if (!validarPagoMovil()) {
                    return false;
                }
            } else if (nombreMetodo.toLowerCase().includes('punto de venta') || nombreMetodo.toLowerCase().includes('pos')) {
                if (!validarPuntoVenta()) {
                    return false;
                }
            } else if (nombreMetodo.toLowerCase().includes('transferencia bancaria') || nombreMetodo.toLowerCase().includes('transferencia')) {
                if (!validarTransferenciaBancaria()) {
                    return false;
                }
            }
        }
        
        return true;
    }

    function validarPagoMovil() {
        // Verificar si los campos de pago móvil están visibles
        const camposPagoMovil = document.getElementById('campos-pago-movil');
        if (!camposPagoMovil || camposPagoMovil.style.display === 'none') {
            return true; // Si los campos no están visibles, no hay nada que validar
        }
        
        const bancoEmisor = document.querySelector('select[name="banco_emisor_pm"]').value;
        const bancoReceptor = document.querySelector('select[name="banco_receptor_pm"]').value;
        const referencia = document.querySelector('input[name="referencia_pm"]').value;
        const telefono = document.querySelector('input[name="telefono_emisor_pm"]').value;
        
        if (!bancoEmisor) {
            Swal.fire('Error', 'Seleccione un banco emisor', 'error');
            return false;
        }
        
        if (!bancoReceptor) {
            Swal.fire('Error', 'Seleccione un banco receptor', 'error');
            return false;
        }
        
        if (!referencia || referencia.length < 4 || referencia.length > 6 || !/^\d+$/.test(referencia)) {
            Swal.fire('Error', 'La referencia debe tener entre 4 y 6 dígitos numéricos', 'error');
            return false;
        }
        
        if (!telefono || telefono.length !== 11 || !/^\d+$/.test(telefono)) {
            Swal.fire('Error', 'El teléfono debe tener 11 dígitos numéricos', 'error');
            return false;
        }
        
        return true;
    }

    function validarPuntoVenta() {
        // Verificar si los campos de punto de venta están visibles
        const camposPuntoVenta = document.getElementById('campos-punto-venta');
        if (!camposPuntoVenta || camposPuntoVenta.style.display === 'none') {
            return true; // Si los campos no están visibles, no hay nada que validar
        }
        
        const referencia = document.querySelector('input[name="referencia_pv"]').value;
        
        if (!referencia || referencia.length < 4 || referencia.length > 6 || !/^\d+$/.test(referencia)) {
            Swal.fire('Error', 'La referencia debe tener entre 4 y 6 dígitos numéricos', 'error');
            return false;
        }
        
        return true;
    }

    function validarTransferenciaBancaria() {
        // Verificar si los campos de transferencia bancaria están visibles
        const camposTransferencia = document.getElementById('campos-transferencia');
        if (!camposTransferencia || camposTransferencia.style.display === 'none') {
            return true; // Si los campos no están visibles, no hay nada que validar
        }
        
        const referencia = document.querySelector('input[name="referencia_tb"]').value;
        
        if (!referencia || referencia.length < 4 || referencia.length > 6 || !/^\d+$/.test(referencia)) {
            Swal.fire('Error', 'La referencia debe tener entre 4 y 6 dígitos numéricos', 'error');
            return false;
        }
        
        return true;
    }

    // Función global para eliminar método de pago desde la lista
    window.eliminarMetodoPago = function(index) {
        const filas = document.querySelectorAll('.metodo-pago-fila');
        if (filas[index]) {
            // Limpiar los campos de la fila
            const select = filas[index].querySelector('.metodo-pago-select');
            const montoInput = filas[index].querySelector('input[name="monto_metodopago[]"]');
            
            if (select) select.value = '';
            if (montoInput) montoInput.value = '';
            
            // Ocultar campos dinámicos
            ocultarTodosLosCamposMetodoPago();
            
            // Actualizar saldo restante y validaciones
            actualizarSaldoRestante();
            validarTotalMetodosPago();
            actualizarBotonesNavegacion();
            
            // console.log eliminado
        }
    };

    // Inicializar eventos de métodos de pago
    inicializarEventosMetodoPago();

    // Función de inicialización para debugging
    function inicializarDebugMetodoPago() {
        
        // Verificar que los elementos existan
        const container = document.getElementById('metodos-pago-container');
        const dinamicos = document.getElementById('campos-metodo-pago-dinamicos');
        

        
        // Verificar que los campos específicos existan
        const camposDivisa = document.getElementById('campos-divisa');
        const camposEfectivo = document.getElementById('campos-efectivo');
        const camposPagoMovil = document.getElementById('campos-pago-movil');
        const camposPuntoVenta = document.getElementById('campos-punto-venta');
        const camposTransferencia = document.getElementById('campos-transferencia');
        

        
        // Verificar opciones del select
        const select = document.querySelector('.metodo-pago-select');
        if (select) {
            
        }
        
        // Inicializar montos si hay un total disponible
        const totalVenta = parseFloat(document.getElementById('total-general-venta')?.textContent.replace('$', '')) || 0;
        if (totalVenta > 0) {
    
            // Los montos se actualizarán cuando se seleccione un método de pago
        }
    }

    // Ejecutar debug al cargar la página
    inicializarDebugMetodoPago();

    // Evento para el botón de ayuda
    const btnAyuda = document.getElementById('btnAyuda');
    if (btnAyuda) {
        btnAyuda.addEventListener('click', function() {
            const driver = window.driver.js.driver;
            const driverObj = new driver({
                nextBtnText: 'Siguiente',
                prevBtnText: 'Anterior',
                doneBtnText: 'Listo',
                popoverClass: 'driverjs-theme',
                closeBtn: false,
                steps: [
                    {
                        element: '.table-color th:nth-child(1)',
                        popover: {
                            title: 'Cliente',
                            description: 'Muestra el nombre y apellido del cliente que realizó la compra.',
                            side: "bottom"
                        }
                    },
                    {
                        element: '.table-color th:nth-child(2)',
                        popover: {
                            title: 'Fecha',
                            description: 'Indica la fecha en la que se registró la venta en el sistema.',
                            side: "bottom"
                        }
                    },
                    {
                        element: '.table-color th:nth-child(3)',
                        popover: {
                            title: 'Total (USD)',
                            description: 'Muestra el monto total de la venta en dólares estadounidenses (USD).',
                            side: "bottom"
                        }
                    },
                    {
                        element: '.table-color th:nth-child(4)',
                        popover: {
                            title: 'Acción',
                            description: 'Contiene el botón para ver los detalles completos de la venta.',
                            side: "bottom"
                        }
                    },
                    {
                        element: '.btn-success[data-bs-target="#registroModal"]',
                        popover: {
                            title: 'Registrar Venta',
                            description: 'Este botón abre el formulario paso a paso para registrar una nueva venta.',
                            side: "bottom",
                            align: 'start'
                        }
                    },
                    {
                        element: '.btn-info',
                        popover: {
                            title: 'Ver Detalles',
                            description: 'Haz clic aquí para ver los detalles completos de una venta específica.',
                            side: "left",
                            align: 'start'
                        }
                    },
                    {
                        element: '#btnAyuda',
                        popover: {
                            title: 'Botón de Ayuda',
                            description: 'Haz clic aquí para ver esta guía interactiva del módulo de ventas.',
                            side: "bottom",
                            align: 'start'
                        }
                    },
                    {
                        popover: {
                            title: '¡Eso es todo!',
                            description: 'Has completado la guía del módulo de ventas. Aquí puedes gestionar todas las ventas del sistema, registrar nuevas ventas y consultar detalles completos. ¡Gracias por usar el sistema!'
                        }
                    }
                ]
            });
            driverObj.drive();
        });
    }

    // Función para actualizar el indicador de pasos
    function actualizarPasos(pasoActual) {
        // Remover todas las clases activas y completadas
        document.querySelectorAll('.step').forEach(step => {
            step.classList.remove('active', 'completed');
        });
        
        // Marcar pasos completados y activo
        for (let i = 1; i <= 4; i++) {
            const step = document.getElementById(`step-${getNombrePaso(i)}`);
            if (i < pasoActual) {
                step.classList.add('completed');
            } else if (i === pasoActual) {
                step.classList.add('active');
            }
        }
    }

    // Función para obtener el nombre del paso
    function getNombrePaso(numero) {
        const pasos = {
            1: 'cliente',
            2: 'productos', 
            3: 'pago',
            4: 'confirmar'
        };
        return pasos[numero] || 'cliente';
    }

    // Función para verificar si un paso está completo
    function verificarPasoCompleto(paso) {
        switch(paso) {
            case 1: // Cliente - Se considera completo si hay datos válidos
                // Verificar que los elementos existan
                if (!cedulaInput || !nombreInput || !apellidoInput || !telefonoInput || !correoInput) {
                    // console.log eliminado
                    return false;
                }
                
                const cedula = cedulaInput.value.trim();
                const nombre = nombreInput.value.trim();
                const apellido = apellidoInput.value.trim();
                const telefono = telefonoInput.value.trim();
                const correo = correoInput.value.trim();
                
                console.log('Validando paso 1:', {
                    cedula: cedula,
                    nombre: nombre,
                    apellido: apellido,
                    telefono: telefono,
                    correo: correo,
                    clienteActualId: clienteActualId,
                    camposVisibles: camposCliente ? camposCliente.style.display !== 'none' : false
                });
                
                // Si hay un cliente registrado (ID válido), el paso está completo
                if (clienteActualId && clienteActualId > 0) {
                    // console.log eliminado
                    // console.log eliminado
                    return true;
                }
                
                // Si no hay cliente registrado, verificar que los campos estén visibles y completos
                const camposVisibles = camposCliente && camposCliente.style.display !== 'none';
                if (!camposVisibles) {
                    // console.log eliminado
                    return false;
                }
                
                // Verificar que todos los campos estén llenos
                const camposCompletos = cedula && nombre && apellido && telefono && correo;
                
                if (!camposCompletos) {
                    // console.log eliminado
                    return false;
                }
                
                // Verificar formato de cédula (más permisivo)
                const cedulaValida = cedula.length >= 7 && cedula.length <= 8 && /^\d+$/.test(cedula);
                
                // Verificar formato de teléfono (más permisivo)
                const telefonoValido = telefono.length >= 10 && telefono.length <= 11 && /^\d+$/.test(telefono);
                
                // Verificar formato de correo (básico)
                const correoValido = correo.includes('@') && correo.includes('.');
                
                const resultado = camposCompletos && cedulaValida && telefonoValido && correoValido;
                // console.log eliminado
                
                return resultado;
                
            case 2: // Productos
                const productos = document.querySelectorAll('#productos-container-venta tr.producto-fila');
                let productosValidos = 0;
                productos.forEach(fila => {
                    const select = fila.querySelector('.producto-select-venta');
                    const cantidad = fila.querySelector('.cantidad-input-venta');
                    if (select && cantidad && select.value && cantidad.value > 0) {
                        productosValidos++;
                    }
                });
                return productosValidos > 0;
            case 3: // Pago
                const metodosPago = document.querySelectorAll('.metodo-pago-select');
                let metodosSeleccionados = 0;
                metodosPago.forEach(select => {
                    if (select && select.value) {
                        metodosSeleccionados++;
                    }
                });
                
                // Verificar que haya al menos un método seleccionado
                if (metodosSeleccionados === 0) {
                    return false;
                }
                
                // Verificar que el pago esté completo
                return validarPagoCompleto();
            case 4: // Confirmación
                return true; // Siempre se puede confirmar si llegamos aquí
            default:
                return false;
        }
    }

    // Función para actualizar automáticamente los pasos
    function actualizarPasosAutomaticamente() {
        if (verificarPasoCompleto(1)) {
            actualizarPasos(2);
        } else {
            actualizarPasos(1);
        }
        
        if (verificarPasoCompleto(2)) {
            actualizarPasos(3);
        }
        
        if (verificarPasoCompleto(3)) {
            actualizarPasos(4);
        }
    }

    // Función para mostrar un paso específico
    function mostrarPaso(paso) {
        // Ocultar todos los pasos
        document.querySelectorAll('.step-content').forEach(content => {
            content.style.display = 'none';
            content.classList.remove('active');
        });
        
        // Mostrar el paso actual
        const pasoContent = document.getElementById(`step-${paso}-content`);
        if (pasoContent) {
            pasoContent.style.display = 'block';
            pasoContent.classList.add('active');
        }
        
        // Actualizar indicador de pasos
        actualizarPasos(paso);
        
        // Actualizar botones de navegación
        actualizarBotonesNavegacion();
        
        // Si es el paso 4, llenar la previsualización
        if (paso === 4) {
            llenarPrevisualizacionResumen();
        }
    }

    // Función para llenar la previsualización en el paso 4
    function llenarPrevisualizacionResumen() {
        // Cliente
        document.getElementById('preview_cedula').value = cedulaInput.value;
        document.getElementById('preview_nombre').value = nombreInput.value;
        document.getElementById('preview_apellido').value = apellidoInput.value;
        document.getElementById('preview_telefono').value = telefonoInput.value;
        document.getElementById('preview_correo').value = correoInput.value;

        // Productos
        const tbody = document.querySelector('#preview_tabla_productos tbody');
        tbody.innerHTML = '';
        let total = 0;
        document.querySelectorAll('#productos-container-venta tr.producto-fila').forEach(fila => {
            const select = fila.querySelector('.producto-select-venta');
            const cantidad = fila.querySelector('.cantidad-input-venta');
            const precio = fila.querySelector('.precio-input-venta');
            const subtotal = fila.querySelector('.subtotal-venta');
            if (select.value && cantidad.value > 0) {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${select.options[select.selectedIndex].text}</td>
                    <td class="text-center">${cantidad.value}</td>
                    <td class="text-center">$${precio.value}</td>
                    <td class="text-center">$${subtotal.textContent}</td>
                `;
                tbody.appendChild(tr);
                total += parseFloat(subtotal.textContent) || 0;
            }
        });
        document.getElementById('preview_total_usd').textContent = `$${total.toFixed(2)}`;

        // Métodos de pago
        const contenedor = document.getElementById('preview_metodos_pago');
        contenedor.innerHTML = '';
        document.querySelectorAll('.metodo-pago-fila').forEach((fila, idx) => {
            const select = fila.querySelector('.metodo-pago-select');
            if (select && select.value) {
                const option = select.options[select.selectedIndex];
                const nombreMetodo = option.getAttribute('data-nombre') || option.textContent;
                const montoPrincipal = fila.querySelector('input[name="monto_metodopago[]"]')?.value || '0.00';
                let detallesMetodo = '';
                
                if (nombreMetodo === 'Divisas $') {
                    detallesMetodo = `Monto: $${montoPrincipal}`;
                } else if (nombreMetodo === 'Efectivo Bs') {
                    const montoBsEfectivo = document.querySelector('input[name="monto_efectivo_bs"]')?.value || '0.00';
                    detallesMetodo = `USD: $${montoPrincipal} | Bs: ${montoBsEfectivo}`;
                } else if (nombreMetodo === 'Pago Movil') {
                    const bancoEmisor = document.querySelector('select[name="banco_emisor_pm"]')?.value || 'No especificado';
                    const bancoReceptor = document.querySelector('select[name="banco_receptor_pm"]')?.value || 'No especificado';
                    const referencia = document.querySelector('input[name="referencia_pm"]')?.value || 'No especificada';
                    const telefono = document.querySelector('input[name="telefono_emisor_pm"]')?.value || 'No especificado';
                    const montoBsPagoMovil = document.querySelector('input[name="monto_pm_bs"]')?.value || '0.00';
                    detallesMetodo = `Emisor: ${bancoEmisor} | Receptor: ${bancoReceptor} | Ref: ${referencia} | Tel: ${telefono} | Monto: Bs ${montoBsPagoMovil}`;
                } else if (nombreMetodo === 'Punto de Venta') {
                    const referencia = document.querySelector('input[name="referencia_pv"]')?.value || 'No especificada';
                    const montoBsPuntoVenta = document.querySelector('input[name="monto_pv_bs"]')?.value || '0.00';
                    detallesMetodo = `Referencia: ${referencia} | Monto: Bs ${montoBsPuntoVenta}`;
                } else if (nombreMetodo === 'Transferencia Bancaria') {
                    const referencia = document.querySelector('input[name="referencia_tb"]')?.value || 'No especificada';
                    const montoBsTransferencia = document.querySelector('input[name="monto_tb_bs"]')?.value || '0.00';
                    detallesMetodo = `Referencia: ${referencia} | Monto: Bs ${montoBsTransferencia}`;
                }
                const div = document.createElement('div');
                div.className = 'metodo-pago-item';
                div.innerHTML = `<p><strong>${idx + 1}. ${nombreMetodo}</strong></p><p class="text-muted small">${detallesMetodo}</p>`;
                contenedor.appendChild(div);
            }
        });

        // Configurar eventos para los collapsibles
        configurarCollapsibles();
    }

    // Función para configurar los eventos de los collapsibles
    function configurarCollapsibles() {
        // Eventos para cambiar iconos cuando se expande/colapsa
        document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(titulo => {
            titulo.addEventListener('click', function() {
                const icono = this.querySelector('.fas.fa-chevron-down, .fas.fa-chevron-up');
                if (icono) {
                    // Cambiar el icono
                    if (icono.classList.contains('fa-chevron-down')) {
                        icono.classList.remove('fa-chevron-down');
                        icono.classList.add('fa-chevron-up');
                    } else {
                        icono.classList.remove('fa-chevron-up');
                        icono.classList.add('fa-chevron-down');
                    }
                }
            });
        });
    }

    // Función para actualizar botones de navegación
    function actualizarBotonesNavegacion() {
        // console.log eliminado
        // console.log eliminado
        
        const btnAnterior = document.getElementById('btnAnterior');
        const btnSiguiente = document.getElementById('btnSiguiente');
        const btnRegistrar = document.getElementById('btnRegistrarVenta');
        
        // Botón Anterior
        if (pasoActual > 1) {
            btnAnterior.style.display = 'inline-block';
        } else {
            btnAnterior.style.display = 'none';
        }
        
        // Botón Siguiente
        if (pasoActual < totalPasos) {
            btnSiguiente.style.display = 'inline-block';
            btnRegistrar.style.display = 'none';
        } else {
            btnSiguiente.style.display = 'none';
            btnRegistrar.style.display = 'inline-block';
        }
        
        // Deshabilitar botón siguiente si el paso actual no está completo
        const pasoCompleto = verificarPasoCompleto(pasoActual);
        // console.log eliminado
        
        if (!pasoCompleto) {
            btnSiguiente.disabled = true;
        } else {
            btnSiguiente.disabled = false;
        }
    }

    // Función para ir al siguiente paso
    function siguientePaso() {
        const pasoCompleto = verificarPasoCompleto(pasoActual);
        
        if (pasoCompleto && pasoActual < totalPasos) {
                        pasoActual++;
                        mostrarPaso(pasoActual);
        }
    }

    // Función para ir al paso anterior
    function pasoAnterior() {
        if (pasoActual > 1) {
            pasoActual--;
            mostrarPaso(pasoActual);
        }
    }

    // Función para generar el resumen de confirmación
    function generarResumenConfirmacion() {
        const resumenContainer = document.getElementById('resumen-venta');
        const resumenHTML = mostrarResumenVenta();
        resumenContainer.innerHTML = resumenHTML;
    }

    // Eventos para botones de navegación
    document.addEventListener('DOMContentLoaded', function() {
        const btnSiguiente = document.getElementById('btnSiguiente');
        const btnAnterior = document.getElementById('btnAnterior');
        const btnValidarPaso4 = document.getElementById('btnValidarPaso4');
        
        if (btnSiguiente) {
            btnSiguiente.addEventListener('click', function(e) {
                e.preventDefault();
                siguientePaso();
            });
        }
        
        if (btnAnterior) {
            btnAnterior.addEventListener('click', function(e) {
                e.preventDefault();
                pasoAnterior();
            });
        }
        
        if (btnValidarPaso4) {
            btnValidarPaso4.addEventListener('click', function() {
                mostrarAlertasPaso4();
            });
        }
        
        // Inicializar el primer paso
        mostrarPaso(1);
        
        // Forzar actualización de validación inicial
        setTimeout(() => {
            actualizarBotonesNavegacion();
        }, 100);
    });

    // Evento adicional para el botón anterior usando delegación
    document.addEventListener('click', function(e) {
        if (e.target && e.target.id === 'btnAnterior') {
            e.preventDefault();
            pasoAnterior();
        }
    });

    // Función para validar el formulario completo antes de enviar
    function validarFormularioCompleto() {
        const errores = [];
        const advertencias = [];
        
        // Validar cliente - ahora solo verifica que tenga datos válidos
        const cedula = cedulaInput.value.trim();
        const nombre = nombreInput.value.trim();
        const apellido = apellidoInput.value.trim();
        const telefono = telefonoInput.value.trim();
        const correo = correoInput.value.trim();
        
        if (!cedula || !nombre || !apellido || !telefono || !correo) {
            errores.push('Los datos del cliente están incompletos');
        } else {
            // Validar formato de cédula
            if (!regexCedula.test(cedula)) {
                errores.push('Formato de cédula inválido');
            }
            
            // Validar formato de teléfono
            if (!regexTelefono.test(telefono)) {
                errores.push('Formato de teléfono inválido');
            }
            
            // Validar formato de correo
            if (!regexCorreo.test(correo)) {
                errores.push('Formato de correo inválido');
            }
        }
        
        // Validar productos
        const productos = document.querySelectorAll('#productos-container-venta tr.producto-fila');
        let productosValidos = 0;
        let totalProductos = 0;

        productos.forEach((fila, index) => {
            const select = fila.querySelector('.producto-select-venta');
            const cantidad = fila.querySelector('.cantidad-input-venta');
            const precio = fila.querySelector('.precio-input-venta');
            
            if (select.value && cantidad.value > 0 && precio.value > 0) {
                productosValidos++;
                totalProductos += parseInt(cantidad.value);
            } else if (select.value || cantidad.value > 0 || precio.value > 0) {
                errores.push(`Fila ${index + 1}: Complete todos los datos del producto`);
            }
        });
        
        if (productosValidos === 0) {
            errores.push('Debe seleccionar al menos un producto');
        }

        if (totalProductos === 0) {
            errores.push('La cantidad total de productos debe ser mayor a 0');
        }

        // Validar total
        const totalVenta = parseFloat(document.getElementById('total-general-venta').textContent.replace('$', '')) || 0;
        if (totalVenta <= 0) {
            errores.push('El total de la venta debe ser mayor a 0');
        }
        
        // Validar métodos de pago
        const metodosPago = document.querySelectorAll('.metodo-pago-select');
        let metodosSeleccionados = 0;
        
        metodosPago.forEach(select => {
            if (select.value) {
                metodosSeleccionados++;
            }
        });
        
        if (metodosSeleccionados === 0) {
            errores.push('Debe seleccionar al menos un método de pago');
        }
        
        // Validar campos específicos de métodos de pago
        if (!validarCamposMetodoPago()) {
            advertencias.push('Verifique los campos de los métodos de pago');
        }

        // Validar suma de métodos de pago
        if (!validarTotalMetodosPago()) {
            advertencias.push('La suma de los métodos de pago no coincide con el total');
        }

        return { errores, advertencias, esValido: errores.length === 0 };
    }

    // Función para mostrar errores de validación
    function mostrarErroresValidacion(errores, advertencias) {
        let mensaje = '';
        let tipo = 'info';

        if (errores.length > 0) {
            mensaje += '<strong>Errores que deben corregirse:</strong><br>';
            errores.forEach(error => {
                mensaje += `• ${error}<br>`;
            });
            mensaje += '<br>';
            tipo = 'error';
        }

        if (advertencias.length > 0) {
            mensaje += '<strong>Advertencias:</strong><br>';
            advertencias.forEach(advertencia => {
                mensaje += `• ${advertencia}<br>`;
            });
            if (errores.length === 0) {
                tipo = 'warning';
            }
        }

        if (errores.length === 0 && advertencias.length === 0) {
            mensaje = '¡Todo está correcto! Puede proceder con el registro de la venta.';
            tipo = 'success';
        }

        Swal.fire({
            title: 'Validación del Formulario',
            html: mensaje,
            icon: tipo,
            confirmButtonText: 'Entendido'
        });

        return errores.length === 0;
    }

    // Función para mostrar alertas de validación en el paso 4
    function mostrarAlertasPaso4() {
        const { errores, advertencias } = validarFormularioCompleto();
        
        let mensaje = '';
        let tipo = 'info';
        
        if (errores.length > 0) {
            mensaje += '<strong>Errores que deben corregirse:</strong><br>';
            errores.forEach(error => {
                mensaje += `• ${error}<br>`;
            });
            mensaje += '<br>';
            tipo = 'error';
        }
        
        if (advertencias.length > 0) {
            mensaje += '<strong>Advertencias:</strong><br>';
            advertencias.forEach(advertencia => {
                mensaje += `• ${advertencia}<br>`;
            });
            if (errores.length === 0) {
                tipo = 'warning';
            }
        }
        
        if (errores.length === 0 && advertencias.length === 0) {
            mensaje = '¡Todo está correcto! Puede proceder con el registro de la venta.';
            tipo = 'success';
        }
        
        // Mostrar alerta
        Swal.fire({
            title: 'Validación del Formulario',
            html: mensaje,
            icon: tipo,
            confirmButtonText: 'Entendido'
        });
        
        return errores.length === 0;
    }

    // Tour de ayuda por pasos en el modal de registro
    if (document.getElementById('btnAyudaModal')) {
      document.getElementById('btnAyudaModal').addEventListener('click', function() {
        const pasoActual = document.querySelector('.step-content.active');
        let steps = [];
        if (pasoActual && pasoActual.id === 'step-1-content') {
          steps = [
            {
              element: '#step-1-content .form-label.fw-bold',
              popover: {
                title: 'Cédula del Cliente',
                description: 'Ingrese la cédula del cliente. Si ya está registrado, los datos se cargarán automáticamente.',
                side: 'bottom'
              }
            },
            {
              element: '#campos-cliente',
              popover: {
                title: 'Datos del Cliente',
                description: 'Complete los datos personales si el cliente es nuevo.',
                side: 'bottom'
              }
            }
          ];
        } else if (pasoActual && pasoActual.id === 'step-2-content') {
          steps = [
            {
              element: '#step-2-content table',
              popover: {
                title: 'Productos',
                description: 'Seleccione los productos, cantidades y revise los subtotales.',
                side: 'top'
              }
            },
            {
              element: '#total-general-venta',
              popover: {
                title: 'Total de la Venta',
                description: 'Aquí se muestra el total acumulado de la venta.',
                side: 'top'
              }
            }
          ];
        } else if (pasoActual && pasoActual.id === 'step-3-content') {
          steps = [
            {
              element: '#step-3-content .form-label.fw-bold',
              popover: {
                title: 'Métodos de Pago',
                description: 'Seleccione y agregue los métodos de pago para cubrir el total de la venta.',
                side: 'bottom'
              }
            },
            {
              element: '#total-pagado-display',
              popover: {
                title: 'Total Pagado',
                description: 'Muestra cuánto se ha pagado hasta el momento.',
                side: 'top'
              }
            },
            {
              element: '#saldo-restante-display',
              popover: {
                title: 'Saldo Restante',
                description: 'Indica cuánto falta por pagar para completar la venta.',
                side: 'top'
              }
            }
          ];
        } else if (pasoActual && pasoActual.id === 'step-4-content') {
          steps = [
            {
              element: '#resumen-venta',
              popover: {
                title: 'Resumen de la Venta',
                description: 'Verifique todos los datos antes de confirmar la venta.',
                side: 'top'
              }
            },
            {
              element: '#btnRegistrarVenta',
              popover: {
                title: 'Registrar Venta',
                description: 'Presione aquí para finalizar y guardar la venta.',
                side: 'top'
              }
            }
          ];
        }
        if (window.driver && window.driver.js && steps.length > 0) {
          const driver = window.driver.js.driver;
          const driverObj = new driver({
            nextBtnText: 'Siguiente',
            prevBtnText: 'Anterior',
            doneBtnText: 'Listo',
            popoverClass: 'driverjs-theme',
            closeBtn: false,
            steps: steps
          });
          driverObj.drive();
        }
      });
    }
}); 
