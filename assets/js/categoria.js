$(function() {
  // ————————————————————————————
  // Helpers
  // ————————————————————————————
  function muestraMensaje(icon, tiempo, titulo, msg) {
    Swal.fire({ icon, title: titulo, html: msg, timer: tiempo, showConfirmButton: false });
  }
  function mensajeOK(text) {
    Swal.fire({ icon:'success', title:text, timer:1000, showConfirmButton:false })
      .then(()=> location.reload());
  }
  function validarkeypress(er, e) {
    const chr = String.fromCharCode(e.which);
    if (!er.test(chr)) e.preventDefault();
  }
  function validarkeyup(er, $el, $span, msg) {
    const v = $el.val().trim();
    if (!v) {
      $el.addClass('is-invalid').removeClass('is-valid');
      $span.text('Este campo es obligatorio');
      return false;
    }
    if (!er.test(v)) {
      $el.addClass('is-invalid').removeClass('is-valid');
      $span.text(msg);
      return false;
    }
    $el.addClass('is-valid').removeClass('is-invalid');
    $span.text('');
    return true;
  }

  // ————————————————————————————
  // Bloquear números y mostrar "No se permiten números"
  // ————————————————————————————
  $('#nombre')
    .on('keypress', function(e) {
      const chr = String.fromCharCode(e.which);
      // solo letras y espacios
      validarkeypress(/^[A-Za-zÁÉÍÓÚáéíóúÑñ ]$/, e);
      if (/\d/.test(chr)) {
        e.preventDefault();
        const $span = $('#snombre');
        $span.text('No se permiten números');
        $(this).addClass('is-invalid').removeClass('is-valid');
        // marca el error y evita que keyup lo borre
        $(this).data('numError', true);
      } else {
        $(this).data('numError', false);
      }
    })
    .on('keyup', function() {
      const $inp = $(this);
      if ($inp.data('numError')) return;       // persiste el mensaje
      validarkeyup(
        /^[A-Za-zÁÉÍÓÚáéíóúÑñ ]{3,30}$/,
        $inp, $('#snombre'),
        'Solo letras, de 3 a 30 caracteres'
      );
    });

  // ————————————————————————————
  // Abrir modal Registrar
  // ————————————————————————————
  $('#btnAbrirRegistrar').click(()=> {
    $('#u')[0].reset();
    $('#u').find('.is-valid, .is-invalid').removeClass('is-valid is-invalid');
    $('#snombre').text('');
    $('#accion').val('registrar');
    $('#modalTitleText').text('Registrar Categoría');
    $('#btnText').text('Registrar');
    new bootstrap.Modal($('#registro')).show();
  });

  // ————————————————————————————
  // Abrir modal Modificar
  // ————————————————————————————
  window.abrirModalModificar = function() {
    // usa this en lugar de event.currentTarget
    const $tr   = $(this).closest('tr');
    const id    = $tr.data('id');
    const nombre= $tr.find('td').eq(0).find('.text-dark b').text().trim();

    $('#id_categoria').val(id);
    $('#nombre')
      .val(nombre)
      .removeClass('is-valid is-invalid')
      .data('numError', false);
    $('#snombre').text('');
    $('#accion').val('actualizar');
    $('#modalTitleText').text('Modificar Categoría');
    $('#btnText').text('Actualizar');
    new bootstrap.Modal($('#registro')).show();
  };
  // mapea todos los botones
  $('.btnModif').click(abrirModalModificar);

  // ————————————————————————————
  // Eliminar
  // ————————————————————————————
  $('.btnElim').click(function() {
    const id = $(this).closest('tr').data('id');
    Swal.fire({
      title:'¿Eliminar categoría?',
      text:'No podrás revertir esto.',
      icon:'warning',
      showCancelButton:true,
      confirmButtonText:'Sí, eliminar'
    }).then(({isConfirmed})=>{
      if (!isConfirmed) return;
      const fd = new FormData($('#u')[0]);
      fd.set('id_categoria', id);
      fd.set('eliminar','eliminar');
      enviaAjax(fd);
    });
  });

  // ————————————————————————————
  // Guardar (Registrar / Modificar)
  // ————————————————————————————
  $('#btnEnviar').click(()=>{
    const $inp = $('#nombre'), $span = $('#snombre');
    if (!validarkeyup(
      /^[A-Za-zÁÉÍÓÚáéíóúÑñ ]{3,30}$/,
      $inp, $span,
      'Solo letras, de 3 a 30 caracteres'
    )) {
      muestraMensaje('error',2000,'Error','Datos inválidos');
      return;
    }
    const fd = new FormData($('#u')[0]);
    if ($('#accion').val() === 'registrar') fd.append('registrar','registrar');
    else fd.append('modificar','modificar');
    enviaAjax(fd);
  });

  // ————————————————————————————
  // Envío AJAX
  // ————————————————————————————
  function enviaAjax(fd) {
    $.ajax({
      url:'?pagina=categoria',
      type:'POST',
      data:fd,
      processData:false,
      contentType:false,
      dataType:'json'
    })
    .done(res=>{
      if (res.accion==='incluir'    && res.respuesta==1) return mensajeOK('Categoría registrada');
      if (res.accion==='actualizar' && res.respuesta==1) return mensajeOK('Categoría modificada');
      if (res.accion==='eliminar'   && res.respuesta==1) return mensajeOK('Categoría eliminada');
      muestraMensaje('error',2000,'Error',res.mensaje);
    })
    .fail(()=> muestraMensaje('error',2000,'Error','Comunicación fallida'));
  }


$('#btnAyuda').on('click', function() {
  const DriverClass = window.driver?.js?.driver;
  if (typeof DriverClass !== 'function') {
    return console.error('Driver.js no detectado');
  }

  const steps = [
    {
      element: '.table-color',
      popover: {
        title: 'Tabla de categorías',
        description: 'Aquí ves todas las categorías activas.',
        side: 'left'
      }
    },
    {
      element: '#btnAbrirRegistrar',
      popover: {
        title: 'Registrar categoría',
        description: 'Abre el modal para crear una nueva categoría.',
        side: 'bottom'
      }
    },
    {
      element: '.btnModif',
      popover: {
        title: 'Editar categoría',
        description: 'Haz clic aquí para modificar una categoría existente.',
        side: 'left'
      }
    },
    {
      element: '.btnElim',
      popover: {
        title: 'Eliminar categoría',
        description: 'Este botón elimina la categoría seleccionada.',
        side: 'left'
      }
    },
    {
      popover: {
        title: '¡Listo!',
        description: 'Has terminado la guía de Categorías.'
      }
    }
  ];

  const driver = new DriverClass({
    nextBtnText: 'Siguiente',
    prevBtnText: 'Anterior',
    doneBtnText: 'Listo',
    closeBtn: false,
    popoverClass: 'driverjs-theme',
    steps
  });

  driver.drive();
});


});
