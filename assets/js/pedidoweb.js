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