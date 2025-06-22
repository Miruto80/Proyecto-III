(() => {
  if (typeof moment !== 'function') {
    return console.error('❌ moment.js no cargado');
  }

  const hasAlert = typeof muestraMensaje === 'function';
  const forms    = document.querySelectorAll('.report-form');

  forms.forEach(form => {
    form.addEventListener('submit', e => {
      e.preventDefault();

      const s   = form.querySelector('input[name="f_start"]')?.value || '';
      const f   = form.querySelector('input[name="f_end"]')?.value   || '';
      const fmt = d => moment(d, 'YYYY-MM-DD').format('DD/MM/YYYY');

      let icon, title, text;

      // 1) Sin fechas (total)
      if (!s && !f) {
        icon  = 'success';
        title = 'Registro general';
        text  = 'Se generará el reporte general';
      }
      // 2) Sólo desde
      else if (s && !f) {
        icon  = 'success';
        title = 'Rango parcial';
        text  = `Reporte desde ${fmt(s)}`;
      }
      // 3) Sólo hasta
      else if (!s && f) {
        icon  = 'success';
        title = 'Rango parcial';
        text  = `Reporte hasta ${fmt(f)}`;
      }
      // 4) Rango invertido → error inmediato
      else if (moment(s).isAfter(moment(f))) {
        return show('error', 'Rango inválido',
                    'La fecha de inicio no puede ser mayor que la fecha de fin.');
      }
      // 5) Misma fecha
      else if (moment(s).isSame(moment(f))) {
        icon  = 'success';
        title = 'Fecha única';
        text  = `Reporte del ${fmt(s)}`;
      }
      // 6) Rango válido
      else {
        icon  = 'success';
        title = 'Rango válido';
        text  = `Desde ${fmt(s)} hasta ${fmt(f)}`;
      }

      // Pasamos al chequeo y envío
      checkAndSubmit(form, icon, title, text);
    });
  });

  /**
   * Realiza el AJAX de checkOnly y muestra SOLO UNA alerta:
   * - Si hay datos: muestra el resumen (icon,title,text) y luego submit.
   * - Si no: muestra error de "Sin datos" y NO submit.
   */
  function checkAndSubmit(form, icon, title, text) {
    // ocultamos la modal antes de la alerta
    const modalEl = form.closest('.modal');
    if (modalEl) {
      (bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl)).hide();
    }

    // chequeo AJAX
    const data = new FormData(form);
    data.append('checkOnly', '1');

    fetch('?pagina=reporte', { method: 'POST', body: data })
      .then(r => r.json())
      .then(json => {
        if (json.count > 0) {
          // hay datos → muestra resumen y al cerrar dispara form.submit()
          show(icon, title, text, () => form.submit());
        } else {
          // no hay datos → solo mostramos error
          show('error', 'Sin datos',
               'No se puede generar el PDF por falta de datos.');
        }
      })
      .catch(err => {
        console.error(err);
        show('error', 'Error',
             'No se pudo verificar la existencia de datos.');
      });
  }

  /**
   * Muestra alerta con muestraMensaje o SweetAlert fallback.
   * cb opcional se ejecuta al cerrar la alerta.
   */
  function show(icon, title, text, cb) {
    if (hasAlert) {
      muestraMensaje(icon, 2000, title, text);
      if (cb) setTimeout(cb, 2100);
    } else {
      Swal.fire({ icon, title, text, timer: 2000, showConfirmButton: false })
         .then(() => cb && cb());
    }
  }
})();
