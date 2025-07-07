document.addEventListener('DOMContentLoaded', () => {
  const bellBtn = document.querySelector('.notification-icon');
  let lastId = Number(localStorage.getItem('lastPedidoId') || 0);

  async function pollPedidos() {
    try {
      const res = await fetch(`?pagina=notificacion&accion=nuevos&lastId=${lastId}`);
      const { count, pedidos } = await res.json();

      if (count > 0) {
        pedidos.forEach(p => {
          const isReserva = p.tipo === 3;
          Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'info',
            title: isReserva
              ? `Nueva reserva #${p.id_pedido}`
              : `Nuevo pedido #${p.id_pedido} – Total: ${p.total} BS`,
            showConfirmButton: false,
            timer: 4000
          });
        });

        // Insertar el “puntito” si no existe
        if (!bellBtn.querySelector('.notif-dot')) {
          bellBtn.insertAdjacentHTML('beforeend', '<span class="notif-dot"></span>');
        }

        // Actualizar lastId y guardarlo
        lastId = pedidos[pedidos.length - 1].id_pedido;
        localStorage.setItem('lastPedidoId', lastId);
      }
    } catch (e) {
      console.error('Error al obtener nuevos pedidos:', e);
    }
  }

  // Primera ejecución al cargar
  pollPedidos();

  // Luego cada 30 segundos
  setInterval(pollPedidos, 30000);
});



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
