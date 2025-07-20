document.addEventListener('DOMContentLoaded', function() {
  let isSubmitting = false;

  // 0) Limpiar filtros al cerrar cualquier modal,
  //    excepto cuando estamos en medio de un envío.
  document.querySelectorAll('.modal').forEach(modalEl => {
    modalEl.addEventListener('hidden.bs.modal', () => {
      if (!isSubmitting) {
        const form = modalEl.querySelector('form.report-form');
        if (form) form.reset();
      }
      // tras el cierre, reseteamos el flag
      isSubmitting = false;
    });
  });

  // 1) Validación + AJAX conteo
  if (typeof moment !== 'function') {
    console.error('moment.js no cargado');
    return;
  }

  const countMap = {
    compra:    'countCompra',
    producto:  'countProducto',
    venta:     'countVenta',
    pedidoWeb: 'countPedidoWeb'
  };

  document.querySelectorAll('.report-form').forEach(form => {
    form.addEventListener('submit', e => {
      e.preventDefault();

      const data  = new FormData(form);
      const start = data.get('f_start') || '';
      const end   = data.get('f_end')   || '';
      const fmt   = d => moment(d, 'YYYY-MM-DD').format('DD/MM/YYYY');

      let icon, title, text;
      if (!start && !end) {
        icon = 'success';
        title = 'Registro general';
        text = 'Se generará el reporte general';
      }
      else if (start && !end) {
        icon = 'success';
        title = 'Rango parcial';
        text = `Reporte desde ${fmt(start)}`;
      }
      else if (!start && end) {
        icon = 'success';
        title = 'Rango parcial';
        text = `Reporte hasta ${fmt(end)}`;
      }
      else if (moment(start).isAfter(moment(end))) {
        return Swal.fire({
          icon: 'error',
          title: 'Rango inválido',
          text: 'La fecha de inicio no puede ser mayor que la fecha de fin.',
          confirmButtonText: 'Aceptar'
        });
      }
      else if (moment(start).isSame(moment(end))) {
        icon = 'success';
        title = 'Fecha única';
        text = `Reporte del ${fmt(start)}`;
      }
      else {
        icon = 'success';
        title = 'Rango válido';
        text = `Desde ${fmt(start)} hasta ${fmt(end)}`;
      }

      // indicamos que empieza el envío
      isSubmitting = true;

      // cerramos modal (se dispara hidden.bs.modal)
      const modalEl = form.closest('.modal');
      if (modalEl) {
        const modal = bootstrap.Modal.getInstance(modalEl);
        modal?.hide();
      }

      // Determina acción de conteo
      const action      = new URL(form.action, location.origin)
                             .searchParams.get('accion');
      const countAction = countMap[action];
      if (!countAction) {
        console.error('Acción inválida en countMap:', action);
        isSubmitting = false;
        return;
      }

      // Arma params
      const params = new URLSearchParams();
      for (let [k, v] of data.entries()) {
        if (['f_start','f_end','f_id','f_prov','f_cat'].includes(k) && v) {
          params.append(k, v);
        }
      }

      // AJAX GET para verificar datos
      fetch(`?pagina=reporte&accion=${countAction}&${params}`)
        .then(r => r.json())
        .then(json => {
          if (json.count > 0) {
            Swal.fire({ icon, title, text, confirmButtonText: 'Aceptar' })
              .then(() => form.submit());
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Sin datos',
              text: 'No hay registros para generar el PDF.',
              confirmButtonText: 'Aceptar'
            });
          }
        })
        .catch(() => Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'No se pudo verificar los datos.',
          confirmButtonText: 'Aceptar'
        }));
    });
  });

  // 2) Ayuda con Driver.js
  const helpBtn = document.getElementById('btnAyuda');
  if (!helpBtn) {
    console.warn('No existe el botón #btnAyuda para la ayuda.');
    return;
  }

  helpBtn.addEventListener('click', function() {
    const DriverClass = window.Driver
      || (window.driver && window.driver.js && window.driver.js.driver);

    if (typeof DriverClass !== 'function') {
      console.error('Driver.js v1 no detectado');
      return;
    }

    const steps = [
      {
        element: '#cardCompra',
        popover: {
          title: 'Reporte de Compras',
          description: 'Genera un PDF con un reporte de compras y Top 10 productos.',
          side: 'bottom'
        }
      },
      {
        element: '#cardProducto',
        popover: {
          title: 'Reporte de Productos',
          description: 'Genera un PDF con un listado de productos y Top 10 por stock.',
          side: 'bottom'
        }
      },
      {
        element: '#cardVentas',
        popover: {
          title: 'Reporte de Ventas',
          description: 'Genera un PDF con un listado de ventas y Top 10 productos vendidos.',
          side: 'bottom'
        }
      },
      {
        element: '#cardPedidoWeb',
        popover: {
          title: 'Reporte Web',
          description: 'Genera un PDF con un reporte de pedidos web y Top 5 productos.',
          side: 'bottom'
        }
      },
      {
        popover: {
          title: '¡Eso es todo!',
          description: 'Ahora ya sabes cómo generar todos los reportes.'
        }
      }
    ];

    const driver = new DriverClass({
      nextBtnText: 'Siguiente',
      prevBtnText: 'Anterior',
      doneBtnText: 'Listo',
      popoverClass: 'driverjs-theme',
      closeBtn: false,
      steps
    });

    if (typeof driver.drive === 'function') {
      driver.drive();
    } else if (typeof driver.start === 'function') {
      driver.start();
    } else {
      console.error('No se encontró método para iniciar el tour en Driver.js');
    }
  });
});
