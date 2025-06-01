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

    // Referencias a las secciones de venta y productos
    const seccionVenta = document.querySelector('.seccion-venta');
    const seccionProductos = document.querySelector('.seccion-productos');

    // Evento para cuando se abre el modal de registro
    const registroModal = document.getElementById('registroModal');
    if (registroModal) {
        registroModal.addEventListener('show.bs.modal', function () {
            // Estado inicial al abrir el modal
            camposCliente.style.display = 'none';
            btnBuscarCliente.style.display = 'block';
            btnCancelarRegistro.style.display = 'none';
            btnRegistrarCliente.style.display = 'block';
            
            // Ocultar secciones de venta y productos
            seccionVenta.style.display = 'none';
            seccionProductos.style.display = 'none';
            
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
    const regexTelefono = /^0[0-9]{10}$/;

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

    // Variable para controlar si se ha buscado el cliente
    let clienteBuscado = false;

    cedulaInput.addEventListener('input', function() {
        // Limpiar cualquier caracter que no sea número
        this.value = this.value.replace(/[^0-9]/g, '');
        clienteBuscado = false;
        
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

    telefonoInput.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
        
        if (this.value.length > 0) {
            if (!this.value.startsWith('0')) {
                mostrarError(this, 'El número debe comenzar con 0');
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else if (this.value.length !== 11) {
                mostrarError(this, 'El teléfono debe tener 11 dígitos');
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else if (!regexTelefono.test(this.value)) {
                mostrarError(this, 'Formato de teléfono inválido');
                this.classList.add('is-invalid');
                this.classList.remove('is-valid');
            } else {
                limpiarError(this);
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            }
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
            // ID 1 = Pago Móvil, ID 2 = Transferencia Bancaria, ID 3 = Punto de Venta
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
        
        // Actualizar total en dólares
        document.getElementById('total-general-venta').textContent = `$${total.toFixed(2)}`;
        
        // Obtener la tasa del día y calcular el total en bolívares
        fetch('https://ve.dolarapi.com/v1/dolares/oficial')
            .then(response => response.json())
            .then(data => {
                const tasaBCV = data.promedio;
                const totalBolivares = total * tasaBCV;
                
                // Crear o actualizar el elemento para mostrar el total en bolívares
                let totalBsElement = document.getElementById('total-general-bs');
                if (!totalBsElement) {
                    totalBsElement = document.createElement('span');
                    totalBsElement.id = 'total-general-bs';
                    totalBsElement.className = 'text-success ms-2';
                    document.getElementById('total-general-venta').parentNode.appendChild(totalBsElement);
                }
                totalBsElement.textContent = ` (${totalBolivares.toFixed(2)} Bs)`;
            })
            .catch(error => {
                console.error('Error al obtener la tasa:', error);
                // Si hay error, intentar usar la tasa mostrada en el slider
                const bcvText = document.getElementById("bcv").textContent;
                const tasaMatch = bcvText.match(/[\d.]+/);
                if (tasaMatch) {
                    const tasaBCV = parseFloat(tasaMatch[0]);
                    const totalBolivares = total * tasaBCV;
                    let totalBsElement = document.getElementById('total-general-bs');
                    if (!totalBsElement) {
                        totalBsElement = document.createElement('span');
                        totalBsElement.id = 'total-general-bs';
                        totalBsElement.className = 'text-success ms-2';
                        document.getElementById('total-general-venta').parentNode.appendChild(totalBsElement);
                    }
                    totalBsElement.textContent = ` (${totalBolivares.toFixed(2)} Bs)`;
                }
            });
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
            
            // Verificar si el producto ya está seleccionado en otra fila
            const productoId = selectedOption.value;
            let filaExistente = null;
            let cantidadExistente = 0;
            
            document.querySelectorAll('.producto-select-venta').forEach(select => {
                if (select !== this && select.value === productoId) {
                    filaExistente = select.closest('tr');
                    cantidadExistente = parseInt(filaExistente.querySelector('.cantidad-input-venta').value) || 0;
                }
            });

            if (filaExistente) {
                // Restaurar el valor anterior del select
                this.value = '';
                inputPrecio.value = '0.00';
                inputCantidad.value = '1';
                stockInfo.textContent = '';
                calcularSubtotal(fila);

                Swal.fire({
                    title: 'Producto ya registrado',
                    text: '¿Desea sumar la cantidad al producto existente?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, sumar',
                    cancelButtonText: 'No, cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const stock = parseInt(selectedOption.getAttribute('data-stock')) || 0;
                        const cantidadNueva = cantidadExistente + 1;
                        
                        if (cantidadNueva > stock) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Stock insuficiente',
                                text: `Solo hay ${stock} unidades disponibles de este producto`
                            });
                            return;
                        }

                        // Actualizar cantidad en la fila existente
                        const inputCantidadExistente = filaExistente.querySelector('.cantidad-input-venta');
                        inputCantidadExistente.value = cantidadNueva;
                        calcularSubtotal(filaExistente);
                    }
                });
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
                    text: 'El teléfono debe tener 11 dígitos y comenzar con 0'
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

    // Evento para mostrar las secciones de venta y productos
    function mostrarSeccionesVenta() {
        seccionVenta.style.display = 'block';
        seccionProductos.style.display = 'block';
    }

    // Función para ocultar las secciones de venta y productos
    function ocultarSeccionesVenta() {
        seccionVenta.style.display = 'none';
        seccionProductos.style.display = 'none';
    }

    // Función para buscar cliente
    function buscarCliente() {
            const cedula = cedulaInput.value.trim();
            clienteBuscado = true;
            
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

                // Mostrar secciones de venta y productos
                mostrarSeccionesVenta();

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
    }

    // Evento para buscar con Enter en el campo de cédula
    cedulaInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault(); // Prevenir el envío del formulario
            buscarCliente();
        }
    });

    // Evento para el botón de búsqueda
    if (btnBuscarCliente) {
        btnBuscarCliente.addEventListener('click', buscarCliente);
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

    // Función para mostrar modo registro
    function mostrarModoRegistro() {
        camposCliente.style.display = 'block';
        btnCancelarRegistro.style.display = 'block';
        btnBuscarCliente.style.display = 'none';
        btnRegistrarCliente.style.display = 'none';
        
        // Mostrar secciones de venta y productos
        mostrarSeccionesVenta();
        
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
        
        // Ocultar secciones de venta y productos
        ocultarSeccionesVenta();
        
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
        
        // Actualizar total general y limpiar total en bolívares
        const totalGeneral = document.getElementById('total-general-venta');
        const totalBs = document.getElementById('total-general-bs');
        if(totalGeneral) totalGeneral.textContent = '$0.00';
        if(totalBs) totalBs.textContent = ' (0.00 Bs)';
        
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

    // Evento para el botón reset del formulario
    if (formVenta) {
        formVenta.addEventListener('reset', function(e) {
            setTimeout(() => {
                mostrarModoBusqueda();
            }, 0);
        });
    }

    // Manejar el modal de delivery
    document.querySelectorAll('[id^="deliveryModal"]').forEach(modal => {
        const form = modal.querySelector('form');
        const direccionInput = modal.querySelector('input[name="direccion"]');
        const btnEditar = modal.querySelector('.btn-warning');
        const estadoSelect = modal.querySelector('select[name="estado_delivery"]');
        
        // Guardar el valor original de la dirección
        let direccionOriginal = direccionInput.value;
        
        if (btnEditar) {
            btnEditar.addEventListener('click', function() {
                if (direccionInput.readOnly) {
                    // Habilitar edición
                    direccionInput.readOnly = false;
                    direccionInput.disabled = false;
                    direccionInput.classList.remove('bg-light');
                    direccionInput.focus();
                    this.innerHTML = '<i class="fas fa-save"></i> Guardar';
                    this.classList.replace('btn-warning', 'btn-success');
                } else {
                    // Validar la dirección antes de guardar
                    if (direccionInput.value.trim().length < 10) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'La dirección debe tener al menos 10 caracteres'
                        });
                        return;
                    }
                    
                    // Deshabilitar edición
                    direccionInput.readOnly = true;
                    direccionInput.disabled = false;
                    direccionInput.classList.add('bg-light');
                    this.innerHTML = '<i class="fas fa-pencil-alt"></i> Editar';
                    this.classList.replace('btn-success', 'btn-warning');
                    
                    // Actualizar el valor original
                    direccionOriginal = direccionInput.value;
                }
            });
        }
        
        // Validación del estado del delivery
        if (estadoSelect) {
            estadoSelect.addEventListener('change', function() {
                const estadoActual = this.value;
                const estadoAnterior = this.getAttribute('data-estado-anterior');
                
                // Validar cambios de estado no permitidos
                if (estadoAnterior === '3' && estadoActual !== '3') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se puede cambiar el estado de un delivery ya entregado'
                    });
                    this.value = estadoAnterior;
                    return;
                }
                
                if (estadoAnterior === '0' && estadoActual !== '0') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se puede cambiar el estado de un delivery cancelado'
                    });
                    this.value = estadoAnterior;
                    return;
                }

                // Validar secuencia lógica de estados
                if (estadoAnterior === '1' && estadoActual === '3') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se puede marcar como entregado un pedido pendiente. Debe pasar por los estados intermedios.'
                    });
                    this.value = estadoAnterior;
                    return;
                }

                if (estadoAnterior === '2' && estadoActual === '1') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se puede regresar a pendiente un pedido en camino'
                    });
                    this.value = estadoAnterior;
                    return;
                }

                // Para estado Enviado (4), solo permitir cambio a Entregado (3) o Cancelado (0)
                if (estadoAnterior === '4' && !['3', '0'].includes(estadoActual)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Un pedido enviado solo puede marcarse como Entregado o Cancelado'
                    });
                    this.value = estadoAnterior;
                    return;
                }
            });
        }
        
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Validar estado
                if (!estadoSelect.value) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Debe seleccionar un estado para el delivery'
                    });
                    return;
                }
                
                // Validar dirección si fue modificada
                if (direccionInput.value !== direccionOriginal && direccionInput.value.trim().length < 10) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'La dirección debe tener al menos 10 caracteres'
                    });
                    return;
                }
                
                Swal.fire({
                    title: '¿Confirmar cambios?',
                    text: "¿Está seguro de actualizar el estado del delivery?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, actualizar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Asegurarse de que la dirección esté habilitada para el envío
                        direccionInput.disabled = false;
                        // Enviar el formulario
                        form.submit();
                    }
                });
            });
            
            // Restaurar valores originales al cerrar el modal
            modal.addEventListener('hidden.bs.modal', function() {
                direccionInput.value = direccionOriginal;
                direccionInput.readOnly = true;
                direccionInput.disabled = true;
                direccionInput.classList.add('bg-light');
                btnEditar.innerHTML = '<i class="fas fa-pencil-alt"></i> Editar';
                btnEditar.classList.replace('btn-success', 'btn-warning');
                estadoSelect.value = estadoSelect.getAttribute('data-estado-anterior');
            });
        }
    });
}); 