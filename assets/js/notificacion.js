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

  // 2) Helper: intercepta forms para SweetAlert + submit
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
          cancelButtonText: 'Cancelar'
        }).then(({ isConfirmed }) => {
          if (isConfirmed) form.submit();
        });
      });
    });
  }

  // 3) Enlazar formulario: Marcar como leída (Admin)
  bindForm('form.marcar-leer-form', {
    title:       '¿Marcar como leída?',
    icon:        'question',
    confirmText: 'Leer'
  });

  // 4) Guía interactiva con Driver.js
  $('#btnAyudanoti').on('click', function() {
    const DriverClass = window.driver.js.driver;
    if (typeof DriverClass !== 'function') {
      console.error('Driver.js v1 no detectado');
      return;
    }

    const steps = [];

    // Tabla de notificaciones
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

    // Botón Marcar como leída
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

    // Paso final
    steps.push({
      popover: {
        title:       '¡Listo!',
        description: 'Terminaste la guía de notificaciones.'
      }
    });

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
