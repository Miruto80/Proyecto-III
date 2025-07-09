$(document).ready(function() {
    // — Funciones de validación y mensajes —
  
    function muestraMensaje(icono, tiempo, titulo, mensaje) {
      Swal.fire({ icon: icono, timer: tiempo, title: titulo, html: mensaje, showConfirmButton: false });
    }
  
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
    
  
    function validarTelefonoEmisor(input) {
      const valor = input.val().trim();
      const valido = /^(0414|0424|0412|0416|0426)[0-9]{7}$/.test(valor);
      valido ? limpiarError(input) : mostrarError(input, "Formato válido: 04141234567");
      return valido;
    }


  
    function validarDireccion(input) {
      const valor = input.val().trim();
      const valido = valor.length >= 10;
      valido ? limpiarError(input) : mostrarError(input, "Mínimo 10 caracteres.");
      return valido;
    }
  
    function validarSelect(select, mensaje) {
      const elem = select;
      const valido = elem.val() !== "" && !elem.val().includes("Seleccione");
      valido ? limpiarError(elem) : mostrarError(elem, mensaje);
      return valido;
    }
  
    function validarFormularioPedido() {
        const errores = [];
      
        // Siempre validamos estos
      
        if (!validarReferenciaBancaria($('#referencia_bancaria'))) errores.push('ref');
        if (!validarTelefonoEmisor($('#telefono_emisor')))    errores.push('tel');
        if (!validarSelect($('#metodopago'), "Seleccione un método de pago")) errores.push('mp');
        if (!validarSelect($('#banco'), "Seleccione un banco origen"))        errores.push('bo');
        if (!validarSelect($('#banco_destino'), "Seleccione un banco destino")) errores.push('bd');
    
      
        const me = $('#metodoentrega').val();
      
        if (me === '1') {
          // delivery → validar zona/parroquia/sector/dirección
          if (!validarSelect($('#zona'),      "Seleccione una zona"))      errores.push('z');
          if (!validarSelect($('#parroquia'), "Seleccione una parroquia")) errores.push('p');
          if (!validarSelect($('#sector'),    "Seleccione un sector"))     errores.push('s');
          if (!validarDireccion($('#direccion')))                         errores.push('d');
        }
        else if (me === '2' || me === '3') {
          // empresa → validamos sólo los inputs de #divEnvio
            
        if (!validarCodigoSu($('#codigoSu'))) errores.push('cs');
        if (!validarnomSu($('#nomSu'))) errores.push('ns');
        
        }
        // otros métodos no necesitan validar ninguna extra
      
        return errores.length === 0;
      }
  
    // — Limitar inputs numéricos —
    $('#referencia_bancaria').on('input', function() {
      let v = this.value.replace(/\D/g, '').slice(0,6);
      $(this).val(v);
    });
    $('#telefono_emisor').on('input', function() {
      let v = this.value.replace(/\D/g, '').slice(0,11);
      $(this).val(v);
    });

    $('#codigoSu').on('input', function() {
      let v = this.value.replace(/\D/g, '').slice(0,6);
      $(this).val(v);
    });
  
    // — Poblado dinámico de selects zona → parroquia → sector —
    const parroquiasPorZonaloc = {
      norte: ["El Cují","Tamaca"], sur: ["Juan de Villegas","Unión"],
      este: ["Santa Rosa","Cabudare"], oeste: ["Concepción"], centro: ["Catedral"]
    };
    const sectoresPorParroquialoc = {
      "Catedral":["Centro","Urbanización Santa Elena","Barrios La Cruz","Colinas del Viento"],
      "Concepción":["La Playa","El Manzano","Urbanización El Obelisco","Barrio Bolívar"],
      "El Cují":["Altos de El Cují","La Pastora","El Cují Centro","Barrio El Caribe"],
      "Juan de Villegas":["La Carucieña","La Paz","Urbanización Sucre","Barrio El Tostao"],
      "Santa Rosa":["Santa Rosa Centro","El Cercado","Urbanización El Ujano","Barrio El Garabatal"],
      "Tamaca":["Tamaca Centro","El Trompillo","Barrio El Jebe","Urbanización El Sisal"],
      "Unión":["Barrio Unión","San Jacinto","Urbanización El Pedregal","Barrio El Carmen"],
      "Cabudare":[ /* …tus sectores… */ ]
    };
  
    $('#zona').on('change', function() {
      const z = this.value;
      $('#parroquia').html('<option value="">-- Selecciona una parroquia --</option>');
      $('#sector').html('<option value="">-- Selecciona un sector --</option>');
      (parroquiasPorZonaloc[z]||[]).forEach(p => {
        $('#parroquia').append(`<option>${p}</option>`);
      });
    });
    $('#parroquia').on('change', function() {
      const p = this.value;
      $('#sector').html('<option value="">-- Selecciona un sector --</option>');
      (sectoresPorParroquialoc[p]||[]).forEach(s => {
        $('#sector').append(`<option>${s}</option>`);
      });
    });

///02
// const ciudadesPorEstado = {
//     Lara:    ["Barquisimeto", "Cabudare", "Carora","Quíbor", "El Tocuyo", "Sanare Sarare", "Duaca", "Siquisique"],
//     Zulia:   ["Maracaibo", "Cabimas", "Ciudad Ojeda"],
//     Miranda: ["Los Teques", "Guarenas", "Charallave"]
//     // …otros estados…
//   };

//   const zonasPorCiudad = {
//     Barquisimeto: ["Norte", "Centro", "Sur", "Este", "Oeste"],
//     Cabudare:     ["Sector 1", "Sector 2", "Sector 3"],
//     Carora:       ["Zona A", "Zona B"],
//     Maracaibo:    ["Metro", "Costa Oriental"],
//     Cabimas:      ["Urbano", "Rural"],
//     "Ciudad Ojeda": ["Norte", "Sur"],
//     "Los Teques": ["Colonial", "La Rinconada"],
//     Guarenas:     ["Centro", "Urbana"],
//     Charallave:   ["Eje 1", "Eje 2"]
//   };

//   const parroquiasPorZona = {
//     Norte:  ["El Cují", "Tamaca"],
//     Centro: ["Catedral", "Miranda"],
//     Sur:    ["Juan de Villegas", "Unión"],
//     Este:   ["Santa Rosa", "Cabudare"],
//     Oeste:  ["Concepción"]
//   };

//   const sectoresPorParroquia = {
//     "El Cují": ["Altos de El Cují", "La Pastora", "El Caribe"],
//     Tamaca:   ["Tamaca Centro", "El Trompillo"],
//     Catedral: ["Centro Histórico", "Colinas del Viento"],
//     Miranda:  ["San Jacinto", "El Carmen"],
//     "Juan de Villegas": ["La Carucieña", "El Tostao"],
//     Unión:    ["Barrio Unión", "Barrio El Carmen"],
//     "Santa Rosa": ["Santa Rosa Centro", "El Garabatal"],
//     Cabudare: ["La Mora", "Valle Hondo"],
//     Concepción: ["La Playa", "Barrio Bolívar"]
//   };

//   // Helper para poblar y habilitar un select
//   function poblar($sel, items, placeholder) {
//     $sel.html(`<option value="">${placeholder}</option>`);
//     items.forEach(item => $sel.append(`<option>${item}</option>`));
//     $sel.prop('disabled', false);
//   }

//   // Inicialmente deshabilitar todos excepto estado
//   $('#ciudad_loc, #zona_loc, #parroquia_loc, #sector_loc').prop('disabled', true);

//   // Estado → Ciudad
//   $('#estado_loc').on('change', function() {
//     const estado = this.value;
//     $('#ciudad_loc').prop('disabled', true).html('<option value="">-- Selecciona ciudad --</option>');
//     $('#zona_loc, #parroquia_loc, #sector_loc')
//       .prop('disabled', true)
//       .html('<option value="">-- --</option>');
//     if (ciudadesPorEstado[estado]) {
//       poblar($('#ciudad_loc'), ciudadesPorEstado[estado], '-- Selecciona ciudad --');
//     }
//   });

//   // Ciudad → Zona
//   $('#ciudad_loc').on('change', function() {
//     const ciudad = this.value;
//     $('#zona_loc').prop('disabled', true).html('<option value="">-- Selecciona zona --</option>');
//     $('#parroquia_loc, #sector_loc')
//       .prop('disabled', true)
//       .html('<option value="">-- --</option>');
//     if (zonasPorCiudad[ciudad]) {
//       poblar($('#zona_loc'), zonasPorCiudad[ciudad], '-- Selecciona zona --');
//     } else {
//       $('#zona_loc').prop('disabled', false);
//     }
//   });

//   // Zona → Parroquia
//   $('#zona_loc').on('change', function() {
//     const zona = this.value;
//     $('#parroquia_loc').prop('disabled', true).html('<option value="">-- Selecciona parroquia --</option>');
//     $('#sector_loc')
//       .prop('disabled', true)
//       .html('<option value="">-- --</option>');
//     if (parroquiasPorZona[zona]) {
//       poblar($('#parroquia_loc'), parroquiasPorZona[zona], '-- Selecciona parroquia --');
//     } else {
//       $('#parroquia_loc').prop('disabled', false);
//     }
//   });

  // Parroquia → Sector
  //$('#parroquia_loc').on('change', function() {
   // const parroquia = this.value;
   // $('#sector_loc').prop('disabled', true).html('<option value="">-- Selecciona sector --</option>');
   // if (sectoresPorParroquia[parroquia]) {
 //     poblar($('#sector_loc'), sectoresPorParroquia[parroquia], '-- Selecciona sector --');
   // } else {
   //   $('#sector_loc').prop('disabled', false);
   // }
 // });

  // Trigger inicial (por si vienen valores preseleccionados)
 // $('#estado_loc').trigger('change');





    // — Toggle suave entre divdelivery y divEnvio según método de entrega —
    function ajustarDeliveryDivs() {
        const val = $('#metodoentrega').val();
        if (val === '1') {
          $('#divEnvio').stop(true,true).slideUp(300);
          $('#divdelivery').stop(true,true).slideDown(300);
        } else if (val === '2' || val === '3') {
            // Empresas MRW/Zoom
            $('#divdelivery').stop(true,true).slideUp(300);
            $('#divEnvio').stop(true,true).slideDown(300);
          }
          else {
            // Ni delivery ni empresa -> no mostramos ninguno
            $('#divdelivery').stop(true,true).slideUp(300);
            $('#divEnvio').stop(true,true).slideUp(300);
      
        }
      }

  
      $('#metodoentrega').on('change', ajustarDeliveryDivs);
      ajustarDeliveryDivs();
    // — Envío por AJAX —
    $('#btn-guardar-pedido').on('click', function(e) {
        e.preventDefault();
        if (!validarFormularioPedido()) return;
      
        // 1) Leer método
        const me = $('#metodoentrega').val();
      
        // 2) Crear FormData
        const fd = new FormData(document.getElementById('formPedido'));
      
        // 3) Totales
        const usd = $('#precio_total_usd').val(),
              bs  = $('#precio_total_bs').val();
      
        // 4) Campos comunes
        fd.set('tipo',             $('#tipo').val());
        fd.set('fecha',            $('#fecha').val() || new Date().toISOString().slice(0,19).replace('T',' '));
        fd.set('estado',           $('#estado').val() || 'pendiente');
        fd.set('precio_total_usd', usd);
        fd.set('precio_total_bs',  bs);
        fd.set('id_persona',       $('#id_persona').val());
      
        fd.set('id_metodopago',       $('#metodopago').val());
        fd.set('referencia_bancaria', $('#referencia_bancaria').val());
        fd.set('telefono_emisor',     $('#telefono_emisor').val());
        fd.set('banco',               $('#banco').val());
        fd.set('banco_destino',       $('#banco_destino').val());
        fd.set('monto',               bs);
        fd.set('monto_usd',           usd);
      
        fd.set('id_metodoentrega', me);
      
        // 5) Rellenar envío según tipo
        if (me === '1') {
          // Delivery propio
          const z = $('#zona').val(),
                p = $('#parroquia').val(),
                s = $('#sector').val(),
                d = $('#direccion').val();
          fd.set('direccion_envio', `Zona: ${z}, Parroquia: ${p}, Sector: ${s}, Dirección: ${d}`);
          fd.set('sucursal_envio', '');  
        }
        else if (me === '2' || me === '3') {
          // Empresa MRW/Zoom
          const codigo = $('#codigoSu').val(),
                nombre = $('#nomSu').val();
          fd.set('direccion_envio', nombre);
          fd.set('sucursal_envio', codigo);
        }
        else {
          // Otros métodos: vacío
          fd.set('direccion_envio', '');
          fd.set('sucursal_envio', '');
        }
      
        // 6) AJAX
        $.ajax({
          url: 'controlador/verpedidoweb.php',
          type: 'POST',
          data: fd,
          processData: false,
          contentType: false,
          dataType: 'json'
        })
        .done(function(res) {
          if (res.success) {
            muestraMensaje('success',2000,'¡Pedido registrado!',res.message);
            setTimeout(()=> window.location.href='?pagina=catalogo',2000);
          } else {
            muestraMensaje('error',3000,'Error',res.message);
          }
        })
        .fail(function(_,__,err) {
          muestraMensaje('error',3000,'Error',err);
        });
      });
        
    // Validación en tiempo real
    $('#nomSu').on('input', ()=>validarnomSu($('#nomSu')));
    $('#codigoSu').on('input', ()=>validarCodigoSu($('#codigoSu')));
    $('#referencia_bancaria').on('input', ()=>validarReferenciaBancaria($('#referencia_bancaria')));
    $('#telefono_emisor').on('input', ()=>validarTelefonoEmisor($('#telefono_emisor')));
    $('#direccion').on('input', ()=>validarDireccion($('#direccion')));
    $('select').on('change', ()=>validarFormularioPedido());
  });
  