// assets/js/notificacion.js

document.addEventListener('DOMContentLoaded', () => {
  // 0) Mostrar alerta de éxito si flashNotif fue inyectado en la vista
  if (window.flashNotif) {
    Swal.fire({
      icon: 'success',
      title: '¡Hecho!',
      text: window.flashNotif,
      confirmButtonText: 'OK'
    });
    window.flashNotif = null;
  }

  // 1) Puntito rosa: micro‐API count
  const baseURL = '?pagina=notificacion';
  const bellBtn = document.querySelector('.notification-icon');

  function updateNotifDot() {
    fetch(`${baseURL}&accion=count`)
      .then(res => res.json())
      .then(d => {
        if (!bellBtn) return;
        const dot = bellBtn.querySelector('.notif-dot');
        if (d.count > 0 && !dot) {
          bellBtn.insertAdjacentHTML('beforeend', '<span class="notif-dot"></span>');
        } else if (d.count === 0 && dot) {
          dot.remove();
        }
      })
      .catch(console.error);
  }
  updateNotifDot();
  setInterval(updateNotifDot, 30000);

  // 2) Helper: intercepta forms para SweetAlert + submit, con prechecks
  function bindForm(selector, opts) {
    document.querySelectorAll(selector).forEach(form => {
      form.addEventListener('submit', e => {
        e.preventDefault();

        // Pre‐check para "vaciar": exige al menos una entregada
        if (opts.requireAnyDelivered) {
          const hasDelivered = Array.from(
            document.querySelectorAll('#notif-body tr')
          ).some(tr => {
            const st = tr.children[2].textContent.trim();
            return st === 'Entregada' || st === 'Leída y entregada';
          });
          if (!hasDelivered) {
            return Swal.fire({
              icon: 'info',
              title: 'Nada que vaciar',
              text: opts.blockText,
              confirmButtonText: 'OK'
            });
          }
        }

        // Pre‐check para "eliminar": exige que esa notificación esté entregada
        if (opts.requireDelivered) {
          const id       = form.querySelector('input[name="id"]').value;
          const row      = document.querySelector(`#notif-${id}`);
          const estadoTd = row.children[2].textContent.trim();
          if (estadoTd !== 'Entregada' && estadoTd !== 'Leída y entregada') {
            return Swal.fire({
              icon: 'warning',
              title: 'Acción no permitida',
              text: opts.blockText,
              confirmButtonText: 'OK'
            });
          }
        }

        // Confirmación estándar
        Swal.fire({
          title: opts.title,
          text: opts.text || '',
          icon: opts.icon || 'warning',
          showCancelButton: true,
          confirmButtonText: opts.confirmText || 'Sí',
          cancelButtonText: 'Cancelar'
        }).then(({ isConfirmed }) => {
          if (isConfirmed) form.submit();
        });
      });
    });
  }

  // 3) Enlazar formularios:

  // Vaciar entregadas (Admin)
  bindForm('#vaciar-notificaciones-form', {
    title:             '¿Vaciar todas las notificaciones entregadas?',
    text:              'Se eliminarán todas las entregadas.',
    icon:              'warning',
    confirmText:       'Vaciar',
    requireAnyDelivered: true,
    blockText:         'No hay notificaciones entregadas para vaciar.'
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
    title:             '¿Eliminar notificación?',
    text:              'Esta acción no se puede deshacer.',
    icon:              'warning',
    confirmText:       'Eliminar',
    requireDelivered:  true,
    blockText:         'Solo puedes eliminar notificaciones entregadas.'
  });

    $('#btnAyudanoti').on('click', function() {
    const DriverClass = window.driver.js.driver;
    if (typeof DriverClass !== 'function') {
      console.error('Driver.js v1 no detectado');
      return;
    }

    const steps = [];

    // Tabla
    if ($('.table-compact').length) {
      steps.push({
        element: '.table-compact',
        popover: {
          title:       'Tabla de notificaciones',
          description: 'Aquí ves todas las notificaciones.',
          side:        'top'
        }
      });
    }

    // Admin: Vaciar entregadas
    if ($('#vaciar-notificaciones').length) {
      steps.push({
        element: '#vaciar-notificaciones',
        popover: {
          title:       'Vaciar entregadas',
          description: 'Elimina las notificaciones entregadas.',
          side:        'left'
        }
      });
    }

    // Admin: Marcar como leída
    if ($('form.marcar-leer-form button').length) {
      steps.push({
        element: 'form.marcar-leer-form button',
        popover: {
          title:       'Marcar como leída',
          description: 'Marca nuevas notificaciones como leídas.',
          side:        'left'
        }
      });
    }

    // Asesora: Marcar como entregada
    if ($('form.marcar-entregar-form button').length) {
      steps.push({
        element: 'form.marcar-entregar-form button',
        popover: {
          title:       'Entregar notificación',
          description: 'Marca notificaciones leídas como entregadas.',
          side:        'left'
        }
      });
    }

    // Admin: Eliminar
    if ($('form.btn-eliminar-form button').length) {
      steps.push({
        element: 'form.btn-eliminar-form button',
        popover: {
          title:       'Eliminar notificación',
          description: 'Elimina notificaciones entregadas.',
          side:        'left'
        }
      });
    }

    // Paso final
    steps.push({
      popover: {
        title:       '¡Listo!',
        description: 'Terminaste la guía de notificaciones.'
      }
    });

    // Ejecutar Driver.js
    const driverObj = new DriverClass({
      nextBtnText:  'Siguiente',
      prevBtnText:  'Anterior',
      doneBtnText:  'Listo',
      popoverClass: 'driverjs-theme',
      closeBtn:     false,
      steps
    });
    driverObj.drive();
  });
});
