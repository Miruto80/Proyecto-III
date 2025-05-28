document.addEventListener('DOMContentLoaded', function() {
    // Referencias a elementos del formulario
    const formVenta = document.getElementById('formRegistroVenta');
    const formBuscarCliente = document.getElementById('formBuscarCliente');
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
    const btnAgregarProducto = document.getElementById('agregar-producto-venta');
    const idClienteHidden = document.getElementById('id_cliente_hidden');

    // Evento para cuando se abre el modal de registro
    const registroModal = document.getElementById('registroModal');
    if (registroModal) {
        registroModal.addEventListener('show.bs.modal', function () {
            // Estado inicial al abrir el modal
            camposCliente.style.display = 'none';
            btnBuscarCliente.style.display = 'block';
            btnCancelarRegistro.style.display = 'none';
            btnRegistrarCliente.style.display = 'block';
            
            // Limpiar todos los campos
            cedulaInput.value = '';
            nombreInput.value = '';
            apellidoInput.value = '';
            telefonoInput.value = '';
            correoInput.value = '';
            idClienteHidden.value = '';
            
            // Restablecer estados de readonly
            nombreInput.readOnly = false;
            apellidoInput.readOnly = false;
            telefonoInput.readOnly = false;
            correoInput.readOnly = false;
        });
    }

    // Variable para almacenar el ID del cliente
    let clienteActualId = null;

    // Expresiones regulares para validaciones
    const regexSoloNumeros = /^[0-9]+$/;
    const regexCedula = /^[0-9]{7,8}$/;
    const regexSoloLetras = /^[A-Za-zÁáÉéÍíÓóÚúÑñ\s]+$/;
    const regexCorreo = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    const regexTelefono = /^[0-9]{10}$/;

    // Expresiones regulares para validaciones de pago
    const regexReferencia = /^[0-9]{4,6}$/;
    const regexTelefonoPago = /^(04|02)[0-9]{9}$/;
    const regexBanco = /^[A-Za-zÁáÉéÍíÓóÚúÑñ\s]{3,20}$/;

    // Función para mostrar mensajes de error
    function mostrarError(elemento, mensaje) {
        let span = elemento.nextElementSibling;
        if (!span || !span.classList.contains('error-message')) {
            span = document.createElement('span');
            span.classList.add('error-message', 'text-danger');
            elemento.parentNode.insertBefore(span, elemento.nextSibling);
        }
        span.textContent = mensaje;
    }

    // Función para limpiar mensajes de error
    function limpiarError(elemento) {
        const span = elemento.nextElementSibling;
        if (span && span.classList.contains('error-message')) {
            span.textContent = '';
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
        
        if (this.value.length > 0 && !regexSoloNumeros.test(this.value)) {
            this.setCustomValidity('Solo se permiten números');
            this.classList.add('is-invalid');
        } else if (this.value.length < 7 || this.value.length > 8) {
            this.setCustomValidity('La cédula debe tener entre 7 y 8 dígitos');
            this.classList.add('is-invalid');
        } else {
            this.setCustomValidity('');
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
        }
    });

    // Validación del formulario de búsqueda
    if (formBuscarCliente) {
        formBuscarCliente.addEventListener('submit', function(e) {
            if (!this.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            this.classList.add('was-validated');
        });
    }

    // Validación de nombre y apellido
    [nombreInput, apellidoInput].forEach(input => {
        input.addEventListener('keypress', function(e) {
            if (!regexSoloLetras.test(e.key) && e.key !== 'Backspace' && e.key !== 'Tab' && e.key !== ' ') {
                e.preventDefault();
            }
        });

        input.addEventListener('keyup', function() {
            if (this.value.length > 0 && !regexSoloLetras.test(this.value)) {
                mostrarError(this, 'Solo se permiten letras');
            } else if (this.value.length < 3) {
                mostrarError(this, 'Debe contener al menos 3 caracteres');
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

    telefonoInput.addEventListener('keyup', function() {
        if (!regexTelefono.test(this.value)) {
            mostrarError(this, 'El teléfono debe tener 10 dígitos');
        } else {
            limpiarError(this);
        }
    });

    // Validación de correo
    correoInput.addEventListener('keyup', function() {
        if (!regexCorreo.test(this.value)) {
            mostrarError(this, 'Ingrese un correo electrónico válido');
        } else {
            limpiarError(this);
        }
    });

    // Evento para cambio en método de pago
    if (metodoPagoSelect) {
        metodoPagoSelect.addEventListener('change', function() {
            if (this.value === '1') {
                camposPagoAdicionales.style.display = 'flex';
                referenciaBancaria.setAttribute('required', true);
                telefonoEmisor.setAttribute('required', true);
                banco.setAttribute('required', true);
                bancoDestino.setAttribute('required', true);
            } else {
                camposPagoAdicionales.style.display = 'none';
                referenciaBancaria.removeAttribute('required');
                telefonoEmisor.removeAttribute('required');
                banco.removeAttribute('required');
                bancoDestino.removeAttribute('required');
                referenciaBancaria.value = '';
                telefonoEmisor.value = '';
                banco.value = '';
                bancoDestino.value = '';
                limpiarError(referenciaBancaria);
                limpiarError(telefonoEmisor);
                limpiarError(banco);
                limpiarError(bancoDestino);
            }
        });
    }

    // Evento para cambio en método de entrega
    if (metodoEntregaSelect && campoDireccion && direccion) {
        metodoEntregaSelect.addEventListener('change', function() {
            console.log('Método de entrega seleccionado:', this.value);
            if (this.value === '1') {
                console.log('Mostrando campo de dirección');
                campoDireccion.style.display = 'block';
                direccion.setAttribute('required', true);
            } else {
                console.log('Ocultando campo de dirección');
                campoDireccion.style.display = 'none';
                direccion.removeAttribute('required');
                direccion.value = '';
                limpiarError(direccion);
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

    // Validación de referencia bancaria
    referenciaBancaria.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);
        
        if (this.value.length > 0) {
            if (!regexReferencia.test(this.value)) {
                mostrarError(this, 'La referencia debe tener entre 4 y 6 dígitos');
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else {
                limpiarError(this);
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        }
    });

    // Validación de teléfono emisor
    telefonoEmisor.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11);
        
        if (this.value.length > 0) {
            if (!regexTelefonoPago.test(this.value)) {
                mostrarError(this, 'El teléfono debe empezar con 04');
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else {
                limpiarError(this);
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
        }
    });

    // Validación de banco
    banco.addEventListener('change', function() {
        if (!this.value) {
            mostrarError(this, 'Debe seleccionar un banco emisor');
            this.classList.add('is-invalid');
            this.classList.remove('is-valid');
        } else {
            limpiarError(this);
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
        }
    });

    // Validación de banco destino
    bancoDestino.addEventListener('change', function() {
        if (!this.value) {
            mostrarError(this, 'Debe seleccionar un banco receptor');
            this.classList.add('is-invalid');
            this.classList.remove('is-valid');
        } else {
            limpiarError(this);
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
        }
    });

    // Validación de dirección
    direccion.addEventListener('input', function() {
        if (this.value.length > 0 && this.value.length < 10) {
            mostrarError(this, 'La dirección debe tener al menos 10 caracteres');
            this.classList.add('is-invalid');
            this.classList.remove('is-valid');
        } else {
            limpiarError(this);
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
        }
    });

    // Función para calcular subtotal
    function calcularSubtotal(fila) {
        const cantidad = parseInt(fila.querySelector('.cantidad-input-venta').value) || 0;
        const precioStr = fila.querySelector('.precio-input-venta').value.replace('$', '').trim();
        const precio = parseFloat(precioStr) || 0;
        const subtotal = cantidad * precio;
        fila.querySelector('.subtotal-venta').textContent = subtotal.toFixed(2);
        actualizarTotal();
    }

    // Función para actualizar el total general
    function actualizarTotal() {
        let total = 0;
        document.querySelectorAll('.subtotal-venta').forEach(span => {
            const subtotalStr = span.textContent.replace('$', '').trim();
            total += parseFloat(subtotalStr) || 0;
        });
        document.getElementById('total-general-venta').textContent = `$${total.toFixed(2)}`;
    }

    // Función para inicializar eventos en fila de producto
    function inicializarEventosProducto(fila) {
        const selectProducto = fila.querySelector('.producto-select-venta');
        const inputCantidad = fila.querySelector('.cantidad-input-venta');
        const inputPrecio = fila.querySelector('.precio-input-venta');
        const btnRemover = fila.querySelector('.remover-producto-venta');
        const btnAgregar = fila.querySelector('.agregar-producto-venta');
        const stockInfo = fila.querySelector('.stock-info');

        // Inicializar evento para agregar producto
        if (btnAgregar) {
            btnAgregar.addEventListener('click', function() {
                const nuevaFila = fila.cloneNode(true);
                
                // Limpiar valores de la nueva fila
                nuevaFila.querySelector('.producto-select-venta').value = '';
                nuevaFila.querySelector('.precio-input-venta').value = '0.00';
                nuevaFila.querySelector('.subtotal-venta').textContent = '0.00';
                nuevaFila.querySelector('.cantidad-input-venta').value = '1';
                nuevaFila.querySelector('.stock-info').textContent = '';
                
                // Insertar la nueva fila después de la fila actual
                fila.parentNode.insertBefore(nuevaFila, fila.nextSibling);
                
                // Inicializar eventos en la nueva fila
                inicializarEventosProducto(nuevaFila);
                actualizarTotal();
            });
        }

        selectProducto.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value === '') {
                inputPrecio.value = '0.00';
                inputCantidad.value = '1';
                inputCantidad.removeAttribute('max');
                stockInfo.textContent = '';
                calcularSubtotal(fila);
                return;
            }
            
            // Obtener precio y stock del option seleccionado
            const precio = selectedOption.getAttribute('data-precio');
            const stock = parseInt(selectedOption.getAttribute('data-stock')) || 0;
            
            // Actualizar campos
            inputPrecio.value = precio || '0.00';
            inputCantidad.value = '1';
            inputCantidad.setAttribute('max', stock);
            
            // Mostrar stock disponible en el span
            stockInfo.textContent = `Stock: ${stock}`;
            if (stock === 0) {
                inputCantidad.classList.add('is-invalid');
                Swal.fire({
                    icon: 'warning',
                    title: 'Sin stock',
                    text: 'Este producto no tiene unidades disponibles'
                });
            } else {
                inputCantidad.classList.remove('is-invalid');
            }
            
            calcularSubtotal(fila);
        });

        inputCantidad.addEventListener('input', function() {
            const selectedOption = selectProducto.options[selectProducto.selectedIndex];
            const stock = parseInt(selectedOption.dataset.stock) || 0;
            const cantidad = parseInt(this.value) || 0;

            if (cantidad < 1) {
                this.value = 1;
            } else if (cantidad > stock) {
                this.value = stock;
                this.classList.add('is-invalid');
                Swal.fire({
                    icon: 'warning',
                    title: 'Stock insuficiente',
                    text: `Solo hay ${stock} unidades disponibles de este producto`
                });
            } else {
                this.classList.remove('is-invalid');
            }
            
            calcularSubtotal(fila);
        });

        btnRemover.addEventListener('click', function() {
            const totalFilas = contenedorProductos.querySelectorAll('.producto-fila').length;
            if (totalFilas > 1) {
                fila.remove();
                actualizarTotal();
            } else {
                Swal.fire({
                    title: '¡Atención!',
                    text: 'Debe mantener al menos un producto en la venta',
                    icon: 'warning',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Entendido'
                });
            }
        });
    }

    // Inicializar eventos en todas las filas existentes
    document.querySelectorAll('.producto-fila').forEach(fila => {
        inicializarEventosProducto(fila);
    });

    // Eliminar el evento de agregar producto del botón global ya que ahora cada fila tiene su propio botón
    if (btnAgregarProducto) {
        btnAgregarProducto.remove();
    }

    // Función para validar el formulario antes de enviar
    function validarFormularioVenta() {
        // Validar cliente (existente o nuevo)
        const idCliente = document.getElementById('id_cliente_hidden').value;
        const esClienteNuevo = !idCliente && 
                              nombreInput.value && 
                              apellidoInput.value && 
                              telefonoInput.value && 
                              correoInput.value;

        if (!idCliente && !esClienteNuevo) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Por favor, seleccione un cliente existente o complete los datos para registrar uno nuevo'
            });
            return false;
        }

        // Si es un cliente nuevo, validar los campos
        if (esClienteNuevo) {
            if (!regexSoloLetras.test(nombreInput.value)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'El nombre del cliente solo debe contener letras'
                });
                return false;
            }
            if (!regexSoloLetras.test(apellidoInput.value)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'El apellido del cliente solo debe contener letras'
                });
                return false;
            }
            if (!regexTelefono.test(telefonoInput.value)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'El teléfono debe tener 10 dígitos'
                });
                return false;
            }
            if (!regexCorreo.test(correoInput.value)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ingrese un correo electrónico válido'
                });
                return false;
            }
        }

        // Validar método de pago
        if (!metodoPagoSelect.value) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Por favor, seleccione un método de pago'
            });
            metodoPagoSelect.focus();
            return false;
        }

        // Validar método de entrega
        if (!metodoEntregaSelect.value) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Por favor, seleccione un método de entrega'
            });
            metodoEntregaSelect.focus();
            return false;
        }

        // Validar que haya al menos un producto
        const productos = document.querySelectorAll('.producto-select-venta');
        let hayProductosValidos = false;
        let todosProductosValidos = true;

        productos.forEach(producto => {
            if (producto.value) {
                hayProductosValidos = true;
                // Validar cantidad
                const cantidadInput = producto.closest('tr').querySelector('.cantidad-input-venta');
                if (!cantidadInput.value || cantidadInput.value < 1) {
                    todosProductosValidos = false;
                }
            }
        });

        if (!hayProductosValidos) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Por favor, seleccione al menos un producto'
            });
            return false;
        }

        if (!todosProductosValidos) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Por favor, ingrese cantidades válidas para todos los productos'
            });
            return false;
        }

        // Validar campos adicionales si es pago móvil
        if (metodoPagoSelect.value === '1') {
            if (!referenciaBancaria.value || !telefonoEmisor.value || !banco.value || !bancoDestino.value) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Por favor, complete todos los campos del pago móvil'
                });
                return false;
            }
        }

        if (metodoEntregaSelect.value === '2' && !direccion.value) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Por favor, ingrese la dirección de entrega'
            });
            return false;
        }

        return true;
    }

    // Evento para buscar cliente
    if (btnBuscarCliente) {
        btnBuscarCliente.addEventListener('click', function() {
            const cedula = cedulaInput.value.trim();
            
            if (cedula.length < 7 || cedula.length > 8) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'La cédula debe tener entre 7 y 8 dígitos'
                });
                return;
            }

            // Crear FormData y agregar los datos
            const formData = new FormData();
            formData.append('buscar_cliente', '1');
            formData.append('cedula', cedula);
            formData.append('csrf_token', document.querySelector('input[name="csrf_token"]').value);

            // Realizar la petición AJAX
            fetch('?pagina=salida', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.respuesta === 1 && data.cliente) {
                    // Cliente encontrado
                    camposCliente.style.display = 'block';
                    btnBuscarCliente.style.display = 'none';
                    btnCancelarRegistro.style.display = 'block';
                    btnRegistrarCliente.style.display = 'none';

                    // Llenar los campos con los datos del cliente
                    nombreInput.value = data.cliente.nombre || '';
                    apellidoInput.value = data.cliente.apellido || '';
                    telefonoInput.value = data.cliente.telefono || '';
                    correoInput.value = data.cliente.correo || '';
                    idClienteHidden.value = data.cliente.id_persona || '';

                    // Hacer los campos de solo lectura
                    nombreInput.readOnly = true;
                    apellidoInput.readOnly = true;
                    telefonoInput.readOnly = true;
                    correoInput.readOnly = true;

                    Swal.fire({
                        icon: 'success',
                        title: '¡Cliente encontrado!',
                        text: 'Los datos del cliente han sido cargados'
                    });
                } else {
                    // Cliente no encontrado - preguntar si desea registrarlo
                    Swal.fire({
                        title: 'Cliente no encontrado',
                        text: '¿Desea registrar un nuevo cliente?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, registrar',
                        cancelButtonText: 'No, cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Mostrar campos para registro
                    camposCliente.style.display = 'block';
                    btnBuscarCliente.style.display = 'none';
                    btnCancelarRegistro.style.display = 'block';
                    btnRegistrarCliente.style.display = 'block';

                    // Limpiar y habilitar los campos para nuevo registro
                    nombreInput.value = '';
                    apellidoInput.value = '';
                    telefonoInput.value = '';
                    correoInput.value = '';
                    idClienteHidden.value = '';

                    // Hacer los campos editables
                    nombreInput.readOnly = false;
                    apellidoInput.readOnly = false;
                    telefonoInput.readOnly = false;
                    correoInput.readOnly = false;
                        } else {
                            // Si cancela, volver al estado inicial
                            camposCliente.style.display = 'none';
                            btnBuscarCliente.style.display = 'block';
                            btnCancelarRegistro.style.display = 'none';
                            btnRegistrarCliente.style.display = 'block';
                            
                            // Limpiar campos
                            cedulaInput.value = '';
                            nombreInput.value = '';
                            apellidoInput.value = '';
                            telefonoInput.value = '';
                            correoInput.value = '';
                            idClienteHidden.value = '';
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Ocurrió un error al buscar el cliente'
                });
            });
        });
    }

    // Botón cancelar registro
    if (btnCancelarRegistro) {
        btnCancelarRegistro.addEventListener('click', function() {
            mostrarModoBusqueda();
        });
    }

    // Para el formulario de registro
    const formRegistroVenta = document.getElementById('formRegistroVenta');
    if (formRegistroVenta) {
        formRegistroVenta.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            if (!validarFormularioVenta()) {
                return;
            }

            try {
                // Si es un cliente nuevo, registrarlo primero
                if (!idClienteHidden.value && nombreInput.value) {
                    const clienteData = new FormData();
                    clienteData.append('registrar_cliente', '1');
                    clienteData.append('cedula', cedulaInput.value);
                    clienteData.append('nombre', nombreInput.value);
                    clienteData.append('apellido', apellidoInput.value);
                    clienteData.append('telefono', telefonoInput.value);
                    clienteData.append('correo', correoInput.value);
                    clienteData.append('csrf_token', document.querySelector('input[name="csrf_token"]').value);

                    const response = await fetch('?pagina=salida', {
                        method: 'POST',
                        body: clienteData
                    });

                    const responseText = await response.text();
                    
                    // Depuración
                    console.log('Respuesta del servidor:', responseText);
                    
                    let clienteResult;
                    try {
                        clienteResult = JSON.parse(responseText);
                    } catch (e) {
                        console.error('Error al parsear JSON:', e);
                        console.error('Respuesta recibida:', responseText);
                        throw new Error('Error al procesar la respuesta del servidor');
                    }

                    if (!clienteResult || !clienteResult.success) {
                        throw new Error(clienteResult?.message || 'Error al registrar el cliente');
                    }

                    // Asignar el ID del cliente nuevo
                    idClienteHidden.value = clienteResult.id_cliente;
                }

                // Continuar con el registro de la venta
                const precioTotal = document.getElementById('total-general-venta').textContent.replace('$', '').trim();
                const inputPrecioTotal = document.createElement('input');
                inputPrecioTotal.type = 'hidden';
                inputPrecioTotal.name = 'precio_total';
                inputPrecioTotal.value = precioTotal;
                this.appendChild(inputPrecioTotal);

                Swal.fire({
                    title: '¿Confirmar venta?',
                    text: "¿Está seguro de registrar esta venta?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, registrar venta',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Mostrar indicador de carga
                        Swal.fire({
                            title: 'Procesando...',
                            text: 'Por favor espere mientras se registra la venta',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            allowEnterKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Crear FormData con los datos del formulario
                        const formData = new FormData(this);
                        formData.append('registrar_venta', '1');

                        // Log de los datos que se envían
                        console.log('Datos del formulario:');
                        for (let [key, value] of formData.entries()) {
                            console.log(`${key}: ${value}`);
                        }

                        // Verificar datos críticos
                        const idPersona = formData.get('id_persona');
                        const idMetodoPago = formData.get('id_metodopago');
                        const idEntrega = formData.get('id_entrega');
                        
                        if (!idPersona || !idMetodoPago || !idEntrega) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Faltan datos importantes: ' + 
                                      (!idPersona ? 'Cliente, ' : '') +
                                      (!idMetodoPago ? 'Método de pago, ' : '') +
                                      (!idEntrega ? 'Método de entrega' : ''),
                                showConfirmButton: true,
                                confirmButtonText: 'Aceptar'
                            });
                            return;
                        }

                        // Verificar productos
                        const productos = formData.getAll('id_producto[]');
                        if (!productos.length) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Debe seleccionar al menos un producto',
                                showConfirmButton: true,
                                confirmButtonText: 'Aceptar'
                            });
                            return;
                        }

                        // Realizar la petición AJAX
                        fetch('?pagina=salida', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`Error HTTP: ${response.status}`);
                            }
                            return response.text();
                        })
                        .then(text => {
                            try {
                                // Verificar si el texto está vacío
                                if (!text.trim()) {
                                    throw new Error('Respuesta vacía del servidor');
                                }
                                // Intentar parsear el JSON
                                const data = JSON.parse(text);
                                if (data && data.respuesta === 1) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: '¡Éxito!',
                                        text: 'Venta registrada correctamente',
                                        showConfirmButton: true,
                                        confirmButtonText: 'Aceptar'
                                    }).then(() => {
                                        window.location.href = '?pagina=salida';
                                    });
                                } else {
                                    throw new Error(data?.error || 'Error al registrar la venta');
                                }
                            } catch (e) {
                                console.error('Error al procesar la respuesta:', text);
                                throw new Error('Error al procesar la respuesta del servidor');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: error.message || 'Ocurrió un error al procesar la venta',
                                showConfirmButton: true,
                                confirmButtonText: 'Aceptar'
                            });
                        });
                    }
                });
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Ocurrió un error al procesar la operación'
                });
            }
        });
    }

    // Manejo de modales y confirmaciones
    // Inicializar todos los modales
    var modales = document.querySelectorAll('.modal');
    modales.forEach(function(modal) {
        new bootstrap.Modal(modal);
    });

    // Manejo de confirmación de eliminación
    document.querySelectorAll('button[name="eliminar_venta"]').forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: '¿Está seguro?',
                text: "¿Desea eliminar esta venta? Esta acción no se puede deshacer",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    button.closest('form').submit();
                }
            });
        });
    });

    // Manejo del formulario de edición
    document.querySelectorAll('form').forEach(function(form) {
        if (form.querySelector('button[name="modificar_venta"]')) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                Swal.fire({
                    title: '¿Confirmar cambios?',
                    text: "¿Está seguro de guardar los cambios en esta venta?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, guardar cambios',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        }
    });

    // Función para mostrar modo registro
    function mostrarModoRegistro() {
        camposCliente.style.display = 'block';
        btnCancelarRegistro.style.display = 'block';
        btnBuscarCliente.style.display = 'none';
        btnRegistrarCliente.style.display = 'none';
        
        // Limpiar y habilitar campos para edición
        nombreInput.value = '';
        apellidoInput.value = '';
        telefonoInput.value = '';
        correoInput.value = '';
        
        nombreInput.removeAttribute('readonly');
        apellidoInput.removeAttribute('readonly');
        telefonoInput.removeAttribute('readonly');
        correoInput.removeAttribute('readonly');
        
        nombreInput.setAttribute('required', true);
        apellidoInput.setAttribute('required', true);
        telefonoInput.setAttribute('required', true);
        correoInput.setAttribute('required', true);
    }

    // Función para mostrar modo búsqueda
    function mostrarModoBusqueda() {
        camposCliente.style.display = 'none';
        btnCancelarRegistro.style.display = 'none';
        btnBuscarCliente.style.display = 'block';
        btnRegistrarCliente.style.display = 'block';
        
        // Limpiar campos del cliente
        cedulaInput.value = '';
        nombreInput.value = '';
        apellidoInput.value = '';
        telefonoInput.value = '';
        correoInput.value = '';
        idClienteHidden.value = '';
        
        // Limpiar clases de validación de la cédula
        cedulaInput.classList.remove('is-valid', 'is-invalid');
        cedulaInput.setCustomValidity('');
        limpiarError(cedulaInput);
        
        // Limpiar campos de pago
        if(metodoPagoSelect) metodoPagoSelect.value = '';
        if(metodoEntregaSelect) metodoEntregaSelect.value = '';
        if(referenciaBancaria) referenciaBancaria.value = '';
        if(telefonoEmisor) telefonoEmisor.value = '';
        if(banco) banco.value = '';
        if(bancoDestino) bancoDestino.value = '';
        if(direccion) direccion.value = '';
        
        // Ocultar campos adicionales
        if(camposPagoAdicionales) camposPagoAdicionales.style.display = 'none';
        if(campoDireccion) campoDireccion.style.display = 'none';
        
        // Limpiar productos
        const primeraFila = document.querySelector('.producto-fila');
        if(primeraFila) {
            const selectProducto = primeraFila.querySelector('.producto-select-venta');
            const inputCantidad = primeraFila.querySelector('.cantidad-input-venta');
            const inputPrecio = primeraFila.querySelector('.precio-input-venta');
            const subtotal = primeraFila.querySelector('.subtotal-venta');
            const stockInfo = primeraFila.querySelector('.stock-info');
            
            if(selectProducto) selectProducto.value = '';
            if(inputCantidad) inputCantidad.value = '1';
            if(inputPrecio) inputPrecio.value = '0.00';
            if(subtotal) subtotal.textContent = '0.00';
            if(stockInfo) stockInfo.textContent = '';
        }
        
        // Eliminar filas adicionales de productos
        const filas = document.querySelectorAll('.producto-fila');
        if(filas.length > 1) {
            for(let i = 1; i < filas.length; i++) {
                filas[i].remove();
            }
        }
        
        // Actualizar total general
        const totalGeneral = document.getElementById('total-general-venta');
        if(totalGeneral) totalGeneral.textContent = '$0.00';
        
        // Quitar required y clases de validación
        const campos = [nombreInput, apellidoInput, telefonoInput, correoInput, 
                       referenciaBancaria, telefonoEmisor, banco, bancoDestino, direccion];
        campos.forEach(campo => {
            if(campo) {
                campo.removeAttribute('required');
                campo.classList.remove('is-valid', 'is-invalid');
                limpiarError(campo);
            }
        });
    }

    // Evento para mostrar campos de registro de cliente
    btnRegistrarCliente.addEventListener('click', function() {
        mostrarModoRegistro();
    });

    // Evento para cancelar registro
    btnCancelarRegistro.addEventListener('click', function() {
        mostrarModoBusqueda();
    });
}); 