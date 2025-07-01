// assets/js/reporte.js

(() => {
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

  // 1) Validación + AJAX conteo
  document.querySelectorAll('.report-form').forEach(form => {
    form.addEventListener('submit', e => {
      e.preventDefault();
      const data  = new FormData(form);
      const start = data.get('f_start') || '';
      const end   = data.get('f_end')   || '';
      const fmt   = d => moment(d, 'YYYY-MM-DD').format('DD/MM/YYYY');

      let icon, title, text;
      if (!start && !end) {
        icon = 'success'; title = 'Registro general';
        text = 'Se generará el reporte general';
      }
      else if (start && !end) {
        icon = 'success'; title = 'Rango parcial';
        text = `Reporte desde ${fmt(start)}`;
      }
      else if (!start && end) {
        icon = 'success'; title = 'Rango parcial';
        text = `Reporte hasta ${fmt(end)}`;
      }
      else if (moment(start).isAfter(moment(end))) {
        return Swal.fire({
          icon:'error',
          title:'Rango inválido',
          text:'La fecha de inicio no puede ser mayor que la fecha de fin.',
          confirmButtonText:'Aceptar'
        });
      }
      else if (moment(start).isSame(moment(end))) {
        icon = 'success'; title = 'Fecha única';
        text = `Reporte del ${fmt(start)}`;
      }
      else {
        icon = 'success'; title = 'Rango válido';
        text = `Desde ${fmt(start)} hasta ${fmt(end)}`;
      }

      // cerrar modal si aplica
      const modalEl = form.closest('.modal');
      if (modalEl) bootstrap.Modal.getInstance(modalEl)?.hide();

      // determinar acción de conteo
      const action      = new URL(form.action, location.origin)
                             .searchParams.get('accion');
      const countAction = countMap[action];
      if (!countAction) {
        console.error('Acción inválida:', action);
        return;
      }

      // armar params
      const params = new URLSearchParams();
      for (let [k,v] of data.entries()) {
        if (['f_start','f_end','f_id','f_prov','f_cat'].includes(k) && v) {
          params.append(k, v);
        }
      }

      // AJAX GET para verificar datos
      fetch(`?pagina=reporte&accion=${countAction}&${params}`)
        .then(r => r.json())
        .then(json => {
          if (json.count > 0) {
            Swal.fire({ icon, title, text, confirmButtonText:'Aceptar' })
              .then(() => form.submit());
          } else {
            Swal.fire({
              icon:'error',
              title:'Sin datos',
              text:'No hay registros para generar el PDF.',
              confirmButtonText:'Aceptar'
            });
          }
        })
        .catch(() => Swal.fire({
          icon:'error',
          title:'Error',
          text:'No se pudo verificar los datos.',
          confirmButtonText:'Aceptar'
        }));
    });
  });

  // 2) Ayuda con Driver.js tal cual en Producto
  $('#btnAyuda').on('click', function () {
    const DriverClass = window.driver.js.driver;
    const driverObj = new DriverClass({
      nextBtnText: 'Siguiente',
      prevBtnText: 'Anterior',
      doneBtnText: 'Listo',
      popoverClass: 'driverjs-theme',
      closeBtn: false,
      steps: [
        {
          element: '#cardCompra',
          popover: {
            title: 'Reporte de Compras',
            description: 'Genera un PDF con el histórico de compras.',
            side: 'bottom'
          }
        },
        {
          element: '#cardProducto',
          popover: {
            title: 'Reporte de Productos',
            description: 'Listado detallado del inventario de productos.',
            side: 'bottom'
          }
        },
        {
          element: '#cardVentas',
          popover: {
            title: 'Reporte de Ventas',
            description: 'Analiza las ventas realizadas en el período.',
            side: 'bottom'
          }
        },
        {
          element: '#cardPedidoWeb',
          popover: {
            title: 'Reporte Web',
            description: 'Muestra los pedidos generados vía e-commerce.',
            side: 'bottom'
          }
        },
        {
          popover: {
            title: '¡Eso es todo!',
            description: 'Ahora ya sabes cómo generar todos los reportes.'
          }
        }
      ]
    });

    driverObj.drive();
  });

})();
