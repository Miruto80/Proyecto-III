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

  function validarCodigoSu(input) {
    const valor = input.val().trim();
    const valido = /^[0-9]{5,7}$/.test(valor);
    valido ? limpiarError(input) : mostrarError(input, "Debe tener entre 5 y 7 dígitos.");
    return valido;
  }

  function validarnomSu(input) {
    const valor = input.val().trim();
    const valido = valor.length >= 5;
    valido ? limpiarError(input) : mostrarError(input, "Mínimo 5 caracteres.");
    return valido;
  }

  function validarDireccion(input) {
    const valor = input.val().trim();
    const valido = valor.length >= 10;
    valido ? limpiarError(input) : mostrarError(input, "Mínimo 10 caracteres.");
    return valido;
  }

  // Ahora definimos validarSelect
  function validarSelect(select, mensaje) {
    if (!select.val()) {
      mostrarError(select, mensaje);
      return false;
    }
    limpiarError(select);
    return true;
  }

  function validarFormularioPedido() {
    const me = $('#metodoentrega').val();
    let ok = true;

    if (me === '3') {
      // delivery → validar zona/parroquia/sector/dirección
      ok = validarSelect($('#zona'), "Seleccione una zona") && ok;
      ok = validarSelect($('#parroquia'), "Seleccione una parroquia") && ok;
      ok = validarSelect($('#sector'), "Seleccione un sector") && ok;
      ok = validarDireccion($('#direccion')) && ok;
    }
    else if (me === '2') {
      // empresa → validamos sólo los inputs de envío
      ok = validarCodigoSu($('#codigoSucursal')) && ok;
      ok = validarnomSu($('#nombreSucursal')) && ok;
    }

    return ok;
  }

  // En tiempo real
  $('#codigoSucursal').on('input', function() {
    let v = this.value.replace(/\D/g, '').slice(0,6);
    $(this).val(v);
    validarCodigoSu($(this));
  });

  $('#nombreSucursal').on('input', () => validarnomSu($('#nombreSucursal')));
  $('#direccion').on('input', () => validarDireccion($('#direccion')));
  $('#zona, #parroquia, #sector').on('change', () => {
    validarSelect($('#zona'), "Seleccione una zona");
    validarSelect($('#parroquia'), "Seleccione una parroquia");
    validarSelect($('#sector'), "Seleccione un sector");
  });

    // Función que asigna validaciones en tiempo real según los campos presentes
    function bindValidations() {
      // Borra handlers anteriores para evitar duplicados
      $('#codigoSucursal').off('input').on('input', function() {
        let v = this.value.replace(/\D/g, '').slice(0,6);
        $(this).val(v);
        validarCodigoSu($(this));
      });
      $('#nombreSucursal').off('input').on('input', () => validarnomSu($('#nombreSucursal')));
      $('#direccion').off('input').on('input', () => validarDireccion($('#direccion')));
      $('#zona, #parroquia, #sector')
        .off('change')
        .on('change', () => {
          validarSelect($('#zona'), "Seleccione una zona");
          validarSelect($('#parroquia'), "Seleccione una parroquia");
          validarSelect($('#sector'), "Seleccione un sector");
        });
    }

  const formularios = {
    op1: $('#form-op1').html(),
    op2: $('#form-op2').html(),
    op3: $('#form-op3').html(),
  };

  $('input[name="metodo_entrega"]').on('change', function() {
    const id = this.id;                  // op1, op2 o op3
    $('#formulario-opciones').html(formularios[id]);

    bindValidations();

    if (id === 'op3') {
      // Definimos los datos
      const parroquiasPorZonaloc = {
        norte: ["El Cují","Tamaca"],
        sur: ["Juan de Villegas","Unión"],
        este: ["Santa Rosa","Cabudare"],
        oeste: ["Concepción"],
        centro: ["Catedral"]
      };
      const sectoresPorParroquialoc = {
        "Catedral": ["Centro","Urbanización Santa Elena","Barrios La Cruz","Colinas del Viento"],
        "Concepción": ["La Playa","El Manzano","Urbanización El Obelisco","Barrio Bolívar"],
        "El Cují": ["Altos de El Cují","La Pastora","El Cují Centro","Barrio El Caribe"],
        "Juan de Villegas": ["La Carucieña","La Paz","Urbanización Sucre","Barrio El Tostao"],
        "Santa Rosa": ["Santa Rosa Centro","El Cercado","Urbanización El Ujano","Barrio El Garabatal"],
        "Tamaca": ["Tamaca Centro","El Trompillo","Barrio El Jebe","Urbanización El Sisal"],
        "Unión": ["Barrio Unión","San Jacinto","Urbanización El Pedregal","Barrio El Carmen"],
        "Cabudare": ["La Piedad Norte","La Mora","El Trigal","Valle Hondo","Tarabana","Agua Viva","El Recreo","La Estancia","Las Mercedes","Los Pinos","La Mata","San Rafael" ]
      };

      // Asociamos handlers ahora que los selects existen en el DOM
      $('#zona').on('change', function() {
        const z = this.value.toLowerCase();
        $('#parroquia').html('<option value="">-- Selecciona una parroquia --</option>');
        $('#sector').html('<option value="">-- Selecciona un sector --</option>');
        (parroquiasPorZonaloc[z] || []).forEach(p => {
          $('#parroquia').append(`<option>${p}</option>`);
        });
      });

      $('#parroquia').on('change', function() {
        const p = this.value;
        $('#sector').html('<option value="">-- Selecciona un sector --</option>');
        (sectoresPorParroquialoc[p] || []).forEach(s => {
          $('#sector').append(`<option>${s}</option>`);
        });
      });
    }
  });

  // Resto de tu lógica de envío...
  $('#btn-continuar-entrega').on('click', function(e) {
      e.preventDefault();
  
      // Validación básica: debe haber una opción seleccionada
      if (!$('input[name="metodo_entrega"]:checked').length) {
        return Swal.fire('Error', 'Seleccione un método de entrega', 'warning');
      }

      if (!validarFormularioPedido()) {
        return; // Ya se muestran errores inline
      }
  
      Swal.fire({
        title: '¿Continuar?',
        text: ' Son correctos sus datos de Envio?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, continuar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (!result.isConfirmed) return;
  
        const fd = new FormData($('#formEntrega')[0]);
        $.ajax({
          url: 'controlador/Pedidoentrega.php',
          type: 'POST',
          data: fd,
          processData: false,
          contentType: false,
          dataType: 'json',
          success: function(res) {
            if (res.success) {
              Swal.fire({
                title: '¡Listo!',
                text: res.message,
                icon: 'success',
                timer: 1000,
                showConfirmButton: false
              }).then(() => {
                window.location = res.redirect;
              });
            } else {
              Swal.fire({ title: 'Error', text: res.message, icon: 'error', timer: 1500, showConfirmButton: false });
            }
          },
          error: function() {
            Swal.fire({ title: 'Error', text: 'Error en la comunicación', icon: 'error', timer: 1500, showConfirmButton: false });
          }
        });
      });
    });
  });
  