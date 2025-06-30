// assets/js/notificacion.js

document.addEventListener('DOMContentLoaded', () => {
  const api = '?pagina=notificacion';
  const tbody = document.getElementById('notif-body');
  const vaciarBtn = document.getElementById('vaciar-notificaciones');

  // 1) Vaciar todas las entregadas (solo Admin)
  vaciarBtn?.addEventListener('click', () => {
    Swal.fire({
      title: '¿Vaciar todas las notificaciones entregadas?',
      text: 'Esto eliminará permanentemente todas las entregadas.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Vaciar',
      cancelButtonText: 'Cancelar'
    }).then(result => {
      if (!result.isConfirmed) return;
      fetch(`${api}&accion=vaciar`, { method: 'POST' })
        .then(r => r.json())
        .then(json => {
          if (!json.ok) {
            return Swal.fire('Error', json.error, 'error');
          }
          if (json.deleted === 0) {
            return Swal.fire(
              'Nada por vaciar',
              'No hay notificaciones entregadas para eliminar.',
              'info'
            );
          }
          Swal.fire(
            'Vaciado',
            `${json.deleted} notificación${json.deleted > 1 ? 'es' : ''} eliminada${json.deleted > 1 ? 's' : ''}`,
            'success'
          ).then(() => location.reload());
        });
    });
  });

  // 2) Delegación en la tabla para leer, entregar y eliminar
  tbody.addEventListener('click', e => {
    const btn = e.target.closest('button');
    if (!btn) return;
    const id = btn.dataset.id;

    // a) Marcar como leída (Admin)
    if (btn.classList.contains('marcar-leer')) {
      fetch(`${api}&accion=marcarLeida`, {
        method: 'POST',
        body: new URLSearchParams({ id })
      })
      .then(r => r.json())
      .then(json => {
        if (json.ok) {
          Swal.fire('Listo', 'Notificación marcada como leída.', 'success')
            .then(() => location.reload());
        } else {
          Swal.fire('Error', json.error, 'error');
        }
      });
      return;
    }

    // b) Marcar como entregada (Asesora)
    if (btn.classList.contains('marcar-entregar')) {
      fetch(`${api}&accion=entregar`, {
        method: 'POST',
        body: new URLSearchParams({ id })
      })
      .then(r => r.json())
      .then(json => {
        if (json.ok) {
          Swal.fire('Entregado', 'Notificación marcada como entregada.', 'success')
            .then(() => location.reload());
        } else {
          Swal.fire('Error', json.error, 'error');
        }
      });
      return;
    }

    // c) Eliminar (Admin) con confirm y manejo de error por estado
    if (btn.classList.contains('btn-eliminar')) {
      Swal.fire({
        title: '¿Eliminar notificación?',
        text: 'Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
      }).then(result => {
        if (!result.isConfirmed) return;
        fetch(`${api}&accion=eliminar`, {
          method: 'POST',
          body: new URLSearchParams({ id })
        })
        .then(r => r.json())
        .then(json => {
          if (json.ok) {
            document.getElementById(`notif-${id}`)?.remove();
            Swal.fire('Eliminado', 'Notificación borrada.', 'success');
          } else {
            Swal.fire('No permitido', json.error, 'warning');
          }
        });
      });
    }
  });
});
