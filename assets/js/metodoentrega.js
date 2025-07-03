$(document).ready(function () {
  // Validaciones en tiempo real para 'nombre'
  $('#nombre').on('keypress', function (e) {
    return validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
  });

  $('#nombre').on('keyup', function () {
    validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, $(this), $('#snombre'), 'Solo letras entre 3 y 30 caracteres');
  });

  // Validaciones en tiempo real para 'descripcion'
  $('#descripcion').on('keypress', function (e) {
    return validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
  });

  $('#descripcion').on('keyup', function () {
    validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, $(this), $('#sdescripcion'), 'Solo letras entre 3 y 30 caracteres');
  });

  //validaciones de modificar

   $('#nombre').on('keypress', function (e) {
    return validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
  });

  $('#nombre_modificar').on('keyup', function () {
    validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, $(this), $('#snombre_modificar'), 'Solo letras entre 3 y 30 caracteres');
  });

  // Validaciones en tiempo real para 'descripcion'
  $('#descripcion_modificar').on('keypress', function (e) {
    return validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
  });

  $('#descripcion_modificar').on('keyup', function () {
    validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, $(this), $('#sdescripcion_modificar'), 'Solo letras entre 3 y 30 caracteres');
  });



  // Registrar método
  $('#registrar').on('click', function () {
    if (!validarEnvio()) return;

    let nombre = $('#nombre').val();
    let descripcion = $('#descripcion').val();

    $.ajax({
      url: 'controlador/metodoentrega.php',
      type: 'POST',
      data: {
        registrar: 'registrar',
        nombre: nombre,
        descripcion: descripcion
      },
      dataType: 'json',
      success: function (res) {
        if (res.respuesta == 1) {
          Swal.fire({
            title: 'Registrado',
            text: 'Método registrado correctamente',
            icon: 'success',
            timer: 1000,
            showConfirmButton: false
          }).then(() => location.reload());
        } else {
          Swal.fire({ title: 'Error', text: res.mensaje || 'Error al registrar', icon: 'error', timer: 1500, showConfirmButton: false });
        }
      },
      error: function () {
        Swal.fire({ title: 'Error', text: 'Error en la comunicación', icon: 'error', timer: 1500, showConfirmButton: false });
      }
    });
  });

 $(document).on('click', '.btn-editar', function () {
  const id = $(this).data('id');
  const nombre = $(this).data('nombre');
  const descripcion = $(this).data('descripcion');

  $('#id_entrega_modificar').val(id);
  $('#nombre_modificar').val(nombre);
  $('#descripcion_modificar').val(descripcion);

  // Limpiar validaciones anteriores
  $('#nombre_modificar').removeClass('is-valid is-invalid');
  $('#descripcion_modificar').removeClass('is-valid is-invalid');

  const modal = new bootstrap.Modal(document.getElementById('modificar'));
  modal.show();
});
  // Modificar método
  $('#btnModificar').on('click', function () {
    if (!validarModificacion()) return;

    let id = $('#id_entrega_modificar').val();
    let nombre = $('#nombre_modificar').val();
    let descripcion = $('#descripcion_modificar').val();

    $.ajax({
      url: 'controlador/metodoentrega.php',
      type: 'POST',
      data: {
        actualizar: 'actualizar',
        id_entrega: id,
        nombre: nombre,
        descripcion: descripcion
      },
      dataType: 'json',
      success: function (res) {
        if (res.respuesta == 1) {
          Swal.fire({ title: 'Modificado', text: 'Método modificado correctamente', icon: 'success', timer: 1000, showConfirmButton: false }).then(() => location.reload());
        } else {
          Swal.fire({ title: 'Error', text: res.mensaje || 'Error al modificar', icon: 'error', timer: 1500, showConfirmButton: false });
        }
      },
      error: function () {
        Swal.fire({ title: 'Error', text: 'Error en la comunicación', icon: 'error', timer: 1500, showConfirmButton: false });
      }
    });
  });

  // Función para eliminar método
  window.eliminarMetodoEntrega = function (id) {
    Swal.fire({
      title: '¿Está seguro?',
      text: 'No podrá revertir esta acción',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: 'controlador/metodoentrega.php',
          type: 'POST',
          data: {
            eliminar: 'eliminar',
            id_entrega: id
          },
          dataType: 'json',
          success: function (res) {
            if (res.respuesta == 1) {
              Swal.fire({ title: 'Eliminado', text: 'Método eliminado correctamente', icon: 'success', timer: 1000, showConfirmButton: false }).then(() => location.reload());
            } else {
              Swal.fire({ title: 'Error', text: res.mensaje || 'Error al eliminar', icon: 'error', timer: 1500, showConfirmButton: false });
            }
          },
          error: function () {
            Swal.fire({ title: 'Error', text: 'Error en la comunicación', icon: 'error', timer: 1500, showConfirmButton: false });
          }
        });
      }
    });
  };

  // Funciones reutilizables de validación
  function validarkeypress(er, e) {
    const key = e.keyCode;
    const tecla = String.fromCharCode(key);
    if (!er.test(tecla)) {
      e.preventDefault();
      return false;
    }
    return true;
  }

  function validarkeyup(er, input, span, mensaje) {
    const valor = input.val();
    if (er.test(valor)) {
      input.removeClass('is-invalid').addClass('is-valid');
      span.text('');
      return true;
    } else {
      input.removeClass('is-valid').addClass('is-invalid');
      span.text(mensaje);
      return false;
    }
  }

  function validarEnvio() {
    const nombreValido = validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, $('#nombre'), $('#snombre'), 'Solo letras entre 3 y 30 caracteres');
    const descValido = validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, $('#descripcion'), $('#sdescripcion'), 'Solo letras entre 3 y 30 caracteres');
    return nombreValido && descValido;
  }

  function validarModificacion() {
    const nombreValido = validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, $('#nombre_modificar'), $('#snombre_modificar'), 'Solo letras entre 3 y 30 caracteres');
    const descValido = validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, $('#descripcion_modificar'), $('#sdescripcion_modificar'), 'Solo letras entre 3 y 30 caracteres');
    return nombreValido && descValido;
  }


});
