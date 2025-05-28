$(document).on('click', '.ver-detalles', function () {
  const detalles = $(this).data('detalles');
  const tbody = $('#tbody-detalles-producto');
  tbody.empty();

  detalles.forEach(detalle => {
    const fila = `
      <tr>
        <td>${detalle.nombre_producto}</td>
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
  console.log('Validar pedido:', idPedido);

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
              console.log('Respuesta:', response);
              try {
                  const res = JSON.parse(response);
                  Swal.fire({
                      title: res.status === 'ok' ? 'Pedido confirmado' : 'Error',
                      text: res.msg,
                      icon: res.status === 'ok' ? 'success' : 'error'
                  }).then(() => {
                      if (res.status === 'ok') location.reload();
                  });
              } catch(e) {
                  Swal.fire('Error', 'Error en la respuesta del servidor.', 'error');
                  console.error(e);
              }
          });
      }
  });
});

$(document).on('click', '.btn-eliminar', function () {
  const idPedido = $(this).data('id');
  console.log('Eliminar pedido:', idPedido);

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
              console.log('Respuesta:', response);
              try {
                  const res = JSON.parse(response);
                  Swal.fire({
                      title: res.status === 'ok' ? 'Pedido eliminado' : 'Error',
                      text: res.msg,
                      icon: res.status === 'ok' ? 'success' : 'error'
                  }).then(() => {
                      if (res.status === 'ok') location.reload();
                  });
              } catch(e) {
                  Swal.fire('Error', 'Error en la respuesta del servidor.', 'error');
                  console.error(e);
              }
          });
      }
  });
});