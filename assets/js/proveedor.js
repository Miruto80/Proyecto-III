function mostrarMensaje(icono, tiempo, titulo, mensaje) {
  Swal.fire({ icon: icono, title: titulo, html: mensaje, timer: tiempo, showConfirmButton: false });
}

$(function(){

  // —— 1) DataTables ——  
  $('#myTable').DataTable();

  // ————————————————————————————
  // Helpers (similares a los de categoría)
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

  // 1) Abrir modal Registrar
  $('#btnAbrirRegistrar').click(()=>{ 
    $('#formProveedor')[0].reset();
    $('#formProveedor').find('.is-invalid, .is-valid').removeClass('is-invalid is-valid');
    $('#formProveedor').find('span.error-message').text('');
    $('#accion').val('registrar');
    $('#modalTitleText').text('Registrar Proveedor');
    $('#btnText').text('Registrar');
    $('#registro').modal('show');
  });

  // 2) Abrir modal Modificar
  window.abrirModalModificar = function(id){
    $.post('', { id_proveedor:id, consultar_proveedor:1 }, function(data){
      // llena el form con data
      $('#id_proveedor').val(data.id_proveedor);
      $('#tipo_documento').val(data.tipo_documento);
      $('#numero_documento').val(data.numero_documento);
      $('#nombre').val(data.nombre);
      $('#correo').val(data.correo);
      $('#telefono').val(data.telefono);
      $('#direccion').val(data.direccion);

      $('#formProveedor').find('.is-invalid, .is-valid').removeClass('is-invalid is-valid');
      $('#formProveedor').find('span.error-message').text('');

      $('#accion').val('actualizar');
      $('#modalTitleText').text('Modificar Proveedor');
      $('#btnText').text('Actualizar');
      $('#registro').modal('show');
    }, 'json')
    .fail(()=> muestraMensaje('error',2000,'Error','No se cargaron datos'));
  };

  // 3) Eliminar
  window.eliminarProveedor = function(id){
    Swal.fire({
      title:'¿Eliminar proveedor?',
      text:'Esta acción no se puede deshacer',
      icon:'warning',
      showCancelButton:true,
      confirmButtonText:'Sí, eliminar',
      cancelButtonText: 'Cancelar'
    }).then(res=>{
      if(res.isConfirmed){
        const fd = new FormData();
        fd.append('id_proveedor', id);
        fd.append('eliminar', '1');
        enviaAjax(fd);
      }
    });
  };

  // 4) Guardar (Registrar o Modificar)
  $('#btnEnviar').click(()=>{
    // Validar todos los campos requeridos
    let esValido = true;
    
    // Validar tipo de documento
    const $tipoDoc = $('#tipo_documento');
    const $spanTipoDoc = $('#stipo_documento');
    if (!$tipoDoc.val()) {
      $tipoDoc.addClass('is-invalid');
      $spanTipoDoc.text('Este campo es obligatorio');
      esValido = false;
    } else {
      $tipoDoc.removeClass('is-invalid').addClass('is-valid');
      $spanTipoDoc.text('');
    }
    
    // Validar número de documento
    const $numDoc = $('#numero_documento');
    const $spanNumDoc = $('#snumero_documento');
    if (!$numDoc.val().trim()) {
      $numDoc.addClass('is-invalid');
      $spanNumDoc.text('Este campo es obligatorio');
      esValido = false;
    } else if (!validarkeyup(/^[0-9]+$/, $numDoc, $spanNumDoc, 'Solo se permiten números')) {
      esValido = false;
    }
    
    // Validar nombre
    const $nombre = $('#nombre');
    const $spanNombre = $('#snombre');
    if (!$nombre.val().trim()) {
      $nombre.addClass('is-invalid');
      $spanNombre.text('Este campo es obligatorio');
      esValido = false;
    } else if (!validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, $nombre, $spanNombre, 'Solo letras entre 3 y 30 caracteres')) {
      esValido = false;
    }
    
    // Validar correo
    const $correo = $('#correo');
    const $spanCorreo = $('#scorreo');
    if (!$correo.val().trim()) {
      $correo.addClass('is-invalid');
      $spanCorreo.text('Este campo es obligatorio');
      esValido = false;
    } else if (!validarkeyup(/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/, $correo, $spanCorreo, 'El formato debe incluir @ y un dominio con punto (ej: proveedor@dominio.com)')) {
      esValido = false;
    }
    
    // Validar teléfono
    const $telefono = $('#telefono');
    const $spanTelefono = $('#stelefono');
    if (!$telefono.val().trim()) {
      $telefono.addClass('is-invalid');
      $spanTelefono.text('Este campo es obligatorio');
      esValido = false;
    } else if (!validarkeyup(/^(04|02)[0-9]{9}$/, $telefono, $spanTelefono, 'Debe comenzar en 04 o 02 y tener el formato 04xx-XXXXXXX')) {
      esValido = false;
    }
    
    // Validar dirección
    const $direccion = $('#direccion');
    const $spanDireccion = $('#sdireccion');
    if (!$direccion.val().trim()) {
      $direccion.addClass('is-invalid');
      $spanDireccion.text('Este campo es obligatorio');
      esValido = false;
    } else if (!validarkeyup(/^.{3,70}$/, $direccion, $spanDireccion, 'La dirección debe tener entre 3 y 70 caracteres')) {
      esValido = false;
    }
    
    if (!esValido) {
      muestraMensaje('error',2000,'Error','Por favor, complete todos los campos obligatorios');
      return;
    }
    
    const form = $('#formProveedor')[0];
    const fd = new FormData(form);
    const acción = $('#accion').val();

    if(acción==='registrar')    fd.append('registrar', '1');
    else if(acción==='actualizar') fd.append('actualizar','1');

    enviaAjax(fd);
  });

  // 5) Ajax genérico
function enviaAjax(fd) {
  $.ajax({
    url: '?pagina=proveedor',    // Asegúrate de que es la ruta de tu controlador
    type: 'POST',                // lo mismo que usas en producto.js
    data: fd,
    cache: false,
    processData: false,
    contentType: false,
    dataType: 'json',            // <— indicamos que esperamos JSON
    timeout: 10000,              // opcional, como en producto.js
    success: function(res) {
      // res YA es un objeto JSON, NO hacemos JSON.parse
      if (res.accion === 'incluir'    && res.respuesta == 1) return mensajeOK('Proveedor registrado');
      if (res.accion === 'actualizar' && res.respuesta == 1) return mensajeOK('Proveedor modificado');
      if (res.accion === 'eliminar'   && res.respuesta == 1) return mensajeOK('Proveedor eliminado');
      // si llegamos aquí, hubo un error de negocio
      muestraMensaje('error', 2000, 'Error', res.mensaje);
    },
    error: function(jqXHR, textStatus, errorThrown) {
      console.groupCollapsed('AJAX Error – proveedor.js');
      console.error('Status:', textStatus);
      console.error('Thrown:', errorThrown);
      console.error('Response:', jqXHR.responseText);
      console.groupEnd();
      muestraMensaje('error', 2000, 'Error', 'Comunicación fallida');
    }
  });
}

  function mensajeOK(texto){
    Swal.fire({ icon:'success', title:texto, timer:1200, showConfirmButton:false });
    setTimeout(()=>location.reload(), 1200);
  }

$("#tipo_documento").on("change", function () {
    let tipo = $(this).val();
    let maxDigitos;

    switch (tipo) {
        case "V":
            maxDigitos = 8; // Cédula Venezolana
            break;
        case "J":
        case "G":
            maxDigitos = 9; // RIF Jurídico y Gubernamental
            break;
        case "E":
            maxDigitos = 9; // Cédula Extranjera
            break;
        default:
            maxDigitos = 9; // Valor por defecto
    }

    $("#numero_documento").attr("maxlength", maxDigitos); // Ajusta dinámicamente el límite
    $("#numero_documento").val(""); // Limpia el campo para evitar valores erróneos
    $("#snumero_documento").text(`Ingrese ${maxDigitos} dígitos`);
});

$("#nombre").on("keypress", function(e) {
    validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
});
$("#nombre").on("keyup", function() {
    validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, $(this), $("#snombre"), "Solo letras entre 3 y 30 caracteres");
});

$("#correo").on("keypress", function(e) {
    validarkeypress(/^[a-zA-Z0-9._%+-@\b]*$/, e);
});
$("#correo").on("keyup", function() {
    validarkeyup(/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/, $(this), $("#scorreo"), "El formato debe incluir @ y un dominio con punto (ej: proveedor@dominio.com)");
});

$("#telefono").on("keypress", function(e) {
    validarkeypress(/^[0-9\b-]*$/, e);
});
$("#telefono").on("keyup", function() {
    validarkeyup(/^(04|02)[0-9]{9}$/, $(this), $("#stelefono"), "Debe comenzar en 04 o 02 y tener el formato 04xx-XXXXXXX");
});

$("#numero_documento").on("keypress", function(e) {
    validarkeypress(/^[0-9]*$/, e); // Solo permite números
});

$("#direccion").on("keypress", function(e) {
    validarkeypress(/^[a-zA-Z0-9\s\#\-\.,]*$/, e);
});
$("#direccion").on("keyup", function() {
    validarkeyup(/^.{3,70}$/, $(this), $("#sdireccion"), "La dirección debe tener entre 3 y 70 caracteres");
});


// Evento para validar número de documento dinámicamente
$("#numero_documento").on("keyup", function() {
    let tipo = $(this).closest("form").find("[name='tipo_documento']").val();
    let maxDigitos;
    let regex;

    switch (tipo) {
        case "V":
            maxDigitos = 8;
            regex = /^[0-9]{8}$/; // Solo permite 8 números
            break;
        case "J":
        case "G":
            maxDigitos = 9;
            regex = /^[0-9]{9}$/; // Solo permite 9 números
            break;
        case "E":
            maxDigitos = 9;
            regex = /^[0-9]{9}$/; // Solo permite 9 números
            break;
        default:
            maxDigitos = 9;
            regex = /^[0-9]{7,9}$/; // Solo permite números dentro del rango
    }

    validarkeyup(regex, $(this), $(this).siblings("span"), `Debe ingresar ${maxDigitos} dígitos numéricos`);
});


// Función para validar por keypress - permite solo caracteres que pasen la regex
function validarkeypress(er, e) {
    let key = e.keyCode || e.which;
    let tecla = String.fromCharCode(key);
    if (!er.test(tecla)) {
        e.preventDefault();
    }
}

// Función para validar por keyup - muestra mensaje y retorna 1 si válido, 0 si no
function validarkeyup(er, etiqueta, etiquetamensaje, mensaje) {
    let valor = etiqueta.val();
    if (valor.trim() === '') {
        etiqueta.removeClass('is-valid').addClass('is-invalid');
        etiquetamensaje.text("Este campo es obligatorio");
        return 0;
    }
    if (er.test(valor)) {
        etiquetamensaje.text('');
        etiqueta.removeClass('is-invalid').addClass('is-valid');
        return 1;
    } else {
        etiquetamensaje.text(mensaje);
        etiqueta.removeClass('is-valid').addClass('is-invalid');
        return 0;
    }
}

// 7) Toggle collapse icons (agrega esto al final de tu $(function(){…}))
document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(el => {
  el.addEventListener('click', function() {
    const icon = this.querySelector('.fas.fa-chevron-down, .fas.fa-chevron-up');
    if (!icon) return;
    icon.classList.toggle('fa-chevron-down');
    icon.classList.toggle('fa-chevron-up');
  });
});


// al final de assets/js/proveedor.js, dentro de tu $('#btnAyuda').on('click', …)
$('#btnAyuda').on('click', function() {
  const DriverClass = window.driver.js.driver;
  const driverObj = new DriverClass({
    nextBtnText: 'Siguiente',
    prevBtnText: 'Anterior',
    doneBtnText: 'Listo',
    popoverClass: 'driverjs-theme',
    closeBtn: false,
    steps: [
      {
        element: '.table-color',
        popover: { title: 'Tabla de Proveedores', description: 'Aquí ves la lista de proveedores registrados.', side: 'top' }
      },
      {
        element: '#btnAbrirRegistrar',
        popover: { title: 'Registrar Proveedor', description: 'Abre el modal para registrar un nuevo proveedor.', side: 'bottom' }
      },
      {
        // NUEVO PASO para el botón Ver Detalles
        element: 'button[data-bs-target^="#verDetallesModal"]',
        popover: {
          title: 'Ver Detalles',
          description: 'Haz clic aquí para observar toda la información del proveedor.',
          side: 'left'
        }
      },
      {
        element: '.modificar',
        popover: { title: 'Editar Proveedor', description: 'Haz clic aquí para modificar los datos de un proveedor.', side: 'left' }
      },
      {
        element: '.eliminar',
        popover: { title: 'Eliminar Proveedor', description: 'Elimina un proveedor de la lista.', side: 'left' }
      },
      {
        popover: { title: '¡Eso es todo!', description: 'Ahora ya sabes cómo funciona este módulo.' }
      }
    ]
  });
  driverObj.drive();
});



});