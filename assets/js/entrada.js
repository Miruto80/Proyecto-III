document.addEventListener('DOMContentLoaded', function() {
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
    form.querySelectorAll('.producto-fila').forEach(fila => {
      const productoSelect = fila.querySelector('.producto-select');
      const cantidad = fila.querySelector('.cantidad-input');
      const precio = fila.querySelector('.precio-input');
      
      if (productoSelect && productoSelect.value) {
        if (!cantidad || !cantidad.value || parseFloat(cantidad.value) <= 0) {
          muestraMensaje("warning", 3000, "Cantidad inválida", "La cantidad debe ser mayor a cero");
          productosValidos = false;
          return;
        }
        
        if (!precio || !precio.value || parseFloat(precio.value) <= 0) {
          muestraMensaje("warning", 3000, "Precio inválido", "El precio unitario debe ser mayor a cero");
          productosValidos = false;
          return;
        }
      }
    });
    
    return productosValidos;
  }
  
  // Agregar validación a todos los formularios
  document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function(e) {
      if (!validarFormulario(this)) {
        e.preventDefault();
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
    
    const productosOptions = productoSelect.innerHTML;
    
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
});