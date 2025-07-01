// assets/js/notificacion.js

document.addEventListener('DOMContentLoaded', () => {
  const urlNotif   = '?pagina=notificacion';
  const tbody      = document.getElementById('notif-body');
  const vaciarBtn  = document.getElementById('vaciar-notificaciones');
  const bellBtn    = document.querySelector('.notification-icon');

  // helper para JSON
  function fetchJSON(url, opts = {}) {
    return fetch(url, opts).then(res => res.json());
  }

  // helper para alertas con confirmButton
  function alertAndReload({ title, text, icon }) {
    Swal.fire({ title, text, icon, confirmButtonText: 'Aceptar' })
       .then(() => location.reload());
  }

  // 1) Puntito rosa
  (function updDot(){
    fetchJSON(`${urlNotif}&accion=count`)
      .then(d => {
        if (!bellBtn) return;
        const dot = bellBtn.querySelector('.notif-dot');
        if (d.count>0 && !dot) bellBtn.insertAdjacentHTML('beforeend','<span class="notif-dot"></span>');
        if (d.count===0 && dot) dot.remove();
      })
      .catch(console.error);
    setTimeout(updDot, 30000);
  })();

  // 2) Vaciar entregadas
  vaciarBtn?.addEventListener('click', () => {
    Swal.fire({
      title: '¿Vaciar todas las notificaciones entregadas?',
      text:  'Se eliminarán permanentemente todas las entregadas.',
      icon:  'warning',
      showCancelButton: true,
      confirmButtonText: 'Vaciar',
      cancelButtonText:  'Cancelar'
    }).then(({ isConfirmed }) => {
      if (!isConfirmed) return;
      fetchJSON(`${urlNotif}&accion=vaciar`, { method: 'POST' })
        .then(j => {
          if (!j.ok) {
            return Swal.fire('Error', j.error, 'error');
          }
          const msg = j.deleted===0
            ? ['Nada por vaciar','No hay entregadas.','info']
            : ['Vaciado', `${j.deleted} eliminada${j.deleted>1?'s':''}`,'success'];

          alertAndReload({ title: msg[0], text: msg[1], icon: msg[2] });
        })
        .catch(console.error);
    });
  });

  // 3) Leer, Entregar y Eliminar
  tbody?.addEventListener('click', e => {
    const btn = e.target.closest('button');
    if (!btn) return;
    const id = btn.dataset.id;

    // ELIMINAR
    if (btn.classList.contains('btn-eliminar')) {
      Swal.fire({
        title: '¿Eliminar notificación?',
        text:  'No podrás deshacer esta acción.',
        icon:  'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText:  'Cancelar'
      }).then(({ isConfirmed }) => {
        if (!isConfirmed) return;
        fetchJSON(`${urlNotif}&accion=eliminar`, {
          method: 'POST',
          body: new URLSearchParams({ id })
        })
        .then(j => {
          if (j.ok) {
            document.getElementById(`notif-${id}`)?.remove();
            Swal.fire('Eliminado', 'Notificación borrada.', 'success');
          } else {
            Swal.fire('No permitido', j.error, 'warning');
          }
        })
        .catch(console.error);
      });
      return;
    }

    // MARCAR COMO LEÍDA
    if (btn.classList.contains('marcar-leer')) {
      Swal.fire({
        title: '¿Marcar como leída?',
        icon:  'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, leer',
        cancelButtonText:  'Cancelar'
      }).then(({ isConfirmed }) => {
        if (!isConfirmed) return;
        fetchJSON(`${urlNotif}&accion=marcarLeida`, {
          method: 'POST',
          body: new URLSearchParams({ id })
        })
        .then(j => {
          if (j.ok) {
            alertAndReload({
              title: 'Listo',
              text:  'Notificación marcada como leída.',
              icon:  'success'
            });
          } else {
            Swal.fire('Error', j.error, 'error');
          }
        })
        .catch(console.error);
      });
      return;
    }

    // MARCAR COMO ENTREGADA
    if (btn.classList.contains('marcar-entregar')) {
      Swal.fire({
        title: '¿Marcar como entregada?',
        icon:  'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, entregar',
        cancelButtonText:  'Cancelar'
      }).then(({ isConfirmed }) => {
        if (!isConfirmed) return;
        fetchJSON(`${urlNotif}&accion=entregar`, {
          method: 'POST',
          body: new URLSearchParams({ id })
        })
        .then(j => {
          if (j.ok) {
            alertAndReload({
              title: 'Entregado',
              text:  'Notificación marcada como entregada.',
              icon:  'success'
            });
          } else {
            Swal.fire('Error', j.error, 'error');
          }
        })
        .catch(console.error);
      });
      return;
    }
  });
});
