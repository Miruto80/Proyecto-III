document.addEventListener('DOMContentLoaded', function () {
    // Validaciones para 'nombre'
    $("#nombre").on("keypress", function(e){
        validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });
    $("#nombre").on("keyup", function(){
        validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, $(this), $("#snombre"), "Solo letras entre 3 y 30 caracteres");
    });

    // Validaciones para 'descripcion'
    $("#descripcion").on("keypress", function(e){
        validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });
    $("#descripcion").on("keyup", function(){
        validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, $(this), $("#sdescripcion"), "Solo letras entre 3 y 30 caracteres");
    });

    $(document).on('click', '#registrar', function () {
        const nombre = $('#nombre').val().trim();
        const descripcion = $('#descripcion').val().trim();
      
        if (!nombre || !descripcion) {
          muestraMensaje('warning', 2000, 'Campos requeridos', 'Completa todos los campos.');
          return;
        }
      
        const datosPeticion = {
          accion: 'incluir',
          datos: {
            nombre: nombre,
            descripcion: descripcion
          }
        };
      
        $.post('controlador/metodoentrega.php', datosPeticion, function (response) {
          try {
            const res = JSON.parse(response);
            const exito = res.respuesta === 1;
      
            muestraMensaje(exito ? 'success' : 'error', 1500,
              exito ? 'Registrado' : 'Error',
              exito ? 'Método registrado correctamente' : 'No se pudo registrar');
      
            if (exito) {
              $('#nombre').val('');
              $('#descripcion').val('');
              setTimeout(() => location.reload(), 1500);
            }
          } catch (e) {
            muestraMensaje('error', 2000, 'Error', 'Respuesta del servidor inválida.');
          }
        });
      });
});
      

$(document).on('click', '#btnModificar', function () {
    const id_entrega = $('#id_entrega_modificar').val().trim();
    const nombre = $('#nombre_modificar').val().trim();
    const descripcion = $('#descripcion_modificar').val().trim();
  
    if (!id_entrega || !nombre || !descripcion) {
      muestraMensaje('warning', 2000, 'Campos requeridos', 'Completa todos los campos.');
      return;
    }
  
    const datosPeticion = {
      accion: 'modificar',
      datos: {
        id_entrega: id_entrega,
        nombre: nombre,
        descripcion: descripcion
      }
    };
  
    $.post('controlador/metodoentrega.php', datosPeticion, function (response) {
      try {
        const res = JSON.parse(response);
        const exito = res.respuesta === 1;
  
        muestraMensaje(exito ? 'success' : 'error', 1500,
          exito ? 'Modificado' : 'Error',
          exito ? 'Método modificado correctamente' : 'No se pudo modificar');
  
        if (exito) {
          setTimeout(() => location.reload(), 1500);
        }
      } catch (e) {
        muestraMensaje('error', 2000, 'Error', 'Respuesta del servidor inválida.');
      }
    });
  });
  
  function abrirModalModificar(id_entrega, nombre, descripcion) {
    document.getElementById('id_entrega_modificar').value = id_entrega;
    document.getElementById('nombre_modificar').value = nombre;
    document.getElementById('descripcion_modificar').value = descripcion;
    $('#modificar').modal('show');
  }
  
  function eliminarMetodoEntrega(id_entrega) {
    Swal.fire({
      title: '¿Estás seguro?',
      text: 'Esta acción no se puede deshacer.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        const datosPeticion = {
          accion: 'eliminar',
          datos: {
            id_entrega: id_entrega
          }
        };
  
        $.post('controlador/metodoentrega.php', datosPeticion, function (response) {
          try {
            const res = JSON.parse(response);
            const exito = res.respuesta === 1;
  
            muestraMensaje(exito ? 'success' : 'error', 1500,
              exito ? 'Eliminado' : 'Error',
              exito ? 'Método eliminado correctamente' : 'No se pudo eliminar');
  
            if (exito) {
              setTimeout(() => location.reload(), 1500);
            }
          } catch (e) {
            muestraMensaje('error', 2000, 'Error', 'Respuesta del servidor inválida.');
          }
        });
      }
    });
  }
      
    function muestraMensaje(icono, tiempo, titulo, mensaje) {
        Swal.fire({
            icon: icono,
            timer: tiempo,
            title: titulo,
            html: mensaje,
            showConfirmButton: false,
        });
    }
    

function validarkeypress(er, e){
    key = e.keyCode;
    tecla = String.fromCharCode(key);
    a = er.test(tecla);
    if (!a) e.preventDefault();
}

function validarkeyup(er, etiqueta, etiquetamensaje, mensaje) {
    a = er.test(etiqueta.val());
    if (a) {
        etiquetamensaje.text("");
        return 1;
    } else {
        etiquetamensaje.text(mensaje);
        return 0;
    }
}

function validarenvio() {
    if (validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, $("#nombre"), $("#snombre"), "Solo letras entre 3 y 30 caracteres") == 0) {
        muestraMensaje("error", 2000, "Error", "Datos incorrectos en campo nombre");
        return false;
    }

    if (validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, $("#descripcion"), $("#sdescripcion"), "Solo letras entre 3 y 30 caracteres") == 0) {
        muestraMensaje("error", 2000, "Error", "Datos incorrectos en campo descripción");
        return false;
    }

    return true;
}

function validarModificacion() {
    if (validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, $("#nombre_modificar"), $("#snombre_modificar"), "Solo letras entre 3 y 30 caracteres") == 0) {
        muestraMensaje("error", 2000, "Error", "Datos incorrectos en campo nombre");
        return false;
    }

    if (validarkeyup(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]{3,30}$/, $("#descripcion_modificar"), $("#sdescripcion_modificar"), "Solo letras entre 3 y 30 caracteres") == 0) {
        muestraMensaje("error", 2000, "Error", "Datos incorrectos en campo descripción");
        return false;
    }

    return true;
}
