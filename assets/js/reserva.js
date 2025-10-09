$(document).ready(function() {
  // Confirmar reserva
  $(document).on('click', '.btn-validar', function() {
    const idPedido = $(this).data('id');
    Swal.fire({
      title: '¿Confirmar reserva?',
      text: 'Una vez confirmada, no podrás modificarla',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, confirmar',
      cancelButtonText: 'Cancelar'
    }).then(result => {
      if (result.isConfirmed) {
        $.post('controlador/reserva.php', { confirmar: 'confirmar', id_pedido: idPedido }, function(res) {
          if (res.respuesta == 1) {
            Swal.fire({ icon: 'success', title: 'Confirmado', text: res.mensaje || 'Reserva confirmada correctamente', timer: 1200, showConfirmButton: false })
              .then(() => location.reload());
          } else {
            Swal.fire({ icon: 'error', title: 'Error', text: res.mensaje || 'No se pudo confirmar la reserva' });
          }
        }, 'json');
      }
    });
  });

  // Eliminar reserva
  $(document).on('click', '.btn-eliminar', function() {
    const idPedido = $(this).data('id');
    Swal.fire({
      title: '¿Eliminar reserva?',
      text: 'Esta acción no se puede deshacer',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar'
    }).then(result => {
      if (result.isConfirmed) {
        $.post('controlador/reserva.php', { eliminar: 'eliminar', id_pedido: idPedido }, function(res) {
          if (res.respuesta == 1) {
            Swal.fire({ icon: 'success', title: 'Eliminado', text: res.mensaje || 'Reserva eliminada correctamente', timer: 1200, showConfirmButton: false })
              .then(() => location.reload());
          } else {
            Swal.fire({ icon: 'error', title: 'Error', text: res.mensaje || 'No se pudo eliminar la reserva' });
          }
        }, 'json');
      }
    });
  });

  // Tour
  $('#btnAyuda').on('click', function() {
    const driver = window.driver.js.driver;
    const driverObj = new driver({
      nextBtnText: 'Siguiente',
      prevBtnText: 'Anterior',
      doneBtnText: 'Listo',
      popoverClass: 'driverjs-theme',
      closeBtn: false,
      steps: [
        { element: '.table-color', popover: { title: 'Tabla de Reservas', description: 'Aquí ves las reservas registradas.', side: "left" }},
        { element: '.btn-info', popover: { title: 'Ver Detalles', description: 'Abre los detalles completos de la reserva.', side: "bottom" }},
        { element: '.btn-validar', popover: { title: 'Confirmar Reserva', description: 'Confirma que la reserva ha sido pagada.', side: "left" }},
        { element: '.btn-eliminar', popover: { title: 'Eliminar Reserva', description: 'Elimina una reserva registrada.', side: "left" }},
        { element: '.dt-search', popover: { title: 'Buscar', description: 'Filtra las reservas fácilmente.', side: "right" }},
        { popover: { title: 'Fin del tour', description: 'Ya sabes cómo gestionar tus reservas.' }}
      ]
    });
    driverObj.drive();
  });

  // DataTable
  $('#tablaReservas').DataTable({
    responsive: true,
    language: { url: "https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json" }
  });
});
