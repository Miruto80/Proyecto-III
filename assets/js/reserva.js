$(document).ready(function () {

  // Mostrar detalles de la reserva
  $(document).on('click', '.ver-detalles', function () {
    let detalles = $(this).data('detalles');

    if (typeof detalles === 'string') {
      try {
        detalles = JSON.parse(detalles);
      } catch (e) {
        console.error('Error al parsear data-detalles:', detalles);
        detalles = {};
      }
    }

    const precioTotalBs = parseFloat(detalles.precio_total_bs);
    const mostrarPrecioBs = !isNaN(precioTotalBs);

    const fecha = detalles.fecha ? new Date(detalles.fecha) : null;
    const productos = detalles.detalles || [];
    const total = productos.reduce((acc, item) => acc + (item.cantidad * item.precio_unitario), 0);

    let html = `
      <div class="container">
        <!-- Fecha y Hora -->
        <div class="mb-3">
          <strong>Fecha:</strong> ${fecha ? fecha.toLocaleDateString() : 'N/A'}<br>
          <strong>Hora:</strong> ${fecha ? fecha.toLocaleTimeString() : 'N/A'}
        </div>

        <!-- Cliente -->
        <div class="mb-3">
          <strong>Nombre y Apellido:</strong> ${detalles.nombre || ''} ${detalles.apellido || ''}<br>
          <strong>Estado:</strong> <span class="badge ${
            detalles.estado == 0 ? 'bg-secondary' :
            detalles.estado == 1 ? 'bg-success' :
            detalles.estado == 2 ? 'bg-info' : 'bg-dark'
          }">
            ${detalles.estado == 0 ? 'Inactivo' :
              detalles.estado == 1 ? 'Activo' :
              detalles.estado == 2 ? 'Entregado' : 'Desconocido'}
          </span>
        </div>

        <!-- Pago -->
        <div class="mb-3">
          <strong>Banco Emisor:</strong> ${detalles.banco || 'N/A'}<br>
          <strong>Banco Receptor:</strong> ${detalles.banco_destino || 'N/A'}<br>
          <strong>Referencia Bancaria:</strong> ${detalles.referencia_bancaria || 'N/A'}<br>
         ${detalles.imagen ? `<strong>Comprobante:</strong><br><img src="assets/img/captures/${detalles.imagen}" class="img-fluid rounded border" style="max-width:300px;">` : ''}
        </div>

        <!-- Detalles de Productos -->
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead class="table-color">
              <tr>
                <th>#</th>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
              </tr>
            </thead>
            <tbody>
              ${productos.map((item, index) => `
                <tr>
                  <td>${index + 1}</td>
                  <td>${item.nombre}</td>
                  <td>${item.cantidad}</td>
                  <td>$${parseFloat(item.precio_unitario).toFixed(2)}</td>
                  <td>$${(item.cantidad * item.precio_unitario).toFixed(2)}</td>
                </tr>`).join('')}
            </tbody>
            <tfoot>
              <tr>
                <th colspan="4" class="text-end">Total USD:</th>
                <th>$${total.toFixed(2)}</th>
              </tr>
              ${mostrarPrecioBs ? `
              <tr>
                <th colspan="4" class="text-end">Total Bs:</th>
                <th>${precioTotalBs.toLocaleString('es-VE', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} Bs</th>
              </tr>` : ''}
            </tfoot>
          </table>
        </div>
      </div>
    `;

    $('#modalContenidoDetalles').html(html);
    $('#modalDetallesReserva').modal('show');
  });

  // Confirmar pedido
  $(document).on('click', '.btn-validar', function () {
    const idPedido = $(this).data('id');

    Swal.fire({
      title: '¿Confirmar reserva?',
      text: 'Una vez confirmada, no podrás modificarla',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, confirmar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: 'controlador/reserva.php',
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
                text: res.mensaje || 'Reserva confirmada correctamente',
                icon: 'success',
                timer: 1200,
                showConfirmButton: false
              }).then(() => location.reload());
            } else {
              Swal.fire({
                title: 'Error',
                text: res.mensaje || 'No se pudo confirmar la reserva',
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
      title: '¿Eliminar reserva?',
      text: 'Esta acción no se puede deshacer',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: 'controlador/reserva.php',
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
                text: res.mensaje || 'Reserva eliminada correctamente',
                icon: 'success',
                timer: 1200,
                showConfirmButton: false
              }).then(() => location.reload());
            } else {
              Swal.fire({
                title: 'Error',
                text: res.mensaje || 'No se pudo eliminar la reserva',
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

  // Tour de ayuda con Driver.js
  $('#btnAyuda').on("click", function () {
    const driver = window.driver.js.driver;
    const driverObj = new driver({
      nextBtnText: 'Siguiente',
      prevBtnText: 'Anterior',
      doneBtnText: 'Listo',
      popoverClass: 'driverjs-theme',
      closeBtn: false,
      steps: [
        { element: '.table-color', popover: { title: 'Tabla de Reservas', description: 'Aquí se listan las reservas.', side: "left" } },
        { element: '.ver-detalles', popover: { title: 'Ver Detalles', description: 'Haz clic para ver los detalles de la reserva.', side: "bottom", align: 'start' } },
        { element: '.btn-validar', popover: { title: 'Confirmar Reserva', description: 'Confirma que la reserva fue recibida.', side: "left", align: 'start' } },
        { element: '.btn-eliminar', popover: { title: 'Eliminar Reserva', description: 'Elimina una reserva registrada.', side: "left", align: 'start' } },
        { popover: { title: 'Fin', description: 'Eso es todo, gracias por usar la app.' } }
      ]
    });
    driverObj.drive();
  });

});
