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

  if (confirm('¿Confirmar este pedido?')) {
      $.post('controlador/pedidoweb.php', { accion: 'confirmar', id_pedido: idPedido }, function (response) {
          console.log('Respuesta:', response);
          try {
              const res = JSON.parse(response);
              alert(res.msg);
              if (res.status === 'ok') location.reload();
          } catch(e) {
              alert('Error en la respuesta del servidor.');
              console.error(e);
          }
      });
  }
});

$(document).on('click', '.btn-eliminar', function () {
  const idPedido = $(this).data('id');
  console.log('Eliminar pedido:', idPedido);

  if (confirm('¿Eliminar este pedido? Esta acción es irreversible.')) {
      $.post('controlador/pedidoweb.php', { accion: 'eliminar', id_pedido: idPedido }, function (response) {
          console.log('Respuesta:', response);
          try {
              const res = JSON.parse(response);
              alert(res.msg);
              if (res.status === 'ok') location.reload();
          } catch(e) {
              alert('Error en la respuesta del servidor.');
              console.error(e);
          }
      });
  }
});