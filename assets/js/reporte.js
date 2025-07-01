// assets/js/reporte.js

(() => {
  if (typeof moment !== 'function') {
    console.error('❌ moment.js no cargado');
    return;
  }

  const forms = document.querySelectorAll('.report-form');
  const countMap = {
    compra:    'countCompra',
    producto:  'countProducto',
    venta:     'countVenta',
    pedidoWeb: 'countPedidoWeb'
  };

  forms.forEach(form => {
    form.addEventListener('submit', e => {
      e.preventDefault();

      // 1) Leer filtros del form
      const data = new FormData(form);
      const start = data.get('f_start') || '';
      const end   = data.get('f_end')   || '';
      const fmt   = d => moment(d, 'YYYY-MM-DD').format('DD/MM/YYYY');

      // 2) Validar rango y construir mensaje
      let icon, title, text;
      if (!start && !end) {
        icon  = 'success';
        title = 'Registro general';
        text  = 'Se generará el reporte general';
      }
      else if (start && !end) {
        icon  = 'success';
        title = 'Rango parcial';
        text  = `Reporte desde ${fmt(start)}`;
      }
      else if (!start && end) {
        icon  = 'success';
        title = 'Rango parcial';
        text  = `Reporte hasta ${fmt(end)}`;
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
        icon  = 'success';
        title = 'Fecha única';
        text  = `Reporte del ${fmt(start)}`;
      }
      else {
        icon  = 'success';
        title = 'Rango válido';
        text  = `Desde ${fmt(start)} hasta ${fmt(end)}`;
      }

      // 3) Cerrar modal si existe
      const modalEl = form.closest('.modal');
      if (modalEl) {
        const bsModal = bootstrap.Modal.getInstance(modalEl);
        bsModal?.hide();
      }

      // 4) Determinar endpoint de conteo
      const action = new URL(form.action, window.location.origin)
                         .searchParams.get('accion');
      const countAction = countMap[action];
      if (!countAction) {
        console.error('Acción inválida:', action);
        return;
      }

      // 5) Construir query string para GET
      const params = new URLSearchParams();
      for (let [key, val] of data.entries()) {
        if (['f_start','f_end','f_id','f_prov','f_cat'].includes(key) && val) {
          params.append(key, val);
        }
      }

      // 6) AJAX GET para verificar existencia de datos
      fetch(`?pagina=reporte&accion=${countAction}&${params}`)
        .then(r => r.json())
        .then(json => {
          if (json.count > 0) {
            // hay datos → confirmación y luego submit POST
            Swal.fire({
              icon,
              title,
              text,
              confirmButtonText: 'Aceptar'
            }).then(() => form.submit());
          } else {
            // sin datos → error
            Swal.fire({
              icon: 'error',
              title: 'Sin datos',
              text: 'No hay registros para generar el PDF.',
              confirmButtonText: 'Aceptar'
            });
          }
        })
        .catch(err => {
          console.error(err);
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo verificar los datos.',
            confirmButtonText: 'Aceptar'
          });
        });
    });
  });
})();
