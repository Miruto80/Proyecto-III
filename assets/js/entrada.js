document.addEventListener('DOMContentLoaded', function() {
  // Ocultar loader al cargar la página
  const loader = document.querySelector('.preloader-wrapper');
  if (loader) {
    loader.style.display = 'none';
  }

  /*||| Funcion para cambiar el boton a loader |||*/
  function activarLoaderBoton(idBoton, texto) {
    const $boton = $(idBoton);
    const textoActual = $boton.html();
    $boton.data('texto-original', textoActual); // Guarda el texto original
    $boton.prop('disabled', true);
    $boton.html(`<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>${texto}`);
  }

  function desactivarLoaderBoton(idBoton) {
    const $boton = $(idBoton);
    const textoOriginal = $boton.data('texto-original');
    $boton.prop('disabled', false);
    $boton.html(textoOriginal);
  }

  // Establecer la fecha máxima como hoy para todos los campos de fecha
  const fechaHoy = new Date().toISOString().split('T')[0];
  document.querySelectorAll('input[type="date"]').forEach(input => {
    input.max = fechaHoy;
  });

  // Configurar eventos para agregar producto en el formulario de registro
  const btnAgregarProducto = document.getElementById('agregar-producto');
  if (btnAgregarProducto) {
    btnAgregarProducto.addEventListener('click', function() {
      agregarFilaProducto('productos-container');
    });
  }
  
  // Configurar eventos para botones de agregar producto en los formularios de edición
  document.querySelectorAll('.agregar-producto-edit').forEach(button => {
    button.addEventListener('click', function() {
      const containerId = this.getAttribute('data-container');
      agregarFilaProducto(containerId);
    });
  });
  
  // Configurar eventos para calcular precios en filas existentes
  configurarEventosCalculoPrecio();
  
  // Configurar todos los botones de eliminar producto en filas existentes
  configurarBotonesEliminar();
  
  // Verificar precios totales al cargar la página
  document.querySelectorAll('.producto-fila').forEach(fila => {
    const cantidadInput = fila.querySelector('.cantidad-input');
    const precioInput = fila.querySelector('.precio-input');
    const precioTotalInput = fila.querySelector('.precio-total');
    
    if (cantidadInput && precioInput && precioTotalInput) {
      const cantidad = parseFloat(cantidadInput.value) || 0;
      const precioUnitario = parseFloat(precioInput.value) || 0;
      const precioTotal = cantidad * precioUnitario;
      precioTotalInput.value = precioTotal.toFixed(2);
    }
  });
  
  // Función para validar formulario antes de enviar
  function validarFormulario(form) {
    // Validar fecha (no puede ser mayor a hoy)
    const fechaInput = form.querySelector('input[type="date"]');
    if (fechaInput && fechaInput.value) {
      const fechaSeleccionada = new Date(fechaInput.value);
      const hoy = new Date();
      hoy.setHours(0, 0, 0, 0); // Reset de horas para comparar solo fechas
      
      if (fechaSeleccionada > hoy) {
        muestraMensaje("warning", 3000, "Fecha inválida", "La fecha no puede ser mayor a hoy");
        return false;
      }
    }
    
    // Validar cantidades y precios de los productos seleccionados
    let productosValidos = true;
    const filas = form.querySelectorAll('.producto-fila');
    
    for (let i = 0; i < filas.length; i++) {
      const fila = filas[i];
      const productoSelect = fila.querySelector('.producto-select');
      const cantidad = fila.querySelector('.cantidad-input');
      const precio = fila.querySelector('.precio-input');
      
      if (productoSelect && productoSelect.value) {
        // Obtener el stock máximo del option seleccionado
        const selectedOption = productoSelect.options[productoSelect.selectedIndex];
        const stockMaximo = selectedOption.getAttribute('data-stock-maximo');
        const stockActual = selectedOption.getAttribute('data-stock-actual');
        
        if (!cantidad || !cantidad.value || parseFloat(cantidad.value) <= 0) {
          muestraMensaje("warning", 3000, "Cantidad inválida", "La cantidad debe ser mayor a cero");
          productosValidos = false;
          break;
        }

        // Validar que no supere el stock máximo
        const nuevaCantidad = parseFloat(cantidad.value);
        const stockTotal = parseFloat(stockActual) + nuevaCantidad;
        
        if (stockMaximo && stockTotal > parseFloat(stockMaximo)) {
          muestraMensaje("warning", 3000, "Stock excedido", `La cantidad ingresada superaría el stock máximo permitido (${stockMaximo})`);
          productosValidos = false;
          break;
        }
        
        if (!precio || !precio.value || parseFloat(precio.value) <= 0) {
          muestraMensaje("warning", 3000, "Precio inválido", "El precio unitario debe ser mayor a cero");
          productosValidos = false;
          break;
        }
      }
    }
    
    return productosValidos;
  }
  
  // Configurar envío AJAX para todos los formularios
  configurarEnvioAjax();
  
  // Agregar validación a todos los formularios
  document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function(e) {
      // Solo validar si no es un envío AJAX
      if (!this.dataset.ajaxConfigurado) {
        console.log('Validando formulario no AJAX...');
        if (!validarFormulario(this)) {
          e.preventDefault();
        }
      } else {
        console.log('Formulario AJAX detectado, saltando validación estándar...');
      }
    });
  });
  
  // Función para agregar una nueva fila de producto
  function agregarFilaProducto(containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;
    
    // Clonamos el template de opciones de productos
    const productoSelect = document.querySelector('.producto-select');
    if (!productoSelect) return;
    
    let productosOptions = productoSelect.innerHTML;
    // Forzar que la opción 'Seleccione un producto' esté seleccionada
    productosOptions = productosOptions.replace(/<option([^>]*)selected([^>]*)>/gi, '<option$1$2>');
    productosOptions = productosOptions.replace(/<option([^>]*)value=""([^>]*)>/i, '<option$1value="" selected$2>');
    
    // Crear nueva fila
    const nuevaFila = document.createElement('div');
    nuevaFila.className = 'row mb-2 producto-fila';
    nuevaFila.innerHTML = `
      <div class="col-md-4">
        <label class="form-label">Producto</label>
        <select class="form-select producto-select" name="id_producto[]" required>
          ${productosOptions}
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label">Cantidad</label>
        <input type="number" class="form-control cantidad-input" name="cantidad[]" placeholder="Cantidad" value="1" min="1" required>
      </div>
      <div class="col-md-2">
        <label class="form-label">Precio Unit.</label>
        <input type="number" step="0.01" class="form-control precio-input" name="precio_unitario[]" placeholder="Precio Unitario" value="0.00" min="0.01" required>
      </div>
      <div class="col-md-2">
        <label class="form-label">Precio Total</label>
        <input type="number" step="0.01" class="form-control precio-total" name="precio_total[]" placeholder="Precio Total" value="0.00" readonly>
      </div>
      <div class="col-md-2">
        <label class="form-label">&nbsp;</label>
        <button type="button" class="btn btn-danger remover-producto form-control">
          <i class="fas fa-trash-alt"></i>
        </button>
      </div>
    `;
    
    // Agregar la fila al contenedor
    container.appendChild(nuevaFila);
    
    // Configurar eventos para la nueva fila
    configurarEventosFilaProducto(nuevaFila);
  }
  
  // Configurar botones de eliminar en todas las filas
  function configurarBotonesEliminar() {
    document.querySelectorAll('.remover-producto').forEach(boton => {
      // Evitar configurar el mismo evento múltiples veces
      if (!boton.dataset.configurado) {
        boton.dataset.configurado = "true";
        boton.addEventListener('click', function() {
          const fila = this.closest('.producto-fila');
          const container = fila.parentElement;
          if (container.querySelectorAll('.producto-fila').length > 1) {
            // Confirmar eliminación con SweetAlert
            Swal.fire({
              title: '¿Eliminar producto?',
              text: "¿Está seguro que desea eliminar este producto?",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Sí, eliminar',
              cancelButtonText: 'Cancelar'
            }).then((result) => {
              if (result.isConfirmed) {
                fila.remove();
              }
            });
          } else {
            muestraMensaje("info", 3000, "Atención", "Debe mantener al menos un producto");
          }
        });
      }
    });
  }
  
  // Configurar eventos para todas las filas de productos existentes
  function configurarEventosCalculoPrecio() {
    // Configurar eventos para todas las filas de productos existentes
    document.querySelectorAll('.producto-fila').forEach(fila => {
      configurarEventosFilaProducto(fila);
    });
  }
  
  // Configurar eventos para una fila de producto específica
  function configurarEventosFilaProducto(fila) {
    const cantidadInput = fila.querySelector('.cantidad-input');
    const precioInput = fila.querySelector('.precio-input');
    const precioTotalInput = fila.querySelector('.precio-total');
    const removerBtn = fila.querySelector('.remover-producto');
    
    // Calcular precio total cuando cambia la cantidad o el precio unitario
    function calcularPrecioTotal() {
      const cantidad = parseFloat(cantidadInput.value) || 0;
      const precioUnitario = parseFloat(precioInput.value) || 0;
      const precioTotal = cantidad * precioUnitario;
      precioTotalInput.value = precioTotal.toFixed(2);
    }
    
    // Evitar configurar el mismo evento múltiples veces
    if (!cantidadInput.dataset.configurado) {
      cantidadInput.dataset.configurado = "true";
      cantidadInput.addEventListener('input', calcularPrecioTotal);
    }
    
    if (!precioInput.dataset.configurado) {
      precioInput.dataset.configurado = "true";
      precioInput.addEventListener('input', calcularPrecioTotal);
    }
    
    // Calcular precio inicial
    calcularPrecioTotal();
    
    // Configurar botón para eliminar la fila
    if (removerBtn && !removerBtn.dataset.configurado) {
      removerBtn.dataset.configurado = "true";
      removerBtn.addEventListener('click', function() {
        // Solo eliminamos si hay más de una fila
        const container = fila.parentElement;
        if (container.querySelectorAll('.producto-fila').length > 1) {
          // Usar SweetAlert para confirmar
          Swal.fire({
            title: '¿Eliminar producto?',
            text: "¿Está seguro que desea eliminar este producto?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
          }).then((result) => {
            if (result.isConfirmed) {
              fila.remove();
            }
          });
        } else {
          muestraMensaje("info", 3000, "Atención", "Debe mantener al menos un producto");
        }
      });
    }
  }
  
  // Prevenir envío del formulario al presionar Enter
  document.querySelectorAll('form input').forEach(input => {
    input.addEventListener('keydown', function(e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        return false;
      }
    });
  });
  
  // Función para mostrar mensajes con SweetAlert (similar a proveedor.js)
  function muestraMensaje(icono, tiempo, titulo, mensaje) {
    Swal.fire({
      icon: icono,
      timer: tiempo,
      title: titulo,
      html: mensaje,
      showConfirmButton: false,
    });
  }
  
  // Verificar si hay mensajes de sesión PHP para mostrarlos con SweetAlert
  const mensajeContainer = document.querySelector('.alert');
  if (mensajeContainer) {
    // Extraer el tipo y el mensaje
    const tipo = mensajeContainer.classList.contains('alert-success') ? 'success' : 
                 mensajeContainer.classList.contains('alert-danger') ? 'error' : 
                 mensajeContainer.classList.contains('alert-warning') ? 'warning' : 'info';
    
    const mensaje = mensajeContainer.textContent.trim();
    
    // Ocultar el mensaje original
    mensajeContainer.style.display = 'none';
    
    // Mostrar con SweetAlert
    muestraMensaje(tipo, 3000, tipo === 'success' ? 'Operación exitosa' : 'Atención', mensaje);
  }

  // --- TOUR DE AYUDA ---
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
              title: 'ID',
              description: 'Identificador único de la compra registrada.',
              side: 'bottom'
            }
          },
          {
            element: '.table-color th:nth-child(2)',
            popover: {
              title: 'Producto',
              description: 'Muestra el primer producto registrado en la compra. Puedes ver más detalles en el botón de "Ver detalles".',
              side: 'bottom'
            }
          },
          {
            element: '.table-color th:nth-child(3)',
            popover: {
              title: 'Fecha Entrada',
              description: 'Fecha en la que se realizó la compra/entrada de productos.',
              side: 'bottom'
            }
          },
          {
            element: '.table-color th:nth-child(4)',
            popover: {
              title: 'Proveedor',
              description: 'Proveedor asociado a la compra.',
              side: 'bottom'
            }
          },
          {
            element: '.table-color th:nth-child(5)',
            popover: {
              title: 'Acciones',
              description: 'Botones para editar la compra o ver sus detalles.',
              side: 'bottom'
            }
          },
          { element: '#btnAyuda', popover: { title: 'Botón de ayuda', description: 'Haz clic aquí para ver esta guía interactiva del módulo de compras.', side: 'bottom', align: 'start' }},
          { element: '.btn-success[data-bs-target="#registroModal"]', popover: { title: 'Registrar compra', description: 'Este botón abre el formulario para registrar una nueva compra.', side: 'bottom', align: 'start' }},
          { element: '.btn-info', popover: { title: 'Ver detalles', description: 'Haz clic aquí para ver los detalles de una compra específica.', side: 'left', align: 'start' }},
          { element: '.btn-primary[data-bs-target^="#editarModal"]', popover: { title: 'Editar compra', description: 'Permite modificar los datos de la compra seleccionada.', side: 'left', align: 'start' }},
          { popover: { title: 'Eso es todo', description: 'Este es el fin de la guía del módulo de compras. ¡Gracias por usar el sistema!' } }
        ]
      });
      driverObj.drive();
    });
  }

  // Función para configurar envío AJAX de formularios
  function configurarEnvioAjax() {
    console.log('Configurando envío AJAX...');
    
    // Formulario de registro
    const formRegistro = document.querySelector('form[name="registrar_compra"]') || 
                        document.querySelector('#registroModal form');
    
    console.log('Formulario de registro encontrado:', formRegistro);
    
    if (formRegistro && !formRegistro.dataset.ajaxConfigurado) {
      formRegistro.dataset.ajaxConfigurado = 'true';
      formRegistro.addEventListener('submit', function(e) {
        console.log('Enviando formulario de registro...');
        e.preventDefault();
        enviarFormularioAjax(this, 'registrar_compra');
      });
    }
    
    // Formularios de modificación
    document.querySelectorAll('[id^="editarModal"] form').forEach(form => {
      console.log('Formulario de edición encontrado:', form);
      if (!form.dataset.ajaxConfigurado) {
        form.dataset.ajaxConfigurado = 'true';
        form.addEventListener('submit', function(e) {
          console.log('Enviando formulario de edición...');
          e.preventDefault();
          enviarFormularioAjax(this, 'modificar_compra');
        });
      }
    });
    
    // Formularios de eliminación (si existen)
    document.querySelectorAll('form').forEach(form => {
      const eliminarBtn = form.querySelector('button[name="eliminar_compra"]');
      if (eliminarBtn && !form.dataset.ajaxConfigurado) {
        form.dataset.ajaxConfigurado = 'true';
        form.addEventListener('submit', function(e) {
          console.log('Enviando formulario de eliminación...');
          e.preventDefault();
          enviarFormularioAjax(this, 'eliminar_compra');
        });
      }
    });
  }
  
  // Función para enviar formulario por AJAX
  function enviarFormularioAjax(form, accion) {
    console.log('Enviando formulario AJAX:', accion);
    
    // Validar formulario antes de enviar
    if (!validarFormulario(form)) {
      console.log('Formulario no válido, cancelando envío');
      return;
    }
    
    // Activar loader en el botón correspondiente
    if (accion === 'registrar_compra') {
      activarLoaderBoton('button[name="registrar_compra"]', 'Registrando...');
    } else if (accion === 'modificar_compra') {
      activarLoaderBoton('button[name="modificar_compra"]', 'Modificando...');
    } else if (accion === 'eliminar_compra') {
      activarLoaderBoton('button[name="eliminar_compra"]', 'Eliminando...');
    }
    
    // Mostrar loader de página
    const loader = document.querySelector('.preloader-wrapper');
    if (loader) {
      loader.style.display = 'flex';
    }
    
    // Crear FormData
    const formData = new FormData(form);
    
    // Agregar la acción correspondiente
    formData.append(accion, '1');
    
    console.log('Datos del formulario:', Object.fromEntries(formData));
    
    // Realizar petición AJAX
    fetch(window.location.href, {
      method: 'POST',
      body: formData,
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    .then(response => {
      console.log('Respuesta del servidor:', response);
      if (!response.ok) {
        throw new Error('Error en la respuesta del servidor');
      }
      return response.json();
    })
    .then(data => {
      console.log('Datos recibidos:', data);
      
      // Ocultar loader de página
      if (loader) {
        loader.style.display = 'none';
      }
      
      // Desactivar loader del botón correspondiente
      if (accion === 'registrar_compra') {
        desactivarLoaderBoton('button[name="registrar_compra"]');
      } else if (accion === 'modificar_compra') {
        desactivarLoaderBoton('button[name="modificar_compra"]');
      } else if (accion === 'eliminar_compra') {
        desactivarLoaderBoton('button[name="eliminar_compra"]');
      }
      
      // Mostrar notificación
      const icono = data.respuesta == 1 ? 'success' : 'error';
      const titulo = data.respuesta == 1 ? '¡Éxito!' : 'Error';
      
      console.log('Mostrando SweetAlert:', { icono, titulo, mensaje: data.mensaje });
      
      Swal.fire({
        icon: icono,
        title: titulo,
        text: data.mensaje,
        timer: 3000,
        showConfirmButton: false
      }).then(() => {
        console.log('SweetAlert cerrado, respuesta:', data.respuesta);
        // Si fue exitoso, recargar la página después de mostrar la notificación
        if (data.respuesta == 1) {
          console.log('Recargando página...');
          // Cerrar modal si está abierto
          const modal = bootstrap.Modal.getInstance(document.querySelector('.modal.show'));
          if (modal) {
            modal.hide();
          }
          window.location.reload();
        }
      });
    })
    .catch(error => {
      console.error('Error en AJAX:', error);
      
      // Ocultar loader de página
      if (loader) {
        loader.style.display = 'none';
      }
      
      // Desactivar loader del botón correspondiente
      if (accion === 'registrar_compra') {
        desactivarLoaderBoton('button[name="registrar_compra"]');
      } else if (accion === 'modificar_compra') {
        desactivarLoaderBoton('button[name="modificar_compra"]');
      } else if (accion === 'eliminar_compra') {
        desactivarLoaderBoton('button[name="eliminar_compra"]');
      }
      
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'Ocurrió un error al procesar la solicitud. Por favor, inténtelo de nuevo.',
        timer: 3000,
        showConfirmButton: false
      });
    });
  }
});