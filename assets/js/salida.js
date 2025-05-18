// Ejecutar cuando el DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', function() {
  // Establecer la fecha máxima como hoy para todos los campos de fecha
  const fechaHoy = new Date().toISOString().split('T')[0];
  document.querySelectorAll('input[type="date"]').forEach(input => {
    input.max = fechaHoy;
  });

  // Manejo de eventos para agregar productos
  const btnAgregarProducto = document.getElementById('agregarFilaProducto');
  if (btnAgregarProducto) {
    btnAgregarProducto.addEventListener('click', agregarFilaProducto);
  }

  // Configurar eventos para calcular precios en las filas existentes
  configurarEventosFilas();

  // Manejar selección de método de pago
  const metodoPagoSelect = document.querySelector('select[name="id_metodopago"]');
  if (metodoPagoSelect) {
    metodoPagoSelect.addEventListener('change', function() {
      const datosPago = document.getElementById('datos-pago');
      // Mostrar campos adicionales si es transferencia o pago móvil
      // Asumiendo que los IDs 2 y 3 corresponden a estos métodos
      if (this.value == '2' || this.value == '3') {
        datosPago.style.display = 'flex';
      } else {
        datosPago.style.display = 'none';
      }
    });
  }

  // Configurar botones para ver detalles
  document.querySelectorAll('.ver-detalles').forEach(btn => {
    btn.addEventListener('click', function() {
      const idPedido = this.getAttribute('data-id');
      cargarDetallesPedido(idPedido);
    });
  });

  // Configurar botones para editar pedido
  document.querySelectorAll('.editar-pedido').forEach(btn => {
    btn.addEventListener('click', function() {
      const idPedido = this.getAttribute('data-id');
      cargarDatosPedido(idPedido);
    });
  });

  // Configurar botones para eliminar pedido
  document.querySelectorAll('.eliminar-pedido').forEach(btn => {
    btn.addEventListener('click', function() {
      const idPedido = this.getAttribute('data-id');
      document.getElementById('eliminar-id-pedido').value = idPedido;
    });
  });

  // Configurar enlaces para cambiar estado
  document.querySelectorAll('.cambiar-estado').forEach(enlace => {
    enlace.addEventListener('click', function(e) {
      e.preventDefault();
      const idPedido = this.getAttribute('data-id');
      const nuevoEstado = this.getAttribute('data-estado');
      
      Swal.fire({
        title: '¿Cambiar estado?',
        text: `¿Está seguro que desea cambiar el estado del pedido a ${nuevoEstado}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, cambiar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
          cambiarEstadoPedido(idPedido, nuevoEstado);
        }
      });
    });
  });

  // Configurar validación del formulario de registro
  const formRegistro = document.getElementById('formRegistroPedido');
  if (formRegistro) {
    formRegistro.addEventListener('submit', function(e) {
      if (!validarFormularioPedido(this)) {
        e.preventDefault();
      }
    });
  }
});

// Función para validar formulario completo de pedido
function validarFormularioPedido(form) {
  // Validar método de pago
  const metodoPago = form.querySelector('select[name="id_metodopago"]');
  if (!metodoPago.value) {
    muestraMensaje("warning", 3000, "Campo requerido", "Debe seleccionar un método de pago");
    return false;
  }

  // Validar método de entrega
  const metodoEntrega = form.querySelector('select[name="id_entrega"]');
  if (!metodoEntrega.value) {
    muestraMensaje("warning", 3000, "Campo requerido", "Debe seleccionar un método de entrega");
    return false;
  }

  // Validar datos de pago para transferencia o pago móvil
  const datosPago = document.getElementById('datos-pago');
  if (datosPago && datosPago.style.display !== 'none') {
    const referencia = form.querySelector('input[name="referencia_bancaria"]');
    if (!referencia.value.trim()) {
      muestraMensaje("warning", 3000, "Campo requerido", "Debe ingresar una referencia bancaria");
      return false;
    }
    
    const telefono = form.querySelector('input[name="telefono_emisor"]');
    if (!telefono.value.trim()) {
      muestraMensaje("warning", 3000, "Campo requerido", "Debe ingresar un teléfono emisor");
      return false;
    }
    
    const banco = form.querySelector('input[name="banco"]');
    if (!banco.value.trim()) {
      muestraMensaje("warning", 3000, "Campo requerido", "Debe ingresar un banco");
      return false;
    }
  }

  // Validar que exista al menos un producto
  const productosSeleccionados = form.querySelectorAll('select[name="id_producto[]"]');
  if (productosSeleccionados.length === 0) {
    muestraMensaje("warning", 3000, "Productos requeridos", "Debe agregar al menos un producto");
    return false;
  }

  // Validar cada producto
  let productosValidos = true;
  let haProductos = false;
  
  productosSeleccionados.forEach((productoSelect, index) => {
    if (productoSelect.value) {
      haProductos = true;
      
      const cantidad = form.querySelectorAll('input[name="cantidad[]"]')[index];
      const cantidadVal = parseInt(cantidad.value);
      const stock = parseInt(productoSelect.options[productoSelect.selectedIndex].getAttribute('data-stock'));
      
      // Validar que la cantidad sea mayor a cero
      if (!cantidadVal || cantidadVal <= 0) {
        muestraMensaje("warning", 3000, "Cantidad inválida", "La cantidad debe ser mayor a cero");
        productosValidos = false;
        return;
      }
      
      // Validar que no exceda el stock disponible
      if (cantidadVal > stock) {
        muestraMensaje("warning", 3000, "Stock insuficiente", 
          `El producto tiene stock disponible de ${stock} unidades, pero se están solicitando ${cantidadVal}`);
        productosValidos = false;
        return;
      }
    }
  });

  if (!haProductos) {
    muestraMensaje("warning", 3000, "Productos requeridos", "Debe seleccionar al menos un producto");
    return false;
  }

  return productosValidos;
}

// Función para agregar una fila de producto
function agregarFilaProducto() {
  const tbody = document.querySelector('#tablaProductos tbody');
  const filaTemplate = document.querySelector('.fila-producto');
  const nuevaFila = filaTemplate.cloneNode(true);
  
  // Limpiar valores
  nuevaFila.querySelector('.select-producto').value = '';
  nuevaFila.querySelector('.cantidad').value = '1';
  nuevaFila.querySelector('.precio-unitario').value = '0.00';
  nuevaFila.querySelector('.subtotal').textContent = '0.00';
  
  // Configurar eventos
  configurarEventosFila(nuevaFila);
  
  tbody.appendChild(nuevaFila);
}

// Configurar eventos para todas las filas de productos
function configurarEventosFilas() {
  document.querySelectorAll('.fila-producto').forEach(fila => {
    configurarEventosFila(fila);
  });
}

// Configurar eventos para una fila específica
function configurarEventosFila(fila) {
  const selectProducto = fila.querySelector('.select-producto');
  const inputCantidad = fila.querySelector('.cantidad');
  const btnRemover = fila.querySelector('.remover-producto');
  
// Evento para selección de producto
selectProducto.addEventListener('change', function() {
  // Verificar que se haya seleccionado un producto
  if (!this.value) {
    return; // No hacer nada si no hay selección
  }
  
  // Obtener precio y stock con valores por defecto seguros
  const precioUnitario = this.options[this.selectedIndex].getAttribute('data-precio') || '0';
  const stock = this.options[this.selectedIndex].getAttribute('data-stock') || '0';
  
  // Convertir a números explícitamente para asegurar comparaciones correctas
  const precioNumerico = parseFloat(precioUnitario);
  const stockNumerico = parseInt(stock, 10);
  
  // Actualizar precio unitario
  fila.querySelector('.precio-unitario').value = precioNumerico.toFixed(2);
  
  // Limitar cantidad al stock disponible
  inputCantidad.max = stockNumerico;
  inputCantidad.value = stockNumerico > 0 ? 1 : 0; // Establecer a 1 si hay stock, 0 si no
  
  // Mostrar alerta según nivel de stock
  if (stockNumerico > 0 && stockNumerico < 5) {
    muestraMensaje("info", 3000, "Stock bajo", `¡Atención! Este producto tiene solo ${stockNumerico} unidades disponibles.`);
  } else if (stockNumerico <= 0) {
    muestraMensaje("warning", 3000, "Sin stock", "Este producto no tiene stock disponible.");
    this.value = ''; // Limpiar selección
    return;
  }
  
  // Verificar si se seleccionó el mismo producto en otra fila
  checkProductoDuplicado(this);
  
  // Actualizar subtotales
  calcularSubtotal(fila);
});

// Función para verificar productos duplicados
function checkProductoDuplicado(select) {
  const productoId = select.value;
  let conteo = 0;
  
  // Contar cuántas veces aparece este producto en todas las filas
  document.querySelectorAll('.select-producto').forEach(sel => {
    if (sel.value === productoId) {
      conteo++;
    }
  });
  
  // Si aparece más de una vez, mostrar advertencia
  if (conteo > 1) {
    muestraMensaje("warning", 3000, "Producto duplicado", 
      "Este producto ya está en el pedido. Considere aumentar la cantidad en lugar de agregar otra línea.");
  }
}
  
  // Evento para cambio de cantidad
  if (inputCantidad) {
    inputCantidad.addEventListener('input', function() {
      // Si hay un producto seleccionado
      if (selectProducto && selectProducto.value) {
        const stock = selectProducto.options[selectProducto.selectedIndex].getAttribute('data-stock');
        const cantidad = parseInt(this.value) || 0;
        
        // Validar que no exceda el stock
        if (cantidad > parseInt(stock)) {
          muestraMensaje("warning", 3000, "Stock insuficiente", 
            `Solo hay ${stock} unidades disponibles de este producto`);
          this.value = stock;
        }
      }
      
      calcularSubtotal(fila);
    });
  }
  
  // Evento para remover producto
  if (btnRemover) {
    btnRemover.addEventListener('click', function() {
      const tbody = document.querySelector('#tablaProductos tbody');
      const filas = tbody.querySelectorAll('.fila-producto');
      
      // Solo eliminar si hay más de una fila
      if (filas.length > 1) {
        Swal.fire({
          title: '¿Eliminar producto?',
          text: "¿Está seguro que desea eliminar este producto del pedido?",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Sí, eliminar',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.isConfirmed) {
            fila.remove();
            calcularTotalGeneral();
          }
        });
      } else {
        muestraMensaje("info", 3000, "Información", "Debe mantener al menos un producto en el pedido");
      }
    });
  }
}

// Función para calcular subtotal de una fila
function calcularSubtotal(fila) {
  if (!fila) return;
  
  const cantidad = parseInt(fila.querySelector('.cantidad')?.value) || 0;
  const precioUnitario = parseFloat(fila.querySelector('.precio-unitario')?.value) || 0;
  const subtotal = cantidad * precioUnitario;
  
  const subtotalElement = fila.querySelector('.subtotal');
  if (subtotalElement) {
    subtotalElement.textContent = subtotal.toFixed(2);
  }
  
  calcularTotalGeneral();
}

// Función para calcular el total general
function calcularTotalGeneral() {
  let total = 0;
  document.querySelectorAll('.fila-producto .subtotal').forEach(span => {
    total += parseFloat(span.textContent) || 0;
  });
  
  const totalElement = document.getElementById('total_general');
  if (totalElement) {
    totalElement.textContent = total.toFixed(2);
  }
  
  const hiddenTotal = document.getElementById('precio_total_hidden');
  if (hiddenTotal) {
    hiddenTotal.value = total.toFixed(2);
  }
}

// Función para cargar detalles de un pedido
function cargarDetallesPedido(idPedido) {
  fetch('?pagina=salida', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
      'X-Requested-With': 'XMLHttpRequest'
    },
    body: `cargar_detalles=1&id_pedido=${idPedido}`
  })
  .then(response => response.json())
  .then(data => {
    // Llenar información del pedido
    document.getElementById('detalle-id-pedido').textContent = data.pedido.id_pedido;
    document.getElementById('detalle-fecha').textContent = new Date(data.pedido.fecha).toLocaleDateString();
    
    // Establecer estado con badge de color
    const estadoElement = document.getElementById('detalle-estado');
    let estadoClass = '';
    switch(data.pedido.estado) {
      case 'pendiente': estadoClass = 'bg-warning'; break;
      case 'aprobado': estadoClass = 'bg-success'; break;
      case 'rechazado': estadoClass = 'bg-danger'; break;
      case 'enviado': estadoClass = 'bg-info'; break;
      case 'entregado': estadoClass = 'bg-primary'; break;
      default: estadoClass = 'bg-secondary';
    }
    estadoElement.innerHTML = `<span class="badge ${estadoClass}">${data.pedido.estado}</span>`;
    
    document.getElementById('detalle-metodo-pago').textContent = data.pedido.metodo_pago;
    document.getElementById('detalle-metodo-entrega').textContent = data.pedido.metodo_entrega;
    
    // Mostrar u ocultar información de pago según método
    const infoPago = document.getElementById('info-pago');
    if (data.pedido.metodo_pago === 'Transferencia' || data.pedido.metodo_pago === 'Pago Móvil') {
      infoPago.style.display = 'block';
      document.getElementById('detalle-referencia').textContent = data.pedido.referencia_bancaria || 'No registrada';
      document.getElementById('detalle-telefono').textContent = data.pedido.telefono_emisor || 'No registrado';
      document.getElementById('detalle-banco').textContent = data.pedido.banco || 'No registrado';
    } else {
      infoPago.style.display = 'none';
    }
    
    // Llenar tabla de productos
    const tbody = document.querySelector('#tabla-detalles-productos tbody');
    tbody.innerHTML = '';
    
    let totalPedido = 0;
    data.detalles.forEach(detalle => {
      const fila = document.createElement('tr');
      fila.innerHTML = `
        <td>${detalle.nombre_producto}</td>
        <td>${detalle.cantidad}</td>
        <td>${parseFloat(detalle.precio_unitario).toFixed(2)}</td>
        <td>${parseFloat(detalle.precio_total).toFixed(2)}</td>
      `;
      tbody.appendChild(fila);
      totalPedido += parseFloat(detalle.precio_total);
    });
    
    document.getElementById('detalle-total').textContent = totalPedido.toFixed(2);
  })
  .catch(error => {
    console.error('Error al cargar detalles:', error);
    muestraMensaje("error", 3000, "Error", "Ocurrió un error al cargar los detalles del pedido");
  });
}

// Función para cargar datos de un pedido para edición
function cargarDatosPedido(idPedido) {
  fetch('?pagina=salida', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
      'X-Requested-With': 'XMLHttpRequest'
    },
    body: `cargar_detalles=1&id_pedido=${idPedido}`
  })
  .then(response => response.json())
  .then(data => {
    document.getElementById('editar-id-pedido').value = data.pedido.id_pedido;
    document.getElementById('editar-estado').value = data.pedido.estado;
    document.getElementById('editar-metodopago').value = data.pedido.id_metodopago;
    document.getElementById('editar-entrega').value = data.pedido.id_entrega;
    
    // Campos de pago
    document.getElementById('editar-referencia').value = data.pedido.referencia_bancaria || '';
    document.getElementById('editar-telefono').value = data.pedido.telefono_emisor || '';
    document.getElementById('editar-banco').value = data.pedido.banco || '';
    
    // Mostrar u ocultar campos de pago según método
    const editarDatosPago = document.getElementById('editar-datos-pago');
    if (data.pedido.metodo_pago === 'Transferencia' || data.pedido.metodo_pago === 'Pago Móvil') {
      editarDatosPago.style.display = 'flex';
    } else {
      editarDatosPago.style.display = 'none';
    }
    
    // Configurar evento para cambio de método de pago en formulario de edición
    const editarMetodoPago = document.getElementById('editar-metodopago');
    editarMetodoPago.addEventListener('change', function() {
      // Mostrar campos adicionales si es transferencia o pago móvil (IDs 2 y 3)
      if (this.value == '2' || this.value == '3') {
        editarDatosPago.style.display = 'flex';
      } else {
        editarDatosPago.style.display = 'none';
      }
    });
  })
  .catch(error => {
    console.error('Error al cargar datos para edición:', error);
    muestraMensaje("error", 3000, "Error", "Ocurrió un error al cargar los datos del pedido");
  });
}

// Función para cambiar estado de un pedido
function cambiarEstadoPedido(idPedido, estado) {
  fetch('?pagina=salida', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
      'X-Requested-With': 'XMLHttpRequest'
    },
    body: `actualizar_estado=1&id_pedido=${idPedido}&estado=${estado}`
  })
  .then(response => response.json())
  .then(data => {
    if (data.respuesta == 1) {
      muestraMensaje("success", 3000, "Éxito", "Estado del pedido actualizado correctamente");
      // Recargar la página para ver los cambios
      setTimeout(() => {
        window.location.reload();
      }, 3000);
    } else {
      muestraMensaje("error", 3000, "Error", "No se pudo actualizar el estado del pedido: " + (data.error || ""));
    }
  })
  .catch(error => {
    console.error('Error al cambiar estado:', error);
    muestraMensaje("error", 3000, "Error", "Ocurrió un error al cambiar el estado del pedido");
  });
}

// Función para mostrar mensajes con SweetAlert
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
document.addEventListener('DOMContentLoaded', function() {
  const mensajeContainer = document.querySelector('.alert');
  if (mensajeContainer) {
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