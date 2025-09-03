
$(document).ready(function() {

 $('#actualizar').on("click", function () {
    Swal.fire({
      title: '¿Desea guardar los cambios?',
      text: '',
      icon: 'question',
      showCancelButton: true,
      color: "#00000",
      confirmButtonColor: '#1e913bff',
      cancelButtonColor: '#42515A',
      confirmButtonText: ' Si, actualizar ',
      cancelButtonText: 'NO'
    }).then((result) => {
      if (result.isConfirmed) {
         activarLoaderBoton('#actualizar');
        var datos = new FormData($('#u')[0]);
        datos.append('actualizar', 'actualizar');
        enviaAjax(datos);
      }
    });
  });

function validarFormulario() {
    let validar = true;

    // Validar campos con la lógica original
    validarCampo($("#clave"), /^.{8,16}$/, $("#textoclave"), "El formato debe ser entre 8 y 16 caracteres");
    validarCampo($("#clavenueva"), /^.{8,16}$/, $("#textoclavenueva"), "El formato debe ser entre 8 y 16 caracteres");
    validarCampo($("#clavenuevac"), /^.{8,16}$/, $("#textoclavenuevac"), "El formato debe ser entre 8 y 16 caracteres");

    // Validar coincidencia entre las contraseñas
    const clavenueva = $("#clavenueva").val();
    const clavenuevac = $("#clavenuevac").val();

    if (clavenueva !== clavenuevac) {
        $("#clavenueva, #clavenuevac").removeClass("is-valid").addClass("is-invalid");
        $("#textoclavenuevac").text("Las contraseñas no coinciden").css("color", "red");
        validar = false;
    }

    // Verificar que todos los campos requeridos estén válidos visualmente
    const clavesValidas = $("#clave").hasClass("is-valid") &&
                          $("#clavenueva").hasClass("is-valid") &&
                          $("#clavenuevac").hasClass("is-valid");

    if (!clavesValidas) {
        validar = false;
    }

    if (!validar) {
        muestraMensajetost("error", "Validaciones incorrectas", "Corrige los errores antes de enviar.", "2500");
    }

    return validar;
}


// Modificar el evento del botón para validar antes de enviar
$('#actualizarclave').on("click", function () {
    if (validarFormulario()) {
        Swal.fire({
            title: '¿Desea Cambiar la clave?',
            text: '',
            icon: 'question',
            showCancelButton: true,
            color: "#00000",
            confirmButtonColor: '#1e913bff',
            cancelButtonColor: '#42515A',
            confirmButtonText: ' Si, cambiar',
            cancelButtonText: 'NO'
        }).then((result) => {
            if (result.isConfirmed) {
                activarLoaderBoton('#actualizarclave');
                var datos = new FormData($('#formclave')[0]);
                datos.append('actualizarclave', 'actualizarclave');
                enviaAjax(datos);
            }
        });
    }
});




$('#direccion').on("click", function () {
  
        Swal.fire({
            title: '¿Deseas cambiar la dirección?',
            icon: 'question',
            showCancelButton: true,
            color: "#00000",
            confirmButtonColor: '#1e913bff',
            cancelButtonColor: '#42515A',
            confirmButtonText: 'Sí, cambiar',
            cancelButtonText: 'NO'
        }).then((result) => {
            if (result.isConfirmed) {
                activarLoaderBoton('#direccion');
                var datos = new FormData($('#editardireccion')[0]);
                datos.append('actualizardireccion', 'actualizardireccion');
                enviaAjax(datos);
            }
        });
    
});


$('#agregardireccion').on("click", function () {
 
        Swal.fire({
            title: '¿Deseas Registrar la direccion?',
            text: '',
            icon: 'question',
            showCancelButton: true,
            color: "#00000",
            confirmButtonColor: '#1e913bff',
            cancelButtonColor: '#42515A',
            confirmButtonText: ' SI',
            cancelButtonText: 'NO'
        }).then((result) => {
            if (result.isConfirmed) {
                activarLoaderBoton('#agregardireccion');
                var datos = new FormData($('#incluir')[0]);
                datos.append('incluir', 'incluir');
                enviaAjax(datos);
            }
        });
    
});

$('#direccionedit').on("click", function () {
 
        Swal.fire({
            title: '¿Deseas cambiar la direccion?',
            text: '',
            icon: 'question',
            showCancelButton: true,
            color: "#00000",
            confirmButtonColor: '#1e913bff',
            cancelButtonColor: '#42515A',
            confirmButtonText: ' Si, cambiar',
            cancelButtonText: 'NO'
        }).then((result) => {
            if (result.isConfirmed) {
                activarLoaderBoton('#agregardireccion');
                var datos = new FormData($('#editardelivery')[0]);
                datos.append('actualizardireccion', 'actualizardireccion');
                enviaAjax(datos);
            }
        });
    
});


});

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
      $("#textotelefono"), "El formato debe ser 0000-0000000");
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
      validarCampo($(this), /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,60}$/,
       $("#textocorreo"), "El formato debe incluir @ y ser válido.");
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
      validarCampo($(this),/^.{8,16}$/, 
      $("#textoclave"), "El formato debe ser entre 8 y 16 caracteres");
    });

     $("#clavenueva").on("keypress", function(e) {
    validarkeyup(/^.{8,16}$/, e);
    });

     $("#clavenueva").on("keyup", function() {
      validarCampo($(this),/^.{8,16}$/, 
      $("#textoclavenueva"), "El formato debe ser entre 8 y 16 caracteres");
    });

    $("#clavenuevac").on("keypress", function(e) {
    validarkeyup(/^.{8,16}$/, e);
    });
    
    $("#clavenuevac").on("keyup", function() {
      validarCampo($(this),/^.{8,16}$/, 
      $("#textoclavenuevac"), "El formato debe ser entre 8 y 16 caracteres");
    });


    $("#confirmar").on("keypress", function (e) {
    validarkeypress(/^[A-Z\s\u00D1\u00C0-\u00D6\u00D8-\u00DE]*$/, e);
    });
    
    $("#confirmar").on("keyup", function() {
      validarCampo($(this),/^[A-Z]{7,7}$/, 
      $("#textoconfirmar"), "El formato es: MAYUSCULA");
    });


    
    $("#modal_direccion").on("keypress",function(e){
    validarkeypress(/^[a-zA-Z0-9\s\b]*$/, e);
    });
  
  $("#modal_direccion").on("keyup", function () {
    validarCampo($(this),/^[a-zA-Z0-9\s]{10,150}$/,
    $("#textodir"),"Debe contener al menos 10 caracteres");
  });

    $("#modal_sucursal").on("keypress",function(e){
      validarkeypress(/^[0-9-\b]*$/,e);
    });
  
  $("#modal_sucursal").on("keyup", function () {
    validarCampo($(this),/^[0-9]{3,8}$/,
    $("#textosur"),"Debe contener al menos 3 caracteres");
  });


  $("#delivery_direccion").on("keypress",function(e){
    validarkeypress(/^[a-zA-Z0-9\s\b]*$/, e);
    });
  
  $("#delivery_direccion").on("keyup", function () {
    validarCampo($(this),/^[a-zA-Z0-9\s]{10,150}$/,
    $("#textodir1"),"Debe contener al menos 10 caracteres");
  });


   $("#agregar_direccion").on("keypress",function(e){
    validarkeypress(/^[a-zA-Z0-9\s\b]*$/, e);
    });
  
  $("#agregar_direccion").on("keyup", function () {
    validarCampo($(this),/^[a-zA-Z0-9\s]{10,150}$/,
    $("#textodir2"),"Debe contener al menos 10 caracteres");
  });

  $("#agregar_sucursal").on("keypress",function(e){
    validarkeypress(/^[0-9-\b]*$/,e);
  });
  
  $("#agregar_sucursal").on("keyup", function () {
    validarCampo($(this),/^[0-9]{3,8}$/,
    $("#textosur1"),"Debe contener al menos 3 caracteres");
  });



 $(document).ready(function() {
    $("#btnEliminar").click(function() {
        let inputValor = $("#confirmar").val().trim(); // Obtener el valor sin espacios

        if (inputValor === "ACEPTAR") {
            let countdown = 10;

            Swal.fire({
                title: "Su cuenta se esta eliminando",
                html: `Cuenta regresiva: <b>${countdown}</b>`,
                timer: 10000,
                timerProgressBar: true,
                showConfirmButton: false,
                allowOutsideClick: false,
                didOpen: () => {
                    const content = Swal.getHtmlContainer();
                    const b = content.querySelector("b");

                    let interval = setInterval(() => {
                        countdown--;
                        b.textContent = countdown;

                        if (countdown <= 0) {
                            clearInterval(interval);
                            activarLoaderBoton('#btnEliminar');
                            var datos = new FormData($('#eliminarForm')[0]);
                            datos.append('eliminar', 'eliminar');
                            enviaAjax(datos);
                        }
                    }, 1000);
                }
            });
        } else {
             muestraMensajetost("error","Error", "Debe escribir exactamente 'ACEPTAR' para continuar", "3000");
        }
    });
});

});


function validarFormulario2() {
    const campoSucursal = validarCampo($("#modal_sucursal"), /^[0-9]{3,8}$/, $("#textosur"), "Debe contener entre 3 y 8 dígitos");
    const campoDireccion = validarCampo($("#modal_direccion"), /^[a-zA-Z0-9\s]{10,150}$/, $("#textodir"), "Debe contener al menos 10 caracteres");

    if (!campoSucursal || !campoDireccion) {
        muestraMensaje("warning", 2500, "Campos inválidos", "Por favor verifica los campos antes de continuar.");
        return false;
    }

    return true;
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

// AJAX
function muestraMensaje(icono, tiempo, titulo, mensaje) {
  Swal.fire({
    icon: icono,
    timer: tiempo,
    title: titulo,
    html: mensaje,
    showConfirmButton: false,
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
            try {
        // Eliminar contenido HTML en caso de que aparezca
        var respuestaLimpiada = respuesta.split("<!DOCTYPE html>")[0].trim();
        var lee = JSON.parse(respuestaLimpiada);
        console.log("JSON parseado correctamente:", lee);
        } catch (error) {
            console.error("Error al parsear JSON:", error.message);
        }

        try {
  
           if (lee.accion == 'actualizar') {
                if (lee.respuesta == 1) {  
                  muestraMensaje("success", 2000, "Se ha Actualizado con éxito", "Sus datos se en modificado con exitosamente");
                  desactivarLoaderBoton('#actualizar');
                  setTimeout(() => {
                 location = '?pagina=catalogo_datos';
                  }, 2000); 
              } else {
                  muestraMensaje("error", 2000, lee.text, "");
                  desactivarLoaderBoton('#actualizar');
                }
              } else if (lee.accion == 'eliminar') {
                if (lee.respuesta == 1) {
                  muestraMensaje("success", 2000, "Se ha eliminado con éxito", "Los datos se han borrado correctamente ");
                      desactivarLoaderBoton('#btnEliminar');
                  setTimeout(function () {
                     location = '?pagina=catalogo';
                  }, 1000);
                } else {
                  muestraMensaje("error", 2000, "ERROR", lee.text);
                  desactivarLoaderBoton('#btnEliminar');
                }
              } else if (lee.accion == 'clave') {
                if (lee.respuesta == 1) {
                  muestraMensaje("success", 2000, "Se ha cambio con éxito", "correctamente ");
                      desactivarLoaderBoton('#actualizarclave');
                  setTimeout(function () {
                     location = '?pagina=catalogo_datos';
                  }, 1000);
                } else {
                  muestraMensaje("error", 2000, lee.text, "Revise y vuelva a intentar" );
                      desactivarLoaderBoton('#actualizarclave');
                }
              } else if (lee.accion == 'incluir') {
                if (lee.respuesta == 1) {
                  muestraMensaje("success", 2000, "Se ha registro con éxito", "Correctamente");
                      desactivarLoaderBoton('#agregardireccion');
                  setTimeout(function () {
                     location = '?pagina=catalogo_datos';
                  }, 2000);
                } else {
                  muestraMensaje("error", 2000, lee.text, "Revise y vuelva a intentar" );
                      desactivarLoaderBoton('#agregardireccion');
                }
              } else if (lee.accion == 'actualizardireccion') {
                if (lee.respuesta == 1) {
                  muestraMensaje("success", 2000, "Se ha cambio con éxito", "Direccion editada correctamente");
                      desactivarLoaderBoton('#direccion');
                  setTimeout(function () {
                     location = '?pagina=catalogo_datos';
                  }, 2000);
                } else {
                  muestraMensaje("error", 2000, lee.text, "Revise y vuelva a intentar" );
                      desactivarLoaderBoton('#direccion');
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