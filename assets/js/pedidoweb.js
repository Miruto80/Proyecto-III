function muestraMensaje(icono, tiempo, titulo, mensaje) {
    Swal.fire({
        icon: icono,
        timer: tiempo,
        title: titulo,
        html: mensaje,
        showConfirmButton: false,
        timerProgressBar: true
    });
  }
  
  
  $(document).on('click', '.ver-detalles', function () {
    const detalles = $(this).data('detalles');
    const tbody = $('#tbody-detalles-producto');
    tbody.empty();
  
    detalles.forEach(detalle => {
      const fila = `
        <tr>
          <td>${detalle.nombre}</td>
          <td>${detalle.cantidad}</td>
          <td>${detalle.precio_unitario}$</td>
        </tr>
      `;
      tbody.append(fila);
    });
    $('#modalDetallesProducto').modal('show');
  });
  
  $(document).on('click', '.btn-validar', function () {
    const idPedido = $(this).data('id');
  
    Swal.fire({
        title: '¿Confirmar este pedido?',
        text: 'Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, confirmar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('controlador/pedidoweb.php', { accion: 'confirmar', id_pedido: idPedido }, function (response) {
              console.log("Texto crudo del servidor:", response);
    
                try {
                    const res = JSON.parse(response);
                    Swal.fire({
                      title: res.respuesta === 'ok' ? 'Pedido confirmado' : 'Error',
                      text: res.mensaje,
                      icon: res.respuesta === 'ok' ? 'success' : 'error',
                      timer: 1500,
                      showConfirmButton: false,
                      timerProgressBar: true
                  }).then(() => {
                      if (res.respuesta === 'ok') location.reload();
                  });
                  } catch (e) {
                    console.error("Error al parsear JSON:", e);
                    muestraMensaje('error', 2000, 'Error', 'Error en la respuesta del servidor.');
                    console.error(e);
                  }
            });
        }
    });
  });
  
  $(document).on('click', '.btn-eliminar', function () {
    const idPedido = $(this).data('id');
  
    Swal.fire({
        title: '¿Eliminar este pedido?',
        text: 'Esta acción es irreversible.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('controlador/pedidoweb.php', { accion: 'eliminar', id_pedido: idPedido }, function (response) {
                try {
                    const res = JSON.parse(response);
                    Swal.fire({
                        title: res.respuesta === 1 ? 'Pedido eliminado' : 'Error',
                        text: res.mensaje,
                        icon: res.respuesta === 1 ? 'success' : 'error',
                        timer: 1500,
                        showConfirmButton: false,
                        timerProgressBar: true
                    }).then(() => {
                        if (res.respuesta === 1) location.reload();
                    });
                } catch(e) {
                    Swal.fire('Error', 'Error en la respuesta del servidor.', 'error');
                    console.error(e);
                }
            });
        }
    });
  });
  
  // Tu código para #btnAyuda queda igual
  $('#btnAyuda').on("click", function () {
    
    const driver = window.driver.js.driver;
    
    const driverObj = new driver({
      nextBtnText: 'Siguiente',
      prevBtnText: 'Anterior',
      doneBtnText: 'Listo',
      popoverClass: 'driverjs-theme',
      closeBtn:false,
      steps: [
        { element: '.table-color', popover: { title: 'Tabla de Pedidos', description: 'Aqui es donde se guardaran los registros de los pedidos web', side: "left", }},
        { element: '.ver-detalles', popover: { title: 'Boton de ver detalle', description: 'Darle click aqui te llevara a un modal para poder ver los detalles del pedido', side: "bottom", align: 'start' }},
        { element: '.btn-validar', popover: { title: 'validar pedido', description: 'Este botón te permite validar un pedido cuando el pago es confirmado', side: "left", align: 'start' }},
        { element: '.btn-eliminar', popover: { title: 'Eliminar pedido', description: 'Usa este botón para eliminar un pedido', side: "left", align: 'start' }},
        { element: '.dt-search', popover: { title: 'Buscar', description: 'Te permite buscar un pedido especifico ', side: "right", align: 'start' }},
        { popover: { title: 'Eso es todo', description: 'Este es el fin de la guia espero hayas entendido'} }
      ]
    });
    
    // Iniciar el tour
    driverObj.drive();
  });
  