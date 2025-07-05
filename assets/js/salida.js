document.addEventListener('DOMContentLoaded', function() {
    // Referencias a elementos del formulario
    const formVenta = document.getElementById('formRegistroVenta');
    const cedulaInput = document.getElementById('cedula_cliente');
    const nombreInput = document.getElementById('nombre_cliente');
    const apellidoInput = document.getElementById('apellido_cliente');
    const telefonoInput = document.getElementById('telefono_cliente');
    const correoInput = document.getElementById('correo_cliente');
    
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

            // Validar métodos de pago
            if (!validarCamposMetodoPago()) {
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
        
        // Actualizar montos en los métodos de pago cuando cambie el total
        // Solo si hay un método de pago seleccionado, obtener la tasa y actualizar
        const metodoSeleccionado = document.querySelector('.metodo-pago-select');
        if (metodoSeleccionado && metodoSeleccionado.value) {
            obtenerTasaCambio();
        }
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

    // Inicializar eventos de productos
    inicializarEventosProducto();

    // Eventos para métodos de pago
    function inicializarEventosMetodoPago() {
        // Evento para agregar método de pago
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('agregar-metodo-pago')) {
                const fila = e.target.closest('.metodo-pago-fila');
                const nuevaFila = fila.cloneNode(true);
                
                // Limpiar valores de la nueva fila
                nuevaFila.querySelector('.metodo-pago-select').value = '';
                nuevaFila.querySelector('input[name="monto_metodopago[]"]').value = '';
                
                // Obtener el contenedor de botones
                const contenedorBotones = nuevaFila.querySelector('.col-md-2:last-child');
                
                // Limpiar todos los botones existentes
                contenedorBotones.innerHTML = '';
                
                // Agregar solo el botón de eliminar
                const btnEliminar = document.createElement('button');
                btnEliminar.type = 'button';
                btnEliminar.className = 'btn btn-danger btn-sm remover-metodo-pago';
                btnEliminar.innerHTML = '<i class="fas fa-trash-alt"></i>';
                contenedorBotones.appendChild(btnEliminar);
                
                document.getElementById('metodos-pago-container').appendChild(nuevaFila);
                inicializarEventosFilaMetodoPago(nuevaFila);
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
        // Eventos para cambio de método de pago
        const select = fila.querySelector('.metodo-pago-select');
        const montoInput = fila.querySelector('.monto-metodopago');
        
        select.addEventListener('change', function() {
            console.log('Cambio en select de método de pago');
            console.log('Valor seleccionado:', this.value);
            
            if (this.value) {
                const option = this.options[this.selectedIndex];
                const nombreMetodo = option.getAttribute('data-nombre');
                console.log('Nombre del método:', nombreMetodo);
                
                mostrarCamposMetodoPago(nombreMetodo);
                validarTotalMetodosPago();
                
                // Actualizar montos cuando se seleccione un método
                setTimeout(() => {
                    actualizarMontosEnBs();
                }, 100);
            } else {
                console.log('No hay método seleccionado');
                ocultarTodosLosCamposMetodoPago();
            }
        });
        
        montoInput.addEventListener('input', function() {
            validarTotalMetodosPago();
            // No llamar actualizarMontosEnBs() aquí ya que requiere tasa de cambio
        });
    }

    function mostrarCamposMetodoPago(nombreMetodo) {
        // Ocultar todos los campos primero
        ocultarTodosLosCamposMetodoPago();
        
        console.log('Método seleccionado:', nombreMetodo); // Debug
        
        // Mostrar campos según el método seleccionado (nombres exactos de la BD)
        if (nombreMetodo === 'Divisas $') {
            console.log('Mostrando campos Divisa $');
            document.getElementById('campos-divisa').style.display = 'block';
        } else if (nombreMetodo === 'Efectivo Bs') {
            console.log('Mostrando campos Efectivo Bs');
            document.getElementById('campos-efectivo').style.display = 'block';
            // Obtener tasa de cambio del API
            obtenerTasaCambio();
        } else if (nombreMetodo === 'Pago Movil') {
            console.log('Mostrando campos Pago Móvil');
            document.getElementById('campos-pago-movil').style.display = 'block';
            obtenerTasaCambio();
        } else if (nombreMetodo === 'Punto de Venta') {
            console.log('Mostrando campos Punto de Venta');
            document.getElementById('campos-punto-venta').style.display = 'block';
            obtenerTasaCambio();
        } else if (nombreMetodo === 'Transferencia Bancaria') {
            console.log('Mostrando campos Transferencia Bancaria');
            document.getElementById('campos-transferencia').style.display = 'block';
            obtenerTasaCambio();
        } else {
            console.log('Método no reconocido:', nombreMetodo);
        }
        
        document.getElementById('campos-metodo-pago-dinamicos').style.display = 'block';
    }

    function ocultarTodosLosCamposMetodoPago() {
        const campos = document.querySelectorAll('.campos-metodo');
        campos.forEach(campo => {
            campo.style.display = 'none';
        });
        document.getElementById('campos-metodo-pago-dinamicos').style.display = 'none';
    }

    async function obtenerTasaCambio() {
        try {
            console.log('Obteniendo tasa de cambio del API...');
            
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
            
            console.log('Tasa de cambio obtenida:', tasaCambio);
            console.log('Fuente: BCV Oficial');
            
            // Calcular montos en Bs basado en el total de productos
            actualizarMontosEnBs(tasaCambio);
            
        } catch (error) {
            console.error('Error al obtener tasa de cambio:', error);
            Swal.fire('Error', 'No se pudo obtener la tasa de cambio del dólar. Intente nuevamente.', 'error');
            // No actualizar montos si falla el API
        }
    }

    function actualizarMontosEnBs(tasaCambio) {
        // Verificar que se proporcione una tasa válida
        if (!tasaCambio || tasaCambio <= 0) {
            console.error('Tasa de cambio no válida:', tasaCambio);
            return;
        }
        
        // Obtener el total de productos (total de la venta)
        const totalVenta = parseFloat(document.getElementById('total-general-venta').textContent.replace('$', '')) || 0;
        
        console.log('Total de productos (USD):', totalVenta);
        console.log('Tasa de cambio utilizada:', tasaCambio);
        
        // Calcular el monto en bolívares
        const montosBs = totalVenta * tasaCambio;
        console.log('Monto en Bs:', montosBs);
        
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
        
        // Actualizar montos en USD para efectivo (total de productos)
        document.querySelectorAll('input[name="monto_efectivo_usd"]').forEach(input => {
            input.value = totalVenta.toFixed(2);
        });
        
        // Actualizar monto en USD para divisa (total de productos)
        document.querySelectorAll('input[name="monto_divisa"]').forEach(input => {
            input.value = totalVenta.toFixed(2);
        });
    }

    function validarTotalMetodosPago() {
        // Esta función puede ser expandida para validar que la suma de los métodos de pago
        // coincida con el total de la venta si es necesario
        console.log('Validando total de métodos de pago...');
        return true;
    }

    // Validaciones específicas para cada método de pago
    function validarCamposMetodoPago() {
        const metodoSeleccionado = document.querySelector('.metodo-pago-select').value;
        const option = document.querySelector('.metodo-pago-select option:checked');
        const nombreMetodo = option ? option.getAttribute('data-nombre') : '';
        
        if (!nombreMetodo) return true;
        
        // Validaciones según el método
        if (nombreMetodo.toLowerCase().includes('pago móvil') || nombreMetodo.toLowerCase().includes('movil')) {
            return validarPagoMovil();
        } else if (nombreMetodo.toLowerCase().includes('punto de venta') || nombreMetodo.toLowerCase().includes('pos')) {
            return validarPuntoVenta();
        } else if (nombreMetodo.toLowerCase().includes('transferencia bancaria') || nombreMetodo.toLowerCase().includes('transferencia')) {
            return validarTransferenciaBancaria();
        }
        
        return true;
    }

    function validarPagoMovil() {
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
        const referencia = document.querySelector('input[name="referencia_pv"]').value;
        
        if (!referencia || referencia.length < 4 || referencia.length > 6 || !/^\d+$/.test(referencia)) {
            Swal.fire('Error', 'La referencia debe tener entre 4 y 6 dígitos numéricos', 'error');
            return false;
        }
        
        return true;
    }

    function validarTransferenciaBancaria() {
        const referencia = document.querySelector('input[name="referencia_tb"]').value;
        
        if (!referencia || referencia.length < 4 || referencia.length > 6 || !/^\d+$/.test(referencia)) {
            Swal.fire('Error', 'La referencia debe tener entre 4 y 6 dígitos numéricos', 'error');
            return false;
        }
        
        return true;
    }

    // Inicializar eventos de métodos de pago
    inicializarEventosMetodoPago();

    // Función de inicialización para debugging
    function inicializarDebugMetodoPago() {
        console.log('Inicializando debug de métodos de pago...');
        
        // Verificar que los elementos existan
        const container = document.getElementById('metodos-pago-container');
        const dinamicos = document.getElementById('campos-metodo-pago-dinamicos');
        
        console.log('Container de métodos:', container);
        console.log('Campos dinámicos:', dinamicos);
        
        // Verificar que los campos específicos existan
        const camposDivisa = document.getElementById('campos-divisa');
        const camposEfectivo = document.getElementById('campos-efectivo');
        const camposPagoMovil = document.getElementById('campos-pago-movil');
        const camposPuntoVenta = document.getElementById('campos-punto-venta');
        const camposTransferencia = document.getElementById('campos-transferencia');
        
        console.log('Campos Divisa:', camposDivisa);
        console.log('Campos Efectivo:', camposEfectivo);
        console.log('Campos Pago Móvil:', camposPagoMovil);
        console.log('Campos Punto de Venta:', camposPuntoVenta);
        console.log('Campos Transferencia:', camposTransferencia);
        
        // Verificar opciones del select
        const select = document.querySelector('.metodo-pago-select');
        if (select) {
            console.log('Opciones disponibles:');
            Array.from(select.options).forEach((option, index) => {
                console.log(`${index}: ${option.text} (${option.value}) - data-nombre: ${option.getAttribute('data-nombre')}`);
            });
        }
        
        // Inicializar montos si hay un total disponible
        const totalVenta = parseFloat(document.getElementById('total-general-venta')?.textContent.replace('$', '')) || 0;
        if (totalVenta > 0) {
            console.log('Inicializando montos con total:', totalVenta);
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
                            title: 'Acción',
                            description: 'Contiene los botones de acción para cada venta: ver detalles.',
                            side: "bottom"
                        }
                    },
                    { element: '#btnAyuda', popover: { title: 'Botón de ayuda', description: 'Haz clic aquí para ver esta guía interactiva del módulo de ventas.', side: "bottom", align: 'start' }},
                    { element: '.btn-success[data-bs-target="#registroModal"]', popover: { title: 'Registrar venta', description: 'Este botón abre el formulario para registrar una nueva venta.', side: "bottom", align: 'start' }},
                    { element: '.btn-info', popover: { title: 'Ver detalles', description: 'Haz clic aquí para ver los detalles de una venta específica.', side: "left", align: 'start' }},
                    { popover: { title: 'Eso es todo', description: 'Este es el fin de la guía del módulo de ventas. ¡Gracias por usar el sistema!' } }
                ]
            });
            driverObj.drive();
        });
    }
}); 