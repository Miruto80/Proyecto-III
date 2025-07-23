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
  });});