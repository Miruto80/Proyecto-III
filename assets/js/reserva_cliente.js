$(document).ready(function () {

  // Activar o desactivar botón según los términos
  $('#check_terminos').on('change', function () {
    const btn = $('#btn-guardar-reserva');
    btn.prop('disabled', !this.checked);
  });

  /* === FUNCIONES DE LOADER EN BOTÓN === */
  function activarLoaderBoton(idBoton, texto) {
    const $boton = $(idBoton);
    const textoOriginal = $boton.html();
    $boton.data('texto-original', textoOriginal);
    $boton.prop('disabled', true);
    $boton.html(
      `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>${texto}`
    );
  }

  function desactivarLoaderBoton(idBoton) {
    const $boton = $(idBoton);
    const textoOriginal = $boton.data('texto-original');
    $boton.prop('disabled', false);
    $boton.html(textoOriginal);
  }

  /* === VALIDACIÓN VISUAL === */
  function mostrarError(campo, mensaje) {
    campo.addClass('is-invalid');
    let span = campo.next('.invalid-feedback');
    if (!span.length) {
      span = $('<span class="invalid-feedback" style="color:red;"></span>');
      campo.after(span);
    }
    span.text(mensaje);
  }

  function limpiarError(campo) {
    campo.removeClass('is-invalid');
    campo.next('.invalid-feedback').text('');
  }

  /* === VALIDACIONES === */
  function validarReferenciaBancaria(input) {
    const valor = input.val().trim();
    const valido = /^[0-9]{4,6}$/.test(valor);
    valido ? limpiarError(input) : mostrarError(input, 'Debe tener entre 4 y 6 dígitos.');
    return valido;
  }

  function validarTelefonoEmisor(input) {
    const valor = input.val().trim();
    const valido = /^(0412|0414|0416|0424|0426)\d{7}$/.test(valor);
    valido ? limpiarError(input) : mostrarError(input, 'Formato válido: 04141234567');
    return valido;
  }

  // Validaciones en tiempo real
  $('#referencia_bancaria').on('input', function () {
    const v = this.value.replace(/\D/g, '').slice(0, 6);
    $(this).val(v);
    validarReferenciaBancaria($('#referencia_bancaria'));
  });

  $('#telefono_emisor').on('input', function () {
    const v = this.value.replace(/\D/g, '').slice(0, 11);
    $(this).val(v);
    validarTelefonoEmisor($('#telefono_emisor'));
  });

  // === VALIDAR Y MOSTRAR VISTA PREVIA DE IMAGEN ===
  $('#imagen').on('change', function (e) {
    const file = e.target.files[0];
    const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];

    if (file) {
      if (!allowedTypes.includes(file.type)) {
        Swal.fire({
          icon: 'error',
          title: 'Formato no permitido',
          text: 'Solo se aceptan imágenes JPG, PNG o WEBP.',
          confirmButtonText: 'OK'
        });
        e.target.value = '';
        return;
      }

      const reader = new FileReader();
      reader.onload = function (event) {
        const preview = document.getElementById('preview');
        preview.src = event.target.result;
        preview.classList.remove('d-none');
      };
      reader.readAsDataURL(file);
    }
  });

  /* === ENVÍO DEL FORMULARIO === */
  $('#btn-guardar-reserva').on('click', function (e) {
    e.preventDefault();

    // Validaciones básicas antes del envío
    if (!$('#banco').val()) {
      return Swal.fire('Error', 'Seleccione un banco de origen', 'warning');
    }
    if (!$('#banco_destino').val()) {
      return Swal.fire('Error', 'Seleccione un banco de destino', 'warning');
    }
    if (!$('#referencia_bancaria').val() || !validarReferenciaBancaria($('#referencia_bancaria'))) {
      return Swal.fire('Error', 'Ingrese una referencia bancaria válida', 'warning');
    }
    if (!$('#telefono_emisor').val() || !validarTelefonoEmisor($('#telefono_emisor'))) {
      return Swal.fire('Error', 'Ingrese un teléfono del emisor válido', 'warning');
    }
    if (!$('#imagen').val()) {
      return Swal.fire('Error', 'Debe adjuntar un comprobante', 'warning');
    }
    if (!$('#check_terminos').is(':checked')) {
      return Swal.fire('Error', 'Debe aceptar los términos y condiciones', 'warning');
    }

    Swal.fire({
      title: '¿Confirmar Reserva?',
      text: 'Se procesará su solicitud de reserva.',
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Sí, reservar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (!result.isConfirmed) return;

      activarLoaderBoton('#btn-guardar-reserva', 'Guardando...');

      const fd = new FormData($('#formReserva')[0]);

      $.ajax({
        url: 'controlador/reserva_cliente.php',
        type: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        dataType: 'json',
        success(res) {
          desactivarLoaderBoton('#btn-guardar-reserva');

          if (res.success) {
            Swal.fire({
              title: '¡Listo!',
              text: res.message,
              icon: 'success',
              timer: 1500,
              showConfirmButton: false
            }).then(() => {
              setTimeout(() => (window.location.href = res.redirect || '?pagina=confirmacion'), 1500);
            });
          } else {
            Swal.fire('Error', res.message || 'Ocurrió un error en la reserva.', 'error');
          }
        },
        error(xhr, status, error) {
          desactivarLoaderBoton('#btn-guardar-reserva');
          console.error('AJAX Error:', status, error);
          Swal.fire('Error', 'Comunicación fallida con el servidor', 'error');
        }
      });
    });
  });
});
