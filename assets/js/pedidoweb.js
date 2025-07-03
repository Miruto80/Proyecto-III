$(document).ready(function() {

  // Mostrar detalles del pedido en modal
  $(document).on('click', '.ver-detalles', function () {
    const detalles = $(this).data('detalles');
    let html = '';

    if (Array.isArray(detalles) && detalles.length > 0) {
      html += `<table class="table table-bordered table-hover">
                <thead class="table-secondary">
                  <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Subtotal</th>
                  </tr>
                </thead>
                <tbody>`;
      
      detalles.forEach(det => {
        html += `
          <tr>
            <td>${det.nombre_producto || 'Producto'}</td>
            <td>${det.cantidad}</td>
            <td>${det.precio}</td>
            <td>${(det.cantidad * det.precio).toFixed(2)}$</td>
          </tr>`;
      });

      html += `</tbody></table>`;
    } else {
      html = `<p class="text-center">No hay detalles disponibles para este pedido.</p>`;
    }

    $('#contenidoDetallesPedido').html(html);
    $('#modalDetallesPedido').modal('show');
  });

  // Confirmar pedido
  $(document).on('click', '.btn-validar', function () {
    const idPedido = $(this).data('id');

    Swal.fire({
      title: '¿Confirmar pedido?',
      text: 'Una vez confirmado, no podrás modificarlo',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, confirmar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: 'controlador/pedidoweb.php',
          type: 'POST',
          data: {
            confirmar: 'confirmar',
            id_pedido: idPedido
          },
          dataType: 'json',
          success: function (res) {
            if (res.respuesta == 1) {
              Swal.fire({
                title: 'Confirmado',
                text: res.mensaje || 'Pedido confirmado correctamente',
                icon: 'success',
                timer: 1200,
                showConfirmButton: false
              }).then(() => location.reload());
            } else {
              Swal.fire({
                title: 'Error',
                text: res.mensaje || 'No se pudo confirmar el pedido',
                icon: 'error',
                timer: 1500,
                showConfirmButton: false
              });
            }
          },
          error: function () {
            Swal.fire({
              title: 'Error',
              text: 'Error en la comunicación con el servidor',
              icon: 'error',
              timer: 1500,
              showConfirmButton: false
            });
          }
        });
      }
    });
  });

  // Eliminar pedido
  $(document).on('click', '.btn-eliminar', function () {
    const idPedido = $(this).data('id');

    Swal.fire({
      title: '¿Eliminar pedido?',
      text: 'Esta acción no se puede deshacer',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: 'controlador/pedidoweb.php',
          type: 'POST',
          data: {
            eliminar: 'eliminar',
            id_pedido: idPedido
          },
          dataType: 'json',
          success: function (res) {
            if (res.respuesta == 1) {
              Swal.fire({
                title: 'Eliminado',
                text: res.mensaje || 'Pedido eliminado correctamente',
                icon: 'success',
                timer: 1200,
                showConfirmButton: false
              }).then(() => location.reload());
            } else {
              Swal.fire({
                title: 'Error',
                text: res.mensaje || 'No se pudo eliminar el pedido',
                icon: 'error',
                timer: 1500,
                showConfirmButton: false
              });
            }
          },
          error: function () {
            Swal.fire({
              title: 'Error',
              text: 'Error en la comunicación con el servidor',
              icon: 'error',
              timer: 1500,
              showConfirmButton: false
            });
          }
        });
      }
    });
  });
});

  // Actualizar estado delivery y dirección
  $(document).on('submit', '.form-delivery', function(e) {
    e.preventDefault();

    const $form = $(this);
    const idPedido = $form.find('input[name="id_pedido"]').val();
    const estadoDelivery = $form.find('select[name="estado_delivery"]').val();
    const direccion = $form.find('input[name="direccion"]').val();

    if (!idPedido || !estadoDelivery || !direccion) {
        Swal.fire({
            icon: 'warning',
            title: 'Faltan datos',
            text: 'Por favor completa todos los campos para actualizar delivery.'
        });
        return;
    }

    const postData = {
        id_pedido: idPedido,
        estado_delivery: estadoDelivery,
        direccion: direccion
    };

    Swal.fire({
        title: 'Actualizando estado delivery...',
        html: 'Por favor espera.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    $.ajax({
        url: 'controlador/pedidoweb.php',
        type: 'POST',
        data: postData,
        dataType: 'json'
    }).done(function(res) {
        Swal.close();
        if (res.respuesta == 1) {
            Swal.fire({
                icon: 'success',
                title: '¡Listo!',
                text: 'Estado de delivery actualizado correctamente',
                timer: 1500,
                showConfirmButton: false
            }).then(() => location.reload());
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: res.mensaje || 'No se pudo actualizar el estado delivery'
            });
        }
    }).fail(function() {
        Swal.close();
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error en la comunicación con el servidor'
        });
    });
});


$('#btnayuda').on("click", function () {
  
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
