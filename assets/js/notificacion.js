// assets/js/notificacion.js

document.addEventListener('DOMContentLoaded', () => {
  const baseURL = '?pagina=notificacion';
  const bellBtn = document.querySelector('.notification-icon');

  // ————————————————————————————
  // 1) Puntito rosa: micro‐API count
  // ————————————————————————————
  function updateNotifDot() {
    fetch(`${baseURL}&accion=count`)
      .then(res => res.json())
      .then(d => {
        if (!bellBtn) return;
        const dot = bellBtn.querySelector('.notif-dot');
        if (d.count > 0 && !dot) {
          bellBtn.insertAdjacentHTML('beforeend','<span class="notif-dot"></span>');
        } else if (d.count === 0 && dot) {
          dot.remove();
        }
      })
      .catch(console.error);
  }

  updateNotifDot();
  setInterval(updateNotifDot, 30000);

  // ————————————————————————————
  // 2) Helper: intercepta forms para SweetAlert + submit
  // ————————————————————————————
  function bindForm(selector, opts) {
    document.querySelectorAll(selector).forEach(form => {
      form.addEventListener('submit', e => {
        e.preventDefault();
        Swal.fire({
          title: opts.title,
          text: opts.text || '',
          icon: opts.icon || 'warning',
          showCancelButton: true,
          confirmButtonText: opts.confirmText || 'Sí',
          cancelButtonText:  'Cancelar'
        }).then(({ isConfirmed }) => {
          if (isConfirmed) form.submit();
        });
      });
    });
  }

  // Vaciar entregadas (Admin)
  bindForm('#vaciar-notificaciones-form', {
    title:       '¿Vaciar todas las notificaciones entregadas?',
    text:        'Se eliminarán permanentemente todas las entregadas.',
    icon:        'warning',
    confirmText: 'Vaciar'
  });

  // Marcar como leída (Admin)
  bindForm('form.marcar-leer-form', {
    title:       '¿Marcar como leída?',
    icon:        'question',
    confirmText: 'Leer'
  });

  // Marcar como entregada (Asesora)
  bindForm('form.marcar-entregar-form', {
    title:       '¿Marcar como entregada?',
    icon:        'question',
    confirmText: 'Entregar'
  });

  // Eliminar notificación (Admin)
  bindForm('form.btn-eliminar-form', {
    title:       '¿Eliminar notificación?',
    text:        'Esta acción no se puede deshacer.',
    icon:        'warning',
    confirmText: 'Eliminar'
  });
});
