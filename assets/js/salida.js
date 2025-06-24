document.addEventListener('DOMContentLoaded', function() {
    // Referencias a elementos del formulario
    const formVenta = document.getElementById('formRegistroVenta');
    const cedulaInput = document.getElementById('cedula_cliente');
    const nombreInput = document.getElementById('nombre_cliente');
    const apellidoInput = document.getElementById('apellido_cliente');
    const telefonoInput = document.getElementById('telefono_cliente');
    const correoInput = document.getElementById('correo_cliente');
    const metodoPagoSelect = document.getElementById('metodo_pago');
    const metodoEntregaSelect = document.getElementById('metodo_entrega');
    const camposPagoAdicionales = document.getElementById('campos_pago_adicionales');
    const campoDireccion = document.getElementById('campo_direccion');
    const referenciaBancaria = document.getElementById('referencia_bancaria');
    const telefonoEmisor = document.getElementById('telefono_emisor');
    const banco = document.getElementById('banco');
    const bancoDestino = document.getElementById('banco_destino');
    const direccion = document.getElementById('direccion');
    
    // Referencias para el registro de cliente
    const btnRegistrarCliente = document.getElementById('registrarCliente');
    const btnCancelarRegistro = document.getElementById('cancelarRegistro');
    const btnBuscarCliente = document.getElementById('btnBuscarCliente');
    const camposCliente = document.getElementById('campos-cliente');
    const contenedorProductos = document.getElementById('productos-container-venta');
    const idClienteHidden = document.getElementById('id_cliente_hidden');

    // Referencias a las secciones de venta y productos
    const seccionVenta = document.querySelector('.seccion-venta');
    const seccionProductos = document.querySelector('.seccion-productos');

    // Variable para almacenar el ID del cliente
    let clienteActualId = null;

    // Expresiones regulares para validaciones
    const regexSoloNumeros = /^[0-9]+$/;
    const regexCedula = /^[0-9]{7,8}$/;
    const regexSoloLetras = /^[A-Za-zÁáÉéÍíÓóÚúÑñ\s]+$/;
    const regexCorreo = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    const regexTelefono = /^0[0-9]{10}$/;
    const regexReferencia = /^[0-9]{4,6}$/;
    const regexTelefonoPago = /^(04|02)[0-9]{9}$/;

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
        elemento.classList.remove('is-invalid');
        elemento.classList.remove('is-valid');
        const errorDiv = elemento.parentNode.querySelector('.invalid-feedback');
        if (errorDiv) {
            errorDiv.remove();
        }
    }

    function mostrarExito(elemento) {
        elemento.classList.remove('is-invalid');
        elemento.classList.add('is-valid');
    }

    function resetearFormulario() {
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
        
        // Ocultar secciones
        camposCliente.style.display = 'none';
        seccionVenta.style.display = 'none';
        seccionProductos.style.display = 'none';
        
        // Mostrar botón de búsqueda
        btnBuscarCliente.style.display = 'block';
        btnCancelarRegistro.style.display = 'none';
        
        clienteActualId = null;
    }

    // Evento para cuando se abre el modal de registro
    const registroModal = document.getElementById('registroModal');
    if (registroModal) {
        registroModal.addEventListener('show.bs.modal', function () {
            resetearFormulario();
        });
    }

    // Evento para buscar cliente
    if (btnBuscarCliente) {
        btnBuscarCliente.addEventListener('click', function() {
            const cedula = cedulaInput.value.trim();
            
            // Validar cédula
            if (!cedula) {
                mostrarError(cedulaInput, 'Por favor ingrese una cédula');
                return;
            }

            if (!regexCedula.test(cedula)) {
                mostrarError(cedulaInput, 'La cédula debe tener entre 7 y 8 dígitos');
                return;
            }

            // Mostrar loading
            btnBuscarCliente.disabled = true;
            btnBuscarCliente.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Buscando...';

            // Obtener token CSRF
            const csrfToken = document.querySelector('input[name="csrf_token"]')?.value;
            if (!csrfToken) {
                Swal.fire('Error', 'Error de seguridad. Recargue la página.', 'error');
                btnBuscarCliente.disabled = false;
                btnBuscarCliente.innerHTML = '<i class="fas fa-search"></i> Buscar';
                return;
            }

            fetch('?pagina=salida', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `buscar_cliente=1&cedula=${encodeURIComponent(cedula)}&csrf_token=${csrfToken}`
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then(data => {
                btnBuscarCliente.disabled = false;
                btnBuscarCliente.innerHTML = '<i class="fas fa-search"></i> Buscar';

                if (data.respuesta === 1 && data.cliente) {
                    // Cliente encontrado
                    clienteActualId = data.cliente.id_persona;
                    idClienteHidden.value = data.cliente.id_persona;
                    
                    nombreInput.value = data.cliente.nombre || '';
                    apellidoInput.value = data.cliente.apellido || '';
                    telefonoInput.value = data.cliente.telefono || '';
                    correoInput.value = data.cliente.correo || '';
                    
                    // Hacer campos readonly
                    nombreInput.readOnly = true;
                    apellidoInput.readOnly = true;
                    telefonoInput.readOnly = true;
                    correoInput.readOnly = true;
                    
                    // Mostrar campos y secciones
                    camposCliente.style.display = 'block';
                    seccionVenta.style.display = 'block';
                    seccionProductos.style.display = 'block';
                    
                    // Ocultar botón de búsqueda y mostrar cancelar
                    btnBuscarCliente.style.display = 'none';
                    btnCancelarRegistro.style.display = 'block';
                    
                    // Mostrar éxito en cédula
                    mostrarExito(cedulaInput);
                    
                    Swal.fire('Éxito', 'Cliente encontrado', 'success');
                } else {
                    // Cliente no encontrado - mostrar campos para registro automáticamente
                    Swal.fire({
                        title: 'Cliente no encontrado',
                        text: 'Complete los datos para registrar un nuevo cliente',
                        icon: 'info',
                        confirmButtonText: 'Entendido'
                    }).then(() => {
                            // Mostrar campos para registro
                            camposCliente.style.display = 'block';
                            nombreInput.readOnly = false;
                            apellidoInput.readOnly = false;
                            telefonoInput.readOnly = false;
                            correoInput.readOnly = false;
                            
                            // Limpiar campos
                            nombreInput.value = '';
                            apellidoInput.value = '';
                            telefonoInput.value = '';
                            correoInput.value = '';
                            
                            // Ocultar botón de búsqueda y mostrar cancelar
                            btnBuscarCliente.style.display = 'none';
                            btnCancelarRegistro.style.display = 'block';
                        
                        // Mostrar secciones de venta y productos
                        seccionVenta.style.display = 'block';
                        seccionProductos.style.display = 'block';
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                btnBuscarCliente.disabled = false;
                btnBuscarCliente.innerHTML = '<i class="fas fa-search"></i> Buscar';
                Swal.fire('Error', 'Error al buscar el cliente. Intente nuevamente.', 'error');
            });
        });
    }

    // Evento para cancelar registro
    if (btnCancelarRegistro) {
        btnCancelarRegistro.addEventListener('click', function() {
            resetearFormulario();
        });
    }

    // Evento para registrar cliente
    if (btnRegistrarCliente) {
        btnRegistrarCliente.addEventListener('click', function() {
        const datos = {
                cedula: cedulaInput.value.trim(),
                nombre: nombreInput.value.trim(),
                apellido: apellidoInput.value.trim(),
                telefono: telefonoInput.value.trim(),
                correo: correoInput.value.trim()
            };

            // Validar datos
            let hayErrores = false;

            if (!datos.cedula) {
                mostrarError(cedulaInput, 'La cédula es obligatoria');
                hayErrores = true;
            } else if (!regexCedula.test(datos.cedula)) {
                mostrarError(cedulaInput, 'La cédula debe tener entre 7 y 8 dígitos');
                hayErrores = true;
            } else {
                mostrarExito(cedulaInput);
            }

            if (!datos.nombre) {
                mostrarError(nombreInput, 'El nombre es obligatorio');
                hayErrores = true;
            } else if (!regexSoloLetras.test(datos.nombre)) {
                mostrarError(nombreInput, 'El nombre solo puede contener letras');
                hayErrores = true;
            } else if (datos.nombre.length < 2) {
                mostrarError(nombreInput, 'El nombre debe tener al menos 2 caracteres');
                hayErrores = true;
            } else {
                mostrarExito(nombreInput);
            }

            if (!datos.apellido) {
                mostrarError(apellidoInput, 'El apellido es obligatorio');
                hayErrores = true;
            } else if (!regexSoloLetras.test(datos.apellido)) {
                mostrarError(apellidoInput, 'El apellido solo puede contener letras');
                hayErrores = true;
            } else if (datos.apellido.length < 2) {
                mostrarError(apellidoInput, 'El apellido debe tener al menos 2 caracteres');
                hayErrores = true;
            } else {
                mostrarExito(apellidoInput);
            }

            if (!datos.telefono) {
                mostrarError(telefonoInput, 'El teléfono es obligatorio');
                hayErrores = true;
            } else if (!regexTelefono.test(datos.telefono)) {
                mostrarError(telefonoInput, 'El teléfono debe tener 11 dígitos y comenzar con 0');
                hayErrores = true;
            } else {
                mostrarExito(telefonoInput);
            }

            if (!datos.correo) {
                mostrarError(correoInput, 'El correo es obligatorio');
                hayErrores = true;
            } else if (!regexCorreo.test(datos.correo)) {
                mostrarError(correoInput, 'Ingrese un correo electrónico válido');
                hayErrores = true;
            } else {
                mostrarExito(correoInput);
            }

            if (hayErrores) {
                return;
            }

            // Mostrar loading
            btnRegistrarCliente.disabled = true;
            btnRegistrarCliente.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Registrando...';

            // Obtener token CSRF
            const csrfToken = document.querySelector('input[name="csrf_token"]')?.value;
            if (!csrfToken) {
                Swal.fire('Error', 'Error de seguridad. Recargue la página.', 'error');
                btnRegistrarCliente.disabled = false;
                btnRegistrarCliente.innerHTML = '<i class="fas fa-user-plus"></i> Registrar cliente';
                return;
            }

            fetch('?pagina=salida', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `registrar_cliente=1&${new URLSearchParams(datos).toString()}&csrf_token=${csrfToken}`
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then(data => {
                btnRegistrarCliente.disabled = false;
                btnRegistrarCliente.innerHTML = '<i class="fas fa-user-plus"></i> Registrar cliente';

                if (data.success) {
                    clienteActualId = data.id_cliente;
                    idClienteHidden.value = data.id_cliente;
                    
                    // Hacer campos readonly
                    nombreInput.readOnly = true;
                    apellidoInput.readOnly = true;
                    telefonoInput.readOnly = true;
                    correoInput.readOnly = true;
                    
                    // Mostrar secciones
                    seccionVenta.style.display = 'block';
                    seccionProductos.style.display = 'block';
                    
                    Swal.fire('Éxito', data.message, 'success');
                } else {
                    Swal.fire('Error', data.message || 'Error al registrar el cliente', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                btnRegistrarCliente.disabled = false;
                btnRegistrarCliente.innerHTML = '<i class="fas fa-user-plus"></i> Registrar cliente';
                Swal.fire('Error', 'Error al registrar el cliente. Intente nuevamente.', 'error');
            });
        });
    }

    // Evento para el formulario de venta
    if (formVenta) {
        formVenta.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validar que se haya seleccionado un cliente
            if (!clienteActualId) {
                Swal.fire('Error', 'Debe seleccionar o registrar un cliente primero', 'error');
                return;
            }

            // Validar método de pago
            if (!metodoPagoSelect.value) {
                Swal.fire('Error', 'Debe seleccionar un método de pago', 'error');
                return;
            }

            // Validar método de entrega
            if (!metodoEntregaSelect.value) {
                Swal.fire('Error', 'Debe seleccionar un método de entrega', 'error');
                return;
            }

            // Validar productos
            const productos = document.querySelectorAll('#productos-container-venta tr.producto-fila');
            if (productos.length === 0) {
                Swal.fire('Error', 'Debe agregar al menos un producto', 'error');
                return;
            }

            // Validar que todos los productos tengan datos válidos
            let productosValidos = 0;
            productos.forEach(fila => {
                const select = fila.querySelector('.producto-select-venta');
                const cantidad = fila.querySelector('.cantidad-input-venta');
                if (select.value && cantidad.value > 0) {
                    productosValidos++;
                }
            });

            if (productosValidos === 0) {
                Swal.fire('Error', 'Debe seleccionar al menos un producto válido', 'error');
                return;
            }

            // Validar datos de pago según el método seleccionado
            if (metodoPagoSelect.value === '1') { // Pago Móvil
                if (!referenciaBancaria.value.trim()) {
                    mostrarError(referenciaBancaria, 'La referencia bancaria es obligatoria');
                    return;
                }
                if (!telefonoEmisor.value.trim()) {
                    mostrarError(telefonoEmisor, 'El teléfono emisor es obligatorio');
                    return;
                }
                if (!banco.value) {
                    mostrarError(banco, 'Debe seleccionar un banco emisor');
                    return;
                }
                if (!bancoDestino.value) {
                    mostrarError(bancoDestino, 'Debe seleccionar un banco receptor');
                    return;
                }
            } else if (metodoPagoSelect.value === '2') { // Transferencia Bancaria
                if (!referenciaBancaria.value.trim()) {
                    mostrarError(referenciaBancaria, 'La referencia bancaria es obligatoria');
                    return;
                }
                if (!banco.value) {
                    mostrarError(banco, 'Debe seleccionar un banco emisor');
                    return;
                }
                if (!bancoDestino.value) {
                    mostrarError(bancoDestino, 'Debe seleccionar un banco receptor');
                    return;
                }
            } else if (metodoPagoSelect.value === '3') { // Punto de Venta
                if (!referenciaBancaria.value.trim()) {
                    mostrarError(referenciaBancaria, 'La referencia del punto es obligatoria');
                    return;
                }
            }

            // Validar dirección si es delivery
            if (metodoEntregaSelect.value === '1' && !direccion.value.trim()) {
                mostrarError(direccion, 'Debe ingresar la dirección de entrega');
                return;
            }

            // Mostrar confirmación
            Swal.fire({
                title: '¿Confirmar venta?',
                text: '¿Está seguro de que desea registrar esta venta?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, registrar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    registrarVenta();
                }
            });
        });
    }

    function registrarVenta() {
        // Mostrar loading
        const submitBtn = formVenta.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Registrando...';

        // Preparar datos del formulario
        const formData = new FormData(formVenta);
        formData.append('registrar', '1');

                    fetch('?pagina=salida', {
                        method: 'POST',
            body: formData
                    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }
            return response.json();
        })
                    .then(data => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;

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
                    })
                    .catch(error => {
                        console.error('Error:', error);
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
            Swal.fire('Error', 'Error al registrar la venta. Intente nuevamente.', 'error');
        });
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
                if (filas.length > 1) {
                    e.target.closest('tr').remove();
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
    }

    // Evento para cambio en método de pago
    if (metodoPagoSelect) {
        metodoPagoSelect.addEventListener('change', function() {
            // Limpiar campos y errores
            [referenciaBancaria, telefonoEmisor, banco, bancoDestino].forEach(limpiarError);
            referenciaBancaria.value = '';
            telefonoEmisor.value = '';
            banco.value = '';
            bancoDestino.value = '';

            if (this.value === '2') { // Transferencia Bancaria
                camposPagoAdicionales.style.display = 'flex';
                document.querySelector('label[for="referencia_bancaria"]').textContent = 'Referencia Bancaria';
                referenciaBancaria.setAttribute('required', true);
                banco.setAttribute('required', true);
                bancoDestino.setAttribute('required', true);
                
                // Ocultar campo de teléfono y ajustar grid
                document.getElementById('campo_telefono_emisor').style.display = 'none';
                
                // Ajustar el espacio de los campos visibles
                const campos = document.querySelectorAll('#campos_pago_adicionales > div:not(#campo_telefono_emisor)');
                campos.forEach(div => {
                    div.className = 'col-md-4';
                });
                
                telefonoEmisor.removeAttribute('required');
                document.querySelector('[for="referencia_bancaria"]').nextElementSibling.placeholder = 'Número de referencia bancaria';

                // Mostrar campos de banco
                banco.parentElement.parentElement.style.display = 'block';
                bancoDestino.parentElement.parentElement.style.display = 'block';

                // Limpiar y configurar las opciones del banco destino
                bancoDestino.innerHTML = `
                    <option value="">Seleccione un banco</option>
                    <option value="0102-Banco De Venezuela">Banco de Venezuela</option>
                    <option value="0105-Banco Mercantil">Banco Mercantil</option>
                `;
            } else if (this.value === '1') { // Pago Móvil
                camposPagoAdicionales.style.display = 'flex';
                document.querySelector('label[for="referencia_bancaria"]').textContent = 'Referencia Bancaria';
                referenciaBancaria.setAttribute('required', true);
                telefonoEmisor.setAttribute('required', true);
                banco.setAttribute('required', true);
                bancoDestino.setAttribute('required', true);

                // Mostrar campo de teléfono y restaurar grid original
                document.getElementById('campo_telefono_emisor').style.display = 'block';
                document.querySelectorAll('#campos_pago_adicionales > div').forEach(div => {
                    div.className = 'col-md-3';
                });

                // Mostrar campos de banco
                banco.parentElement.parentElement.style.display = 'block';
                bancoDestino.parentElement.parentElement.style.display = 'block';
                document.querySelector('[for="referencia_bancaria"]').nextElementSibling.placeholder = 'Número de referencia bancaria';

                // Limpiar y configurar las opciones del banco destino
                bancoDestino.innerHTML = `
                    <option value="">Seleccione un banco</option>
                    <option value="0102-Banco De Venezuela">Banco de Venezuela</option>
                    <option value="0105-Banco Mercantil">Banco Mercantil</option>
                `;
            } else if (this.value === '3') { // Punto de Venta
                camposPagoAdicionales.style.display = 'flex';
                document.querySelector('label[for="referencia_bancaria"]').textContent = 'Referencia del Punto';
                referenciaBancaria.setAttribute('required', true);
                document.querySelector('[for="referencia_bancaria"]').nextElementSibling.placeholder = 'Número de referencia del punto';

                // Ocultar campos innecesarios
                document.getElementById('campo_telefono_emisor').style.display = 'none';
                banco.parentElement.parentElement.style.display = 'none';
                bancoDestino.parentElement.parentElement.style.display = 'none';

                // Ajustar el espacio del campo de referencia
                document.querySelector('#campos_pago_adicionales > div:first-child').className = 'col-md-12';
                
                // Remover required de campos ocultos
                telefonoEmisor.removeAttribute('required');
                banco.removeAttribute('required');
                bancoDestino.removeAttribute('required');

            } else {
                camposPagoAdicionales.style.display = 'none';
                referenciaBancaria.removeAttribute('required');
                telefonoEmisor.removeAttribute('required');
                banco.removeAttribute('required');
                bancoDestino.removeAttribute('required');
            }
        });
    }

    // Evento para cambio en método de entrega
    if (metodoEntregaSelect && campoDireccion && direccion) {
        metodoEntregaSelect.addEventListener('change', function() {
            // Limpiar campo y errores
            limpiarError(direccion);
            direccion.value = '';

            if (this.value === '1') { // Delivery
                campoDireccion.style.display = 'block';
                document.querySelector('label[for="direccion"]').textContent = 'Dirección de Entrega';
                direccion.setAttribute('required', true);
                direccion.setAttribute('placeholder', 'Ingrese la dirección completa para la entrega');
                
                // Ajustar el espacio del campo de dirección
                document.querySelector('#campo_direccion > div').className = 'col-md-12';
                
            } else if (this.value === '2') { // MRW
                campoDireccion.style.display = 'block';
                document.querySelector('label[for="direccion"]').textContent = 'Dirección de Oficina MRW';
                direccion.setAttribute('required', true);
                direccion.setAttribute('placeholder', 'Ingrese la dirección de la oficina MRW para el retiro');
                
                // Ajustar el espacio del campo de dirección
                document.querySelector('#campo_direccion > div').className = 'col-md-12';
                
            } else if (this.value === '3') { // Zoom
                campoDireccion.style.display = 'block';
                document.querySelector('label[for="direccion"]').textContent = 'Dirección de Oficina Zoom';
                direccion.setAttribute('required', true);
                direccion.setAttribute('placeholder', 'Ingrese la dirección de la oficina Zoom para el retiro');
                
                // Ajustar el espacio del campo de dirección
                document.querySelector('#campo_direccion > div').className = 'col-md-12';
                
            } else {
                campoDireccion.style.display = 'none';
                direccion.removeAttribute('required');
            }
        });
    }

    // Asegurarse de que los campos adicionales estén ocultos inicialmente
    if (camposPagoAdicionales) {
        camposPagoAdicionales.style.display = 'none';
    }
    if (campoDireccion) {
        campoDireccion.style.display = 'none';
    }

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
            }
        } else {
            limpiarError(this);
        }
    });

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
    });

    // Validación de referencia bancaria
    if (referenciaBancaria) {
    referenciaBancaria.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);
        
        if (this.value.length > 0) {
            if (!regexReferencia.test(this.value)) {
                mostrarError(this, 'La referencia debe tener entre 4 y 6 dígitos');
                } else {
                    mostrarExito(this);
                }
            } else {
                limpiarError(this);
        }
    });
    }

    // Validación de teléfono emisor
    if (telefonoEmisor) {
    telefonoEmisor.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);
        
        if (this.value.length > 0) {
            if (!regexTelefonoPago.test(this.value)) {
                    mostrarError(this, 'El teléfono debe empezar con 04 o 02');
                } else {
                    mostrarExito(this);
                }
            } else {
                limpiarError(this);
        }
    });
    }

    // Validación de banco
    if (banco) {
    banco.addEventListener('change', function() {
            if (this.value) {
                mostrarExito(this);
        } else {
            limpiarError(this);
        }
    });
    }

    // Validación de banco destino
    if (bancoDestino) {
    bancoDestino.addEventListener('change', function() {
            if (this.value) {
                mostrarExito(this);
        } else {
            limpiarError(this);
        }
    });
    }

    // Validación de dirección
    if (direccion) {
    direccion.addEventListener('input', function() {
            if (this.value.length > 0) {
                if (this.value.length < 10) {
            mostrarError(this, 'La dirección debe tener al menos 10 caracteres');
                } else {
                    mostrarExito(this);
                }
        } else {
            limpiarError(this);
        }
    });
    }

    // Inicializar eventos de productos
    inicializarEventosProducto();

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
                            title: 'Estado',
                            description: 'Refleja el estado actual de la venta. Ejemplo: Verificar pago, Entregado, Enviado, Anulado, etc.',
                            side: "bottom"
                        }
                    },
                    {
                        element: '.table-color th:nth-child(4)',
                        popover: {
                            title: 'Total',
                            description: 'Muestra el monto total de la venta en dólares estadounidenses (USD).',
                            side: "bottom"
                        }
                    },
                    {
                        element: '.table-color th:nth-child(5)',
                        popover: {
                            title: 'Método Pago',
                            description: 'Indica el método de pago utilizado por el cliente, por ejemplo: Pago móvil, Transferencia bancaria, Punto de venta, etc.',
                            side: "bottom"
                        }
                    },
                    {
                        element: '.table-color th:nth-child(6)',
                        popover: {
                            title: 'Método Entrega',
                            description: 'Especifica el método de entrega seleccionado para la venta, como Delivery, MRW, Zoom, etc.',
                            side: "bottom"
                        }
                    },
                    {
                        element: '.table-color th:nth-child(7)',
                        popover: {
                            title: 'Acción',
                            description: 'Contiene los botones de acción para cada venta: ver detalles, gestionar delivery o actualizar el estado.',
                            side: "bottom"
                        }
                    },
                    { element: '#btnAyuda', popover: { title: 'Botón de ayuda', description: 'Haz clic aquí para ver esta guía interactiva del módulo de ventas.', side: "bottom", align: 'start' }},
                    { element: '.btn-success[data-bs-target="#registroModal"]', popover: { title: 'Registrar venta', description: 'Este botón abre el formulario para registrar una nueva venta.', side: "bottom", align: 'start' }},
                    { element: '.btn-info', popover: { title: 'Ver detalles', description: 'Haz clic aquí para ver los detalles de una venta específica.', side: "left", align: 'start' }},
                    { element: '.btn-primary[data-bs-target^="#deliveryModal"]', popover: { title: 'Gestionar delivery', description: 'Permite actualizar el estado de entrega de la venta.', side: "left", align: 'start' }},
                    { popover: { title: 'Eso es todo', description: 'Este es el fin de la guía del módulo de ventas. ¡Gracias por usar el sistema!' } }
                ]
            });
            driverObj.drive();
        });
    }

    // Eventos para editar dirección en modales de delivery
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('btnEditarDireccion')) {
            const input = e.target.previousElementSibling;
            const btnEditar = e.target;
            
            if (input.readOnly) {
                input.readOnly = false;
                input.style.backgroundColor = 'white';
                btnEditar.innerHTML = '<i class="fas fa-save"></i> Guardar';
                btnEditar.className = 'btn btn-success btn-sm btnEditarDireccion';
            } else {
                input.readOnly = true;
                input.style.backgroundColor = '#e9ecef';
                btnEditar.innerHTML = '<i class="fas fa-pencil-alt"></i> Editar';
                btnEditar.className = 'btn btn-warning btn-sm btnEditarDireccion';
            }
        }
    });
}); 