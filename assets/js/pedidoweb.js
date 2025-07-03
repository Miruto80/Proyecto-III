$(document).ready(function() {

  // Mostrar detalles del pedido en modal
  $('.ver-detalles').on('click', function() {
    let detallesJSON = $(this).attr('data-detalles');
    if (!detallesJSON) return;

    let detalles = [];
    try {
      detalles = JSON.parse(detallesJSON);
    } catch (e) {
      console.error('Error parseando detalles:', e);
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'No se pudo cargar los detalles del pedido',
      });
      return;
    }

    $('#tbody-detalles-producto').empty();

    detalles.forEach(det => {
      let fila = `<tr>
                    <td>${det.nombre}</td>
                    <td>${det.cantidad}</td>
                    <td>${det.precio_unitario}</td>
                  </tr>`;
      $('#tbody-detalles-producto').append(fila);
    });

    $('#modalDetallesProducto').modal('show');
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
