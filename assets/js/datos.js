//Función para validar por Keypress
function validarkeypress(er,e){
  key = e.keyCode;
    tecla = String.fromCharCode(key);
    a = er.test(tecla);
    if(!a){
    e.preventDefault();
    }
}
//Función para validar por keyup
function validarkeyup(er,etiqueta,etiquetamensaje,
mensaje){
  a = er.test(etiqueta.val());
  if(a){
    etiquetamensaje.text("");
    return 1;
  }
  else{
    etiquetamensaje.text(mensaje);
    return 0;
  }
}

/*||| Funcion para cambiar el boton a loader |||*/
function activarLoaderBoton(idBoton, texto = 'Cargando...') {
    const $boton = $(idBoton);
    const textoActual = $boton.html();
    $boton.data('texto-original', textoActual); // Guarda el texto original
    $boton.prop('disabled', true);
    $boton.html(`<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>${texto}`);
}

function desactivarLoaderBoton(idBoton) {
    const $boton = $(idBoton);
    const textoOriginal = $boton.data('texto-original');
    $boton.prop('disabled', false);
    $boton.html(textoOriginal);
}

/*||| Funcion para validar compas de formulario |||*/
function validarCampo(campo, regex, textoError, mensaje) {
  const valor = campo.val();

  if (campo.is("select")) {
   
    if (valor === "") {
      campo.removeClass("is-valid").addClass("is-invalid");
      textoError.text(mensaje);
    } else {
      campo.removeClass("is-invalid").addClass("is-valid");
      textoError.text("");
    }
  } else {
   
    if (regex.test(valor)) {
      campo.removeClass("is-invalid").addClass("is-valid");
      textoError.text("");
    } else {
      campo.removeClass("is-valid").addClass("is-invalid");
      textoError.text(mensaje);
    }
  }
}

$(document).ready(function() {


   $('#actualizar').on("click", function () {
    Swal.fire({
      title: '¿Desea guardar los cambios?',
      text: '',
      icon: 'question',
      showCancelButton: true,
      color: "#00000",
      confirmButtonColor: '#58c731',
      cancelButtonColor: '#42515A',
      confirmButtonText: ' Si, actualizar ',
      cancelButtonText: 'NO'
    }).then((result) => {
      if (result.isConfirmed) {
        var datos = new FormData($('#datos')[0]);
        activarLoaderBoton('#actualizar');
        datos.append('actualizar', 'actualizar');
        enviaAjax(datos);
      }
    });
 });
 
  $("#cedula").on("keypress",function(e){
    validarkeypress(/^[0-9-\b]*$/,e);
  });
  
  $("#cedula").on("keyup", function () {
    validarCampo($(this),/^[0-9]{7,8}$/,
    $("#textocedula"),"El formato debe ser 1222333");
  });


   $("#telefono").on("keypress", function (e) {
      validarkeypress(/^[0-9-\-]*$/, e);
    });
  
      $("#telefono").on("keyup", function () {
      validarCampo($(this),/^[0-9]{4}[-]{1}[0-9]{7}$/,
      $("#textotelefono"), "El formato debe ser 0422-0001020");
    });
  
    $("#telefono").on("input", function () {
      var input = $(this).val().replace(/[^0-9]/g, '');
      if (input.length > 4) {
        input = input.substring(0, 4) + '-' + input.substring(4, 11);
      }
      $(this).val(input);
    });


     $("#correo").on("keypress", function (e) {
      validarkeypress(/^[a-zA-Z0-9._%+-@\b]*$/, e);
    });

     $("#correo").on("keyup", function () {
      validarCampo($(this), /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,60}$/, $("#textocorreo"), "El formato debe incluir @ y ser válido.");
    });

    $("#nombre").on("keypress", function (e) {
    validarkeypress(/^[A-Za-z\b\s\u00f1\u00d1\u00E0-\u00FC]*$/, e);
    });
    
    $("#nombre").on("keyup", function () {
      validarCampo($(this),/^[a-zA-Z]{3,30}$/,
      $("#textonombre"), "El formato debe ser solo letras");
    });

    $("#apellido").on("keypress", function (e) {
      validarkeypress(/^[a-z-A-Z-\b]*$/, e);
    });

    $("#apellido").on("keyup", function () {
      validarCampo($(this),/^[a-z-A-Z]{3,30}$/,
      $("#textoapellido"), "El formato debe ser solo letras");
    });

    $("#clave").on("keypress", function(e) {
       validarkeyup(/^.{8,16}$/, e);
    });
    
    
    $("#clave").on("keyup", function() {
      validarCampo($(this),/^.{8,16}$/, $("#textoclave"), "El formato debe ser entre 8 y 16 caracteres");
    });

      $("#clavenueva").on("keypress", function(e) {
       validarkeyup(/^.{8,16}$/, e);
    });
    
    
    $("#clavenueva").on("keyup", function() {
      validarCampo($(this),/^.{8,16}$/, $("#textoclavenueva"), "El formato debe ser entre 8 y 16 caracteres");
    });

      $("#clavenuevac").on("keypress", function(e) {
       validarkeyup(/^.{8,16}$/, e);
    });
    
    
    $("#clavenuevac").on("keyup", function() {
      validarCampo($(this),/^.{8,16}$/, $("#textoclavenuevac"), "El formato debe ser entre 8 y 16 caracteres");
    });

    function validarFormulario() {
    let validar = true;

    validarCampo($("#clave"), /^.{8,16}$/, $("#textoclave"), "El formato debe ser entre 8 y 16 caracteres");
    if (!/^.{8,16}$/.test($("#clave").val())) validar = false;

    validarCampo($("#clavenueva"), /^.{8,16}$/, $("#textoclavenueva"), "El formato debe ser entre 8 y 16 caracteres");
    if (!/^.{8,16}$/.test($("#clavenueva").val())) validar = false;

    validarCampo($("#clavenuevac"), /^.{8,16}$/, $("#textoclavenuevac"), "El formato debe ser entre 8 y 16 caracteres");
    if (!/^.{8,16}$/.test($("#clavenuevac").val())) validar = false;

    // Comparación entre nueva contraseña y confirmación
    if ($("#clavenueva").val() !== $("#clavenuevac").val()) {
        $("#clavenuevac").removeClass("is-valid").addClass("is-invalid");
        $("#clavenueva").removeClass("is-valid").addClass("is-invalid");
        $("#textoclavenuevac").text("Las contraseñas no coinciden");
        validar = false;
    }


    if (!validar) {
        muestraMensajetost('error', 'Validaciones incorrectas', 'Corrige los errores antes de enviar.', '3000');
    }

    return validar;
}


// Modificar el evento del botón para validar antes de enviar
$('#actualizarclave').on("click", function () {
    if (validarFormulario()) {
        Swal.fire({
            title: '¿Desea Cambiar la Clave?',
            text: '',
            icon: 'question',
            showCancelButton: true,
            color: "#00000",
            confirmButtonColor: '#58c731',
            cancelButtonColor: '#42515A',
            confirmButtonText: ' Si, Cambiar ',
            cancelButtonText: 'NO'
        }).then((result) => {
            if (result.isConfirmed) {
                var datos = new FormData($('#formclave')[0]);
                activarLoaderBoton('#actualizarclave');
                datos.append('actualizarclave', 'actualizarclave');
                enviaAjax(datos);
            }
        });
    }
});

});

function muestraMensaje(icono, tiempo, titulo, mensaje) {
  Swal.fire({
    icon: icono,
    timer: tiempo,
    title: titulo,
    html: mensaje,
    showConfirmButton: false,
  });
}

function muestraMensajetost(icono, titulo, mensaje, tiempo) {
  Swal.fire({
    icon: icono,
    title: titulo,
    text: mensaje,
    toast: true,
    position: "top",
    showConfirmButton: false,
    timer: tiempo,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.onmouseenter = Swal.stopTimer;
      toast.onmouseleave = Swal.resumeTimer;
    }
  });
}

function enviaAjax(datos) {
    $.ajax({
      async: true,
      url: "",
      type: "POST",
      contentType: false,
      data: datos,
      processData: false,
      cache: false,
      beforeSend: function () { },
      timeout: 10000,
      success: function (respuesta) {
        console.log(respuesta);
        var lee = JSON.parse(respuesta);
        try {
  
           if (lee.accion == 'clave') {
                if (lee.respuesta == 1) {  
                  muestraMensaje("success", 1500, "Se ha Cambiado su clave con exito", "");
                  desactivarLoaderBoton('#actualizarclave');
                  setTimeout(function () {
                    location = '?pagina=datos';
                  }, 1500);
                } else {
                  muestraMensaje("error", 2000, lee.text, "");
                  desactivarLoaderBoton('#actualizarclave');
                }
              } else if (lee.accion == 'actualizar') {
                if (lee.respuesta == 1) {
                  muestraMensaje("success", 1500, "Se ha Modificado con éxito", "Su registro se ha Actualizado exitosamente");
                  desactivarLoaderBoton('#actualizar');
                  setTimeout(function () {
                    location = '?pagina=datos&m=a';
                  }, 1500);
                } else {  
                  muestraMensaje("error", 2000, lee.text, "");
                 desactivarLoaderBoton('#actualizar');
                }
              } 
        } catch (e) {
          alert("Error en JSON " + e.name);
        }
      },
      error: function (request, status, err) {
        Swal.close();
        if (status == "timeout") {
          muestraMensaje("error", 2000, "Error", "Servidor ocupado, intente de nuevo");
        } else {
          muestraMensaje("error", 2000, "Error", "ERROR: <br/>" + request + status + err);
        }
      },
      complete: function () {
      }
    });
  }
  