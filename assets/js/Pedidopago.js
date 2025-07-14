$(document).ready(function() {

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

  function validarReferenciaBancaria(input) {
    const valor = input.val().trim();
    const valido = /^[0-9]{4,6}$/.test(valor);
    valido ? limpiarError(input) : mostrarError(input, "Debe tener entre 4 y 6 dígitos.");
    return valido;
  }

  function validarTelefonoEmisor(input) {
    const valor = input.val().trim();
    const valido = /^(0414|0424|0412|0416|0426)[0-9]{7}$/.test(valor);
    valido ? limpiarError(input) : mostrarError(input, "Formato válido: 04141234567");
    return valido;
  }



  $('#referencia_bancaria').on('input', function() {
    let v = this.value.replace(/\D/g, '').slice(0,6);
    $(this).val(v);
  });
  $('#telefono_emisor').on('input', function() {
    let v = this.value.replace(/\D/g, '').slice(0,11);
    $(this).val(v);
  });

   // Validación en tiempo real


   $('#referencia_bancaria').on('input', ()=>validarReferenciaBancaria($('#referencia_bancaria')));
   $('#telefono_emisor').on('input', ()=>validarTelefonoEmisor($('#telefono_emisor')));

  
  





 
  
    $('#btn-guardar-pago').on('click', function(e) {
      e.preventDefault();
  
       // Validaciones básicas
    if (!$('#metodopago').val()) {
      return Swal.fire('Error', 'Seleccione un método de pago', 'warning');
    }
    if (!$('#referencia_bancaria').val() || !validarReferenciaBancaria($('#referencia_bancaria'))) {
      return Swal.fire('Error', 'Ingrese una referencia bancaria válida', 'warning');
    }
    if (!$('#telefono_emisor').val() || !validarTelefonoEmisor($('#telefono_emisor'))) {
      return Swal.fire('Error', 'Ingrese un teléfono del emisor válido', 'warning');
    }
    if (!$('#banco').val()) {
      return Swal.fire('Error', 'Seleccione un banco de origen', 'warning');
    }
    if (!$('#banco_destino').val()) {
      return Swal.fire('Error', 'Seleccione un banco de destino', 'warning');
    }
    // Validar términos y condiciones
    if (!$('#che').is(':checked')) {
      return Swal.fire('Error', 'Debe aceptar los términos y condiciones', 'warning');
    }
  
      Swal.fire({
        title: '¿Confirmar Pago?',
        text: 'Se procesará su orden y pago.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, pagar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (!result.isConfirmed) return;
  
        const fd = new FormData($('#formPago')[0]);
        $.ajax({
          url: 'controlador/Pedidopago.php',
          type: 'POST',
          data: fd,
          processData: false,
          contentType: false,
          dataType: 'json',
          success(res) {
            if (res.success) {
              Swal.fire({ title: '¡Listo!', text: res.message, icon: 'success', timer: 1500, showConfirmButton: false })
                .then(() => setTimeout(()=> window.location.href='?pagina=Pedidoconfirmar',2000));
            } else {
              Swal.fire({ title: 'Error', text: res.message, icon: 'error', timer: 1500, showConfirmButton: false });
            }
          },
          error() {
            Swal.fire({ title: 'Error', text: 'Comunicación fallida', icon: 'error', timer: 1500, showConfirmButton: false });
          }
        });
      });
    });
  });
  