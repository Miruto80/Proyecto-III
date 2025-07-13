document.addEventListener('DOMContentLoaded', () => {
  const BASE    = '?pagina=Notificacion';
  const bellBtn = document.querySelector('.notification-icon');
  const tbody   = document.getElementById('notif-body');
  const helpBtn = document.getElementById('btnAyudanoti');
  let lastId    = Number(localStorage.getItem('lastPedidoId') || 0);

  // 1) Contador de badge
  async function updateBadge() {
    if (!bellBtn) return;
    try {
      const res         = await fetch(`${BASE}&accion=count`);
      const { count }   = await res.json();
      const dotExisting = bellBtn.querySelector('.notif-dot');

      if (count > 0 && !dotExisting) {
        bellBtn.insertAdjacentHTML('beforeend',
          '<span class="notif-dot"></span>');
      } else if (count === 0 && dotExisting) {
        dotExisting.remove();
      }
    } catch (err) {
      console.error('updateBadge error:', err);
    }
  }

  // 2) Polling de nuevos pedidos/reservas
  async function pollPedidos() {
    if (!bellBtn) return;
    try {
      const res               = await fetch(`${BASE}&accion=nuevos&lastId=${lastId}`);
      const { count, pedidos} = await res.json();

      if (count > 0) {
        pedidos.forEach(p => {
          const title = p.tipo === 3
            ? `Nueva reserva #${p.id_pedido}`
            : `Nuevo pedido #${p.id_pedido} – Bs. ${p.total}`;

          Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'info',
            title,
            showConfirmButton: false,
            timer: 4000
          });
        });

        // inject dot if missing
        if (!bellBtn.querySelector('.notif-dot')) {
          bellBtn.insertAdjacentHTML('beforeend',
            '<span class="notif-dot"></span>');
        }

        // update lastId
        lastId = pedidos[pedidos.length - 1].id_pedido;
        localStorage.setItem('lastPedidoId', lastId);
      }
    } catch (err) {
      console.error('pollPedidos error:', err);
    }
  }

  // 3) Delegación: marcar como leída (solo si existe la tabla)
  if (tbody) {
    tbody.addEventListener('click', async e => {
      const btn = e.target.closest('.btn-action');
      if (!btn) return;
      e.preventDefault();

      const id     = btn.dataset.id;
      const accion = btn.dataset.accion;
      const row    = btn.closest('tr');

      const { isConfirmed } = await Swal.fire({
        title: '¿Marcar como leída?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí',
        cancelButtonText: 'Cancelar'
      });
      if (!isConfirmed) return;

      try {
        const res  = await fetch(`${BASE}&accion=${accion}`, {
          method: 'POST',
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: new URLSearchParams({ id })
        });
        const data = await res.json();

        await Swal.fire(
          data.success ? '¡Listo!' : 'Error',
          data.mensaje,
          data.success ? 'success' : 'error'
        );

if (data.success && row) {
  // 1) elimino la fila
  row.remove();

  // 2) actualizo el badge
  updateBadge();

  // 3) si ya no hay ninguna fila
  if (tbody.children.length === 0) {
    const msg = tbody.dataset.emptyMsg || 'No hay notificaciones.';
    const tr  = document.createElement('tr');
    tr.innerHTML = `
      <td colspan="5" class="text-center py-3">
        ${msg}
      </td>
    `;
    tbody.appendChild(tr);
  }
}

      } catch (err) {
        console.error('marcarLeida error:', err);
        Swal.fire('Error','No se pudo conectar','error');
      }
    });
  }

  // 4) Guía interactiva (solo si existe el botón)
  if (helpBtn) {
    helpBtn.addEventListener('click', () => {
      const Driver = window.driver?.js?.driver;
      if (typeof Driver !== 'function') {
        return console.error('Driver.js no detectado');
      }

      const steps = [];

      if (document.querySelector('.table-compact')) {
        steps.push({
          element: '.table-compact',
          popover: {
            title: 'Tabla de notificaciones',
            description: 'Aquí ves todas las notificaciones.',
            side: 'top'
          }
        });
      }
      if (document.querySelector('.btn-action[data-accion="marcarLeida"]')) {
        steps.push({
          element: '.btn-action[data-accion="marcarLeida"]',
          popover: {
            title: 'Marcar como leída',
            description: 'Haz clic para leer la notificación.',
            side: 'left'
          }
        });
      }
          if (document.querySelector('.btn-action[data-accion="marcarLeidaAsesora"]')) {
      steps.push({
        element: '.btn-action[data-accion="marcarLeidaAsesora"]',
        popover: {
          title: 'Leer',
          description: 'Haz clic para marcar esta notificación como leída.',
          side: 'left'
        }
      });
    }
      steps.push({
        popover: {
          title: '¡Listo!',
          description: 'Terminaste la guía de notificaciones.'
        }
      });

      new Driver({
        nextBtnText:  'Siguiente',
        prevBtnText:  'Anterior',
        doneBtnText:  'Listo',
        popoverClass: 'driverjs-theme',
        closeBtn:     false,
        steps
      }).drive();
    });
  }

  // 5) Inicialización
  updateBadge();
  pollPedidos();
  setInterval(updateBadge, 30000);
  setInterval(pollPedidos, 30000);
});
